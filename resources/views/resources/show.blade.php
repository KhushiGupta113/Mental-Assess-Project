@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('resources.index') }}" class="inline-flex items-center text-sm text-sage-500 hover:text-sage-700 mb-6 transition">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Resources
    </a>

    <article class="glass-card-solid overflow-hidden" data-aos="fade-up">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-sage-100 via-teal-50 to-sage-50 px-6 md:px-10 py-8 border-b border-sage-200/50">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/70 flex items-center justify-center text-3xl flex-shrink-0 shadow-sm">{{ $resource->icon ?? '📄' }}</div>
                <div>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <span class="badge-nature text-xs">{{ ucfirst($resource->category ?? 'General') }}</span>
                        <span class="badge-teal text-xs">{{ ucfirst($resource->content_type ?? 'Article') }}</span>
                        @if($resource->difficulty)
                        <span class="badge-indigo text-xs">{{ ucfirst($resource->difficulty) }}</span>
                        @endif
                        @if($resource->duration)
                        <span class="text-xs text-sage-400 flex items-center">🕐 {{ $resource->duration }}</span>
                        @endif
                    </div>
                    <h1 class="text-2xl md:text-3xl font-serif font-bold text-sage-800">{{ $resource->title }}</h1>
                    <p class="text-sage-500 mt-2 leading-relaxed">{{ $resource->description }}</p>
                </div>
            </div>
        </div>

        {{-- Body Content --}}
        <div class="px-6 md:px-10 py-8">
            @if($resource->body)
            <div class="prose prose-sage max-w-none text-sage-700 leading-relaxed">
                {!! nl2br(e($resource->body)) !!}
            </div>
            @else
            <div class="text-center py-10">
                <div class="text-4xl mb-3">📝</div>
                <p class="text-sage-500">Detailed content for this resource is coming soon.</p>
                <p class="text-sage-400 text-sm mt-2">In the meantime, the description above provides a helpful overview.</p>
            </div>
            @endif

            {{-- Tags --}}
            @if(!empty($resource->tags) && is_array($resource->tags))
            <div class="flex flex-wrap gap-2 mt-8 pt-6 border-t border-sage-100">
                @foreach($resource->tags as $tag)
                <a href="{{ route('resources.index', ['search' => $tag]) }}" class="text-xs text-sage-500 bg-sage-50 px-3 py-1 rounded-full hover:bg-sage-100 transition-colors">#{{ $tag }}</a>
                @endforeach
            </div>
            @endif
        </div>
    </article>

    {{-- Related Resources --}}
    @if($related->count() > 0)
    <div class="mt-10" data-aos="fade-up">
        <h2 class="text-xl font-serif font-bold text-sage-800 mb-4">Related Resources</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($related as $r)
            <a href="{{ route('resources.show', $r) }}" class="glass-card-solid p-5 hover:shadow-lift hover:-translate-y-1 transition-all duration-300 block group">
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-xl">{{ $r->icon ?? '📄' }}</span>
                    <span class="badge-nature text-[10px]">{{ ucfirst($r->category ?? 'General') }}</span>
                </div>
                <h3 class="font-serif font-semibold text-sage-800 text-sm mb-1 group-hover:text-sage-900 transition-colors">{{ $r->title }}</h3>
                <p class="text-sage-500 text-xs line-clamp-2">{{ $r->description }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
