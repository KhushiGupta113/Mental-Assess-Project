<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\GuidanceRule;
use App\Models\Result;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function index()
    {
        $assessments = Assessment::all();
        return view('assessments.index', compact('assessments'));
    }

    public function show(Assessment $assessment)
    {
        $assessment->load(['questions' => function ($query) {
            $query->orderBy('order', 'asc');
        }]);
        return view('assessments.show', compact('assessment'));
    }

    public function store(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer',
        ]);

        $totalScore = 0;
        $answersData = [];

        foreach ($validated['answers'] as $questionId => $optionScore) {
            $totalScore += (int) $optionScore;
            $answersData[] = [
                'question_id' => $questionId,
                'option_score' => (int) $optionScore,
            ];
        }

        $result = Result::create([
            'user_id' => Auth::id(), // null if guest
            'assessment_id' => $assessment->id,
            'total_score' => $totalScore,
            'answers' => $answersData,
        ]);

        return redirect()->route('assessments.result', $result);
    }

    public function result(Result $result)
    {
        $assessment = $result->assessment;
        $assessment->load('questions');

        $rule = GuidanceRule::where('assessment_id', $assessment->id)
            ->where('min_score', '<=', $result->total_score)
            ->where('max_score', '>=', $result->total_score)
            ->first();

        // Get historical results for comparison
        $historicalResults = collect();
        if (Auth::check()) {
            $historicalResults = Result::where('user_id', Auth::id())
                ->where('assessment_id', $assessment->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($r) {
                    return [
                        'date' => $r->created_at->format('M d, Y'),
                        'score' => $r->total_score,
                    ];
                });
        }

        // Determine max possible score dynamically
        $questionCount = $assessment->questions->count();
        // Find max option score from first question's options
        $maxOptionScore = 3;
        if ($questionCount > 0 && isset($assessment->questions[0]->options)) {
            $maxOptionScore = collect($assessment->questions[0]->options)->max('score') ?? 3;
        }
        $maxPossibleScore = $questionCount * $maxOptionScore;

        $severityPercentage = $maxPossibleScore > 0
            ? round(($result->total_score / $maxPossibleScore) * 100)
            : 0;

        $severityLevel = match (true) {
            $severityPercentage <= 25 => 'minimal',
            $severityPercentage <= 50 => 'mild',
            $severityPercentage <= 75 => 'moderate',
            default => 'severe',
        };

        $severityColor = match ($severityLevel) {
            'minimal' => 'text-teal-600',
            'mild' => 'text-sage-600',
            'moderate' => 'text-amber-600',
            'severe' => 'text-red-600',
        };

        // Generate AI-powered personalized tips
        $aiService = new AIService();
        $personalizedTips = $aiService->generateAssessmentTips(
            $assessment->type ?? 'general',
            $result->total_score,
            $severityLevel,
            $rule?->interpretation
        );

        // Generate AI wellness summary if API key available
        $aiSummary = $aiService->generateWellnessSummary([
            'assessment' => $assessment->title,
            'score' => $result->total_score,
            'max_score' => $maxPossibleScore,
            'severity' => $severityLevel,
            'interpretation' => $rule?->interpretation ?? '',
        ]);

        return view('assessments.result', compact(
            'result', 'assessment', 'rule',
            'historicalResults', 'severityPercentage',
            'severityLevel', 'severityColor',
            'personalizedTips', 'aiSummary',
            'maxPossibleScore'
        ));
    }
}
