<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $showDetailsModal = false;
    public $selectedUser;

    public function viewUser($id)
    {
        $this->selectedUser = User::with(['orders', 'addresses'])->findOrFail($id);
        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->selectedUser = null;
    }

    public function resetUserPassword($userId)
    {
        $user = User::findOrFail($userId);
        $tempPassword = 'TempPass' . random_int(10000, 99999);
        $user->update(['password' => bcrypt($tempPassword)]);
        
        session()->flash('message', "Password reset to: $tempPassword (User should change on login)");
    }

    public function toggleUserStatus($userId)
    {
        // Toggle is_active if you add it, or delete/restore
        $user = User::findOrFail($userId);
        $user->delete(); // Soft delete toggle
        
        session()->flash('message', 'User status updated!');
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(20);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ])->layout('components.layouts.admin', [
            'header' => 'Users',
        ]);
    }
}
