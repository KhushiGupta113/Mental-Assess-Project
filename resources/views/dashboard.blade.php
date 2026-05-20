@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-th-primary-light to-th-surface-alt rounded-[2rem] p-8 flex flex-col md:flex-row items-center justify-between shadow-xl border border-th-border relative overflow-hidden" data-aos="fade-in">
        <div class="relative z-10 flex items-center space-x-6">
            <div class="w-20 h-20 bg-white rounded-full p-2 shadow-lg hidden md:block">
                <img src="{{ asset('images/cute_brain.png') }}" alt="Cute Brain" class="w-full h-full object-cover rounded-full">
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-serif font-bold t-text mb-2">Welcome back, {{ Auth::user()->name ?? 'Friend' }}</h1>
                <p class="t-muted text-lg font-medium">Take a deep breath. This is your personal space for reflection and growth.</p>
            </div>
        </div>
        <div class="mt-6 md:mt-0 relative z-10">
            @if($stats['streak'] > 0)
            <div class="t-card rounded-2xl px-6 py-4 text-center shadow-lg border border-th-border">
                <div class="text-3xl font-bold t-primary mb-1">{{ $stats['streak'] }}</div>
                <div class="text-xs font-bold t-muted uppercase tracking-wider flex items-center justify-center gap-1">
                    <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" /></svg>
                    Day Streak
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4" data-aos="fade-up">
        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl mb-2 flex items-center justify-center" style="background:var(--th-primary-light)">
                <svg class="w-5 h-5" style="color:var(--th-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div class="text-2xl font-bold t-text">{{ $stats['total_assessments'] }}</div>
            <div class="text-xs t-muted mt-1">Assessments</div>
        </div>
        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl mb-2 flex items-center justify-center bg-teal-50 dark:bg-teal-900/30">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div class="text-2xl font-bold t-text">{{ $stats['total_journals'] }}</div>
            <div class="text-xs t-muted mt-1">Journals</div>
        </div>
        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl mb-2 flex items-center justify-center bg-indigo-50 dark:bg-indigo-900/30">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div class="text-2xl font-bold t-text">{{ $stats['total_moods'] }}</div>
            <div class="text-xs t-muted mt-1">Mood Logs</div>
        </div>
        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl mb-2 flex items-center justify-center bg-amber-50 dark:bg-amber-900/30">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="text-2xl font-bold t-text">{{ $stats['avg_mood'] }}/5</div>
            <div class="text-xs t-muted mt-1">Avg Mood (7d)</div>
        </div>
        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl mb-2 flex items-center justify-center bg-violet-50 dark:bg-violet-900/30">
                <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </div>
            <div class="text-2xl font-bold t-text">{{ $stats['avg_sleep'] }}h</div>
            <div class="text-xs t-muted mt-1">Avg Sleep (7d)</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column --}}
        <div class="space-y-8">

            {{-- Daily Check-in --}}
            <div class="glass-card-solid p-6" data-aos="fade-right" x-data="{ selectedMood: null, selectedEmoji: '' }">
                <h2 class="font-serif text-xl font-bold t-text mb-1">Daily Check-in</h2>
                <p class="text-sm t-muted mb-4">How are you feeling right now?</p>

                @if($todaysMood)
                    <div class="t-surface border-nature rounded-xl p-4 text-center">
                        <div class="flex justify-center mb-2">
                            <x-mood-icon :score="$todaysMood->mood_score ?? 3" class="w-12 h-12" />
                        </div>
                        <p class="text-sm t-text font-medium">You've checked in today!</p>
                        <p class="text-xs t-muted mt-1">{{ $todaysMood->created_at->diffForHumans() }}</p>
                    </div>
                @else
                    @if(session('status') === 'mood-logged')
                    <div class="bg-teal-50 text-teal-700 dark:bg-teal-900/40 dark:text-teal-300 rounded-xl p-3 mb-4 text-sm flex items-center">
                        <span class="mr-2">✅</span> Mood recorded!
                    </div>
                    @endif

                    <form method="POST" action="{{ route('mood.store') }}">
                        @csrf
                        <input type="hidden" name="mood_emoji" x-model="selectedEmoji">
                        <div class="grid grid-cols-5 gap-2 mb-6">
                            @php
                                $moodIcons = [
                                    ['emoji'=>'angry', 'score'=>1],
                                    ['emoji'=>'sad', 'score'=>2],
                                    ['emoji'=>'neutral', 'score'=>3],
                                    ['emoji'=>'happy', 'score'=>4],
                                    ['emoji'=>'very_happy', 'score'=>5]
                                ];
                            @endphp
                            @foreach($moodIcons as $mood)
                            <button type="button" @click="selectedMood = {{ $mood['score'] }}; selectedEmoji = '{{ $mood['emoji'] }}'"
                                :class="selectedMood === {{ $mood['score'] }} ? 'scale-125' : 'hover:scale-110 opacity-60 hover:opacity-100'" 
                                class="w-14 h-14 rounded-full flex items-center justify-center transition-all duration-300 mx-auto">
                                <x-mood-icon :score="$mood['score']" class="w-10 h-10" />
                            </button>
                            @endforeach
                        </div>
                        <div class="space-y-3 mb-4">
                            <input type="number" name="sleep_hours" placeholder="Sleep hours (e.g. 7)" step="0.5" min="0" max="24" class="input-nature text-sm !py-2">
                            <select name="energy_level" class="input-nature text-sm !py-2">
                                <option value="">Energy level...</option>
                                <option value="1">1 - Very Low</option>
                                <option value="2">2 - Low</option>
                                <option value="3">3 - Moderate</option>
                                <option value="4">4 - Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                            <textarea name="notes" rows="2" placeholder="Any notes about today..." class="textarea-nature text-sm !py-2"></textarea>
                        </div>
                        <button type="submit" class="btn-nature w-full !rounded-xl" :disabled="!selectedMood">Log Mood</button>
                    </form>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="glass-card-solid p-6" data-aos="fade-right" data-aos-delay="100">
                <h2 class="font-serif text-lg font-bold t-text mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('assessments.index') }}" class="flex items-center p-3 rounded-xl hover:bg-black/5 dark:hover:t-card/5 transition-colors group border border-transparent hover:border-nature">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 dark:bg-indigo-900/40 dark:text-indigo-400 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div class="flex-1"><p class="text-sm font-medium t-text">Take Assessment</p><p class="text-xs t-muted">7 validated screening tools</p></div>
                        <svg class="w-4 h-4 t-muted group-hover:t-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('journal.create') }}" class="flex items-center p-3 rounded-xl hover:bg-black/5 dark:hover:t-card/5 transition-colors group border border-transparent hover:border-nature">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-500 dark:bg-teal-900/40 dark:text-teal-400 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div class="flex-1"><p class="text-sm font-medium t-text">Write Journal</p><p class="text-xs t-muted">Reflect on your day</p></div>
                        <svg class="w-4 h-4 t-muted group-hover:t-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('resources.index') }}" class="flex items-center p-3 rounded-xl hover:bg-black/5 dark:hover:t-card/5 transition-colors group border border-transparent hover:border-nature">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 dark:bg-amber-900/40 dark:text-amber-400 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div class="flex-1"><p class="text-sm font-medium t-text">Browse Resources</p><p class="text-xs t-muted">Exercises & articles</p></div>
                        <svg class="w-4 h-4 t-muted group-hover:t-text" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @if(Auth::user()->country)
                    <a href="{{ route('crisis.index', ['country' => Auth::user()->country]) }}" class="flex items-center p-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group border border-transparent hover:border-red-200">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 dark:bg-red-900/40 dark:text-red-400 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        </div>
                        <div class="flex-1"><p class="text-sm font-medium t-text">Help in {{ Auth::user()->country }}</p><p class="text-xs t-muted">Local crisis helplines</p></div>
                        <svg class="w-4 h-4 t-muted group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Personalized Recommendations --}}
            @if(!empty(Auth::user()->concerns) && is_array(Auth::user()->concerns))
            <div class="glass-card-solid p-6" data-aos="fade-right" data-aos-delay="150">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg"></span>
                    <h2 class="font-serif text-lg font-bold t-text">Recommended for You</h2>
                </div>
                <p class="text-xs t-muted mb-3">Based on your concerns</p>
                <div class="space-y-2">
                    @php
                    $concernAssessments = [
                        'anxiety' => ['title' => 'GAD-7 Anxiety Screen', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>', 'desc' => 'Measure your anxiety levels'],
                        'depression' => ['title' => 'PHQ-9 Depression Screen', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>', 'desc' => 'Check for depression symptoms'],
                        'stress' => ['title' => 'PSS Stress Scale', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>', 'desc' => 'Evaluate your stress levels'],
                        'sleep' => ['title' => 'ISI Insomnia Index', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>', 'desc' => 'Assess your sleep quality'],
                        'burnout' => ['title' => 'Burnout Assessment', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>', 'desc' => 'Check for burnout signs'],
                        'adhd' => ['title' => 'ASRS ADHD Screen', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>', 'desc' => 'Evaluate focus & attention'],
                    ];
                    $shown = 0;
                    @endphp
                    @foreach(Auth::user()->concerns as $concern)
                        @if(isset($concernAssessments[$concern]) && $shown < 3)
                        @php $ca = $concernAssessments[$concern]; $shown++; @endphp
                        <a href="{{ route('assessments.index') }}" class="flex items-center p-3 rounded-xl border border-nature hover:bg-black/5 dark:hover:t-card/5 transition-all group">
                            <svg class="w-6 h-6 t-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $ca['icon'] !!}</svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium t-text">{{ $ca['title'] }}</p>
                                <p class="text-xs t-muted">{{ $ca['desc'] }}</p>
                            </div>
                            <span class="badge-nature text-[9px]">Suggested</span>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column (2 cols) --}}
        <div class="lg:col-span-2 space-y-8">

            {{-- Mood Chart --}}
            <div class="glass-card-solid p-6" data-aos="fade-left">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-serif text-xl font-bold t-text">Mood Trends</h2>
                    <a href="{{ route('mood.index') }}" class="text-sm t-primary font-medium hover:underline transition-colors">View All →</a>
                </div>
                @if($moodChartData->count() > 1)
                <div class="h-64">
                    <canvas id="moodChart"></canvas>
                </div>
                @else
                <div class="h-48 flex items-center justify-center t-surface border-nature rounded-xl">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto mb-2 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        <p class="t-muted text-sm">Log at least 2 moods to see trends</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- AI Insights --}}
            <div class="glass-card-solid p-6" data-aos="fade-left" data-aos-delay="100">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-400 to-teal-400 flex items-center justify-center text-white text-lg">✨</div>
                    <h2 class="font-serif text-xl font-bold t-text">AI Wellness Insights</h2>
                </div>
                <div class="space-y-3">
                    @foreach($insights as $insight)
                <div class="flex gap-3 mb-4 last:mb-0">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-{{ $insight['type'] === 'care' ? 'blue' : ($insight['type'] === 'positive' ? 'green' : 'purple') }}-100 text-{{ $insight['type'] === 'care' ? 'blue' : ($insight['type'] === 'positive' ? 'green' : 'purple') }}-600 flex items-center justify-center text-sm">
                        @if($insight['icon'] === 'care')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        @elseif($insight['icon'] === 'positive')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        @elseif($insight['icon'] === 'sleep')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        @elseif($insight['icon'] === 'support')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @elseif($insight['icon'] === 'growth')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        @elseif($insight['icon'] === 'journal')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <p class="t-muted text-sm">{{ $insight['text'] }}</p>
                </div>
                @endforeach
                </div>
            </div>

            {{-- Recent Results & Journal --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Recent Assessments --}}
                <div class="glass-card-solid p-6" data-aos="fade-up">
                    <h2 class="font-serif text-lg font-bold t-text mb-4">Recent Assessments</h2>
                    @if($recentResults->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentResults->take(4) as $result)
                        <div class="flex items-center justify-between p-2">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg">{{ $result->assessment->icon ?? '📋' }}</span>
                                <div>
                                    <p class="text-sm font-medium t-text">{{ Str::limit($result->assessment->title ?? 'Assessment', 20) }}</p>
                                    <p class="text-xs t-muted">{{ $result->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="badge-nature">{{ $result->total_score }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6">
                        <p class="t-muted text-sm">No assessments yet</p>
                        <a href="{{ route('assessments.index') }}" class="t-primary text-sm font-medium hover:underline mt-1 inline-block">Take your first →</a>
                    </div>
                    @endif
                </div>

                {{-- Recent Journal --}}
                <div class="glass-card-solid p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-serif text-lg font-bold t-text">Recent Journal</h2>
                        <a href="{{ route('journal.create') }}" class="t-primary text-xs font-medium hover:underline">+ New</a>
                    </div>
                    @if($recentJournals->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentJournals as $entry)
                        <a href="{{ route('journal.show', $entry) }}" class="block p-3 rounded-xl hover:bg-black/5 dark:hover:t-card/5 border border-transparent hover:border-nature transition-colors">
                            <p class="text-sm font-medium t-text">{{ Str::limit($entry->title, 30) }}</p>
                            <p class="text-xs t-muted mt-1">{{ Str::limit($entry->content, 60) }}</p>
                            <p class="text-xs t-light mt-1">{{ $entry->created_at->diffForHumans() }}</p>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6">
                        <p class="t-muted text-sm">No journal entries yet</p>
                        <a href="{{ route('journal.create') }}" class="t-primary text-sm font-medium hover:underline mt-1 inline-block">Write your first →</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($moodChartData->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('moodChart').getContext('2d');
    const data = @json($moodChartData);

    // Get CSS variables for dynamic theming
    const style = getComputedStyle(document.body);
    const primaryColor = style.getPropertyValue('--th-primary').trim() || '#697a59';
    const accentColor = style.getPropertyValue('--th-accent').trim() || '#14b8a6';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [{
                label: 'Mood',
                data: data.map(d => d.score),
                borderColor: primaryColor,
                backgroundColor: primaryColor + '20', // 20 hex = 12% opacity
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: primaryColor,
            },
            {
                label: 'Sleep (hrs)',
                data: data.map(d => d.sleep),
                borderColor: accentColor,
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.4,
                fill: false,
                pointRadius: 4,
                pointBackgroundColor: accentColor,
                borderDash: [5, 5],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { family: 'Inter', size: 12 }, color: style.getPropertyValue('--th-text').trim() || '#2f372b' } },
            },
            scales: {
                y: { beginAtZero: true, max: 10, grid: { color: style.getPropertyValue('--th-border').trim() || 'rgba(105, 122, 89, 0.15)' }, ticks: { color: style.getPropertyValue('--th-text-muted').trim() || '#697a59', font: { family: 'Inter', size: 11 } } },
                x: { grid: { display: false }, ticks: { color: style.getPropertyValue('--th-text-muted').trim() || '#697a59', font: { family: 'Inter', size: 11 } } }
            }
        }
    });
});
</script>
@endif
@endsection

