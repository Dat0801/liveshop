<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

#[Title('Verify Email')]
class VerifyEmail extends Component
{
    public ?string $successMessage = null;

    public function sendVerificationEmail()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('profile');
        }

        Auth::user()->sendEmailVerificationNotification();

        $this->successMessage = 'Verification email has been sent!';
    }

    public function verifyEmail()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('profile');
        }

        if (Auth::user()->markEmailAsVerified()) {
            event(new Verified(Auth::user()));
        }

        return redirect()->route('profile');
    }

    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
