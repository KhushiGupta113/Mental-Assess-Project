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
        ]);

        $userMessage = $request->input('message');
        $aiService = new AIService();
        $response = $aiService->chat($userMessage, Auth::user());

        return response()->json([
            'reply' => $response,
        ]);
    }
}
