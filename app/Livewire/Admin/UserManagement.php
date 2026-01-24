<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';
    public $showDetailsModal = false;
    public $showAddUserModal = false;
    public $selectedUser;

    // Add user form properties
    public $newUserName = '';
    public $newUserEmail = '';
    public $newUserPhone = '';
    public $newUserRole = 'customer';
    public $newUserPassword = '';

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

    public function openAddUserModal()
    {
        $this->showAddUserModal = true;
        $this->reset(['newUserName', 'newUserEmail', 'newUserPhone', 'newUserRole', 'newUserPassword']);
    }

    public function closeAddUserModal()
    {
        $this->showAddUserModal = false;
    }

    public function addUser()
    {
        $this->validate([
            'newUserName' => 'required|string|max:255',
            'newUserEmail' => 'required|email|unique:users,email',
            'newUserPhone' => 'nullable|string|max:20',
            'newUserPassword' => 'required|min:8',
            'newUserRole' => 'required|in:customer,admin',
        ]);

        $user = User::create([
            'name' => $this->newUserName,
            'email' => $this->newUserEmail,
            'phone' => $this->newUserPhone,
            'password' => bcrypt($this->newUserPassword),
        ]);

        $user->assignRole($this->newUserRole);

        session()->flash('message', 'User added successfully!');
        $this->closeAddUserModal();
        $this->reset(['newUserName', 'newUserEmail', 'newUserPhone', 'newUserRole', 'newUserPassword']);
    }

    public function exportCSV()
    {
        $users = User::with('roles')->get();
        
        $filename = 'users_export_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Status', 'Join Date']);

            foreach ($users as $user) {
                $role = $user->roles->first()?->name ?? 'customer';
                $status = $user->is_blocked ? 'Blocked' : 'Active';
                
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? '',
                    ucfirst($role),
                    $status,
                    $user->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function resetUserPassword($userId)
    {
        $user = User::findOrFail($userId);
        $tempPassword = 'TempPass' . random_int(10000, 99999);
        $user->update(['password' => bcrypt($tempPassword)]);
        
        session()->flash('message', "Password reset to: $tempPassword (User should change on login)");
    }

    public function toggleUserBlock($userId)
    {
        $user = User::findOrFail($userId);
        $user->is_blocked = !($user->is_blocked ?? false);
        $user->save();
        
        $status = $user->is_blocked ? 'blocked' : 'activated';
        session()->flash('message', "User has been {$status}!");
    }

    public function render()
    {
        $query = User::with('roles');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->role($this->roleFilter);
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_blocked', false);
        } elseif ($this->statusFilter === 'blocked') {
            $query->where('is_blocked', true);
        }

        $users = $query->orderByDesc('created_at')->paginate(4);
        $totalUsers = User::count();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'totalUsers' => $totalUsers,
        ])->layout('components.layouts.admin', [
            'header' => 'Users',
        ]);
    }
}
