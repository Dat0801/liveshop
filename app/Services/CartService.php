<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use DomainException;

class CartService
{
    protected $sessionKey = 'cart';

    /**
     * Get or create cart for authenticated user
     */
    protected function getOrCreateCart(): ?Cart
    {
        if (!Auth::check()) {
            return null;
        }

        return Cart::firstOrCreate(
            ['user_id' => Auth::id()]
        );
    }

    /**
     * Get all items in the cart.
     *
     * @return Collection
     */
    public function getItems(): Collection
    {
        // For authenticated users, use database
        if (Auth::check()) {
            return $this->getItemsFromDatabase();
        }

        // For guests, use session
        $cart = Session::get($this->sessionKey, []);
        
        // Extract all unique product IDs, handling legacy items
        $productIds = collect($cart)->map(function ($item, $key) {
            return $item['product_id'] ?? $key;
        })->unique()->values()->all();
        
        // Fetch fresh product data with variants
        $products = Product::with('variants')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $items = collect($cart)->map(function ($item, $rowId) use ($products) {
            // Handle legacy items where rowId is the productId
            $productId = $item['product_id'] ?? $rowId;
            $product = $products->get($productId);
            
            if (!$product) {
                $this->remove($rowId);
                return null;
            }

            // Validate provided variants exist
            $product_variant_id = null;
            if (isset($item['variants']) && is_array($item['variants'])) {
                foreach ($item['variants'] as $type => $value) {
                    $variant = $product->variants
                        ->where('type', $type)
                        ->where('value', $value)
                        ->first();

                    if (!$variant) {
                        $this->remove($rowId);
                        return null;
                    }
                    $product_variant_id = $variant->id;
                }
            }

            // Calculate price with variants
            $price = $this->calculatePrice($product, $item['variants'] ?? []);

            return (object) [
                'id' => $rowId, // Use the rowId as the unique identifier for the cart item
                'product_id' => $product->id,
                'product' => $product,
                'product_variant_id' => $product_variant_id,
                'variant' => null,
                'variants' => $item['variants'] ?? [],
                'quantity' => $item['quantity'],
                'price' => $price,
                'subtotal' => $price * $item['quantity'],
            ];
        })->filter();

        return $items;
    }

    /**
     * Get items from database for authenticated users
     */
    protected function getItemsFromDatabase(): Collection
    {
        $cart = $this->getOrCreateCart();
        if (!$cart) {
            return collect();
        }

        $cartItems = $cart->items()->with('product.variants')->get();

        return $cartItems->map(function ($item) {
            if (!$item->product) {
                $item->delete();
                return null;
            }

            $product = $item->product;
            $variants = $item->variants ?? [];

            // Calculate price with variants
            $price = $this->calculatePrice($product, $variants);

            // Generate rowId for consistency
            ksort($variants);
            $rowId = md5($product->id . serialize($variants));

            return (object) [
                'id' => $rowId,
                'product_id' => $product->id,
                'product' => $product,
                'product_variant_id' => null,
                'variant' => null,
                'variants' => $variants,
                'quantity' => $item->quantity,
                'price' => $price,
                'subtotal' => $price * $item->quantity,
            ];
        })->filter();
    }

    /**
     * Add a product to the cart.
     *
     * @param int $productId
     * @param int $quantity
     * @param array $variants
     * @return void
     */
    public function add(int $productId, int $quantity = 1, array $variants = []): void
    {
        $product = Product::with('variants')->findOrFail($productId);

        // Validate stock
        $available = $this->getAvailableStock($product, $variants);
        if ($available <= 0) {
            throw new DomainException("{$product->name} is out of stock.");
        }

        // For authenticated users, use database
        if (Auth::check()) {
            $this->addToDatabase($productId, $quantity, $variants, $product, $available);
            return;
        }

        // For guests, use session
        $cart = Session::get($this->sessionKey, []);
        
        // Generate a unique ID for this specific combination of product + variants
        // We sort variants to ensure array order doesn't affect the ID
        ksort($variants);
        $rowId = md5($productId . serialize($variants));

        $currentQuantity = $cart[$rowId]['quantity'] ?? 0;
        $desiredQuantity = $currentQuantity + $quantity;

        if ($desiredQuantity > $available) {
            throw new DomainException("Only {$available} left for {$product->name}.");
        }

        if (isset($cart[$rowId])) {
            $cart[$rowId]['quantity'] += $quantity;
        } else {
            $cart[$rowId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'variants' => $variants,
            ];
        }

        Session::put($this->sessionKey, $cart);
    }

