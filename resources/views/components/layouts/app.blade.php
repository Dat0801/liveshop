<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'LiveShop - E-commerce Platform' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Brand -->
                <div class="flex items-center space-x-8">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-orange-500 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-900">LiveShop</span>
                    </a>
                    
                    <!-- Main Navigation Menu -->
                    <div class="hidden md:flex md:space-x-8">
                        <a href="/" class="text-gray-700 hover:text-orange-500 text-sm font-medium">Home</a>
                        <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-orange-500 text-sm font-medium">Fashion</a>
                        <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-orange-500 text-sm font-medium">Electronics</a>
                        <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-orange-500 text-sm font-medium">Home</a>
                        <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-orange-500 text-sm font-medium">Beauty</a>
                        <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-orange-500 text-sm font-medium">Sport</a>
                    </div>
                </div>

                <!-- Center Search -->
                <div class="hidden md:block flex-1 max-w-md mx-4">
                    <div class="relative">
                        <input type="search" placeholder="Search for products or..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                        <svg class="absolute right-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Right Side Icons -->
                <div class="flex items-center space-x-6">
                    <!-- Wishlist Icon -->
                    <button class="text-gray-700 hover:text-orange-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>

                    <!-- Cart Icon with Counter -->
                    @livewire('cart-icon')

                    <!-- User Menu or Auth Links -->
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-orange-500 transition">
                                <div class="w-8 h-8 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="hidden sm:inline text-sm font-medium">{{ Auth::user()->name }}</span>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-orange-600">My Profile</a>
                                <a href="{{ route('profile.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-orange-600">Orders</a>
                                @role('admin')
                                    <hr class="my-1">
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-orange-600">Admin Panel</a>
                                @endrole
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-orange-600">
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-orange-500 text-sm font-medium">Login</a>
                            <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium py-2 px-4 rounded-lg transition">Register</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Shopping Cart Sidebar -->
    @livewire('shopping-cart')

    <!-- Main Content -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8 mb-8">
                <!-- Brand Section -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-orange-500 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-900">LiveShop</span>
                    </div>
                    <p class="text-gray-600 text-sm">Experience the next generation of shopping with live engagement and exclusive live drops.</p>
                </div>

                <!-- Shop Section -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Shop</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-orange-500 transition">Featured Creators</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition">Daily Sales</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition">Gift Cards</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition">Live Schedule</a></li>
                    </ul>
                </div>

                <!-- Support Section -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Support</h4>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-orange-500 transition">Help Center</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition">Track Order</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition">Shipping Info</a></li>
                        <li><a href="#" class="hover:text-orange-500 transition">Returns</a></li>
                    </ul>
                </div>

                <!-- Newsletter Section -->
                <div class="md:col-span-2">
                    <h4 class="font-semibold text-gray-900 mb-4">Newsletter</h4>
                    <p class="text-gray-600 text-sm mb-4">Get the latest on new streams and drops</p>
                    <div class="flex gap-2">
                        <input type="email" placeholder="Email" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded-lg transition text-sm">
                            Join
                        </button>
                    </div>
                </div>
            </div>

            <!-- Social and Links -->
            <div class="border-t border-gray-200 pt-8">
                <div class="flex justify-between items-center flex-col sm:flex-row gap-4">
                    <div class="flex items-center space-x-6">
                        <a href="#" class="text-gray-600 hover:text-orange-500 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-orange-500 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417a9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                    </div>

                    <div class="text-center text-sm text-gray-600">
                        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-4">
                            <a href="#" class="hover:text-orange-500">PRIVACY POLICY</a>
                            <a href="#" class="hover:text-orange-500">TERMS OF SERVICE</a>
                            <a href="#" class="hover:text-orange-500">COOKIE SETTINGS</a>
                        </div>
                        <p>&copy; 2026 LIVESHOP INTERACTIVE. ALL RIGHTS RESERVED.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
