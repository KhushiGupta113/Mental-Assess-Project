@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Result Card --}}
    <div class="glass-card-solid overflow-hidden" data-aos="fade-up">

        {{-- Header with Score --}}
        <div class="bg-gradient-to-br from-sage-100 via-teal-50 to-sage-50 px-6 md:px-10 py-10 border-b border-sage-200/50">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">

                {{-- Score Ring --}}
                <div class="flex-shrink-0 flex flex-col items-center">
                    <div class="relative w-32 h-32">
                        <svg class="progress-ring w-32 h-32" viewBox="0 0 128 128">
                            <circle cx="64" cy="64" r="54" stroke="#e3e7df" stroke-width="7" fill="none"/>
                            <circle class="progress-ring__circle" cx="64" cy="64" r="54"
                                stroke="{{ $severityLevel === 'minimal' ? '#14b8a6' : ($severityLevel === 'mild' ? '#697a59' : ($severityLevel === 'moderate' ? '#d97706' : '#ef4444')) }}"
                                stroke-width="7" fill="none" stroke-linecap="round"
                                stroke-dasharray="{{ 2 * 3.14159 * 54 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 54 * (1 - $severityPercentage / 100) }}"/>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-bold font-serif {{ $severityColor }}">{{ $result->total_score }}</span>
                            <span class="text-[10px] text-sage-400 uppercase tracking-wider">/ {{ $maxPossibleScore }}</span>
                        </div>
                    </div>
                    <span class="mt-2 badge-{{ $severityLevel === 'minimal' ? 'teal' : ($severityLevel === 'mild' ? 'nature' : ($severityLevel === 'moderate' ? 'warning' : 'danger')) }} text-sm px-4 py-1">{{ ucfirst($severityLevel) }}</span>
                </div>

                {{-- Assessment Info --}}
                <div class="text-center md:text-left flex-1">
                    <span class="badge-nature mb-2 inline-block">Assessment Complete ✅</span>
                    <h1 class="text-2xl md:text-3xl font-bold font-serif text-sage-800 mb-2">{{ $assessment->title }}</h1>

                    @if($rule)
                    <div class="bg-white/60 rounded-xl p-4 mt-3 border border-sage-200/40">
                        <p class="text-sm font-semibold text-sage-700 mb-1">{{ $rule->interpretation }}</p>
                        <p class="text-sm text-sage-500 leading-relaxed">{{ $rule->recommendation }}</p>
                    </div>
                    @endif

                    @if(!empty($aiSummary))
                    <div class="bg-indigo-50/70 rounded-xl p-4 mt-3 border border-indigo-100/50 flex items-start gap-3">
                        <span class="text-lg mt-0.5">✨</span>
                        <div>
                            <p class="text-xs font-semibold text-indigo-600 mb-0.5">AI Wellness Insight</p>
                            <p class="text-sm text-indigo-700 leading-relaxed">{{ $aiSummary }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Personalized Tips Section --}}
        <div class="px-6 md:px-10 py-8 space-y-8">

            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sage-400 to-teal-500 flex items-center justify-center text-white text-lg">💡</div>
                    <div>
                        <h2 class="text-xl font-serif font-bold text-sage-800">Personalized Tips & Strategies</h2>
                        <p class="text-xs text-sage-400">Based on your assessment results</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($personalizedTips as $i => $tip)
                    <div class="group p-5 bg-gradient-to-br from-white to-sage-50/40 rounded-xl border border-sage-100 hover:shadow-md hover:border-sage-200 transition-all duration-200" data-aos="fade-up" data-aos-delay="{{ $i * 60 }}">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl flex-shrink-0 mt-0.5">{{ $tip['icon'] ?? '🌱' }}</span>
                            <div>
                                <h3 class="font-semibold text-sage-800 text-sm mb-1">{{ $tip['title'] }}</h3>
                                <p class="text-sage-500 text-sm leading-relaxed">{{ $tip['description'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Suggested Resources --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white text-lg">📚</div>
                    <h2 class="text-lg font-serif font-bold text-sage-800">Recommended Next Steps</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <a href="{{ route('resources.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-sage-100 hover:bg-sage-50 hover:border-sage-200 transition-all group">
                        <span class="text-xl">📖</span>
                        <div>
                            <p class="text-sm font-medium text-sage-700 group-hover:text-sage-800">Browse Resources</p>
                            <p class="text-xs text-sage-400">Articles & exercises</p>
                        </div>
                    </a>
                    @auth
                    <a href="{{ route('journal.create') }}" class="flex items-center gap-3 p-4 rounded-xl border border-sage-100 hover:bg-sage-50 hover:border-sage-200 transition-all group">
                        <span class="text-xl">📝</span>
                        <div>
                            <p class="text-sm font-medium text-sage-700 group-hover:text-sage-800">Write a Journal Entry</p>
                            <p class="text-xs text-sage-400">Reflect on your feelings</p>
                        </div>
                    </a>
                    <a href="{{ route('mood.index') }}" class="flex items-center gap-3 p-4 rounded-xl border border-sage-100 hover:bg-sage-50 hover:border-sage-200 transition-all group">
                        <span class="text-xl">📊</span>
                        <div>
                            <p class="text-sm font-medium text-sage-700 group-hover:text-sage-800">Track Your Mood</p>
                            <p class="text-xs text-sage-400">Monitor daily patterns</p>
                        </div>
                    </a>
                    @else
                    <a href="{{ route('register') }}" class="flex items-center gap-3 p-4 rounded-xl border border-teal-100 bg-teal-50/50 hover:bg-teal-50 transition-all group sm:col-span-2">
                        <span class="text-xl">🌱</span>
                        <div>
                            <p class="text-sm font-medium text-teal-700 group-hover:text-teal-800">Create Free Account</p>
                            <p class="text-xs text-teal-500">Save results, track mood, journal & get AI insights</p>
                        </div>
                    </a>
                    @endauth
                </div>
            </div>

            {{-- Historical Chart --}}
            @if($historicalResults->count() > 1)
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white text-lg">📈</div>
                    <h2 class="text-lg font-serif font-bold text-sage-800">Your Progress Over Time</h2>
                </div>
                <div class="h-48 bg-sage-50 rounded-xl p-4">
                    <canvas id="historyChart"></canvas>
                </div>
            </div>
            @endif

            {{-- Disclaimer --}}
            <div class="crisis-banner p-5 flex items-start gap-3">
                <span class="text-lg flex-shrink-0">⚠️</span>
                <div>
                    <p class="text-sm text-sage-700 font-semibold mb-1">Important Reminder</p>
                    <p class="text-sm text-sage-600">This is a self-assessment tool for educational purposes, <strong>not a medical diagnosis</strong>. If you are in crisis or need immediate help, please reach out to a <a href="{{ route('crisis.index') }}" class="text-red-500 font-semibold hover:underline">crisis helpline</a>.</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row justify-center gap-3 pt-2">
                <a href="{{ route('assessments.index') }}" class="btn-nature-outline text-center !text-sm">Take Another Assessment</a>
                @auth
                <a href="{{ route('dashboard') }}" class="btn-nature text-center !text-sm">Return to Dashboard</a>
                @else
                <a href="{{ route('register') }}" class="btn-nature text-center !text-sm">Create Account to Save</a>
                @endauth
            </div>
        </div>
    </div>
</div>

@if($historicalResults->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('historyChart').getContext('2d');
    const data = @json($historicalResults);
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [{ label: 'Score', data: data.map(d => d.score), borderColor: '#697a59', backgroundColor: 'rgba(105,122,89,0.1)', borderWidth: 2, tension: 0.4, fill: true, pointRadius: 5, pointBackgroundColor: '#697a59' }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(105,122,89,0.08)' } }, x: { grid: { display: false } } } }
    });
});
</script>
@endif
@endsection