    /**
     * Add to database cart for authenticated users
     */
    protected function addToDatabase(int $productId, int $quantity, array $variants, Product $product, int $available): void
    {
        $cart = $this->getOrCreateCart();
        
        // Find existing cart item with same product and variants
        $cartItem = $cart->items()
            ->where('product_id', $productId)
            ->get()
            ->first(function ($item) use ($variants) {
                return $item->variants == $variants;
            });

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $available) {
                throw new DomainException("Only {$available} left for {$product->name}.");
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            if ($quantity > $available) {
                throw new DomainException("Only {$available} left for {$product->name}.");
            }
            $price = $this->calculatePrice($product, $variants);
            $cart->items()->create([
                'product_id' => $productId,
                'variants' => $variants,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }
    }

    /**
     * Update the quantity of a product in the cart.
     *
     * @param string $rowId
     * @param int $quantity
     * @return void
     */
    public function update(string $rowId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($rowId);
            return;
        }

        // For authenticated users, use database
        if (Auth::check()) {
            $this->updateInDatabase($rowId, $quantity);
            return;
        }

        // For guests, use session
        $cart = Session::get($this->sessionKey, []);

        if (isset($cart[$rowId])) {
            $cartItem = $cart[$rowId];
            $product = Product::with('variants')->findOrFail($cartItem['product_id']);
            $available = $this->getAvailableStock($product, $cartItem['variants'] ?? []);

            if ($quantity > $available) {
                throw new DomainException("Only {$available} left for {$product->name}.");
            }

            $cart[$rowId]['quantity'] = $quantity;
            Session::put($this->sessionKey, $cart);
        }
    }

    /**
     * Update cart item in database
     */
    protected function updateInDatabase(string $rowId, int $quantity): void
    {
        $items = $this->getItemsFromDatabase();
        $item = $items->firstWhere('id', $rowId);
        
        if (!$item) {
            return;
        }

        $cart = $this->getOrCreateCart();
        $cartItem = $cart->items()
            ->where('product_id', $item->product_id)
            ->get()
            ->first(function ($ci) use ($item) {
                return $ci->variants == $item->variants;
            });

        if ($cartItem) {
            $available = $this->getAvailableStock($item->product, $item->variants);
            if ($quantity > $available) {
                throw new DomainException("Only {$available} left for {$item->product->name}.");
            }
            $cartItem->update(['quantity' => $quantity]);
        }
    }

    /**
     * Remove a product from the cart.
     *
     * @param string $rowId
     * @return void
     */
    public function remove(string $rowId): void
    {
        // For authenticated users, use database
        if (Auth::check()) {
            $this->removeFromDatabase($rowId);
            return;
        }

        // For guests, use session
        $cart = Session::get($this->sessionKey, []);

        if (isset($cart[$rowId])) {
            unset($cart[$rowId]);
            Session::put($this->sessionKey, $cart);
        }
    }

    /**
     * Remove cart item from database
     */
    protected function removeFromDatabase(string $rowId): void
    {
        $items = $this->getItemsFromDatabase();
        $item = $items->firstWhere('id', $rowId);
        
        if (!$item) {
            return;
        }

        $cart = $this->getOrCreateCart();
        $cart->items()
            ->where('product_id', $item->product_id)
            ->get()
            ->first(function ($ci) use ($item) {
                return $ci->variants == $item->variants;
            })?->delete();
    }

    /**
     * Clear the entire cart.
     *
     * @return void
     */
    public function clear(): void
    {
        if (Auth::check()) {
            $cart = $this->getOrCreateCart();
            $cart->items()->delete();
            return;
        }

        Session::forget($this->sessionKey);
    }

    /**
     * Merge session cart into database cart on login
     */
    public function mergeSessionCart(): void
    {
        if (!Auth::check()) {
            return;
        }

        $sessionCart = Session::get($this->sessionKey, []);
        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $item) {
            try {
                $this->add(
                    $item['product_id'],
                    $item['quantity'],
                    $item['variants'] ?? []
                );
            } catch (\Exception $e) {
                // Skip items that can't be added (out of stock, etc.)
                continue;
            }
        }

        // Clear session cart after merging
        Session::forget($this->sessionKey);
    }

    /**
     * Calculate the total price of the cart.
     *
     * @return float
     */
    public function total(): float
    {
        return $this->getItems()->sum('subtotal');
    }

    /**
     * Get the number of items in the cart.
     *
     * @return int
     */
    public function count(): int
    {
        if (Auth::check()) {
            $cart = $this->getOrCreateCart();
            return $cart->items()->sum('quantity');
        }

        $cart = Session::get($this->sessionKey, []);
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Calculate unit price with variant adjustments.
     */
    protected function calculatePrice(Product $product, array $variants = []): float
    {
        $price = $product->discount_price ?? $product->base_price;

        foreach ($variants as $type => $value) {
            $variant = $product->variants
                ->where('type', $type)
                ->where('value', $value)
                ->first();

            if ($variant) {
                $price += $variant->price_adjustment;
            }
        }

        return $price;
    }

    /**
     * Get available stock for a product + selected variants.
     */
    public function getAvailableStock(Product $product, array $variants = []): int
    {
        $available = $product->stock_quantity;

        if (!empty($variants)) {
            foreach ($variants as $type => $value) {
                $variant = $product->variants
                    ->where('type', $type)
                    ->where('value', $value)
                    ->first();

                if (!$variant) {
                    return 0;
                }

                $available = min($available, (int) $variant->stock_quantity);
            }
        }

        return max($available, 0);
    }
}
