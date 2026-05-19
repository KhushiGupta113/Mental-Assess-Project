@extends('layouts.main')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8" data-aos="fade-down">
        <h1 class="section-heading mb-2">Mood Analytics</h1>
        <p class="text-sage-500">Track your emotional patterns and discover insights over time.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8" data-aos="fade-up">
        <div class="stat-card"><div class="text-2xl mb-1">📊</div><div class="text-2xl font-bold text-sage-800">{{ $stats['total'] }}</div><div class="text-xs text-sage-500">Total Logs</div></div>
        <div class="stat-card"><div class="text-2xl mb-1">😊</div><div class="text-2xl font-bold text-sage-800">{{ $stats['avg_mood'] }}/5</div><div class="text-xs text-sage-500">Avg Mood (7d)</div></div>
        <div class="stat-card"><div class="text-2xl mb-1">🌙</div><div class="text-2xl font-bold text-sage-800">{{ $stats['avg_sleep'] }}h</div><div class="text-xs text-sage-500">Avg Sleep (7d)</div></div>
    </div>

    {{-- Chart --}}
    <div class="glass-card-solid p-6 mb-8" data-aos="fade-up">
        <h2 class="font-serif text-xl font-bold text-sage-800 mb-4">30-Day Mood & Sleep Trends</h2>
        @if($weeklyData->count() > 1)
        <div class="h-72"><canvas id="moodTrendChart"></canvas></div>
        @else
        <div class="h-48 flex items-center justify-center bg-sage-50 rounded-xl">
            <p class="text-sage-400 text-sm">Log more moods to see trends</p>
        </div>
        @endif
    </div>

    {{-- Mood Log History --}}
    <div class="glass-card-solid p-6" data-aos="fade-up">
        <h2 class="font-serif text-xl font-bold text-sage-800 mb-4">Mood History</h2>
        @if($moodLogs->count() > 0)
        <div class="space-y-3">
            @foreach($moodLogs as $log)
            <div class="flex items-center justify-between p-3 bg-sage-50/50 rounded-xl">
                <div class="flex items-center space-x-3">
                    <span class="text-2xl">{{ $log->mood_emoji }}</span>
                    <div>
                        <p class="text-sm font-medium text-sage-700">{{ $log->created_at->format('M d, Y — g:i A') }}</p>
                        @if($log->notes)<p class="text-xs text-sage-500 mt-0.5">{{ Str::limit($log->notes, 60) }}</p>@endif
                    </div>
                </div>
                <div class="flex items-center gap-3 text-xs text-sage-400">
                    @if($log->sleep_hours)<span>🌙 {{ $log->sleep_hours }}h</span>@endif
                    @if($log->energy_level)<span>⚡ {{ $log->energy_level }}/5</span>@endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $moodLogs->links() }}</div>
        @else
        <div class="text-center py-10">
            <p class="text-4xl mb-3">📊</p>
            <p class="text-sage-500">No mood logs yet. Start tracking from your dashboard!</p>
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
