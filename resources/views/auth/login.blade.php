@extends('layouts.guest')

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700" for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="input w-full">
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700" for="password">Password</label>
        <input id="password" name="password" type="password" required autocomplete="current-password" class="input w-full">
    </div>

    <div class="flex items-center justify-between">
        <label class="flex items-center text-sm text-gray-600">
            <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
            Remember me
        </label>
        <a href="{{ route('register') }}" class="text-sm text-primary-600 hover:text-primary-700">Create account</a>
    </div>

    <div>
        <button type="submit" class="btn btn-primary w-full">Log in</button>
    </div>

    <div class="text-center text-sm text-gray-500">
        <a href="/" class="hover:text-primary-600">Back to store</a>
    </div>
</form>
@endsection
