<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title('Login')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;
    public ?string $errorMessage = null;

    protected $rules = [
        'email' => 'required|string|email',
        'password' => 'required|string',
    ];

    protected $messages = [
        'email.required' => 'Please enter your email',
        'email.email' => 'Invalid email address',
        'password.required' => 'Please enter your password',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            return redirect()->intended('/');
        }

        $this->errorMessage = 'Invalid email or password';
    }

    public function render()
    {
        return view('livewire.auth.login', [
            'errorMessage' => $this->errorMessage,
        ]);
    }
}
