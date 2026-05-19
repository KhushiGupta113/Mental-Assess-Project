@extends('layouts.main')

@section('content')
{{-- Emergency Banner --}}
<div class="bg-gradient-to-r from-red-500 to-red-600 text-white py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="font-bold text-base">🚨 If you are in immediate danger, please call your local emergency number (911, 112, etc.)</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="text-center mb-8" data-aos="fade-down">
        <h1 class="section-heading mb-3">Crisis Support & Helplines</h1>
        <p class="section-subheading mx-auto">You are not alone. Help is available 24/7. Reach out to any of these resources if you or someone you know is in crisis.</p>
    </div>

    {{-- Search by Country --}}
    <div class="glass-card-solid p-5 mb-8" data-aos="fade-up">
        <form method="GET" action="{{ route('crisis.index') }}" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-xs font-semibold text-sage-600 mb-1">🔍 Find Help in Your Country</label>
                <select name="country" class="input-nature text-sm !py-2.5">
                    <option value="">All Countries</option>
                    @foreach($countries as $c)
                    <option value="{{ $c }}" {{ $search == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-nature !py-2.5 !text-sm whitespace-nowrap">Search Helplines</button>
            @if($search)
            <a href="{{ route('crisis.index') }}" class="text-sage-400 hover:text-sage-600 text-sm font-medium">Clear</a>
            @endif
        </form>
        @if($userCountry && !$search)
        <p class="text-xs text-teal-600 mt-2 flex items-center">
            <span class="mr-1">📍</span> Showing results for <strong class="ml-1">{{ $userCountry }}</strong> first (based on your profile).
        </p>
        @endif
    </div>

    {{-- Quick Help --}}
    <div class="crisis-banner p-6 mb-8 text-center" data-aos="fade-up">
        <h2 class="font-serif font-bold text-sage-800 text-lg mb-2">Need Immediate Help?</h2>
        <p class="text-sage-600 text-sm mb-4">If you're feeling suicidal or are in crisis, please reach out now:</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="tel:1800-599-0019" class="btn-crisis !animate-none">🇮🇳 India: 1800-599-0019</a>
            <a href="tel:988" class="btn-crisis !animate-none">🇺🇸 USA: 988</a>
            <a href="tel:116123" class="btn-crisis !animate-none">🇬🇧 UK: 116 123</a>
            <a href="tel:1-833-456-4566" class="btn-crisis !animate-none">🇨🇦 Canada: 1-833-456-4566</a>
            <a href="tel:13-11-14" class="btn-crisis !animate-none">🇦🇺 Australia: 13 11 14</a>
        </div>
    </div>

    {{-- Resources by Country (Primary) --}}
    @foreach($resources as $country => $countryResources)
    <div class="mb-8" data-aos="fade-up">
        <h2 class="font-serif font-bold text-sage-800 text-xl mb-4 flex items-center">
            @php
            $flags = ['India'=>'🇮🇳','USA'=>'🇺🇸','UK'=>'🇬🇧','Canada'=>'🇨🇦','Australia'=>'🇦🇺','Germany'=>'🇩🇪','France'=>'🇫🇷','Japan'=>'🇯🇵','Brazil'=>'🇧🇷','South Africa'=>'🇿🇦','Nigeria'=>'🇳🇬','Pakistan'=>'🇵🇰','Bangladesh'=>'🇧🇩','Philippines'=>'🇵🇭','Mexico'=>'🇲🇽'];
            @endphp
            <span class="mr-2">{{ $flags[$country] ?? '🌍' }}</span>
            {{ $country }}
            @if($userCountry === $country && !$search)
            <span class="badge-teal text-[10px] ml-2">Your country</span>
            @endif
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($countryResources as $resource)
            <div class="glass-card-solid p-5 border-l-4 border-red-300 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-2">
                    <h3 class="font-bold text-sage-800">{{ $resource->name }}</h3>
                    @if($resource->type)
                    <span class="badge-{{ $resource->type === 'text_line' ? 'teal' : ($resource->type === 'chat' ? 'indigo' : 'danger') }} text-[10px]">{{ ucfirst(str_replace('_', ' ', $resource->type)) }}</span>
                    @endif
                </div>
                <p class="text-sage-600 text-sm mb-3">{{ $resource->description }}</p>
                <div class="space-y-1.5">
                    <p class="flex items-center text-sm">
                        <span class="text-sage-400 mr-2">📞</span>
                        <a href="tel:{{ $resource->phone }}" class="font-bold text-red-500 hover:text-red-600">{{ $resource->phone }}</a>
                    </p>
                    @if($resource->available_hours)
                    <p class="flex items-center text-sm text-sage-500">
                        <span class="mr-2">🕐</span>{{ $resource->available_hours }}
                    </p>
                    @endif
                    @if(!empty($resource->languages) && is_array($resource->languages))
                    <p class="flex items-center text-sm text-sage-500">
                        <span class="mr-2">🌐</span>{{ implode(', ', $resource->languages) }}
                    </p>
                    @endif
                    @if($resource->url)
                    <p class="flex items-center text-sm">
                        <span class="mr-2">🔗</span>
                        <a href="{{ $resource->url }}" target="_blank" rel="noopener" class="text-teal-600 hover:underline">Visit Website →</a>
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Other countries --}}
    @if($otherResources && $otherResources->count() > 0)
    <div class="border-t border-sage-200 pt-8 mt-4">
        <h2 class="font-serif font-bold text-sage-700 text-lg mb-6 text-center">Other Countries</h2>
        @foreach($otherResources as $country => $countryResources)
        <div class="mb-6" data-aos="fade-up">
            <h3 class="font-serif font-bold text-sage-800 text-lg mb-3 flex items-center">
                <span class="mr-2">{{ $flags[$country] ?? '🌍' }}</span>{{ $country }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($countryResources as $resource)
                <div class="glass-card-solid p-4 border-l-4 border-red-200">
                    <h4 class="font-bold text-sage-800 text-sm mb-1">{{ $resource->name }}</h4>
                    <p class="text-sage-500 text-xs mb-2">{{ $resource->description }}</p>
                    <a href="tel:{{ $resource->phone }}" class="font-bold text-red-500 hover:text-red-600 text-sm">📞 {{ $resource->phone }}</a>
                    @if($resource->url)
                    <span class="text-sage-300 mx-2">•</span>
                    <a href="{{ $resource->url }}" target="_blank" rel="noopener" class="text-teal-600 hover:underline text-xs">Website →</a>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Safety Planning --}}
    <div class="glass-card-solid p-8 mt-8" data-aos="fade-up">
        <h2 class="font-serif font-bold text-sage-800 text-xl mb-4 text-center">💚 Safety Planning Tips</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-sage-600">
            <div class="p-4 bg-sage-50 rounded-xl">
                <p class="font-semibold text-sage-700 mb-2">1. Recognize Warning Signs</p>
                <p>Know your personal triggers and early warning signs of a crisis.</p>
            </div>
            <div class="p-4 bg-sage-50 rounded-xl">
                <p class="font-semibold text-sage-700 mb-2">2. Use Coping Strategies</p>
                <p>Deep breathing, grounding exercises, and safe activities can help.</p>
            </div>
            <div class="p-4 bg-sage-50 rounded-xl">
                <p class="font-semibold text-sage-700 mb-2">3. Reach Out to Others</p>
                <p>Contact a trusted friend, family member, or helpline.</p>
            </div>
            <div class="p-4 bg-sage-50 rounded-xl">
                <p class="font-semibold text-sage-700 mb-2">4. Make Your Environment Safe</p>
                <p>Remove or secure items that could be used for self-harm.</p>
            </div>
        </div>
    </div>
</div>
@endsection
