@extends('layouts.main')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12" data-aos="fade-down">
        <span class="badge-teal mb-3 inline-block">🌿 About MindAssess</span>
        <h1 class="section-heading mb-4">About Mental Health & Our Mission</h1>
        <p class="section-subheading mx-auto">Understanding mental health is the first step towards wellness. We're here to make that journey safer and more accessible.</p>
    </div>

    <div class="space-y-8">
        {{-- Mission --}}
        <div class="glass-card-solid p-8" data-aos="fade-up">
            <h2 class="font-serif font-bold text-sage-800 text-2xl mb-4">Our Mission</h2>
            <p class="text-sage-600 leading-relaxed mb-4">MindAssess is an AI-assisted emotional wellness companion designed to help people understand their mental state through clinically validated screening tools, daily mood tracking, reflective journaling, and personalized AI-driven guidance.</p>
            <p class="text-sage-600 leading-relaxed">We believe that mental wellness should be accessible, private, and non-judgmental. Our platform combines evidence-based assessment tools with modern AI technology to provide meaningful insights — while always encouraging professional support when needed.</p>
        </div>

        {{-- What We Offer --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-aos="fade-up">
            @foreach([
                ['📋', 'Validated Assessments', '7 clinically recognized tools including PHQ-9, GAD-7, PSS, WHO-5, ISI, ASRS, and Copenhagen Burnout Inventory.'],
                ['📊', 'Mood Tracking', 'Daily mood, energy, and sleep logging with trend analysis and visual charts.'],
                ['📝', 'Smart Journaling', 'Reflective writing with AI prompts, sentiment analysis, and gratitude practice.'],
                ['🤖', 'AI Insights', 'Pattern detection and personalized recommendations based on your data.'],
                ['📚', 'Resource Library', 'Curated articles, breathing exercises, meditation guides, and coping strategies.'],
                ['🚨', 'Crisis Support', '24/7 crisis helplines and emergency resources always accessible.'],
            ] as $item)
            <div class="glass-card-solid p-6">
                <div class="text-3xl mb-3">{{ $item[0] }}</div>
                <h3 class="font-serif font-bold text-sage-800 text-lg mb-2">{{ $item[1] }}</h3>
                <p class="text-sage-500 text-sm leading-relaxed">{{ $item[2] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Disclaimer --}}
        <div class="crisis-banner p-8 text-center" data-aos="fade-up">
            <h2 class="font-serif font-bold text-sage-800 text-xl mb-3">⚠️ Important Disclaimer</h2>
            <p class="text-sage-600 max-w-2xl mx-auto">This platform is designed for <strong>educational and self-assessment purposes only</strong>. It is not a substitute for professional medical advice, diagnosis, or treatment. Always consult a qualified healthcare provider with any questions about your mental health.</p>
            <a href="{{ route('crisis.index') }}" class="btn-crisis mt-4 inline-block !animate-none">Access Crisis Resources</a>
        </div>

        {{-- Privacy --}}
        <div class="glass-card-solid p-8" data-aos="fade-up">
            <h2 class="font-serif font-bold text-sage-800 text-2xl mb-4">🔒 Privacy & Security</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="bg-sage-50 rounded-xl p-4">
                    <p class="font-semibold text-sage-700 mb-1">Private by Design</p>
                    <p class="text-sage-500">Your data stays yours. We never share personal information with third parties.</p>
                </div>
                <div class="bg-sage-50 rounded-xl p-4">
                    <p class="font-semibold text-sage-700 mb-1">Anonymous Mode</p>
                    <p class="text-sage-500">Take assessments without creating an account for complete anonymity.</p>
                </div>
                <div class="bg-sage-50 rounded-xl p-4">
                    <p class="font-semibold text-sage-700 mb-1">Data Control</p>
                    <p class="text-sage-500">Export or delete your data at any time. You're always in control.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
