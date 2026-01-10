@if ($errorMessage)
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
        {{ $errorMessage }}
    </div>
@endif

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-md bg-white rounded-lg shadow-xl p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2 text-center">Login</h1>
        <p class="text-gray-600 text-center mb-8">Sign in to your account to continue shopping</p>

        <form wire:submit="login" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email"
                    wire:model="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="your@email.com"
                >
                @error('email')
                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
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

            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="remember"
                    wire:model="remember"
                    class="h-4 w-4 rounded border-gray-300"
                >
                <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>

            <button 
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
            >
                Login
            </button>
        </form>

        <div class="mt-6 space-y-3 text-center">
            <p>
                <a href="{{ route('password.request') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Forgot password?</a>
            </p>
            <p class="text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Register here</a>
            </p>
        </div>
    </div>
</div>
