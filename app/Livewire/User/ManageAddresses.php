<?php

namespace App\Livewire\User;

use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;

#[Title('Manage Addresses')]
class ManageAddresses extends Component
{
    public bool $showForm = false;
    public ?Address $editingAddress = null;
    public string $full_name = '';
    public string $phone_number = '';
    public string $street_address = '';
    public string $city = '';
    public string $state = '';
    public string $postal_code = '';
    public string $country = '';
    public string $notes = '';
    public bool $is_default = false;
    public ?string $successMessage = null;

    protected $rules = [
        'full_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'street_address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'postal_code' => 'required|string|max:20',
        'country' => 'required|string|max:100',
        'notes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'full_name.required' => 'Please enter your full name',
        'phone_number.required' => 'Please enter your phone number',
        'street_address.required' => 'Please enter your address',
        'city.required' => 'Please enter your city',
        'state.required' => 'Please enter your state',
        'postal_code.required' => 'Please enter your postal code',
        'country.required' => 'Please enter your country',
    ];

    #[\Livewire\Attributes\Computed]
    public function addresses()
    {
        return Auth::user()->addresses()->get();
    }

    public function openForm()
    {
        $this->reset();
        $this->showForm = true;
        $this->editingAddress = null;
    }

    public function editAddress(Address $address)
    {
        $this->editingAddress = $address;
        $this->full_name = $address->full_name;
        $this->phone_number = $address->phone_number;
        $this->street_address = $address->street_address;
        $this->city = $address->city;
        $this->state = $address->state;
        $this->postal_code = $address->postal_code;
        $this->country = $address->country;
        $this->notes = $address->notes ?? '';
        $this->is_default = $address->is_default;
        $this->showForm = true;
    }

    public function saveAddress()
    {
        $this->validate();

        $user = Auth::user();

        if ($this->is_default) {
            $user->addresses()->update(['is_default' => false]);
        }

        if ($this->editingAddress) {
            $this->editingAddress->update([
                'full_name' => $this->full_name,
                'phone_number' => $this->phone_number,
                'street_address' => $this->street_address,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'notes' => $this->notes,
                'is_default' => $this->is_default,
            ]);
        } else {
            $user->addresses()->create([
                'full_name' => $this->full_name,
                'phone_number' => $this->phone_number,
                'street_address' => $this->street_address,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
                'notes' => $this->notes,
                'is_default' => $this->is_default,
            ]);
        }

        $this->successMessage = 'Address saved successfully!';
        $this->reset();
        $this->showForm = false;
    }

    public function deleteAddress(Address $address)
    {
        $address->delete();
        $this->successMessage = 'Address deleted successfully!';
    }

    public function setDefault(Address $address)
    {
        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        $this->successMessage = 'Default address has been updated!';
    }

    public function render()
    {
        return view('livewire.user.manage-addresses');
    }
}
