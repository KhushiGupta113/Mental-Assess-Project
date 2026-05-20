@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-10" data-aos="fade-down">
        <span class="badge-nature mb-3 inline-flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            Wellness Library
        </span>
        <h1 class="section-heading mb-3">Resources & Exercises</h1>
        <p class="section-subheading mx-auto">Evidence-based articles, breathing exercises, meditation guides, and coping strategies to support your mental wellness.</p>
    </div>

    {{-- Filters --}}
    @php
        $catIcons = [
            'anxiety' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'depression' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21v-2m-4 2v-2m8 2v-2"/></svg>',
            'meditation' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
            'sleep' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>',
            'stress' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
            'burnout' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>',
            'relationships' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>',
        ];
        $typeIcons = [
            'article' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-.586-1.414l-4.5-4.5A2 2 0 0012.586 3H5m14 17h-4"/></svg>',
            'exercise' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
            'breathing' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ];
        
        $currentCat = request('category', 'all');
        $currentCatLabel = $currentCat === 'all' ? 'All Categories' : ($categories[$currentCat] ?? 'All Categories');
        $currentCatIcon = $currentCat === 'all' ? '' : ($catIcons[$currentCat] ?? '');

        $currentType = request('type', 'all');
        $currentTypeLabel = $currentType === 'all' ? 'All Types' : ($contentTypes[$currentType] ?? 'All Types');
        $currentTypeIcon = $currentType === 'all' ? '' : ($typeIcons[$currentType] ?? '');
    @endphp

    <div class="glass-card-solid p-5 mb-8 relative z-50" data-aos="fade-up">
        <form method="GET" action="{{ route('resources.index') }}" class="flex flex-wrap gap-3 items-end" id="resource-filter-form">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold t-muted mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search resources..." class="input-nature text-sm !py-2 w-full">
            </div>
            
            <div class="relative min-w-[220px]" x-data="{ open: false, value: '{{ $currentCat }}', label: '{{ $currentCatLabel }}' }">
                <label class="block text-xs font-semibold t-muted mb-1">Category</label>
                <input type="hidden" name="category" x-model="value">
                <button type="button" @click="open = !open" @click.away="open = false" class="input-nature text-sm !py-2 w-full flex items-center justify-between text-left focus:ring-th-primary">
                    <span class="flex items-center gap-2 t-text">
                        {!! $currentCatIcon !!}
                        <span x-text="label"></span>
                    </span>
                    <svg class="w-4 h-4 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition.opacity class="absolute z-20 mt-1 w-full bg-white dark:bg-th-surface border border-th-border rounded-xl shadow-xl overflow-hidden py-1" style="display: none;">
                    <button type="button" @click="value = 'all'; label = 'All Categories'; open = false; $nextTick(() => { document.getElementById('resource-filter-form').submit(); })" class="w-full text-left px-4 py-2.5 text-sm hover:bg-th-surface-alt flex items-center gap-2 t-text font-medium">
                        All Categories
                    </button>
                    @foreach($categories as $key => $label)
                    <button type="button" @click="value = '{{ $key }}'; label = '{{ $label }}'; open = false; $nextTick(() => { document.getElementById('resource-filter-form').submit(); })" class="w-full text-left px-4 py-2.5 text-sm hover:bg-th-surface-alt flex items-center gap-2 t-text">
                        <span class="t-muted">{!! $catIcons[$key] ?? '' !!}</span>
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="relative min-w-[200px]" x-data="{ open: false, value: '{{ $currentType }}', label: '{{ $currentTypeLabel }}' }">
                <label class="block text-xs font-semibold t-muted mb-1">Type</label>
                <input type="hidden" name="type" x-model="value">
                <button type="button" @click="open = !open" @click.away="open = false" class="input-nature text-sm !py-2 w-full flex items-center justify-between text-left focus:ring-th-primary">
                    <span class="flex items-center gap-2 t-text">
                        {!! $currentTypeIcon !!}
                        <span x-text="label"></span>
                    </span>
                    <svg class="w-4 h-4 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition.opacity class="absolute z-20 mt-1 w-full bg-white dark:bg-th-surface border border-th-border rounded-xl shadow-xl overflow-hidden py-1" style="display: none;">
                    <button type="button" @click="value = 'all'; label = 'All Types'; open = false; $nextTick(() => { document.getElementById('resource-filter-form').submit(); })" class="w-full text-left px-4 py-2.5 text-sm hover:bg-th-surface-alt flex items-center gap-2 t-text font-medium">
                        All Types
                    </button>
                    @foreach($contentTypes as $key => $label)
                    <button type="button" @click="value = '{{ $key }}'; label = '{{ $label }}'; open = false; $nextTick(() => { document.getElementById('resource-filter-form').submit(); })" class="w-full text-left px-4 py-2.5 text-sm hover:bg-th-surface-alt flex items-center gap-2 t-text">
                        <span class="t-muted">{!! $typeIcons[$key] ?? '' !!}</span>
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn-nature text-sm !py-2 hidden md:block">Filter</button>
            @if(request()->hasAny(['search', 'category', 'type']))
            <a href="{{ route('resources.index') }}" class="t-light hover:t-muted text-sm font-medium transition-colors hidden md:block">Clear</a>
            @endif
        </form>
    </div>

    {{-- Resources Grid --}}
    @if($resources->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($resources as $i => $resource)
        <a href="{{ route('resources.show', $resource) }}" class="glass-card-solid p-6 hover:shadow-lift hover:-translate-y-1 transition-all duration-300 block group" data-aos="fade-up" data-aos-delay="{{ $i * 40 }}">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-sage-100 flex items-center justify-center text-sage-600 group-hover:scale-110 transition-transform">
                        @if($resource->category === 'anxiety' || $resource->content_type === 'breathing')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($resource->category === 'stress' || $resource->category === 'burnout')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @elseif($resource->category === 'sleep')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        @elseif($resource->category === 'depression')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21v-2m-4 2v-2m8 2v-2"/></svg>
                        @elseif($resource->category === 'meditation')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        @elseif($resource->category === 'relationships')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @endif
                    </div>
                    <div>
                        <span class="badge-nature text-[10px]">{{ ucfirst($resource->category ?? 'General') }}</span>
                        <span class="badge-teal text-[10px] ml-0.5">{{ ucfirst($resource->content_type ?? 'Article') }}</span>
                    </div>
                </div>
                @if($resource->duration)
                <span class="text-xs t-light">{{ $resource->duration }}</span>
                @endif
            </div>
            <h3 class="font-serif font-bold t-text mb-2 group-hover:t-text transition-colors">{{ $resource->title }}</h3>
            <p class="t-muted text-sm leading-relaxed mb-3 line-clamp-2">{{ $resource->description }}</p>
            <div class="flex items-center justify-between">
                @if(!empty($resource->tags) && is_array($resource->tags))
                <div class="flex flex-wrap gap-1">
                    @foreach(array_slice($resource->tags, 0, 3) as $tag)
                    <span class="text-[10px] t-light t-surface px-2 py-0.5 rounded-full">#{{ $tag }}</span>
                    @endforeach
                </div>
                @else
                <div></div>
                @endif
                <span class="t-light group-hover:t-muted text-sm font-medium transition-colors flex items-center">
                    Read
                    <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-8">{{ $resources->appends(request()->query())->links() }}</div>
    @else
    <div class="text-center py-16 glass-card-solid">
        <svg class="w-16 h-16 mx-auto mb-4 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <h3 class="text-xl font-serif t-text mb-2">No resources found</h3>
        <p class="t-muted text-sm mb-4">Try adjusting your filters or search terms.</p>
        <a href="{{ route('resources.index') }}" class="btn-nature-outline !text-sm">View All Resources</a>
    </div>
    @endif
</div>
@endsection

