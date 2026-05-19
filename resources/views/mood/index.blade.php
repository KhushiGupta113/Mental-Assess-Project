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
    <div class="mb-8" data-aos="fade-down">
        <h1 class="section-heading mb-2">Mood Analytics</h1>
        <p class="t-muted">Track your emotional patterns and discover insights over time.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8" data-aos="fade-up">
        <div class="stat-card"><div class="text-2xl mb-1">📊</div><div class="text-2xl font-bold t-text">{{ $stats['total'] }}</div><div class="text-xs t-muted">Total Logs</div></div>
        <div class="stat-card"><div class="text-2xl mb-1">😊</div><div class="text-2xl font-bold t-text">{{ $stats['avg_mood'] }}/5</div><div class="text-xs t-muted">Avg Mood (7d)</div></div>
        <div class="stat-card"><div class="text-2xl mb-1">🌙</div><div class="text-2xl font-bold t-text">{{ $stats['avg_sleep'] ?: '0' }}h</div><div class="text-xs t-muted">Avg Sleep (7d)</div></div>
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
            <h2 class="font-serif text-xl font-bold t-text mb-1">{{ $startOfMonth->format('F Y') }}</h2>
            <p class="text-xs t-muted mb-4">Outer: Mood • Inner: Sleep</p>
            
            <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs font-semibold t-muted">
                <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
            </div>
            <div class="grid grid-cols-7 gap-1">
                @for($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="calendar-day empty"></div>
                @endfor
                
                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php 
                        $data = $calendarData[$day]; 
                        $moodColor = getMoodColor($data['mood']);
                        $sleepColor = getSleepColor($data['sleep']);
                        $isToday = $day == \Carbon\Carbon::today()->day && $startOfMonth->isCurrentMonth();
                    @endphp
                    <div class="calendar-day {{ $isToday ? 'today' : '' }} flex items-center justify-center relative group" title="Day {{ $day }} | Mood: {{ $data['mood'] ?? 'N/A' }} | Sleep: {{ $data['sleep'] ?? 'N/A' }}h">
                        <span class="absolute z-10 text-[10px] font-medium {{ $isToday ? 't-text' : 't-muted' }}">{{ $day }}</span>
                        @if($data['mood'] || $data['sleep'] !== null)
                        <svg class="w-8 h-8 opacity-40 group-hover:opacity-80 transition-opacity" viewBox="0 0 24 24" fill="none">
                            {{-- Outer Heart (Mood) --}}
                            @if($data['mood'])
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="{{ $moodColor }}" stroke-width="2" fill="transparent" />
                            @endif
                            {{-- Inner Heart (Sleep) --}}
                            @if($data['sleep'] !== null)
                            <path d="M12 18.5l-1.0-0.9C6.5 13.5 3.8 11.0 3.8 8.0 3.8 5.6 5.6 3.8 8.0 3.8c1.3 0 2.6 0.6 3.4 1.6 0.8-1.0 2.1-1.6 3.4-1.6 2.4 0 4.2 1.8 4.2 4.2 0 3.0-2.7 5.5-7.2 9.6L12 18.5z" stroke="{{ $sleepColor }}" stroke-width="1.5" fill="transparent" />
                            @endif
                        </svg>
                        @endif
                    </div>
                @endfor
            </div>
            
            <div class="mt-4 flex flex-wrap gap-2 justify-center">
                <div class="flex items-center text-[9px] t-muted"><span class="w-2 h-2 rounded-full bg-red-500 mr-1"></span> Low/Poor</div>
                <div class="flex items-center text-[9px] t-muted"><span class="w-2 h-2 rounded-full bg-yellow-500 mr-1"></span> Moderate</div>
                <div class="flex items-center text-[9px] t-muted"><span class="w-2 h-2 rounded-full bg-teal-500 mr-1"></span> Good/High</div>
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
                    <span class="text-2xl">{{ $log->mood_emoji }}</span>
                    <div>
                        <p class="text-sm font-medium t-text">{{ $log->created_at->format('M d, Y — g:i A') }}</p>
                        @if($log->notes)<p class="text-xs t-muted mt-0.5">{{ Str::limit($log->notes, 60) }}</p>@endif
                    </div>
                </div>
                <div class="flex items-center gap-3 text-xs t-muted">
                    @if($log->sleep_hours !== null)<span>🌙 {{ $log->sleep_hours }}h</span>@endif
                    @if($log->energy_level)<span>⚡ {{ $log->energy_level }}/5</span>@endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $moodLogs->links() }}</div>
        @else
        <div class="text-center py-10">
            <p class="text-4xl mb-3">📊</p>
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
