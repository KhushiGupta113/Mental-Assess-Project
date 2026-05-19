<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="age_group" :value="__('Age Group')" />
            <select id="age_group" name="age_group" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Select an option</option>
                <option value="under_18" {{ old('age_group', $user->age_group) == 'under_18' ? 'selected' : '' }}>Under 18</option>
                <option value="18-24" {{ old('age_group', $user->age_group) == '18-24' ? 'selected' : '' }}>18 - 24</option>
                <option value="25-34" {{ old('age_group', $user->age_group) == '25-34' ? 'selected' : '' }}>25 - 34</option>
                <option value="35-44" {{ old('age_group', $user->age_group) == '35-44' ? 'selected' : '' }}>35 - 44</option>
                <option value="45-54" {{ old('age_group', $user->age_group) == '45-54' ? 'selected' : '' }}>45 - 54</option>
                <option value="55_plus" {{ old('age_group', $user->age_group) == '55_plus' ? 'selected' : '' }}>55+</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('age_group')" />
        </div>

        <div>
            <x-input-label for="occupation" :value="__('Occupation / Status')" />
            <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full" :value="old('occupation', $user->occupation)" placeholder="e.g. Student, Software Engineer, Unemployed" />
            <x-input-error class="mt-2" :messages="$errors->get('occupation')" />
        </div>

        <div>
            <x-input-label for="lifestyle_habits" :value="__('Lifestyle Habits')" />
            <textarea id="lifestyle_habits" name="lifestyle_habits" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" placeholder="Briefly describe your diet, exercise, and hobbies">{{ old('lifestyle_habits', $user->lifestyle_habits) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('lifestyle_habits')" />
        </div>

        <div>
            <x-input-label for="sleep_tracking" :value="__('Average Sleep (Hours)')" />
            <x-text-input id="sleep_tracking" name="sleep_tracking" type="number" step="0.5" class="mt-1 block w-full" :value="old('sleep_tracking', $user->sleep_tracking)" />
            <x-input-error class="mt-2" :messages="$errors->get('sleep_tracking')" />
        </div>

        <div class="flex items-center mt-4">
            <input id="is_anonymous" type="checkbox" name="is_anonymous" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_anonymous', $user->is_anonymous) ? 'checked' : '' }}>
            <label for="is_anonymous" class="ml-2 text-sm text-gray-600">{{ __('Anonymous Mode (Hide name on public interactions)') }}</label>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
