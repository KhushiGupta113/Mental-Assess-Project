@extends('layouts.main')

@section('content')
{{-- ═══ Hero Section ═══ --}}
<section class="relative overflow-hidden bg-hero-gradient">
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-20 left-10 w-72 h-72 breathe-circle"></div>
        <div class="absolute bottom-10 right-20 w-96 h-96 breathe-circle" style="animation-delay: 2s;"></div>
        <div class="absolute top-40 right-40 w-48 h-48 breathe-circle" style="animation-delay: 4s;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-36">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div data-aos="fade-right">
                <span class="badge-nature mb-4 inline-block">🌿 Your Wellness Companion</span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-sage-900 leading-tight mb-6">
                    Understand your mind,<br>
                    <span class="text-gradient-nature">nurture your soul</span>
                </h1>
                <p class="text-lg text-sage-600 max-w-xl mb-8 leading-relaxed">
                    Take clinically validated self-assessments, track your mood, journal your thoughts, and receive personalized AI-powered wellness guidance — all in a safe, private space.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('assessments.index') }}" class="btn-nature text-base !py-3.5 !px-8">
                        Start Assessment
                        <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('about') }}" class="btn-nature-outline text-base !py-3.5 !px-8">Learn More</a>
                </div>
                <p class="mt-6 text-xs text-sage-400 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Private & confidential • Not a diagnostic tool
                </p>
            </div>

            <div class="hidden lg:flex justify-center" data-aos="fade-left">
                <div class="relative">
                    <div class="w-80 h-80 breathe-circle"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="glass-card p-8 text-center max-w-xs animate-float">
                            <div class="text-5xl mb-4">🧘</div>
                            <p class="font-serif text-sage-800 text-lg font-semibold mb-2">Breathe In...</p>
                            <p class="text-sage-500 text-sm">Take a moment for yourself</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ Features Grid ═══ --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="badge-teal mb-3 inline-block">How It Works</span>
            <h2 class="section-heading mb-4">Your wellness journey, guided</h2>
            <p class="section-subheading mx-auto">Clinically inspired tools combined with AI-powered insights to help you understand and improve your mental well-being.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $features = [
                ['icon'=>'📋','title'=>'Self-Assessments','desc'=>'7 clinically validated screening tools for depression, anxiety, stress, burnout, sleep, ADHD, and wellbeing.','color'=>'bg-indigo-50 text-indigo-600'],
                ['icon'=>'📊','title'=>'Mood Tracking','desc'=>'Log your daily mood, energy, sleep, and triggers. See patterns with beautiful analytics charts.','color'=>'bg-teal-50 text-teal-600'],
                ['icon'=>'📝','title'=>'Smart Journal','desc'=>'Write daily reflections with AI-generated prompts. Get sentiment analysis and keyword insights.','color'=>'bg-sage-50 text-sage-600'],
                ['icon'=>'🤖','title'=>'AI Insights','desc'=>'Receive personalized wellness recommendations based on your patterns and assessment results.','color'=>'bg-amber-50 text-amber-600'],
                ['icon'=>'📚','title'=>'Resource Library','desc'=>'Access breathing exercises, meditation guides, articles, and evidence-based coping strategies.','color'=>'bg-purple-50 text-purple-600'],
                ['icon'=>'🚨','title'=>'Crisis Support','desc'=>'Immediate access to crisis helplines and emergency mental health resources, available 24/7.','color'=>'bg-red-50 text-red-600'],
            ];
            @endphp

            @foreach($features as $i => $f)
            <div class="glass-card-solid p-7 hover:shadow-lift hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $i * 80 }}">
                <div class="w-14 h-14 rounded-2xl {{ $f['color'] }} flex items-center justify-center text-2xl mb-5">{{ $f['icon'] }}</div>
                <h3 class="text-xl font-serif font-bold text-sage-800 mb-3">{{ $f['title'] }}</h3>
                <p class="text-sage-500 leading-relaxed text-sm">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ Assessments Preview ═══ --}}
<section class="py-20 bg-nature-gradient">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="badge-nature mb-3 inline-block">Validated Tools</span>
            <h2 class="section-heading mb-4">Clinically recognized assessments</h2>
            <p class="section-subheading mx-auto">Each screening tool is based on established clinical questionnaires used by healthcare professionals worldwide.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($assessments->take(8) as $i => $assessment)
            <a href="{{ route('assessments.show', $assessment) }}" class="assessment-card" data-aos="fade-up" data-aos-delay="{{ $i * 60 }}">
                <div class="p-6">
                    <div class="card-icon bg-{{ $assessment->color ?? 'sage' }}-100 text-{{ $assessment->color ?? 'sage' }}-600 mb-4">
                        <span class="text-2xl">{{ $assessment->icon ?? '💚' }}</span>
                    </div>
                    <h3 class="font-serif font-bold text-sage-800 mb-2">{{ $assessment->title }}</h3>
                    <p class="text-sage-500 text-sm mb-3 line-clamp-2">{{ $assessment->description }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-sage-400">~{{ $assessment->estimated_minutes ?? 5 }} min</span>
                        <span class="text-sage-400 group-hover:text-sage-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-10" data-aos="fade-up">
            <a href="{{ route('assessments.index') }}" class="btn-nature-outline">View All Assessments →</a>
        </div>
    </div>
</section>

{{-- ═══ Disclaimer Banner ═══ --}}
<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up">
        <div class="crisis-banner flex flex-col md:flex-row items-center gap-6 p-8">
            <div class="text-4xl">⚠️</div>
            <div class="flex-1 text-center md:text-left">
                <h3 class="font-serif font-bold text-sage-800 text-lg mb-2">Important Disclaimer</h3>
                <p class="text-sage-600 text-sm">This platform is <strong>not a medical diagnosis system</strong>. It provides self-assessment tools for educational purposes. If you are experiencing a mental health crisis, please contact emergency services or a crisis helpline immediately.</p>
            </div>
            <a href="{{ route('crisis.index') }}" class="btn-crisis whitespace-nowrap !animate-none">Get Help Now</a>
        </div>
    </div>
</section>

{{-- ═══ CTA Section ═══ --}}
<section class="py-20 bg-gradient-to-br from-sage-700 to-sage-900 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-6">Begin your wellness journey today</h2>
        <p class="text-sage-200 text-lg mb-8 max-w-2xl mx-auto">Take a free self-assessment, start tracking your mood, and discover personalized insights to support your mental well-being.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-white text-sage-800 font-semibold px-8 py-3.5 rounded-xl hover:bg-sage-50 hover:shadow-lift transition-all duration-200">Create Free Account</a>
            <a href="{{ route('assessments.index') }}" class="border-2 border-white/30 text-white font-semibold px-8 py-3.5 rounded-xl hover:bg-white/10 transition-all duration-200">Take Assessment</a>
        </div>
    </div>
</section>
@endsection
