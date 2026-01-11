<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Password;

#[Title('Forgot Password')]
class ForgotPassword extends Component
{
    public string $email = '';
    public ?string $successMessage = null;

    protected $rules = [
        'email' => 'required|string|email|exists:users',
    ];

    protected $messages = [
        'email.required' => 'Please enter your email',
        'email.email' => 'Invalid email address',
        'email.exists' => 'Email does not exist in the system',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->successMessage = 'Password reset link has been sent to your email!';
            $this->email = '';
        } else {
            session()->flash('error', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
