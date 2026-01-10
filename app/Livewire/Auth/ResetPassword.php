<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

#[Title('Reset Password')]
class ResetPassword extends Component
{
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?string $successMessage = null;

    protected $rules = [
        'email' => 'required|string|email',
        'password' => 'required|string|min:8|confirmed',
    ];

    protected $messages = [
        'email.required' => 'Please enter your email',
        'email.email' => 'Invalid email address',
        'password.required' => 'Please enter your password',
        'password.min' => 'Password must be at least 8 characters',
        'password.confirmed' => 'Password confirmation does not match',
    ];

    public function mount($token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->saveQuietly();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->successMessage = 'Password has been reset successfully!';
            return redirect()->route('login');
        }

        session()->flash('error', __($status));
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
