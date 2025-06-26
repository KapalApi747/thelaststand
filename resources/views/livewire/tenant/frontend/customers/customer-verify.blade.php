<div class="max-w-lg mx-auto px-8 py-12 text-center text-gray-900">
    <h1 class="text-2xl font-semibold mb-4">Verify Your Email</h1>
    <p class="mb-4">
        Before continuing, please check your email for a verification link.
        If you didn’t receive one, click the button below.
    </p>

    @if (session('message'))
        <div class="text-green-600 mb-4">{{ session('message') }}</div>
    @endif

    <form method="POST" action="{{ route('customer-verification.send') }}">
        @csrf
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white px-4 py-2 rounded transition-colors duration-300 cursor-pointer"
        >
            Resend Verification Email
        </button>
    </form>
</div>
