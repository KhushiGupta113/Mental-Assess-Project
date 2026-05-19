@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-10" data-aos="fade-down">
        <span class="badge-nature mb-3 inline-block">📚 Wellness Library</span>
        <h1 class="section-heading mb-3">Resources & Exercises</h1>
        <p class="section-subheading mx-auto">Evidence-based articles, breathing exercises, meditation guides, and coping strategies to support your mental wellness.</p>
    </div>

    {{-- Filters --}}
    <div class="glass-card-solid p-5 mb-8" data-aos="fade-up">
        <form method="GET" action="{{ route('resources.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-sage-600 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search resources..." class="input-nature text-sm !py-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-sage-600 mb-1">Category</label>
                <select name="category" class="input-nature text-sm !py-2">
                    <option value="all">All Categories</option>
                    @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-sage-600 mb-1">Type</label>
                <select name="type" class="input-nature text-sm !py-2">
                    <option value="all">All Types</option>
                    @foreach($contentTypes as $key => $label)
                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn-nature text-sm !py-2">Filter</button>
            @if(request()->hasAny(['search', 'category', 'type']))
            <a href="{{ route('resources.index') }}" class="text-sage-400 hover:text-sage-600 text-sm font-medium transition-colors">Clear</a>
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
                    <div class="w-11 h-11 rounded-xl bg-sage-100 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">{{ $resource->icon ?? '📄' }}</div>
                    <div>
                        <span class="badge-nature text-[10px]">{{ ucfirst($resource->category ?? 'General') }}</span>
                        <span class="badge-teal text-[10px] ml-0.5">{{ ucfirst($resource->content_type ?? 'Article') }}</span>
                    </div>
                </div>
                @if($resource->duration)
                <span class="text-xs text-sage-400">{{ $resource->duration }}</span>
                @endif
            </div>
            <h3 class="font-serif font-bold text-sage-800 mb-2 group-hover:text-sage-900 transition-colors">{{ $resource->title }}</h3>
            <p class="text-sage-500 text-sm leading-relaxed mb-3 line-clamp-2">{{ $resource->description }}</p>
            <div class="flex items-center justify-between">
                @if(!empty($resource->tags) && is_array($resource->tags))
                <div class="flex flex-wrap gap-1">
                    @foreach(array_slice($resource->tags, 0, 3) as $tag)
                    <span class="text-[10px] text-sage-400 bg-sage-50 px-2 py-0.5 rounded-full">#{{ $tag }}</span>
                    @endforeach
                </div>
                @else
                <div></div>
                @endif
                <span class="text-sage-400 group-hover:text-sage-600 text-sm font-medium transition-colors flex items-center">
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
        <p class="text-4xl mb-3">🔍</p>
        <h3 class="text-xl font-serif text-sage-800 mb-2">No resources found</h3>
        <p class="text-sage-500 text-sm mb-4">Try adjusting your filters or search terms.</p>
        <a href="{{ route('resources.index') }}" class="btn-nature-outline !text-sm">View All Resources</a>
    </div>
    @endif
</div>
@endsection
