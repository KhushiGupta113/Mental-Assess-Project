<?php

namespace App\Http\Controllers;

use App\Models\MoodLog;
use App\Models\Result;
use App\Models\Assessment;
use App\Models\JournalEntry;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(RecommendationService $recommendationService)
    {
        $user = Auth::user();

        // Redirect to onboarding if not completed
        if (!$user->onboarding_completed) {
            return redirect()->route('onboarding.show');
        }
        // Recent mood logs (last 7 days)
        $recentMoods = MoodLog::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->get();

        // Mood chart data (last 14 days)
        $moodChartData = MoodLog::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(14))
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

        // Recent assessment results
        $recentResults = Result::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentResults as $result) {
            $result->assessment = Assessment::find($result->assessment_id);
        }

        // Recent journal entries
        $recentJournals = JournalEntry::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Streak calculation
        $streak = $this->calculateStreak($user);

        // AI insights based on real data
        $insights = $this->generateInsights($user, $recentMoods, $recentResults);

        // Stats
        $stats = [
            'total_assessments' => Result::where('user_id', $user->id)->count(),
            'total_moods' => MoodLog::where('user_id', $user->id)->count(),
            'total_journals' => JournalEntry::where('user_id', $user->id)->count(),
            'streak' => $streak,
        ];

        // Today's mood
        $todaysMood = MoodLog::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::today())
            ->first();

        return view('dashboard', compact(
            'recentMoods',
            'moodChartData',
            'recentResults',
            'recentJournals',
            'insights',
            'stats',
            'todaysMood'
        ));
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

    private function calculateStreak($user): int
    {
        $streak = 0;
        $date = Carbon::today();

        while (true) {
            $hasActivity = MoodLog::where('user_id', $user->id)
                ->whereDate('created_at', $date)
                ->exists();

            if (!$hasActivity) {
                $hasActivity = JournalEntry::where('user_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->exists();
            }

            if ($hasActivity) {
                $streak++;
                $date->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    private function generateInsights($user, $moods, $results): array
    {
        $insights = [];

        if ($moods->count() >= 3) {
            $avgScore = $moods->avg(fn($m) => $m->mood_score ?? $this->emojiToScore($m->mood_emoji));

            if ($avgScore < 3) {
                $insights[] = [
                    'icon' => '💙',
                    'text' => 'Your mood has been lower than usual this week. Remember to be gentle with yourself.',
                    'type' => 'care',
                ];
            } elseif ($avgScore >= 4) {
                $insights[] = [
                    'icon' => '🌟',
                    'text' => 'Your mood has been positive this week! Keep up the great self-care.',
                    'type' => 'positive',
                ];
            }

            $avgSleep = $moods->whereNotNull('sleep_hours')->avg('sleep_hours');
            if ($avgSleep && $avgSleep < 6) {
                $insights[] = [
                    'icon' => '🌙',
                    'text' => 'Your average sleep is below 6 hours. Better sleep can significantly improve your mood.',
                    'type' => 'suggestion',
                ];
            }
        }

        if ($results->count() > 0) {
            $latestResult = $results->first();
            if ($latestResult && $latestResult->total_score > 14) {
                $insights[] = [
                    'icon' => '🤝',
                    'text' => 'Based on your recent assessment, consider reaching out to a counselor or trusted friend.',
                    'type' => 'care',
                ];
            }
        }

        if (empty($insights)) {
            $insights = [
                ['icon' => '🌱', 'text' => 'Start tracking your mood daily to get personalized insights.', 'type' => 'suggestion'],
                ['icon' => '📝', 'text' => 'Try writing a short journal entry to reflect on your day.', 'type' => 'suggestion'],
                ['icon' => '🧘', 'text' => 'A 5-minute breathing exercise can help reduce stress levels.', 'type' => 'suggestion'],
            ];
        }

        return $insights;
    }
}
