<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use DomainException;

class CartService
{
    protected $sessionKey = 'cart';

    /**
     * Get all items in the cart.
     *
     * @return Collection
     */
    public function getItems(): Collection
    {
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
                }
            }

            // Calculate price with variants
            $price = $this->calculatePrice($product, $item['variants'] ?? []);

            return (object) [
                'id' => $rowId, // Use the rowId as the unique identifier for the cart item
                'product_id' => $product->id,
                'product' => $product,
                'variants' => $item['variants'] ?? [],
                'quantity' => $item['quantity'],
                'price' => $price,
                'subtotal' => $price * $item['quantity'],
            ];
        })->filter();

        return $items;
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

        $cart = Session::get($this->sessionKey, []);
        
        // Generate a unique ID for this specific combination of product + variants
        // We sort variants to ensure array order doesn't affect the ID
        ksort($variants);
        $rowId = md5($productId . serialize($variants));

        $currentQuantity = $cart[$rowId]['quantity'] ?? 0;
        $desiredQuantity = $currentQuantity + $quantity;

        $available = $this->getAvailableStock($product, $variants);

        if ($available <= 0) {
            throw new DomainException("{$product->name} is out of stock.");
        }

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
     * Update the quantity of a product in the cart.
     *
     * @param string $rowId
     * @param int $quantity
     * @return void
     */
    public function update(string $rowId, int $quantity): void
    {
        $cart = Session::get($this->sessionKey, []);

        if ($quantity <= 0) {
            $this->remove($rowId);
            return;
        }

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
     * Remove a product from the cart.
     *
     * @param string $rowId
     * @return void
     */
    public function remove(string $rowId): void
    {
        $cart = Session::get($this->sessionKey, []);

        if (isset($cart[$rowId])) {
            unset($cart[$rowId]);
            Session::put($this->sessionKey, $cart);
        }
    }

    /**
     * Clear the entire cart.
     *
     * @return void
     */
    public function clear(): void
    {
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

    // Database syncing removed; cart is session-only.
}
