@extends('layouts.main')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12" data-aos="fade-down">
        <span class="badge-teal mb-3 inline-flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            About MindAssess
        </span>
        <h1 class="section-heading mb-4">About Mental Health & Our Mission</h1>
        <p class="section-subheading mx-auto">Understanding mental health is the first step towards wellness. We're here to make that journey safer and more accessible.</p>
    </div>

    <div class="space-y-8">
        {{-- Mission --}}
        <div class="glass-card-solid p-8" data-aos="fade-up">
            <h2 class="font-serif font-bold t-text text-2xl mb-4">Our Mission</h2>
            <p class="t-muted leading-relaxed mb-4">MindAssess is an AI-assisted emotional wellness companion designed to help people understand their mental state through clinically validated screening tools, daily mood tracking, reflective journaling, and personalized AI-driven guidance.</p>
            <p class="t-muted leading-relaxed">We believe that mental wellness should be accessible, private, and non-judgmental. Our platform combines evidence-based assessment tools with modern AI technology to provide meaningful insights — while always encouraging professional support when needed.</p>
        </div>

        {{-- What We Offer --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-aos="fade-up">
            @foreach([
                ['<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>', 'Validated Assessments', '7 clinically recognized tools including PHQ-9, GAD-7, PSS, WHO-5, ISI, ASRS, and Copenhagen Burnout Inventory.'],
                ['<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>', 'Mood Tracking', 'Daily mood, energy, and sleep logging with trend analysis and visual charts.'],
                ['<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>', 'Smart Journaling', 'Reflective writing with AI prompts, sentiment analysis, and gratitude practice.'],
                ['<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>', 'AI Insights', 'Pattern detection and personalized recommendations based on your data.'],
                ['<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>', 'Resource Library', 'Curated articles, breathing exercises, meditation guides, and coping strategies.'],
                ['<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>', 'Crisis Support', '24/7 crisis helplines and emergency resources always accessible.'],
            ] as $item)
            <div class="glass-card-solid p-6">
                <div class="mb-4 text-th-primary">{!! $item[0] !!}</div>
                <h3 class="font-serif font-bold t-text text-lg mb-2">{{ $item[1] }}</h3>
                <p class="t-muted text-sm leading-relaxed">{{ $item[2] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Disclaimer --}}
        <div class="crisis-banner p-8 text-center" data-aos="fade-up">
            <h2 class="font-serif font-bold text-red-600 text-xl mb-3 flex items-center justify-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Important Disclaimer
            </h2>
            <p class="t-muted max-w-2xl mx-auto">This platform is designed for <strong>educational and self-assessment purposes only</strong>. It is not a substitute for professional medical advice, diagnosis, or treatment. Always consult a qualified healthcare provider with any questions about your mental health.</p>
            <a href="{{ route('crisis.index') }}" class="btn-crisis mt-4 inline-block !animate-none">Access Crisis Resources</a>
        </div>

        {{-- Privacy --}}
        <div class="glass-card-solid p-8" data-aos="fade-up">
            <h2 class="font-serif font-bold t-text text-2xl mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 t-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Privacy & Security
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="t-surface rounded-xl p-4">
                    <p class="font-semibold t-text mb-1">Private by Design</p>
                    <p class="t-muted">Your data stays yours. We never share personal information with third parties.</p>
                </div>
                <div class="t-surface rounded-xl p-4">
                    <p class="font-semibold t-text mb-1">Anonymous Mode</p>
                    <p class="t-muted">Take assessments without creating an account for complete anonymity.</p>
                </div>
                <div class="t-surface rounded-xl p-4">
                    <p class="font-semibold t-text mb-1">Data Control</p>
                    <p class="t-muted">Export or delete your data at any time. You're always in control.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

