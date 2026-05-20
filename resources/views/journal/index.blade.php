@extends('layouts.main')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8" data-aos="fade-down">
        <div>
            <h1 class="section-heading mb-2">My Journal</h1>
            <p class="t-muted">Reflect, release, and grow through writing.</p>
        </div>
        <a href="{{ route('journal.create') }}" class="btn-nature mt-4 sm:mt-0">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Entry
        </a>
    </div>

    @if(session('status') === 'journal-saved')
    <div class="bg-teal-50 text-teal-700 rounded-xl p-4 mb-6 flex items-center" data-aos="fade-in">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Journal entry saved successfully!
    </div>
    @endif
    @if(session('status') === 'journal-deleted')
    <div class="bg-red-50 text-red-600 rounded-xl p-4 mb-6 flex items-center" data-aos="fade-in">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> Journal entry deleted.
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
                        <span class="badge-teal text-xs flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Gratitude</span>
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
                    <h3 class="font-serif font-bold t-text text-lg mb-1">{{ $entry->title }}</h3>
                    <p class="t-muted text-sm line-clamp-2">{{ Str::limit($entry->content, 150) }}</p>
                    @if(!empty($entry->tags) && is_array($entry->tags))
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @foreach(array_slice($entry->tags, 0, 4) as $tag)
                        <span class="text-xs t-light t-surface px-2 py-0.5 rounded-full">#{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="text-right ml-4 flex-shrink-0">
                    <p class="text-xs t-light">{{ $entry->created_at->format('M d') }}</p>
                    <p class="text-xs t-light">{{ $entry->created_at->format('Y') }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-8">{{ $entries->links() }}</div>
    @else
    <div class="text-center py-20 glass-card-solid" data-aos="fade-in">
        <svg class="w-16 h-16 mx-auto mb-4 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        <h3 class="text-xl font-serif t-text mb-2">No journal entries yet</h3>
        <p class="t-muted text-sm mb-6">Start your journaling practice today. Even a few sentences can make a difference.</p>
        <a href="{{ route('journal.create') }}" class="btn-nature">Write Your First Entry</a>
    </div>
    @endif
</div>
@endsection

