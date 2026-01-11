<?php

namespace App\Livewire\User;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

#[Title('Change Password')]
class ChangePassword extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    protected $rules = [
        'current_password' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ];

    protected $messages = [
        'current_password.required' => 'Please enter your current password',
        'password.required' => 'Please enter your new password',
        'password.min' => 'Password must be at least 8 characters',
        'password.confirmed' => 'Password confirmation does not match',
    ];

    public function changePassword()
    {
        $this->validate();

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->errorMessage = 'Current password is incorrect';
            return;
        }

        $user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->successMessage = 'Password changed successfully!';
    }

    public function render()
    {
        return view('livewire.user.change-password');
    }
}
