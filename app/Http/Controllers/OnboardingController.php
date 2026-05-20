<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // If already completed onboarding, redirect to dashboard
        if ($user->onboarding_completed) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'age_group'  => 'required|string|in:under_18,18_24,25_34,35_44,45_54,55_plus',
            'gender'     => 'required|string|in:male,female,non_binary,prefer_not_to_say',
            'country'    => 'required|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'concerns'   => 'required|array|min:1|max:5',
            'concerns.*' => 'string|in:anxiety,depression,stress,sleep,burnout,adhd,relationships,self_esteem,grief,loneliness,anger,trauma',
            'avatar' => 'nullable|string|max:10',
        ]);

        $user = Auth::user();
        $user->update([
            'age_group'  => $validated['age_group'],
            'gender'     => $validated['gender'],
            'country'    => $validated['country'],
            'occupation' => $validated['occupation'] ?? null,
            'concerns'   => $validated['concerns'],
            'avatar' => $validated['avatar'] ?? '🌱',
            'onboarding_completed' => true,
        ]);

        return redirect()->route('dashboard')
            ->with('status', 'Welcome! Your personalized wellness journey has begun.');
    }
}
