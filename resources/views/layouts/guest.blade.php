<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'LiveShop Auth' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="mb-8 text-center">
            <a href="/" class="text-3xl font-bold text-primary-600">LiveShop</a>
            <p class="text-gray-500 mt-1">Sign in to continue</p>
        </div>

        <main class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">
            @if (session('status'))
                <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-200 rounded px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-200 rounded px-4 py-3">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>

        <p class="mt-6 text-xs text-gray-500">&copy; 2026 LiveShop. All rights reserved.</p>
    </div>

    @livewireScripts
</body>
</html>
