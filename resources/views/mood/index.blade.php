@extends('layouts.main')

@section('content')
@php
function getMoodColor($score) {
    if (!$score) return 'transparent';
    return match((int)$score) {
        1 => '#ef4444', // red
        2 => '#f97316', // orange
        3 => '#eab308', // yellow
        4 => '#14b8a6', // teal
        5 => '#6366f1', // indigo
        default => '#9ca3af'
    };
}
function getSleepColor($hours) {
    if ($hours === null) return 'transparent';
    if ($hours < 5) return '#ef4444'; // red
    if ($hours < 7) return '#f59e0b'; // amber
    return '#14b8a6'; // teal
}
@endphp
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-indigo-500/10 via-purple-500/5 to-transparent rounded-[2rem] p-8 mb-8 border border-th-border relative overflow-hidden" data-aos="fade-down">
        <div class="absolute -right-8 -top-8 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-8 -bottom-8 w-48 h-48 bg-teal-500/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <h1 class="text-3xl font-serif font-bold t-text mb-1 tracking-tight">Mood Analytics</h1>
                <p class="t-muted text-lg font-medium">Track your emotional patterns and discover insights over time.</p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8" data-aos="fade-up">
        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl mb-2 flex items-center justify-center" style="background:var(--th-primary-light)">
                <svg class="w-5 h-5" style="color:var(--th-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div class="text-2xl font-bold t-text">{{ $stats['total'] }}</div>
            <div class="text-xs t-muted">Total Logs</div>
        </div>
        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl mb-2 flex items-center justify-center" style="background:var(--th-accent-light, var(--th-primary-light))">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="text-2xl font-bold t-text">{{ $stats['avg_mood'] }}/5</div>
            <div class="text-xs t-muted">Avg Mood (7d)</div>
        </div>
        <div class="stat-card">
            <div class="w-10 h-10 rounded-xl mb-2 flex items-center justify-center bg-indigo-50 dark:bg-indigo-900/30">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </div>
            <div class="text-2xl font-bold t-text">{{ $stats['avg_sleep'] > 0 ? $stats['avg_sleep'] : '—' }}h</div>
            <div class="text-xs t-muted">Avg Sleep (7d)</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Chart --}}
        <div class="lg:col-span-2 glass-card-solid p-6" data-aos="fade-up">
            <h2 class="font-serif text-xl font-bold t-text mb-4">30-Day Trends</h2>
            @if($weeklyData->count() > 1)
            <div class="h-72"><canvas id="moodTrendChart"></canvas></div>
            @else
            <div class="h-48 flex items-center justify-center t-surface rounded-xl">
                <p class="t-muted text-sm">Log more moods to see trends</p>
            </div>
            @endif
        </div>

        {{-- Calendar Hearts --}}
        <div class="glass-card-solid p-6" data-aos="fade-up" data-aos-delay="100">
            {{-- Month Navigation --}}
            <div class="flex items-center justify-between mb-1">
                <a href="{{ route('mood.index', ['month' => $prevMonth]) }}" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:t-card/5 transition-colors" title="Previous Month">
                    <svg class="w-4 h-4 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h2 class="font-serif text-lg font-bold t-text">{{ $startOfMonth->format('F Y') }}</h2>
                @if(!$isCurrentMonth)
                <a href="{{ route('mood.index', ['month' => $nextMonth]) }}" class="p-1.5 rounded-lg hover:bg-black/5 dark:hover:t-card/5 transition-colors" title="Next Month">
                    <svg class="w-4 h-4 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @else
                <div class="w-7"></div>
                @endif
            </div>
            <p class="text-xs t-muted mb-3 text-center flex items-center justify-center gap-3">
                <span class="flex items-center"><svg class="w-3 h-3 text-indigo-400 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> Outer Heart: Mood</span>
                <span class="t-light">·</span>
                <span class="flex items-center"><svg class="w-3 h-3 text-teal-400 mr-1" viewBox="0 0 24 24" fill="currentColor"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg> Inner Heart: Sleep</span>
            </p>
            
            <div class="grid grid-cols-7 gap-0.5 mb-1.5 text-center text-[10px] font-semibold t-muted uppercase tracking-wider">
                <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
            </div>
            <div class="grid grid-cols-7 gap-0.5">
                @for($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="calendar-day empty"></div>
                @endfor
                
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php 
                        $data = $calendarData[$day]; 
                        $moodColor = getMoodColor($data['mood']);
                        $sleepColor = getSleepColor($data['sleep']);
                        $isToday = $day == \Carbon\Carbon::today()->day && $startOfMonth->isCurrentMonth();
                        $hasData = $data['mood'] || $data['sleep'] !== null;
                    @endphp
                    <div class="calendar-day {{ $isToday ? 'today' : '' }} relative group" 
                         title="Day {{ $day }}{{ $data['mood'] ? ' · Mood: '.$data['mood'].'/5' : '' }}{{ $data['sleep'] !== null ? ' · Sleep: '.$data['sleep'].'h' : '' }}">
                        @if($hasData)
                        <svg class="w-full h-full p-0.5 opacity-50 group-hover:opacity-90 transition-opacity" viewBox="0 0 36 36" fill="none">
                            {{-- Outer Heart (Mood) — larger --}}
                            @if($data['mood'])
                            <path d="M18 30l-1.8-1.6C8.4 21.4 4 17.5 4 12.8 4 8.9 7.1 5.8 11 5.8c2.2 0 4.3 1 5.6 2.6L18 10l1.4-1.6C20.7 6.8 22.8 5.8 25 5.8c3.9 0 7 3.1 7 7 0 4.7-4.4 8.6-12.2 15.6L18 30z" stroke="{{ $moodColor }}" stroke-width="2.2" fill="{{ $moodColor }}22"/>
                            @endif
                            {{-- Inner Heart (Sleep) — smaller, centered --}}
                            @if($data['sleep'] !== null)
                            <path d="M18 24.5l-1.1-1C11.8 18.9 9 16.5 9 13.6 9 11.2 10.9 9.3 13.3 9.3c1.3 0 2.6.6 3.4 1.6l1.3 1.5 1.3-1.5c.8-1 2.1-1.6 3.4-1.6C25.1 9.3 27 11.2 27 13.6c0 2.9-2.8 5.3-7.9 9.9L18 24.5z" stroke="{{ $sleepColor }}" stroke-width="1.8" fill="{{ $sleepColor }}22"/>
                            @endif
                        </svg>
                        @endif
                        <span class="absolute inset-0 flex items-center justify-center text-[9px] font-bold {{ $isToday ? 't-text' : 't-muted' }}" style="{{ $hasData ? 'text-shadow: 0 0 3px var(--th-card-bg), 0 0 6px var(--th-card-bg)' : '' }}">{{ $day }}</span>
                    </div>
                @endfor
            </div>
            
            <div class="mt-3 flex flex-wrap gap-3 justify-center text-[9px] t-muted">
                <div class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-red-400 mr-1"></span>Low</div>
                <div class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 mr-1"></span>Fair</div>
                <div class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-teal-400 mr-1"></span>Good</div>
                <div class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-indigo-400 mr-1"></span>Great</div>
            </div>
        </div>
    </div>

    {{-- Mood Log History --}}
    <div class="glass-card-solid p-6" data-aos="fade-up">
        <h2 class="font-serif text-xl font-bold t-text mb-4">Mood History</h2>
        @if($moodLogs->count() > 0)
        <div class="space-y-3">
            @foreach($moodLogs as $log)
            <div class="flex items-center justify-between p-3 t-surface border border-nature rounded-xl">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 flex items-center justify-center flex-shrink-0">
                        <x-mood-icon :score="$log->mood_score ?? 3" class="w-10 h-10" />
                    </div>
                    <div>
                        <p class="text-sm font-medium t-text">{{ $log->created_at->format('M d, Y — g:i A') }}</p>
                        @if($log->notes)<p class="text-xs t-muted mt-0.5">{{ Str::limit($log->notes, 60) }}</p>@endif
                    </div>
                </div>
                <div class="flex items-center gap-3 text-xs t-muted">
                    @if($log->sleep_hours !== null)<span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg> {{ $log->sleep_hours }}h</span>@endif
                    @if($log->energy_level)<span class="flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> {{ $log->energy_level }}/5</span>@endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $moodLogs->links() }}</div>
        @else
        <div class="text-center py-10">
            <svg class="w-16 h-16 mx-auto mb-3 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
            <p class="t-muted">No mood logs yet. Start tracking from your dashboard!</p>
        </div>
        @endif
    </div>
</div>

@if($weeklyData->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const data = @json($weeklyData);
    new Chart(document.getElementById('moodTrendChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [
                { label: 'Mood', data: data.map(d => d.score), borderColor: '#697a59', backgroundColor: 'rgba(105,122,89,0.1)', borderWidth: 2, tension: 0.4, fill: true, pointRadius: 4, pointBackgroundColor: '#697a59' },
                { label: 'Sleep', data: data.map(d => d.sleep), borderColor: '#14b8a6', borderWidth: 2, tension: 0.4, fill: false, pointRadius: 3, pointBackgroundColor: '#14b8a6', borderDash: [5,5] },
                { label: 'Energy', data: data.map(d => d.energy), borderColor: '#6366f1', borderWidth: 2, tension: 0.4, fill: false, pointRadius: 3, pointBackgroundColor: '#6366f1', borderDash: [3,3] }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 15 } } }, scales: { y: { beginAtZero: true, max: 10, grid: { color: 'rgba(105,122,89,0.08)' } }, x: { grid: { display: false } } } }
    });
});
</script>
@endif
@endsection

