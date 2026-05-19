<?php

namespace App\Http\Controllers;

use App\Models\CrisisResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrisisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('country');

        // If user is logged in and has a country set, prioritize that
        $userCountry = null;
        if (Auth::check() && Auth::user()->country) {
            $userCountry = Auth::user()->country;
        }

        if ($search) {
            // Search for specific country
            $resources = CrisisResource::where('country', 'like', "%{$search}%")
                ->get()
                ->groupBy('country');

            // Also get remaining countries
            $otherResources = CrisisResource::where('country', 'not like', "%{$search}%")
                ->get()
                ->groupBy('country');
        } elseif ($userCountry) {
            // Prioritize user's country
            $resources = CrisisResource::where('country', $userCountry)
                ->get()
                ->groupBy('country');

            $otherResources = CrisisResource::where('country', '!=', $userCountry)
                ->get()
                ->groupBy('country');
        } else {
            $resources = CrisisResource::all()->groupBy('country');
            $otherResources = collect();
        }

        // Get list of all countries for search dropdown
        $countries = CrisisResource::pluck('country')->unique()->sort()->values();

        return view('crisis.index', compact('resources', 'otherResources', 'countries', 'search', 'userCountry'));
    }
}
