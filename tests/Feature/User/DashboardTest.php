<?php

namespace Tests\Feature\User;

use App\Livewire\User\Dashboard;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_counts_active_vouchers_for_the_dashboard(): void
    {
        $user = User::factory()->create();

        Coupon::create([
            'code' => 'SAVE10',
            'type' => 'percentage',
            'value' => 10,
            'min_purchase' => null,
            'max_discount' => null,
            'usage_limit' => null,
            'usage_count' => 0,
            'per_user_limit' => null,
            'valid_from' => now()->subDay(),
            'valid_until' => now()->addDay(),
            'is_active' => true,
            'applicable_categories' => [],
            'applicable_products' => [],
        ]);

        Coupon::create([
            'code' => 'EXPIRED',
            'type' => 'fixed',
            'value' => 5,
            'min_purchase' => null,
            'max_discount' => null,
            'usage_limit' => null,
            'usage_count' => 0,
            'per_user_limit' => null,
            'valid_from' => now()->subDays(10),
            'valid_until' => now()->subDay(),
            'is_active' => true,
            'applicable_categories' => [],
            'applicable_products' => [],
        ]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertViewHas('stats', function (array $stats) {
                return $stats['active_vouchers'] === 1
                    && $stats['total_orders'] === 0;
            });
    }
}
