<?php

namespace App\Http\Controllers;

use App\Models\MoodLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MoodController extends Controller
{
    public function index()
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
        $avgMood = MoodLog::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->get()
            ->avg(fn($m) => $m->mood_score ?? $this->emojiToScore($m->mood_emoji));
        $avgSleep = MoodLog::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->whereNotNull('sleep_hours')
            ->avg('sleep_hours');

        $stats = [
            'total' => $totalLogs,
            'avg_mood' => round($avgMood ?? 0, 1),
            'avg_sleep' => round($avgSleep ?? 0, 1),
        ];

        // Calendar Data for current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
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
            // Prefer the latest log for a day if multiple exist
            $calendarData[$day]['mood'] = $log->mood_score ?? $this->emojiToScore($log->mood_emoji);
            $calendarData[$day]['sleep'] = $log->sleep_hours;
        }

        return view('mood.index', compact('moodLogs', 'weeklyData', 'stats', 'calendarData', 'startDayOfWeek', 'daysInMonth', 'startOfMonth'));
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
            '😢' => 1,
            '😔' => 2,
            '😐' => 3,
            '🙂' => 4,
            '😊' => 5,
            '✨' => 5,
            '😄' => 5,
            default => 3,
        };
    }
}
