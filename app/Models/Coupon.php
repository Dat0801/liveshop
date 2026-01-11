<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'usage_count',
        'per_user_limit',
        'valid_from',
        'valid_until',
        'is_active',
        'applicable_categories',
        'applicable_products',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
        'applicable_categories' => 'array',
        'applicable_products' => 'array',
    ];

    /**
     * Check if the coupon is valid for use
     */
    public function isValid(?int $userId = null): bool
    {
        // Check if active
        if (!$this->is_active) {
            return false;
        }

        // Check date validity
        $now = now();
        if ($this->valid_from && $now->lt($this->valid_from)) {
            return false;
        }
        if ($this->valid_until && $now->gt($this->valid_until)) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        // Check per user limit
        if ($userId && $this->per_user_limit) {
            $userUsageCount = Order::where('user_id', $userId)
                ->where('coupon_code', $this->code)
                ->count();
            
            if ($userUsageCount >= $this->per_user_limit) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if coupon is applicable to given cart items
     */
    public function isApplicable(array $cartItems): bool
    {
        if (empty($this->applicable_categories) && empty($this->applicable_products)) {
            return true; // No restrictions
        }

        foreach ($cartItems as $item) {
            // Check product restriction
            if (!empty($this->applicable_products) && in_array($item['product_id'], $this->applicable_products)) {
                return true;
            }

            // Check category restriction
            if (!empty($this->applicable_categories)) {
                $product = Product::find($item['product_id']);
                if ($product && in_array($product->category_id, $this->applicable_categories)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
            
            // Apply max discount limit if set
            if ($this->max_discount && $discount > $this->max_discount) {
                return $this->max_discount;
            }
            
            return $discount;
        }

        // Fixed amount discount
        return min($this->value, $subtotal);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Scope to get only valid coupons
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereRaw('usage_count < usage_limit');
            });
    }
}
