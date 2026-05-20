@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('resources.index') }}" class="inline-flex items-center text-sm t-muted hover:t-text mb-6 transition">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Resources
    </a>

    <article class="glass-card-solid overflow-hidden" data-aos="fade-up">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-sage-100 via-teal-50 to-sage-50 px-6 md:px-10 py-8 border-b border-th-border-strong/50">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl t-card/70 flex items-center justify-center text-sage-600 flex-shrink-0 shadow-sm">
                        @if($resource->category === 'anxiety' || $resource->content_type === 'breathing')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($resource->category === 'stress' || $resource->category === 'burnout')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @elseif($resource->category === 'sleep')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        @elseif($resource->category === 'depression')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21v-2m-4 2v-2m8 2v-2"/></svg>
                        @elseif($resource->category === 'meditation')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        @elseif($resource->category === 'relationships')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @else
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @endif
                </div>
                <div>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <span class="badge-nature text-xs">{{ ucfirst($resource->category ?? 'General') }}</span>
                        <span class="badge-teal text-xs">{{ ucfirst($resource->content_type ?? 'Article') }}</span>
                        @if($resource->difficulty)
                        <span class="badge-indigo text-xs">{{ ucfirst($resource->difficulty) }}</span>
                        @endif
                        @if($resource->duration)
                        <span class="text-xs t-light flex items-center">🕐 {{ $resource->duration }}</span>
                        @endif
                    </div>
                    <h1 class="text-2xl md:text-3xl font-serif font-bold t-text">{{ $resource->title }}</h1>
                    <p class="t-muted mt-2 leading-relaxed">{{ $resource->description }}</p>
                </div>
            </div>
        </div>

        {{-- Body Content --}}
        <div class="px-6 md:px-10 py-8">
            @if($resource->body)
            <div class="prose prose-sage max-w-none t-text leading-relaxed">
                {!! nl2br(e($resource->body)) !!}
            </div>
            @else
            <div class="text-center py-10">
                <svg class="w-10 h-10 mx-auto mb-3 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <p class="t-muted">Detailed content for this resource is coming soon.</p>
                <p class="t-light text-sm mt-2">In the meantime, the description above provides a helpful overview.</p>
            </div>
            @endif

            {{-- Tags --}}
            @if(!empty($resource->tags) && is_array($resource->tags))
            <div class="flex flex-wrap gap-2 mt-8 pt-6 border-t border-th-border">
                @foreach($resource->tags as $tag)
                <a href="{{ route('resources.index', ['search' => $tag]) }}" class="text-xs t-muted t-surface px-3 py-1 rounded-full hover:bg-sage-100 transition-colors">#{{ $tag }}</a>
                @endforeach
            </div>
            @endif
        </div>
    </article>

    {{-- Related Resources --}}
    @if($related->count() > 0)
    <div class="mt-10" data-aos="fade-up">
        <h2 class="text-xl font-serif font-bold t-text mb-4">Related Resources</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($related as $r)
            <a href="{{ route('resources.show', $r) }}" class="glass-card-solid p-5 hover:shadow-lift hover:-translate-y-1 transition-all duration-300 block group">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-sage-600">
                        @if($r->category === 'anxiety' || $r->content_type === 'breathing')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @elseif($r->category === 'stress' || $r->category === 'burnout')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @elseif($r->category === 'sleep')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        @elseif($r->category === 'depression')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21v-2m-4 2v-2m8 2v-2"/></svg>
                        @elseif($r->category === 'meditation')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        @elseif($r->category === 'relationships')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @endif
                    </span>
                    <span class="badge-nature text-[10px]">{{ ucfirst($r->category ?? 'General') }}</span>
                </div>
                <h3 class="font-serif font-semibold t-text text-sm mb-1 group-hover:t-text transition-colors">{{ $r->title }}</h3>
                <p class="t-muted text-xs line-clamp-2">{{ $r->description }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection


