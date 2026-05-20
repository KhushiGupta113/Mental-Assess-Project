@extends('layouts.main')

@section('content')
    <div class="py-12 flex flex-col items-center">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="mb-4 sm:px-0 px-4">
                <h2 class="section-heading text-2xl">{{ __('Profile Settings') }}</h2>
                <p class="section-subheading">Update your account information and preferences.</p>
            </div>

            <div class="p-4 sm:p-8 t-card shadow-sm border border-th-border sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 t-card shadow-sm border border-th-border sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 t-card shadow-sm border border-th-border sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection


