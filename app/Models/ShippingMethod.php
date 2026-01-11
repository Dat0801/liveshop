<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'rate',
        'min_order',
        'max_order',
        'min_weight',
        'max_weight',
        'processing_days_min',
        'processing_days_max',
        'applicable_countries',
        'applicable_regions',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'applicable_countries' => 'array',
        'applicable_regions' => 'array',
        'is_active' => 'boolean',
        'rate' => 'decimal:2',
        'min_order' => 'decimal:2',
        'max_order' => 'decimal:2',
    ];

    public function calculateRate(float $subtotal, int $weight = 0): float
    {
        if ($this->type === 'flat') {
            return $this->rate;
        }

        if ($this->type === 'weight-based' && $weight > 0) {
            return ($weight / 1000) * $this->rate; // Assuming weight in grams
        }

        if ($this->type === 'amount-based') {
            return $subtotal * ($this->rate / 100);
        }

        return 0;
    }

    public function isAvailable(float $subtotal = 0, int $weight = 0, ?string $country = null): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->min_order && $subtotal < $this->min_order) {
            return false;
        }

        if ($this->max_order && $subtotal > $this->max_order) {
            return false;
        }

        if ($this->min_weight && $weight < $this->min_weight) {
            return false;
        }

        if ($this->max_weight && $weight > $this->max_weight) {
            return false;
        }

        if ($country && $this->applicable_countries && !in_array($country, $this->applicable_countries)) {
            return false;
        }

        return true;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order');
    }
}
