@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-sage-100 via-teal-50 to-sage-50 rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between border border-sage-200/50" data-aos="fade-in">
        <div>
            <h1 class="text-2xl md:text-3xl font-serif font-bold text-sage-800 mb-2">Welcome back, {{ Auth::user()->name ?? 'Friend' }} {{ Auth::user()->avatar_emoji ?? '🌱' }}</h1>
            <p class="text-sage-600">Take a deep breath. This is your personal space for reflection and growth.</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            @if($stats['streak'] > 0)
            <div class="glass-card px-5 py-3 text-center">
                <div class="text-2xl font-bold text-sage-800">{{ $stats['streak'] }}</div>
                <div class="text-xs text-sage-500">Day Streak 🔥</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4" data-aos="fade-up">
        <div class="stat-card">
            <div class="text-3xl mb-2">📋</div>
            <div class="text-2xl font-bold text-sage-800">{{ $stats['total_assessments'] }}</div>
            <div class="text-xs text-sage-500 mt-1">Assessments</div>
        </div>
        <div class="stat-card">
            <div class="text-3xl mb-2">😊</div>
            <div class="text-2xl font-bold text-sage-800">{{ $stats['total_moods'] }}</div>
            <div class="text-xs text-sage-500 mt-1">Mood Logs</div>
        </div>
        <div class="stat-card">
            <div class="text-3xl mb-2">📝</div>
            <div class="text-2xl font-bold text-sage-800">{{ $stats['total_journals'] }}</div>
            <div class="text-xs text-sage-500 mt-1">Journal Entries</div>
        </div>
        <div class="stat-card">
            <div class="text-3xl mb-2">🔥</div>
            <div class="text-2xl font-bold text-sage-800">{{ $stats['streak'] }}</div>
            <div class="text-xs text-sage-500 mt-1">Day Streak</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column --}}
        <div class="space-y-8">

            {{-- Daily Check-in --}}
            <div class="glass-card-solid p-6" data-aos="fade-right" x-data="{ selectedMood: null, selectedEmoji: '' }">
                <h2 class="font-serif text-xl font-bold text-sage-800 mb-1">Daily Check-in</h2>
                <p class="text-sm text-sage-500 mb-4">How are you feeling right now?</p>

                @if($todaysMood)
                    <div class="bg-sage-50 rounded-xl p-4 text-center">
                        <p class="text-3xl mb-2">{{ $todaysMood->mood_emoji }}</p>
                        <p class="text-sm text-sage-600 font-medium">You've checked in today!</p>
                        <p class="text-xs text-sage-400 mt-1">{{ $todaysMood->created_at->diffForHumans() }}</p>
                    </div>
                @else
                    @if(session('status') === 'mood-logged')
                    <div class="bg-teal-50 text-teal-700 rounded-xl p-3 mb-4 text-sm flex items-center">
                        <span class="mr-2">✅</span> Mood recorded!
                    </div>
                    @endif

                    <form method="POST" action="{{ route('mood.store') }}">
                        @csrf
                        <input type="hidden" name="mood_emoji" x-model="selectedEmoji">
                        <div class="grid grid-cols-5 gap-2 mb-4">
                            @foreach(['😢' => 1, '😔' => 2, '😐' => 3, '🙂' => 4, '😊' => 5] as $emoji => $score)
                            <button type="button" @click="selectedMood = {{ $score }}; selectedEmoji = '{{ $emoji }}'"
                                :class="selectedMood === {{ $score }} ? 'active' : ''" class="mood-btn">{{ $emoji }}</button>
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
                <h2 class="font-serif text-lg font-bold text-sage-800 mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('assessments.index') }}" class="flex items-center p-3 rounded-xl hover:bg-sage-50 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center mr-3">📋</div>
                        <div class="flex-1"><p class="text-sm font-medium text-sage-700">Take Assessment</p><p class="text-xs text-sage-400">7 validated screening tools</p></div>
                        <svg class="w-4 h-4 text-sage-300 group-hover:text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('journal.create') }}" class="flex items-center p-3 rounded-xl hover:bg-sage-50 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-500 flex items-center justify-center mr-3">📝</div>
                        <div class="flex-1"><p class="text-sm font-medium text-sage-700">Write Journal</p><p class="text-xs text-sage-400">Reflect on your day</p></div>
                        <svg class="w-4 h-4 text-sage-300 group-hover:text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    <a href="{{ route('resources.index') }}" class="flex items-center p-3 rounded-xl hover:bg-sage-50 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center mr-3">📚</div>
                        <div class="flex-1"><p class="text-sm font-medium text-sage-700">Browse Resources</p><p class="text-xs text-sage-400">Exercises & articles</p></div>
                        <svg class="w-4 h-4 text-sage-300 group-hover:text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @if(Auth::user()->country)
                    <a href="{{ route('crisis.index', ['country' => Auth::user()->country]) }}" class="flex items-center p-3 rounded-xl hover:bg-red-50 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center mr-3">🚨</div>
                        <div class="flex-1"><p class="text-sm font-medium text-sage-700">Help in {{ Auth::user()->country }}</p><p class="text-xs text-sage-400">Local crisis helplines</p></div>
                        <svg class="w-4 h-4 text-sage-300 group-hover:text-sage-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Personalized Recommendations (based on onboarding concerns) --}}
            @if(!empty(Auth::user()->concerns) && is_array(Auth::user()->concerns))
            <div class="glass-card-solid p-6" data-aos="fade-right" data-aos-delay="150">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-lg">🎯</span>
                    <h2 class="font-serif text-lg font-bold text-sage-800">Recommended for You</h2>
                </div>
                <p class="text-xs text-sage-400 mb-3">Based on your concerns</p>
                <div class="space-y-2">
                    @php
                    $concernAssessments = [
                        'anxiety' => ['title' => 'GAD-7 Anxiety Screen', 'icon' => '😰', 'desc' => 'Measure your anxiety levels'],
                        'depression' => ['title' => 'PHQ-9 Depression Screen', 'icon' => '💙', 'desc' => 'Check for depression symptoms'],
                        'stress' => ['title' => 'PSS Stress Scale', 'icon' => '🔥', 'desc' => 'Evaluate your stress levels'],
                        'sleep' => ['title' => 'ISI Insomnia Index', 'icon' => '🌙', 'desc' => 'Assess your sleep quality'],
                        'burnout' => ['title' => 'Burnout Assessment', 'icon' => '😮‍💨', 'desc' => 'Check for burnout signs'],
                        'adhd' => ['title' => 'ASRS ADHD Screen', 'icon' => '⚡', 'desc' => 'Evaluate focus & attention'],
                    ];
                    $shown = 0;
                    @endphp
                    @foreach(Auth::user()->concerns as $concern)
                        @if(isset($concernAssessments[$concern]) && $shown < 3)
                        @php $ca = $concernAssessments[$concern]; $shown++; @endphp
                        <a href="{{ route('assessments.index') }}" class="flex items-center p-3 rounded-xl border border-sage-100 hover:bg-sage-50 hover:border-sage-200 transition-all group">
                            <span class="text-xl mr-3">{{ $ca['icon'] }}</span>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-sage-700">{{ $ca['title'] }}</p>
                                <p class="text-xs text-sage-400">{{ $ca['desc'] }}</p>
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
                    <h2 class="font-serif text-xl font-bold text-sage-800">Mood Trends</h2>
                    <a href="{{ route('mood.index') }}" class="text-sm text-sage-500 hover:text-sage-700 transition-colors">View All →</a>
                </div>
                @if($moodChartData->count() > 1)
                <div class="h-64">
                    <canvas id="moodChart"></canvas>
                </div>
                @else
                <div class="h-48 flex items-center justify-center bg-sage-50 rounded-xl">
                    <div class="text-center">
                        <p class="text-3xl mb-2">📊</p>
                        <p class="text-sage-500 text-sm">Log at least 2 moods to see trends</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- AI Insights --}}
            <div class="glass-card-solid p-6" data-aos="fade-left" data-aos-delay="100">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-400 to-teal-400 flex items-center justify-center text-white text-lg">✨</div>
                    <h2 class="font-serif text-xl font-bold text-sage-800">AI Wellness Insights</h2>
                </div>
                <div class="space-y-3">
                    @foreach($insights as $insight)
                    <div class="flex items-start space-x-3 p-3 bg-sage-50/60 rounded-xl">
                        <span class="text-xl mt-0.5">{{ $insight['icon'] }}</span>
                        <p class="text-sm text-sage-600 leading-relaxed">{{ $insight['text'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Results & Journal --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Recent Assessments --}}
                <div class="glass-card-solid p-6" data-aos="fade-up">
                    <h2 class="font-serif text-lg font-bold text-sage-800 mb-4">Recent Assessments</h2>
                    @if($recentResults->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentResults->take(4) as $result)
                        <div class="flex items-center justify-between p-2">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg">{{ $result->assessment->icon ?? '📋' }}</span>
                                <div>
                                    <p class="text-sm font-medium text-sage-700">{{ Str::limit($result->assessment->title ?? 'Assessment', 20) }}</p>
                                    <p class="text-xs text-sage-400">{{ $result->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="badge-nature">{{ $result->total_score }}</span>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6">
                        <p class="text-sage-400 text-sm">No assessments yet</p>
                        <a href="{{ route('assessments.index') }}" class="text-teal-600 text-sm hover:underline mt-1 inline-block">Take your first →</a>
                    </div>
                    @endif
                </div>

                {{-- Recent Journal --}}
                <div class="glass-card-solid p-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-serif text-lg font-bold text-sage-800">Recent Journal</h2>
                        <a href="{{ route('journal.create') }}" class="text-teal-600 text-xs hover:underline">+ New</a>
                    </div>
                    @if($recentJournals->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentJournals as $entry)
                        <a href="{{ route('journal.show', $entry) }}" class="block p-3 rounded-xl hover:bg-sage-50 transition-colors">
                            <p class="text-sm font-medium text-sage-700">{{ Str::limit($entry->title, 30) }}</p>
                            <p class="text-xs text-sage-400 mt-1">{{ Str::limit($entry->content, 60) }}</p>
                            <p class="text-xs text-sage-300 mt-1">{{ $entry->created_at->diffForHumans() }}</p>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6">
                        <p class="text-sage-400 text-sm">No journal entries yet</p>
                        <a href="{{ route('journal.create') }}" class="text-teal-600 text-sm hover:underline mt-1 inline-block">Write your first →</a>
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

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [{
                label: 'Mood',
                data: data.map(d => d.score),
                borderColor: '#697a59',
                backgroundColor: 'rgba(105, 122, 89, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#697a59',
            },
            {
                label: 'Sleep (hrs)',
                data: data.map(d => d.sleep),
                borderColor: '#14b8a6',
                backgroundColor: 'rgba(20, 184, 166, 0.05)',
                borderWidth: 2,
                tension: 0.4,
                fill: false,
                pointRadius: 4,
                pointBackgroundColor: '#14b8a6',
                borderDash: [5, 5],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { family: 'Inter', size: 12 } } },
            },
            scales: {
                y: { beginAtZero: true, max: 10, grid: { color: 'rgba(105, 122, 89, 0.08)' }, ticks: { font: { family: 'Inter', size: 11 } } },
                x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11 } } }
            }
        }
    });
});
</script>
@endif
@endsection
