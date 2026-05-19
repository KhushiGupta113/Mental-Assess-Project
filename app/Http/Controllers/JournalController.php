<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    public function index()
    {
        $entries = JournalEntry::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('journal.index', compact('entries'));
    }

    public function create()
    {
        $prompts = [
            "What are three things you're grateful for today?",
            "Describe a moment today that made you smile.",
            "What's one thing you'd like to let go of?",
            "Write about a challenge you overcame recently.",
            "What does your ideal peaceful day look like?",
            "Who made a positive impact on you today?",
            "What's something you're looking forward to?",
            "Describe how your body feels right now.",
            "What would you tell your past self about today?",
            "Write about a small victory you had this week.",
        ];

        $todayPrompt = $prompts[array_rand($prompts)];

        return view('journal.create', compact('todayPrompt'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'mood_tag' => 'nullable|string',
            'tags' => 'nullable|string',
            'is_gratitude' => 'nullable|boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['tags'] = !empty($validated['tags'])
            ? array_map('trim', explode(',', $validated['tags']))
            : [];
        $validated['is_gratitude'] = $validated['is_gratitude'] ?? false;
        $validated['sentiment_score'] = $this->analyzeSentiment($validated['content']);

        JournalEntry::create($validated);

        return redirect()->route('journal.index')->with('status', 'journal-saved');
    }

    public function show(JournalEntry $entry)
    {
        if ($entry->user_id !== Auth::id()) {
            abort(403);
        }

        return view('journal.show', compact('entry'));
    }

    public function destroy(JournalEntry $entry)
    {
        if ($entry->user_id !== Auth::id()) {
            abort(403);
        }

        $entry->delete();

        return redirect()->route('journal.index')->with('status', 'journal-deleted');
    }

    /**
     * Basic sentiment analysis using keyword matching.
     * Returns a score from -1.0 (very negative) to 1.0 (very positive).
     */
    private function analyzeSentiment(string $text): float
    {
        $positiveWords = [
            'happy', 'grateful', 'thankful', 'joy', 'love', 'wonderful', 'great', 'amazing',
            'excited', 'peaceful', 'calm', 'relaxed', 'hopeful', 'proud', 'confident',
            'blessed', 'smile', 'laugh', 'beautiful', 'good', 'better', 'best', 'progress',
            'accomplish', 'achieve', 'success', 'kind', 'gentle', 'warm', 'bright',
        ];

        $negativeWords = [
            'sad', 'anxious', 'worried', 'stressed', 'angry', 'frustrated', 'overwhelmed',
            'depressed', 'lonely', 'tired', 'exhausted', 'hopeless', 'afraid', 'scared',
            'nervous', 'panic', 'cry', 'hurt', 'pain', 'fail', 'failure', 'terrible',
            'awful', 'hate', 'worst', 'difficult', 'struggle', 'suffer', 'helpless',
        ];

        $words = str_word_count(strtolower($text), 1);
        $totalWords = count($words);

        if ($totalWords === 0) return 0.0;

        $positiveCount = count(array_intersect($words, $positiveWords));
        $negativeCount = count(array_intersect($words, $negativeWords));

        $score = ($positiveCount - $negativeCount) / max($totalWords * 0.1, 1);

        return max(-1.0, min(1.0, round($score, 2)));
    }
}
