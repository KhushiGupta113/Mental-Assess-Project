@extends('layouts.main')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('journal.index') }}" class="inline-flex items-center text-sm text-sage-500 hover:text-sage-700 mb-6 transition">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Journal
    </a>

    <div class="glass-card-solid overflow-hidden" data-aos="fade-up">
        <div class="bg-gradient-to-r from-sage-100 to-teal-50 px-8 py-6 border-b border-sage-200/50">
            <div class="flex items-center gap-2 mb-2">
                @if($entry->is_gratitude)<span class="badge-teal">🙏 Gratitude</span>@endif
                @if($entry->mood_tag)<span class="badge-nature">{{ $entry->mood_tag }}</span>@endif
                @if($entry->sentiment_score !== null)
                    @if($entry->sentiment_score > 0.3)<span class="badge-teal">Positive ✨</span>
                    @elseif($entry->sentiment_score < -0.3)<span class="badge-danger">Heavy 💙</span>
                    @else<span class="badge-nature">Neutral</span>@endif
                @endif
            </div>
            <h1 class="text-2xl font-serif font-bold text-sage-800">{{ $entry->title }}</h1>
            <p class="text-sm text-sage-500 mt-1">{{ $entry->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>

        <div class="p-8">
            <div class="prose prose-sage max-w-none text-sage-700 leading-relaxed whitespace-pre-wrap">{{ $entry->content }}</div>

            @if(!empty($entry->tags) && is_array($entry->tags))
            <div class="flex flex-wrap gap-2 mt-6 pt-6 border-t border-sage-100">
                @foreach($entry->tags as $tag)
                <span class="text-xs text-sage-400 bg-sage-50 px-3 py-1 rounded-full">#{{ $tag }}</span>
                @endforeach
            </div>
            @endif

            <div class="flex justify-between items-center mt-8 pt-6 border-t border-sage-100">
                <a href="{{ route('journal.index') }}" class="text-sage-500 hover:text-sage-700 text-sm transition">← All Entries</a>
                <form method="POST" action="{{ route('journal.destroy', $entry) }}" onsubmit="return confirm('Are you sure you want to delete this entry?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-600 text-sm transition">Delete Entry</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
