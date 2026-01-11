<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <a href="{{ route('profile') }}" class="text-blue-600 hover:text-blue-700 font-semibold mb-6 inline-block">
                ← Back
            </a>

            <h1 class="text-3xl font-bold text-gray-900 mb-8">Change Password</h1>

            @if ($successMessage)
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                    {{ $successMessage }}
                </div>
            @endif

            @if ($errorMessage)
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                    {{ $errorMessage }}
                </div>
            @endif

            <form wire:submit="changePassword" class="space-y-6">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input 
                        type="password" 
                        id="current_password"
                        wire:model="current_password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••"
                    >
                    @error('current_password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input 
                        type="password" 
                        id="password"
                        wire:model="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input 
                        type="password" 
                        id="password_confirmation"
                        wire:model="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••"
                    >
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <button 
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                >
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>
