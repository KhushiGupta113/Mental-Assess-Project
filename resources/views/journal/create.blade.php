@extends('layouts.main')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('journal.index') }}" class="inline-flex items-center text-sm text-sage-500 hover:text-sage-700 mb-6 transition">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Journal
    </a>

    <div class="glass-card-solid p-8" data-aos="fade-up">
        <h1 class="section-heading mb-2">New Journal Entry</h1>
        <p class="text-sage-500 mb-6">Write freely. This is your private space.</p>

        {{-- AI Prompt --}}
        <div class="bg-indigo-50 rounded-xl p-4 mb-6 flex items-start space-x-3">
            <span class="text-xl mt-0.5">✨</span>
            <div>
                <p class="text-xs font-semibold text-indigo-600 mb-1">Today's Writing Prompt</p>
                <p class="text-sm text-indigo-700 italic">{{ $todayPrompt }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('journal.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-sage-700 mb-1.5">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Give this entry a name..." class="input-nature" required>
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-sage-700 mb-1.5">Your Thoughts</label>
                <textarea name="content" rows="8" placeholder="Write whatever is on your mind..." class="textarea-nature journal-lines" required>{{ old('content') }}</textarea>
                @error('content')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-sage-700 mb-1.5">Mood Tag</label>
                    <select name="mood_tag" class="input-nature">
                        <option value="">Select mood...</option>
                        <option value="😊 Happy">😊 Happy</option>
                        <option value="😌 Calm">😌 Calm</option>
                        <option value="😐 Neutral">😐 Neutral</option>
                        <option value="😔 Sad">😔 Sad</option>
                        <option value="😰 Anxious">😰 Anxious</option>
                        <option value="😤 Stressed">😤 Stressed</option>
                        <option value="🙏 Grateful">🙏 Grateful</option>
                        <option value="💪 Motivated">💪 Motivated</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-sage-700 mb-1.5">Tags</label>
                    <input type="text" name="tags" placeholder="e.g. work, sleep, exercise" class="input-nature">
                    <p class="text-xs text-sage-400 mt-1">Separate with commas</p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_gratitude" value="1" id="gratitude" class="checkbox checkbox-sm checkbox-success border-sage-300">
                <label for="gratitude" class="text-sm text-sage-600">This is a gratitude entry 🙏</label>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('journal.index') }}" class="btn-nature-outline">Cancel</a>
                <button type="submit" class="btn-nature">Save Entry</button>
            </div>
        </form>
    </div>
</div>
@endsection
