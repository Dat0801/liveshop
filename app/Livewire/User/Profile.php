<?php

namespace App\Livewire\User;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title('User Profile')]
class Profile extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public ?string $successMessage = null;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
    }

    protected $rules = [
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:20',
    ];

    protected $messages = [
        'name.required' => 'Please enter your name',
        'name.max' => 'Name cannot exceed 255 characters',
    ];

    public function updateProfile()
    {
        $this->validate();

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'phone' => $this->phone,
        ]);

        $this->successMessage = 'Profile updated successfully!';
        $this->dispatch('user-updated');
    }

    public function render()
    {
        return view('livewire.user.profile');
    }
}
