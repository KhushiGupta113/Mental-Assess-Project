<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::query();

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('content_type', $request->type);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $resources = $query->orderBy('created_at', 'desc')->paginate(12);

        $categories = [
            'anxiety' => 'Anxiety Management',
            'depression' => 'Depression Support',
            'meditation' => 'Meditation & Mindfulness',
            'sleep' => 'Sleep Hygiene',
            'stress' => 'Stress Management',
            'burnout' => 'Burnout Prevention',
            'relationships' => 'Relationships',
        ];

        $contentTypes = [
            'article' => 'Articles',
            'exercise' => 'Exercises',
            'breathing' => 'Guided Breathing',
        ];

        return view('resources.index', compact('resources', 'categories', 'contentTypes'));
    }

    public function show(Resource $resource)
    {
        // Get related resources in the same category
        $related = Resource::where('category', $resource->category)
            ->where('_id', '!=', $resource->id)
            ->limit(3)
            ->get();

        return view('resources.show', compact('resource', 'related'));
    }
}
