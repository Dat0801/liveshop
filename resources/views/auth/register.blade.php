@extends('layouts.guest')

@section('content')
<form method="POST" action="{{ route('register') }}" class="space-y-6">
    @csrf

    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" class="input w-full">
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700" for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="input w-full">
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700" for="password">Password</label>
        <input id="password" name="password" type="password" required autocomplete="new-password" class="input w-full">
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700" for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="input w-full">
    </div>

    <div>
        <button type="submit" class="btn btn-primary w-full">Create account</button>
    </div>

    <div class="text-center text-sm text-gray-500">
        <span class="mr-1">Already registered?</span>
        <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700">Log in</a>
    </div>
</form>
@endsection
