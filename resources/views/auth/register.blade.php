<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-2xl font-serif font-bold t-text">Create Your Account</h1>
        <p class="text-sm t-muted mt-1">Begin your path to better well-being</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium t-text mb-1.5">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="input-nature">
            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-sm text-red-500" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium t-text mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="input-nature">
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-sm text-red-500" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium t-text mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="input-nature">
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-sm text-red-500" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium t-text mb-1.5">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="input-nature">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-sm text-red-500" />
        </div>

        <button type="submit" class="btn-nature w-full !py-3 text-base">
            Create Account
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm t-muted">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color:var(--th-primary)">Sign in</a>
        </p>
    </div>
</x-guest-layout>
