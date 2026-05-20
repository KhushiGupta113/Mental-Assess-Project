<?php

namespace App\Http\Controllers;

use App\Models\MoodLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MoodController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $moodLogs = MoodLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Weekly chart data
        $weeklyData = MoodLog::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($log) {
                return [
                    'date' => $log->created_at->format('M d'),
                    'score' => $log->mood_score ?? $this->emojiToScore($log->mood_emoji),
                    'emoji' => $log->mood_emoji,
                    'sleep' => $log->sleep_hours,
                    'energy' => $log->energy_level,
                ];
            });

        // Stats
        $totalLogs = MoodLog::where('user_id', $user->id)->count();
        
        $recentLogs = MoodLog::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->get();

        $avgMood = $recentLogs->count() > 0
            ? round($recentLogs->avg(fn($m) => $m->mood_score ?? $this->emojiToScore($m->mood_emoji)), 1)
            : 0;
        
        // Calculate avg sleep manually to handle MongoDB type issues
        $sleepValues = $recentLogs
            ->filter(fn($m) => $m->sleep_hours !== null && $m->sleep_hours > 0)
            ->pluck('sleep_hours')
            ->map(fn($v) => (float) $v);

        $avgSleep = $sleepValues->count() > 0
            ? round($sleepValues->avg(), 1)
            : 0;

        $stats = [
            'total' => $totalLogs,
            'avg_mood' => $avgMood,
            'avg_sleep' => $avgSleep,
        ];

        // Calendar Data — support month navigation via ?month=YYYY-MM
        $calendarMonth = $request->query('month')
            ? Carbon::createFromFormat('Y-m', $request->query('month'))->startOfMonth()
            : Carbon::now()->startOfMonth();
        
        $startOfMonth = $calendarMonth->copy();
        $endOfMonth = $calendarMonth->copy()->endOfMonth();
        $startDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sunday) - 6 (Saturday)
        $daysInMonth = $startOfMonth->daysInMonth;

        $monthLogs = MoodLog::where('user_id', $user->id)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $calendarData = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $calendarData[$i] = ['mood' => null, 'sleep' => null];
        }

        foreach ($monthLogs as $log) {
            $day = $log->created_at->day;
            $calendarData[$day]['mood'] = $log->mood_score ?? $this->emojiToScore($log->mood_emoji);
            $calendarData[$day]['sleep'] = $log->sleep_hours !== null ? (float) $log->sleep_hours : null;
        }

        // Previous/Next month strings for navigation
        $prevMonth = $startOfMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $startOfMonth->copy()->addMonth()->format('Y-m');
        $isCurrentMonth = $startOfMonth->isCurrentMonth();

        return view('mood.index', compact('moodLogs', 'weeklyData', 'stats', 'calendarData', 'startDayOfWeek', 'daysInMonth', 'startOfMonth', 'prevMonth', 'nextMonth', 'isCurrentMonth'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mood_emoji' => 'required|string',
            'mood_label' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'energy_level' => 'nullable|integer|min:1|max:5',
            'sleep_hours' => 'nullable|numeric|min:0|max:24',
            'triggers' => 'nullable|array',
            'activities' => 'nullable|array',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['mood_score'] = $this->emojiToScore($validated['mood_emoji']);

        MoodLog::create($validated);

        return redirect()->route('dashboard')->with('status', 'mood-logged');
    }

    private function emojiToScore(string $emoji): int
    {
        return match ($emoji) {
            '😢', 'angry' => 1,
            '😔', 'sad' => 2,
            '😐', 'neutral' => 3,
            '🙂', 'happy' => 4,
            '😊', '✨', '😄', 'very_happy' => 5,
            default => 3,
        };
    }
}
