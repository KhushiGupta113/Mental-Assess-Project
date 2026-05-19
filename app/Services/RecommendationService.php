<?php

namespace App\Services;

class RecommendationService
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function getPersonalizedPlan($user, $latestResult = null): string
    {
        $prompt = "Generate a short wellness recommendation for a user.";
        if ($latestResult) {
            $prompt .= " They recently scored " . $latestResult->total_score . " on an assessment.";
        }
        
        return $this->aiService->generateRecommendation($prompt);
    }
}
