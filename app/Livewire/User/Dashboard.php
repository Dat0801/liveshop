<?php

namespace App\Livewire\User;

use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{
    #[\Livewire\Attributes\Computed]
    public function stats(): array
    {
        $user = Auth::user();

        $activeVouchers = Coupon::valid()
            ->get()
            ->filter(fn (Coupon $coupon) => $coupon->isValid($user->id))
            ->count();

        return [
            'total_orders' => $user->orders()->count(),
            'total_points' => $user->points ?? 0,
            'active_vouchers' => $activeVouchers,
        ];
    }

    public function render(): View
    {
        return view('livewire.user.dashboard', [
            'stats' => $this->stats,
        ])->layout('components.layouts.app');
    }
}
