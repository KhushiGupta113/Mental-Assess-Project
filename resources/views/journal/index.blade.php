@extends('layouts.main')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8" data-aos="fade-down">
        <div>
            <h1 class="section-heading mb-2">My Journal</h1>
            <p class="text-sage-500">Reflect, release, and grow through writing.</p>
        </div>
        <a href="{{ route('journal.create') }}" class="btn-nature mt-4 sm:mt-0">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Entry
        </a>
    </div>

    @if(session('status') === 'journal-saved')
    <div class="bg-teal-50 text-teal-700 rounded-xl p-4 mb-6 flex items-center" data-aos="fade-in">
        <span class="mr-2">✅</span> Journal entry saved successfully!
    </div>
    @endif
    @if(session('status') === 'journal-deleted')
    <div class="bg-red-50 text-red-600 rounded-xl p-4 mb-6 flex items-center" data-aos="fade-in">
        <span class="mr-2">🗑️</span> Journal entry deleted.
    </div>
    @endif

    @if($entries->count() > 0)
    <div class="space-y-4">
        @foreach($entries as $i => $entry)
        <a href="{{ route('journal.show', $entry) }}" class="block glass-card-solid p-6 hover:shadow-lift hover:-translate-y-0.5 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        @if($entry->is_gratitude)
                        <span class="badge-teal text-xs">🙏 Gratitude</span>
                        @endif
                        @if($entry->mood_tag)
                        <span class="badge-nature text-xs">{{ $entry->mood_tag }}</span>
                        @endif
                        @if($entry->sentiment_score !== null)
                            @if($entry->sentiment_score > 0.3)
                            <span class="w-2 h-2 rounded-full bg-teal-400" title="Positive sentiment"></span>
                            @elseif($entry->sentiment_score < -0.3)
                            <span class="w-2 h-2 rounded-full bg-red-400" title="Negative sentiment"></span>
                            @else
                            <span class="w-2 h-2 rounded-full bg-sage-300" title="Neutral sentiment"></span>
                            @endif
                        @endif
                    </div>
                    <h3 class="font-serif font-bold text-sage-800 text-lg mb-1">{{ $entry->title }}</h3>
                    <p class="text-sage-500 text-sm line-clamp-2">{{ Str::limit($entry->content, 150) }}</p>
                    @if(!empty($entry->tags) && is_array($entry->tags))
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @foreach(array_slice($entry->tags, 0, 4) as $tag)
                        <span class="text-xs text-sage-400 bg-sage-50 px-2 py-0.5 rounded-full">#{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="text-right ml-4 flex-shrink-0">
                    <p class="text-xs text-sage-400">{{ $entry->created_at->format('M d') }}</p>
                    <p class="text-xs text-sage-300">{{ $entry->created_at->format('Y') }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-8">{{ $entries->links() }}</div>
    @else
    <div class="text-center py-20 glass-card-solid" data-aos="fade-in">
        <p class="text-5xl mb-4">📝</p>
        <h3 class="text-xl font-serif text-sage-800 mb-2">No journal entries yet</h3>
        <p class="text-sage-500 text-sm mb-6">Start your journaling practice today. Even a few sentences can make a difference.</p>
        <a href="{{ route('journal.create') }}" class="btn-nature">Write Your First Entry</a>
    </div>
    @endif
</div>
@endsection
