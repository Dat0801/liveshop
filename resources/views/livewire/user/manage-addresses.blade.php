<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <a href="{{ route('profile') }}" class="text-blue-600 hover:text-blue-700 font-semibold mb-4 inline-block">
                        ← Back
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">My Addresses</h1>
                </div>
                @if (!$showForm)
                    <button 
                        wire:click="openForm"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200"
                    >
                        + Add Address
                    </button>
                @endif
            </div>

            @if ($successMessage)
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                    {{ $successMessage }}
                </div>
            @endif

            <!-- Form -->
            @if ($showForm)
                <div class="mb-8 p-6 bg-gray-50 border border-gray-200 rounded-lg">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        {{ $editingAddress ? 'Edit Address' : 'Add New Address' }}
                    </h2>

                    <form wire:submit="saveAddress" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input 
                                    type="text" 
                                    id="full_name"
                                    wire:model="full_name"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                @error('full_name')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input 
                                    type="tel" 
                                    id="phone_number"
                                    wire:model="phone_number"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                @error('phone_number')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="street_address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <input 
                                type="text" 
                                id="street_address"
                                wire:model="street_address"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            @error('street_address')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input 
                                    type="text" 
                                    id="city"
                                    wire:model="city"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                @error('city')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                                <input 
                                    type="text" 
                                    id="state"
                                    wire:model="state"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                @error('state')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                <input 
                                    type="text" 
                                    id="postal_code"
                                    wire:model="postal_code"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                @error('postal_code')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <input 
                                    type="text" 
                                    id="country"
                                    wire:model="country"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                >
                                @error('country')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea 
                                id="notes"
                                wire:model="notes"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            ></textarea>
                        </div>

                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="is_default"
                                wire:model="is_default"
                                class="h-4 w-4 rounded border-gray-300"
                            >
                            <label for="is_default" class="ml-2 block text-sm text-gray-700">
                                Set as default address
                            </label>
                        </div>

                        <div class="flex gap-4">
                            <button 
                                type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                            >
                                {{ $editingAddress ? 'Update Address' : 'Add Address' }}
                            </button>
                            <button 
                                type="button"
                                wire:click="reset"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 font-semibold py-2 px-4 rounded-lg transition duration-200"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Address List -->
            @if ($this->addresses->count() > 0)
                <div class="space-y-4">
                    @foreach ($this->addresses as $address)
                        <div class="p-6 border border-gray-300 rounded-lg hover:shadow-md transition duration-200">
                            @if ($address->is_default)
                                <div class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full mb-4">
                                    Default
                                </div>
                            @endif
                            
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $address->full_name }}</h3>
                            <p class="text-gray-600 mb-1">{{ $address->street_address }}</p>
                            <p class="text-gray-600 mb-1">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                            <p class="text-gray-600 mb-4">{{ $address->country }}</p>
                            <p class="text-gray-600 mb-4">Phone: {{ $address->phone_number }}</p>
                            
                            @if ($address->notes)
                                <p class="text-gray-500 text-sm mb-4 italic">Notes: {{ $address->notes }}</p>
                            @endif

                            <div class="flex gap-4">
                                <button 
                                    wire:click="editAddress({{ $address->id }})"
                                    class="text-blue-600 hover:text-blue-700 font-semibold text-sm"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="deleteAddress({{ $address->id }})"
                                    class="text-red-600 hover:text-red-700 font-semibold text-sm"
                                    onclick="return confirm('Are you sure you want to delete this address?')"
                                >
                                    Delete
                                </button>
                                @if (!$address->is_default)
                                    <button 
                                        wire:click="setDefault({{ $address->id }})"
                                        class="text-green-600 hover:text-green-700 font-semibold text-sm"
                                    >
                                        Set as Default
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 mb-6">You have not saved any address</p>
                    <button 
                        wire:click="openForm"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200"
                    >
                        + Add Address
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
