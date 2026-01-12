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
            'total_spent' => $user->orders()->sum('total'),
            'pending_orders' => $user->orders()->where('status', 'pending')->count(),
            'addresses_count' => $user->addresses()->count(),
        ];
    }

    public function render()
    {
        return view('livewire.user.dashboard', [
            'stats' => $this->stats,
        ]);
    }
}
