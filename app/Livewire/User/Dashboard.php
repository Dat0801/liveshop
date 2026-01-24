<?php

namespace App\Livewire\User;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title('Dashboard')]
class Dashboard extends Component
{
    #[\Livewire\Attributes\Computed]
    public function stats()
    {
        $user = Auth::user();
        return [
            'total_orders' => $user->orders()->count(),
            'total_points' => $user->points ?? 0,
            'active_vouchers' => $user->coupons()->where('is_active', true)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.user.dashboard', [
            'stats' => $this->stats,
        ])->layout('components.layouts.app');
    }
}
