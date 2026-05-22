<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $apiKey;

    /**
     * Models to try in order. If the first model's quota is exhausted (429),
     * the next model is attempted automatically.
     */
    protected array $models = [
        'gemini-2.5-flash',
        'gemini-2.5-flash-lite',
        'gemini-2.0-flash',
        'gemini-2.0-flash-lite',
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key', env('AI_API_KEY', ''));
    }

    /**
     * Centralized Gemini API caller with automatic model fallback.
     * Tries each model in $this->models; on 429 (quota exhausted), moves to next.
     */
    private function callGemini(array $payload, int $timeout = 12): ?array
    {
        foreach ($this->models as $model) {
            try {
                $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $this->apiKey;

                $response = Http::timeout($timeout)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, $payload);

                if ($response->successful()) {
                    return $response->json();
                }

                // On quota exhaustion, try the next model
                if ($response->status() === 429) {
                    Log::info("Gemini model {$model} quota exhausted, trying next model...");
                    continue;
                }

                // Other errors — log and stop trying
                Log::warning("Gemini API failed on {$model}", ['status' => $response->status(), 'body' => $response->body()]);
                return null;
            } catch (\Exception $e) {
                Log::warning("Gemini API exception on {$model}", ['error' => $e->getMessage()]);
                return null;
            }
        }

        Log::warning('All Gemini models exhausted (quota exceeded on all).');
        return null;
    }

    /**
     * Generate personalized tips based on assessment type, score, and severity.
     */
    public function generateAssessmentTips(string $assessmentType, int $score, string $severity, ?string $interpretation = null): array
    {
        // If API key is set, try Gemini first
        if (!empty($this->apiKey)) {
            $tips = $this->callGeminiAPI($assessmentType, $score, $severity, $interpretation);
            if ($tips) return $tips;
        }

        // Fallback to comprehensive built-in recommendations
        return $this->getBuiltInTips($assessmentType, $severity);
    }

    /**
     * Call Google Gemini API for personalized recommendations.
     */
    private function callGeminiAPI(string $type, int $score, string $severity, ?string $interpretation): ?array
    {
        $prompt = "You are a supportive mental wellness companion (NOT a doctor or therapist). A user just completed a {$type} self-assessment and scored {$score} (severity: {$severity}). " .
            ($interpretation ? "Interpretation: {$interpretation}. " : "") .
            "Generate exactly 5 personalized, actionable wellness tips. Each tip should have a short title (3-5 words) and a brief description (1-2 sentences). " .
            "Be warm, encouraging, and non-clinical. Never diagnose or prescribe medication. " .
            "Return as JSON array: [{\"title\": \"...\", \"description\": \"...\", \"icon\": \"emoji\"}]";

        $data = $this->callGemini([
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 2048,
                'thinkingConfig' => ['thinkingBudget' => 0],
            ],
        ], 15);

        if ($data) {
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // Extract JSON from response
            if (preg_match('/\[.*\]/s', $text, $matches)) {
                $tips = json_decode($matches[0], true);
                if (is_array($tips) && count($tips) > 0) {
                    return $tips;
                }
            }
        }

        return null;
    }

    /**
     * Comprehensive built-in tips organized by assessment type and severity.
     */
    private function getBuiltInTips(string $type, string $severity): array
    {
        $tipSets = [
            'phq9' => [
                'minimal' => [
                    ['title' => 'Keep Your Routine', 'description' => 'Maintaining a consistent daily routine helps stabilize mood. Continue your healthy habits of sleep, exercise, and social connection.', 'icon' => '📅'],
                    ['title' => 'Practice Gratitude', 'description' => 'Write down 3 things you are grateful for each evening. This simple practice rewires your brain towards positivity.', 'icon' => '🙏'],
                    ['title' => 'Stay Socially Connected', 'description' => 'Regular social interaction is protective against depression. Reach out to a friend or family member today.', 'icon' => '👥'],
                    ['title' => 'Move Your Body', 'description' => 'Even 20 minutes of walking releases endorphins. Aim for some form of physical activity most days.', 'icon' => '🚶'],
                    ['title' => 'Prioritize Sleep', 'description' => 'Quality sleep is foundational for mental health. Aim for 7-9 hours and keep a consistent bedtime.', 'icon' => '🌙'],
                ],
                'mild' => [
                    ['title' => 'Start a Mood Journal', 'description' => 'Track your mood daily to identify patterns and triggers. Writing helps process emotions and builds self-awareness.', 'icon' => '📝'],
                    ['title' => 'Try the 5-4-3-2-1 Grounding', 'description' => 'When feeling low, name 5 things you see, 4 you touch, 3 you hear, 2 you smell, and 1 you taste. This anchors you to the present.', 'icon' => '🧘'],
                    ['title' => 'Limit Social Media', 'description' => 'Excessive scrolling correlates with lower mood. Try setting a 30-minute daily limit and notice how you feel.', 'icon' => '📱'],
                    ['title' => 'Establish Morning Sunlight', 'description' => 'Get 10-15 minutes of natural sunlight within an hour of waking. This regulates serotonin and improves mood.', 'icon' => '☀️'],
                    ['title' => 'Connect With Someone', 'description' => 'Isolation worsens low mood. Share how you are feeling with a trusted friend, family member, or counselor.', 'icon' => '💬'],
                ],
                'moderate' => [
                    ['title' => 'Consider Professional Support', 'description' => 'A therapist or counselor can help you develop effective coping strategies. There is no shame in seeking help — it is a sign of strength.', 'icon' => '🤝'],
                    ['title' => 'Practice Behavioral Activation', 'description' => 'Depression makes you want to withdraw. Gently push yourself to do one enjoyable activity each day, even for 10 minutes.', 'icon' => '⚡'],
                    ['title' => 'Use the 2-Minute Rule', 'description' => 'When tasks feel overwhelming, commit to just 2 minutes. You will often find the momentum to continue once you start.', 'icon' => '⏱️'],
                    ['title' => 'Try Deep Breathing', 'description' => 'Practice 4-7-8 breathing: inhale for 4 seconds, hold for 7, exhale for 8. Do this 3 times when you feel overwhelmed.', 'icon' => '🌬️'],
                    ['title' => 'Set Micro-Goals', 'description' => 'Break your day into small, achievable goals. Completing even tiny tasks builds a sense of accomplishment.', 'icon' => '🎯'],
                ],
                'severe' => [
                    ['title' => 'Reach Out for Help Now', 'description' => 'Your score suggests you would benefit from professional support. Please contact a mental health professional or crisis helpline.', 'icon' => '🚨'],
                    ['title' => 'You Are Not Alone', 'description' => 'What you are feeling is real and valid. Millions of people experience similar struggles, and effective help is available.', 'icon' => '💙'],
                    ['title' => 'Create a Safety Plan', 'description' => 'Identify warning signs, coping strategies, and people you can contact. Keep emergency numbers easily accessible.', 'icon' => '📋'],
                    ['title' => 'Focus on One Hour at a Time', 'description' => 'When things feel overwhelming, focus only on getting through the next hour. Small steps forward are still steps forward.', 'icon' => '🕐'],
                    ['title' => 'Remove Harmful Triggers', 'description' => 'Make your environment safer. Remove or secure items that could be harmful, and surround yourself with supportive people.', 'icon' => '🛡️'],
                ],
            ],
            'gad7' => [
                'minimal' => [
                    ['title' => 'Continue Mindfulness', 'description' => 'Regular mindfulness practice keeps anxiety at bay. Even 5 minutes of focused breathing daily makes a difference.', 'icon' => '🧘'],
                    ['title' => 'Maintain Work-Life Balance', 'description' => 'Set clear boundaries between work and personal time. Schedule breaks and activities you enjoy.', 'icon' => '⚖️'],
                    ['title' => 'Stay Physically Active', 'description' => 'Exercise is one of the most effective natural anxiety reducers. Aim for at least 30 minutes most days.', 'icon' => '🏃'],
                    ['title' => 'Practice Progressive Relaxation', 'description' => 'Tense and release each muscle group from toes to head. This technique reduces physical tension that accompanies anxiety.', 'icon' => '💆'],
                    ['title' => 'Limit Caffeine Intake', 'description' => 'Caffeine can amplify anxiety symptoms. Try reducing to 1-2 cups of coffee and avoid it after 2 PM.', 'icon' => '☕'],
                ],
                'mild' => [
                    ['title' => 'Try Box Breathing', 'description' => 'Breathe in for 4 counts, hold 4, exhale 4, hold 4. Repeat 4 times. This activates your calm response system.', 'icon' => '📦'],
                    ['title' => 'Challenge Anxious Thoughts', 'description' => 'When worry strikes, ask: "Is this thought based on facts or fears?" and "What would I tell a friend in this situation?"', 'icon' => '🧠'],
                    ['title' => 'Create a Worry Window', 'description' => 'Designate 15 minutes daily for worry. When anxious thoughts arise outside this time, note them and postpone to your worry window.', 'icon' => '🪟'],
                    ['title' => 'Reduce Information Overload', 'description' => 'Limit news consumption and social media. Set specific times to check updates rather than constantly scrolling.', 'icon' => '📵'],
                    ['title' => 'Ground Yourself in Nature', 'description' => 'Spend 20 minutes outdoors daily. Nature exposure significantly reduces cortisol levels and calms the nervous system.', 'icon' => '🌿'],
                ],
                'moderate' => [
                    ['title' => 'Seek Therapeutic Support', 'description' => 'Cognitive Behavioral Therapy (CBT) is highly effective for anxiety. Consider finding a therapist who specializes in anxiety disorders.', 'icon' => '🤝'],
                    ['title' => 'Practice Body Scan Meditation', 'description' => 'Lie down and slowly scan your body from head to toe, releasing tension in each area. This calms both mind and body.', 'icon' => '🔍'],
                    ['title' => 'Create an Anxiety Toolkit', 'description' => 'Prepare items that help: a stress ball, calming playlist, essential oils, comfort photos. Keep them accessible for anxious moments.', 'icon' => '🧰'],
                    ['title' => 'Establish an Evening Wind-Down', 'description' => 'Create a 30-minute pre-sleep routine: dim lights, avoid screens, gentle stretching, calming tea. This prevents nighttime anxiety.', 'icon' => '🌙'],
                    ['title' => 'Practice Self-Compassion', 'description' => 'Treat yourself with the kindness you would show a close friend. Anxiety is not a character flaw — it is a treatable condition.', 'icon' => '💚'],
                ],
                'severe' => [
                    ['title' => 'Contact a Professional Today', 'description' => 'Severe anxiety significantly impacts quality of life. Please reach out to a mental health professional — effective treatments exist.', 'icon' => '🚨'],
                    ['title' => 'Use Emergency Grounding', 'description' => 'Hold ice cubes, splash cold water on your face, or press your feet firmly into the ground. These interrupt the anxiety spiral.', 'icon' => '🧊'],
                    ['title' => 'Call a Support Line', 'description' => 'If anxiety feels unbearable, call a mental health helpline. Trained counselors are available 24/7 to help you through this.', 'icon' => '📞'],
                    ['title' => 'Remember: This Shall Pass', 'description' => 'Anxiety peaks feel permanent but they are not. Each one passes. Focus on slow breathing until the wave subsides.', 'icon' => '🌊'],
                    ['title' => 'Avoid Major Decisions Now', 'description' => 'When anxiety is this intense, your thinking is affected. Postpone big decisions until you are feeling more stable.', 'icon' => '⏸️'],
                ],
            ],
        ];

        // Generic tips for assessment types not specifically covered
        $genericTips = [
            'minimal' => [
                ['title' => 'Great Self-Awareness', 'description' => 'Taking time to check in with yourself shows excellent self-awareness. Continue monitoring your wellbeing regularly.', 'icon' => '🌟'],
                ['title' => 'Maintain Healthy Habits', 'description' => 'Keep up your current lifestyle practices. Regular sleep, exercise, and social connection are protective factors.', 'icon' => '💪'],
                ['title' => 'Practice Mindfulness', 'description' => 'Daily mindfulness, even for 5 minutes, strengthens your emotional resilience and present-moment awareness.', 'icon' => '🧘'],
                ['title' => 'Connect With Others', 'description' => 'Strong social bonds are one of the best predictors of mental wellness. Nurture your relationships.', 'icon' => '👥'],
                ['title' => 'Celebrate Small Wins', 'description' => 'Acknowledge your daily accomplishments, no matter how small. This builds a positive self-narrative.', 'icon' => '🎉'],
            ],
            'mild' => [
                ['title' => 'Start Journaling', 'description' => 'Writing down your thoughts and feelings for 10 minutes daily can significantly improve emotional processing.', 'icon' => '📝'],
                ['title' => 'Build a Self-Care Routine', 'description' => 'Schedule at least one activity each day that is purely for your enjoyment and wellbeing.', 'icon' => '🛁'],
                ['title' => 'Practice Deep Breathing', 'description' => 'The 4-7-8 technique (breathe in 4s, hold 7s, out 8s) activates your parasympathetic nervous system.', 'icon' => '🌬️'],
                ['title' => 'Monitor Your Triggers', 'description' => 'Pay attention to what situations, people, or times worsen your symptoms. Awareness is the first step to change.', 'icon' => '🔍'],
                ['title' => 'Limit Screen Time Before Bed', 'description' => 'Blue light disrupts melatonin production. Put devices away 1 hour before sleep for better rest.', 'icon' => '📱'],
            ],
            'moderate' => [
                ['title' => 'Consider Professional Help', 'description' => 'Speaking with a therapist or counselor can provide you with proven strategies tailored to your specific needs.', 'icon' => '🤝'],
                ['title' => 'Try Structured Relaxation', 'description' => 'Progressive muscle relaxation, guided meditation, or yoga can significantly reduce symptoms at this level.', 'icon' => '🧘'],
                ['title' => 'Reach Out to Someone', 'description' => 'Share what you are experiencing with someone you trust. Verbalizing feelings reduces their intensity.', 'icon' => '💬'],
                ['title' => 'Create a Daily Structure', 'description' => 'When symptoms increase, having a structured routine provides stability and reduces decision fatigue.', 'icon' => '📅'],
                ['title' => 'Practice Self-Compassion', 'description' => 'Be gentle with yourself. What you are experiencing is valid, and seeking help is a sign of courage.', 'icon' => '💚'],
            ],
            'severe' => [
                ['title' => 'Seek Professional Help Now', 'description' => 'Your score indicates significant distress. Please reach out to a healthcare provider or mental health professional as soon as possible.', 'icon' => '🚨'],
                ['title' => 'Contact a Helpline', 'description' => 'If you are in crisis, trained counselors are available 24/7. India: 1800-599-0019 | USA: 988 | UK: 116 123', 'icon' => '📞'],
                ['title' => 'You Deserve Support', 'description' => 'What you are feeling right now is temporary, even if it does not feel that way. Effective help and treatment are available.', 'icon' => '💙'],
                ['title' => 'Take It One Step at a Time', 'description' => 'Focus only on the next small thing you can do. Get through the next hour. Then the next. Small steps matter.', 'icon' => '👣'],
                ['title' => 'Make Your Space Safe', 'description' => 'Surround yourself with supportive people. Remove anything that could be harmful. You matter.', 'icon' => '🛡️'],
            ],
        ];

        return $tipSets[$type][$severity] ?? $genericTips[$severity] ?? $genericTips['mild'];
    }

    /**
     * Generate a personalized wellness summary from mood and assessment data.
     */
    public function generateWellnessSummary(array $context): string
    {
        if (!empty($this->apiKey)) {
            $prompt = "You are a warm, supportive wellness companion. Based on this user data, write a brief 2-3 sentence personalized insight. " .
                "Data: " . json_encode($context) . ". " .
                "Be encouraging and actionable. Never diagnose. Speak directly to the user with 'you'.";

            $data = $this->callGemini([
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 512,
                    'thinkingConfig' => ['thinkingBudget' => 0],
                ],
            ], 10);

            if ($data) {
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            }
        }

        return '';
    }

    /**
     * Chat with the AI wellness companion.
     */
    public function chat(string $message, $user = null): string
    {
        $userContext = '';
        if ($user) {
            $concerns = is_array($user->concerns) ? implode(', ', $user->concerns) : '';
            $userContext = "User context: age group {$user->age_group}, gender {$user->gender}, country {$user->country}, concerns: {$concerns}. ";
        }

        // Try Gemini API first
        if (!empty($this->apiKey)) {
            $systemPrompt = "You are MindAssess Buddy, a warm, empathetic mental wellness companion chatbot. " .
                "Rules you MUST follow:\n" .
                "1. You are NOT a doctor, therapist, or medical professional. Never diagnose or prescribe.\n" .
                "2. Provide supportive, evidence-based wellness suggestions.\n" .
                "3. For serious concerns, gently recommend professional help.\n" .
                "4. If someone mentions self-harm or suicide, immediately provide crisis helpline numbers and urge them to call.\n" .
                "5. Keep responses concise (2-4 sentences), warm, and actionable.\n" .
                "6. Use a conversational, caring tone.\n" .
                $userContext .
                "Respond to the user's message.";

            $data = $this->callGemini([
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => $systemPrompt . "\n\nUser: " . $message]]],
                ],
                'generationConfig' => [
                    'temperature' => 0.8,
                    'maxOutputTokens' => 1024,
                    'thinkingConfig' => ['thinkingBudget' => 0],
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                ],
            ], 15);

            if ($data) {
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                if (!empty($text)) return trim($text);
            }
        }

        // Built-in smart responses
        return $this->getBuiltInChatResponse($message);
    }

    /**
     * Keyword-based intelligent fallback chat responses.
     */
    private function getBuiltInChatResponse(string $message): string
    {
        $msg = strtolower($message);

        // Crisis detection — ALWAYS handle first
        if (preg_match('/\b(suicid|kill myself|end my life|want to die|self.?harm|hurt myself|don\'t want to live)\b/i', $msg)) {
            return "I hear you, and I want you to know that your life matters. Please reach out to a crisis helpline right now:\n\n" .
                "🇮🇳 India: 1800-599-0019 (Kiran)\n" .
                "🇺🇸 USA: 988 (Suicide & Crisis Lifeline)\n" .
                "🇬🇧 UK: 116 123 (Samaritans)\n\n" .
                "You don't have to go through this alone. A trained counselor is available 24/7. 💚";
        }

        // Greetings
        if (preg_match('/\b(hello|hi|hey|greetings|good morning|good evening|howdy)\b/i', $msg)) {
            $greetings = [
                "Hello! 🌿 I'm your MindAssess Buddy. How are you feeling today? I'm here to listen and offer support.",
                "Hi there! 💚 Welcome. I'm here to help with wellness tips, breathing exercises, or just to chat. What's on your mind?",
                "Hey! 🌱 Nice to see you. Whether you need a quick breathing exercise, some coping strategies, or just want to talk — I'm here for you.",
            ];
            return $greetings[array_rand($greetings)];
        }

        // Anxiety
        if (preg_match('/\b(anxious|anxiety|worried|panic|nervous|fear|scared|overwhelm)\b/i', $msg)) {
            return "I understand anxiety can feel overwhelming. Here's something that can help right now:\n\n" .
                "🫧 **Box Breathing**: Breathe in for 4 seconds → Hold 4 seconds → Exhale 4 seconds → Hold 4 seconds. Repeat 4 times.\n\n" .
                "This activates your body's calm response. Would you like to try a guided breathing exercise, or would you prefer some other anxiety management tips?";
        }

        // Depression / sadness
        if (preg_match('/\b(depress|sad|down|hopeless|empty|worthless|unmotivated|no energy|tired of)\b/i', $msg)) {
            return "I'm sorry you're feeling this way. Your feelings are valid, and it takes courage to acknowledge them. 💙\n\n" .
                "One small step that can help: try doing just one tiny pleasant activity today — even a 5-minute walk, listening to a favorite song, or making yourself a warm drink.\n\n" .
                "Remember: depression is treatable, and asking for help is a sign of strength. Would you like me to suggest a self-assessment or some coping resources?";
        }

        // Stress
        if (preg_match('/\b(stress|pressur|overwhelm|too much|can\'t cope|burnout|burn out|exhaust)\b/i', $msg)) {
            return "Stress can feel like a heavy weight. Let's lighten the load together. 🌿\n\n" .
                "**Quick relief**: Close your eyes and take 3 slow, deep breaths. With each exhale, imagine releasing tension from your shoulders.\n\n" .
                "For longer-term strategies, I'd recommend trying our Stress (PSS) or Burnout assessments to better understand your stress patterns. Would that be helpful?";
        }

        // Sleep
        if (preg_match('/\b(sleep|insomnia|can\'t sleep|tired|exhausted|restless|nighttime|bedtime)\b/i', $msg)) {
            return "Sleep troubles can really affect everything else. Here are some quick tips: 🌙\n\n" .
                "1. **Avoid screens** 30 min before bed\n" .
                "2. Keep your room **cool and dark**\n" .
                "3. Try the **4-7-8 breathing** technique in bed\n\n" .
                "We also have a Sleep Hygiene Guide in our Resources section and an Insomnia (ISI) assessment that might help identify patterns.";
        }

        // Relationships
        if (preg_match('/\b(relationship|partner|friend|family|lonely|alone|breakup|fight|argument)\b/i', $msg)) {
            return "Relationship challenges can be really draining emotionally. It's good that you're reflecting on this. 💛\n\n" .
                "A helpful starting point is to think about boundaries — what you need to feel respected and safe. Our Resources section has a guide on \"Healthy Relationship Boundaries\" that many users find helpful.\n\n" .
                "Would you like to talk more about what's going on, or would you prefer some specific coping strategies?";
        }

        // Gratitude / positive
        if (preg_match('/\b(grateful|thankful|happy|better|good|great|wonderful|amazing)\b/i', $msg)) {
            return "That's wonderful to hear! 🌟 Acknowledging positive moments is actually a powerful wellness practice.\n\n" .
                "Try writing down what you're grateful for in your journal — research shows that regular gratitude practice can increase happiness by 25%! Would you like to create a journal entry?";
        }

        // Help / what can you do
        if (preg_match('/\b(help|what can you|what do you|features|options|what should I)\b/i', $msg)) {
            return "I'm your MindAssess Buddy! Here's what I can help with: 🌿\n\n" .
                "💬 **Talk** about how you're feeling\n" .
                "🫧 **Breathing exercises** for quick calm\n" .
                "📋 **Assessment recommendations** based on your concerns\n" .
                "📚 **Wellness resources** and coping strategies\n" .
                "🚨 **Crisis support** and helpline information\n\n" .
                "What would you like to explore?";
        }

        // Breathing exercise request
        if (preg_match('/\b(breath|breathing|calm|relax|meditation|meditate)\b/i', $msg)) {
            return "Let's do a quick breathing exercise together! 🌬️\n\n" .
                "**4-7-8 Technique:**\n" .
                "1. Breathe IN through your nose for **4 seconds**\n" .
                "2. HOLD your breath for **7 seconds**\n" .
                "3. Exhale slowly through your mouth for **8 seconds**\n\n" .
                "Repeat this 3-4 times. You should feel calmer within a minute. For guided exercises, check out our Breathing resources! 🧘";
        }

        // Default / catch-all
        $defaults = [
            "Thank you for sharing that with me. 💚 I'm here to support you. Could you tell me more about how you're feeling? I can suggest relevant assessments, resources, or coping strategies.",
            "I appreciate you opening up. 🌱 Every step counts on your wellness journey. Would you like to try a self-assessment, explore our resource library, or just chat about how you're doing?",
            "I'm here for you. 🌿 Whether it's anxiety, stress, sleep issues, or anything else — I can help point you toward helpful tools and strategies. What's weighing on your mind?",
        ];

        return $defaults[array_rand($defaults)];
    }
}

