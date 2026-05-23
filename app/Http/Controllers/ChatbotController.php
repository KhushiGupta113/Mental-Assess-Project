<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function respond(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
            'history.*.role' => 'required|string|in:user,bot',
            'history.*.text' => 'required|string|max:2000',
        ]);

        $userMessage = $request->input('message');
        $history = $request->input('history', []);
        $aiService = new AIService();
        $response = $aiService->chat($userMessage, Auth::user(), $history);

        return response()->json([
            'reply' => $response,
        ]);
    }
}
