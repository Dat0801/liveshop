<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Verify Email</h1>

            @if (auth()->user()->hasVerifiedEmail())
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                    ✓ Your email has been verified successfully!
                </div>
                <a href="{{ route('profile') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                    Go to profile
                </a>
            @else
                <div class="mb-6">
                    <p class="text-gray-600 mb-4">
                        A verification email has been sent to <strong>{{ auth()->user()->email }}</strong>
                    </p>
                    <p class="text-gray-600 mb-6">
                        Please check your email and click on the verification link.
                    </p>

                    @if ($successMessage)
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 mb-6">
                            {{ $successMessage }}
                        </div>
                    @endif

                    <button 
                        wire:click="sendVerificationEmail"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 mb-4"
                    >
                        Resend Verification Email
                    </button>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button 
                        type="submit"
                        class="text-gray-600 hover:text-gray-900 font-semibold"
                    >
                        Logout
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
