<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class ProductReviews extends Component
{
    use WithPagination;

    public Product $product;

    public $showReviewForm = false;

    #[Validate('required|integer|min:1|max:5')]
    public $rating = 5;

    #[Validate('nullable|string|max:5000')]
    public $comment = '';

    #[Validate('nullable|string|max:255')]
    public $name = '';

    #[Validate('nullable|email|max:255')]
    public $email = '';

    public $canReview = false;

    public $hasPurchased = false;

    public $existingReview = null;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->checkCanReview();

        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->email = auth()->user()->email;
        }
    }

    protected function checkCanReview(): void
    {
        if (! auth()->check()) {
            $this->canReview = true; // Guests can review

            return;
        }

        // Check if user has purchased this product
        $this->hasPurchased = Order::where('user_id', auth()->id())
            ->where('status', '!=', 'cancelled')
            ->whereHas('items', function ($query) {
                $query->where('product_id', $this->product->id);
            })
            ->exists();

        // Check if user already reviewed
        $this->existingReview = Review::where('product_id', $this->product->id)
            ->where('user_id', auth()->id())
            ->first();

        $this->canReview = ! $this->existingReview;
    }

    public function openReviewForm()
    {
        if (! auth()->check()) {
            session()->flash('error', 'Please login to write a review.');

            return redirect()->route('login');
        }

        if ($this->existingReview) {
            session()->flash('error', 'You have already reviewed this product.');

            return;
        }

        $this->showReviewForm = true;
    }

    public function closeReviewForm()
    {
        $this->showReviewForm = false;
        $this->reset(['rating', 'comment', 'name', 'email']);
    }

    public function submitReview()
    {
        $this->validate();

        if (! auth()->check()) {
            session()->flash('error', 'Please login to write a review.');

            return;
        }

        if ($this->existingReview) {
            session()->flash('error', 'You have already reviewed this product.');

            return;
        }

        // Check if user has purchased (for verified purchase badge)
        $orderId = null;
        if ($this->hasPurchased) {
            $order = Order::where('user_id', auth()->id())
                ->where('status', '!=', 'cancelled')
                ->whereHas('items', function ($query) {
                    $query->where('product_id', $this->product->id);
                })
                ->first();
            $orderId = $order?->id;
        }

        Review::create([
            'product_id' => $this->product->id,
            'user_id' => auth()->id(),
            'order_id' => $orderId,
            'name' => auth()->user()->name ?? $this->name,
            'email' => auth()->user()->email ?? $this->email,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_approved' => true, // Auto-approve for now, can be changed to require admin approval
            'is_verified_purchase' => $this->hasPurchased,
        ]);

        session()->flash('message', 'Thank you for your review!');
        $this->closeReviewForm();
        $this->checkCanReview();
        $this->resetPage();
    }

    public function render()
    {
        $reviews = Review::where('product_id', $this->product->id)
            ->approved()
            ->with(['user', 'order'])
            ->latest()
            ->paginate(10);

        $ratingDistribution = Review::where('product_id', $this->product->id)
            ->approved()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating')
            ->toArray();

        return view('livewire.product-reviews', [
            'reviews' => $reviews,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }
}
