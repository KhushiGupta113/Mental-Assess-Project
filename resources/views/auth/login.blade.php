<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-2xl font-serif font-bold t-text">Welcome Back</h1>
        <p class="text-sm t-muted mt-1">Sign in to continue your wellness journey</p>
    </div>

    <x-auth-session-status class="mb-4 text-sm p-3 rounded-xl text-teal-700 bg-teal-50" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium t-text mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="input-nature">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-sm text-red-500" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium t-text mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="input-nature">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-sm text-red-500" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-2 focus:ring-2 transition-colors"
                       style="border-color:var(--th-border-strong); color:var(--th-primary); --tw-ring-color:var(--th-primary-light)">
                <span class="ms-2 text-sm t-muted">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium hover:underline" style="color:var(--th-primary)" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-nature w-full !py-3 text-base">
            Sign In
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm t-muted">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color:var(--th-primary)">Create one</a>
        </p>
    </div>
</x-guest-layout>
