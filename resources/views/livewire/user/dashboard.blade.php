<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Hello, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600">Manage your account and orders</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-gray-500 text-sm font-semibold mb-2">TOTAL ORDERS</div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-gray-500 text-sm font-semibold mb-2">TOTAL SPENT</div>
                <div class="text-3xl font-bold text-gray-900">${{ number_format($stats['total_spent'] ?? 0, 2) }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-gray-500 text-sm font-semibold mb-2">PENDING ORDERS</div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['pending_orders'] ?? 0 }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-gray-500 text-sm font-semibold mb-2">SAVED ADDRESSES</div>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['addresses_count'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('profile.settings') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
                <div class="text-gray-900 font-semibold mb-2">⚙️ Profile Settings</div>
                <p class="text-gray-600 text-sm">Update your personal information</p>
            </a>
            <a href="{{ route('profile.change-password') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
                <div class="text-gray-900 font-semibold mb-2">🔐 Change Password</div>
                <p class="text-gray-600 text-sm">Change your account password</p>
            </a>
            <a href="{{ route('profile.addresses') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
                <div class="text-gray-900 font-semibold mb-2">📍 Addresses</div>
                <p class="text-gray-600 text-sm">Manage delivery addresses</p>
            </a>
            <a href="{{ route('profile.orders') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
                <div class="text-gray-900 font-semibold mb-2">📦 Orders</div>
                <p class="text-gray-600 text-sm">View order history</p>
            </a>
        </div>

        <!-- Logout -->
        <div class="text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button 
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200"
                >
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
