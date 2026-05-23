@extends('layouts.main')

@section('content')
{{-- ═══ Scroll Canvas for Falling Nature Elements ═══ --}}
<div id="petalWindCanvas" class="fixed inset-0 pointer-events-none overflow-hidden" style="z-index: 10;" aria-hidden="true"></div>

{{-- ═══ Hero Section ═══ --}}
<section class="relative overflow-hidden bg-hero-gradient pb-20 pt-32 lg:pt-40" style="z-index:1;">
    {{-- Immersive Thematic Nature Backgrounds --}}
    <div class="absolute inset-0 opacity-30 dark:opacity-15 pointer-events-none overflow-hidden select-none z-0">
        <!-- Sage: Forest -->
        <img class="hero-theme-bg sage-only w-full h-full object-cover" src="{{ asset('images/bg_sage.png') }}" alt="Sage Forest Background">

        <!-- Lavender: Lavender Field -->
        <img class="hero-theme-bg lavender-only w-full h-full object-cover" src="{{ asset('images/bg_lavender.png') }}" alt="Lavender Field Background">

        <!-- Rose: Rose Garden -->
        <img class="hero-theme-bg rose-only w-full h-full object-cover" src="{{ asset('images/bg_rose.png') }}" alt="Rose Garden Background">

        <!-- Ocean: Coastal Water -->
        <img class="hero-theme-bg ocean-only w-full h-full object-cover" src="{{ asset('images/bg_ocean.png') }}" alt="Ocean View Background">

        <!-- Peach: Autumn Sunset -->
        <img class="hero-theme-bg peach-only w-full h-full object-cover" src="{{ asset('images/bg_peach.png') }}" alt="Peach Autumn Background">
    </div>

    {{-- Hero Blur Blobs --}}
    <div class="absolute inset-0 opacity-40 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-th-primary/20 blur-[100px]"></div>
        <div class="absolute top-1/2 -right-32 w-[30rem] h-[30rem] rounded-full bg-teal-400/20 blur-[120px]"></div>
        <div class="absolute bottom-0 left-1/3 w-[25rem] h-[25rem] rounded-full bg-th-accent/15 blur-[100px]"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-4xl mx-auto" data-aos="fade-up">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/50 dark:bg-black/20 border border-th-border backdrop-blur-md mb-8 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                <span class="text-sm font-semibold t-text tracking-wide uppercase">Your Mindful Space</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-serif font-extrabold t-text leading-[1.1] mb-6 tracking-tight">
                Mental clarity starts <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-th-primary to-teal-500">with a single step.</span>
            </h1>
            
            <p class="text-lg md:text-2xl t-muted mb-10 leading-relaxed font-medium max-w-2xl mx-auto">
                Track your mood, journal your thoughts, and discover insights. A sanctuary for your mental well-being, designed just for you.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="btn-nature text-lg !py-4 !px-8 rounded-full shadow-xl shadow-th-primary/20 hover:shadow-2xl hover:shadow-th-primary/30 transition-all hover:-translate-y-1">
                    Get Started Free
                </a>
                <a href="#how-it-works" class="px-8 py-4 rounded-full font-semibold t-text hover:bg-black/5 dark:hover:bg-white/5 transition-all text-lg flex items-center group">
                    Learn more
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

        {{-- Hero Visual (Bento-style preview) — Glassmorphism --}}
        <div class="mt-20 relative mx-auto w-full max-w-5xl" data-aos="fade-up" data-aos-delay="200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Mood Card -->
                <div class="glass-card-premium p-6 transform rotate-[-2deg] hover:rotate-0 transition-all duration-500 ease-out z-10 relative">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-serif font-bold text-xl t-text">How are you?</h3>
                        <span class="bg-teal-100/80 dark:bg-teal-900/40 text-teal-700 dark:text-teal-300 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider backdrop-blur-sm">Today</span>
                    </div>
                    <div class="flex justify-between items-center gap-2">
                        @foreach(['angry','sad','neutral','happy','very_happy'] as $i => $mood)
                            <div class="relative group">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center transition-all cursor-pointer {{ $i === 3 ? 'bg-teal-50 ring-2 ring-teal-400 scale-110 shadow-md' : 'bg-white/40 dark:bg-white/10 hover:scale-110' }}">
                                    <x-mood-icon :score="$i+1" class="w-8 h-8 {{ $i === 3 ? '' : 'opacity-70 group-hover:opacity-100' }}" />
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-sm font-medium text-teal-600 dark:text-teal-400">Feeling Good</p>
                    </div>
                </div>
                
                <!-- Welcome/Stats Card -->
                <div class="bg-gradient-to-br from-th-primary to-teal-500 rounded-[2rem] p-8 shadow-2xl shadow-th-primary/30 z-20 relative md:-mt-8 md:col-span-1 transform hover:scale-[1.02] transition-transform duration-500 flex flex-col justify-center items-center text-center overflow-hidden">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4xNSkiLz48L3N2Zz4=')] opacity-50"></div>
                    <div class="absolute inset-0 backdrop-blur-[1px]"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-16 h-16 rounded-full bg-white/20 mb-4 flex items-center justify-center text-white backdrop-blur-md shadow-inner border border-white/30 text-3xl">
                            🍃
                        </div>
                        <h3 class="font-serif font-bold text-2xl text-white mb-2">Welcome Back</h3>
                        <div class="bg-white/20 rounded-full px-4 py-1.5 backdrop-blur-md border border-white/20 inline-flex items-center">
                            <span class="text-white font-bold mr-1">12</span>
                            <span class="text-white/90 text-sm font-medium">Day Streak</span>
                            <span class="ml-1.5 text-orange-300">🔥</span>
                        </div>
                    </div>
                </div>

                <!-- Journal Card -->
                <div class="glass-card-premium p-6 transform rotate-[2deg] hover:rotate-0 transition-all duration-500 ease-out z-10 relative">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-serif font-bold text-lg t-text">Journal</h3>
                        <span class="text-[10px] font-bold t-muted bg-white/40 dark:bg-white/10 px-2 py-1 rounded-md border border-white/30 dark:border-white/10 flex items-center backdrop-blur-sm">
                            <svg class="w-3 h-3 mr-1 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Saved
                        </span>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm t-text font-medium leading-relaxed italic">"Today was surprisingly calm. I took a long walk in the park and finally felt that heavy weight lift off my shoulders..."</p>
                        <div class="flex gap-2 mt-3">
                            <span class="text-[10px] uppercase font-bold text-indigo-500 bg-indigo-50/80 dark:bg-indigo-900/30 px-2 py-1 rounded-md backdrop-blur-sm">Peaceful</span>
                            <span class="text-[10px] uppercase font-bold text-orange-500 bg-orange-50/80 dark:bg-orange-900/30 px-2 py-1 rounded-md backdrop-blur-sm">Nature</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ Horizontal Scrolling Marquee / Badges ═══ --}}
<div class="py-6 bg-th-primary text-th-primary-lighter overflow-hidden whitespace-nowrap border-y border-th-border-strong relative" style="z-index:1;">
    <div class="animate-marquee inline-flex items-center text-xl md:text-2xl font-serif font-bold uppercase tracking-wider">
        @php
            $marqueeItems = [
                ['text' => 'Better Sleep', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>'],
                ['text' => 'Reduced Stress', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>'],
                ['text' => 'Mood Analytics', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>'],
                ['text' => 'Smart Journaling', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>'],
                ['text' => 'Validated Assessments', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ];
            // Duplicate for smooth infinite scroll
            $marqueeItems = array_merge($marqueeItems, $marqueeItems, $marqueeItems);
        @endphp
        
        @foreach($marqueeItems as $item)
            <span class="mx-8 flex items-center gap-3">
                <svg class="w-6 h-6 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
                {{ $item['text'] }}
            </span>
            <span class="text-white/30">•</span>
        @endforeach
    </div>
</div>

<style>
@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-marquee {
    animation: marquee 25s linear infinite;
}
</style>

{{-- ═══ Features Grid (Bento Style) — Glassmorphism ═══ --}}
<section id="how-it-works" class="py-32 relative" style="background:var(--th-card-bg); z-index:1;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-serif font-bold t-text mb-6">Everything you need, <br>in one peaceful space.</h2>
            <p class="text-xl t-muted max-w-2xl mx-auto">Discover a suite of tools designed to help you understand your mind and cultivate a healthier lifestyle.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 auto-rows-[280px]">
            <!-- Assessment Bento -->
            <div class="md:col-span-2 md:row-span-2 glass-card-premium p-10 relative overflow-hidden group" data-aos="fade-up">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 z-0 transition-opacity opacity-0 group-hover:opacity-100 duration-500"></div>
                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50/80 dark:bg-indigo-900/30 flex items-center justify-center mb-6 backdrop-blur-sm">
                            <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <h3 class="text-3xl font-serif font-bold t-text mb-4">Self-Assessments</h3>
                        <p class="t-muted text-lg leading-relaxed max-w-sm">Take clinically validated screening tools for depression, anxiety, ADHD, and more to understand where you stand.</p>
                    </div>
                    <div class="flex gap-4 mt-8 overflow-hidden">
                        <!-- Mock Assessment Pills -->
                        <span class="px-4 py-2 rounded-full bg-white/60 dark:bg-white/10 shadow-sm border border-white/40 dark:border-white/10 text-sm font-semibold whitespace-nowrap backdrop-blur-sm">PHQ-9 (Depression)</span>
                        <span class="px-4 py-2 rounded-full bg-white/60 dark:bg-white/10 shadow-sm border border-white/40 dark:border-white/10 text-sm font-semibold whitespace-nowrap backdrop-blur-sm">GAD-7 (Anxiety)</span>
                    </div>
                </div>
            </div>

            <!-- Mood Tracking Bento -->
            <div class="md:col-span-2 glass-card-premium p-8 relative overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                <div class="relative z-10 flex items-center justify-between h-full">
                    <div class="max-w-[60%]">
                        <div class="w-12 h-12 rounded-xl bg-teal-50/80 dark:bg-teal-900/30 flex items-center justify-center mb-4 backdrop-blur-sm">
                            <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h3 class="text-2xl font-serif font-bold t-text mb-2">Mood Trends</h3>
                        <p class="t-muted">Track your daily feelings and visualize your emotional journey over time.</p>
                    </div>
                    <div class="w-1/3 flex justify-end">
                        <div class="flex flex-col gap-2 opacity-80 group-hover:scale-110 transition-transform duration-500">
                            <x-mood-icon score="4" class="w-12 h-12" />
                            <x-mood-icon score="5" class="w-12 h-12 ml-4" />
                            <x-mood-icon score="3" class="w-12 h-12" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Smart Journal Bento -->
            <div class="glass-card-premium p-8 relative group" data-aos="fade-up" data-aos-delay="200">
                <div class="w-12 h-12 rounded-xl bg-emerald-50/80 dark:bg-emerald-900/30 flex items-center justify-center mb-4 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <h3 class="text-xl font-serif font-bold t-text mb-2">Smart Journal</h3>
                <p class="t-muted text-sm">Write reflections with AI-generated prompts & insights.</p>
            </div>

            <!-- Resource Library Bento -->
            <div class="glass-card-premium p-8 relative group" data-aos="fade-up" data-aos-delay="300">
                <div class="w-12 h-12 rounded-xl bg-purple-50/80 dark:bg-purple-900/30 flex items-center justify-center mb-4 backdrop-blur-sm">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="text-xl font-serif font-bold t-text mb-2">Library</h3>
                <p class="t-muted text-sm">Explore evidence-based coping strategies and guides.</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══ Assessments Preview Section ═══ --}}
<section class="py-24 bg-nature-gradient border-y border-th-border-strong relative" style="z-index:1;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16" data-aos="fade-up">
            <div class="max-w-2xl">
                <span class="badge-nature mb-3 inline-block">Validated Tools</span>
                <h2 class="text-4xl font-serif font-bold t-text mb-4">Clinically recognized tests</h2>
                <p class="text-lg t-muted">Screening tools based on established clinical questionnaires used by healthcare professionals.</p>
            </div>
            <a href="{{ route('assessments.index') }}" class="mt-6 md:mt-0 font-semibold t-primary hover:underline flex items-center">
                Explore all tools 
                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($assessments->take(8) as $i => $assessment)
            <a href="{{ route('assessments.show', $assessment) }}" class="assessment-card-glass group" data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
                <div class="p-6 h-full flex flex-col">
                    <div class="card-icon bg-{{ $assessment->color ?? 'sage' }}-100/80 dark:bg-{{ $assessment->color ?? 'sage' }}-900/30 text-{{ $assessment->color ?? 'sage' }}-600 mb-5 flex items-center justify-center backdrop-blur-sm">
                        @if($assessment->type === 'phq9')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21v-2m-4 2v-2m8 2v-2"/></svg>
                        @elseif($assessment->type === 'gad7')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm3.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75z"/></svg>
                        @elseif($assessment->type === 'pss')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                        @elseif($assessment->type === 'who5')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.536a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        @elseif($assessment->type === 'isi')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                        @elseif($assessment->type === 'asrs')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z"/></svg>
                        @elseif($assessment->type === 'cbi')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 10.5h.375c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125H21M3 7.5h15c.828 0 1.5.672 1.5 1.5v6c0 .828-.672 1.5-1.5 1.5H3c-.828 0-1.5-.672-1.5-1.5v-6c0-.828.672-1.5 1.5-1.5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 10.5h3v3H6v-3z"/></svg>
                        @else
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                        @endif
                    </div>
                    <h3 class="font-serif font-bold text-lg t-text mb-2">{{ $assessment->title }}</h3>
                    <p class="t-muted text-sm mb-4 line-clamp-2 flex-grow">{{ $assessment->description }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-white/20 dark:border-white/5">
                        <span class="text-xs font-medium t-light uppercase tracking-wider">{{ $assessment->estimated_minutes ?? 5 }} min</span>
                        <span class="t-light group-hover:t-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ Call to Action ═══ --}}
<section class="py-32 relative overflow-hidden text-center bg-th-primary" style="z-index:1;">
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] rounded-full bg-white/20 blur-[100px] animate-breathe"></div>
    </div>
    <div class="relative max-w-3xl mx-auto px-4 z-10" data-aos="zoom-in">
        <h2 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6">Begin your wellness journey.</h2>
        <p class="text-xl text-white/80 mb-10 max-w-xl mx-auto">Join a supportive space designed to help you thrive. Start tracking, reflecting, and growing today.</p>
        <a href="{{ route('register') }}" class="cta-glow-btn inline-block bg-white text-emerald-700 font-bold text-lg px-10 py-5 rounded-full shadow-2xl hover:scale-105 hover:shadow-white/20 transition-all duration-300">
            Create Free Account
        </a>
    </div>
</section>

{{-- ═══ Scroll-Triggered Falling Petals / Leaves Script ═══ --}}
<script>
(function() {
    const canvas = document.getElementById('petalWindCanvas');
    if (!canvas) return;

    let lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
    let scrollAccumulator = 0;

    function getTheme() {
        return document.documentElement.getAttribute('data-color-theme') || 'sage';
    }

    function getPetalSVG(theme) {
        if (theme === 'lavender') {
            // Purple lavender flower bud
            return `<svg viewBox="0 0 50 50" class="w-full h-full">
                <path d="M25 5 C25 25 5 25 5 25 C5 25 25 25 25 45 C25 25 45 25 45 25 C45 25 25 25 25 5 Z" fill="var(--th-primary)" opacity="0.75"/>
                <circle cx="25" cy="25" r="3.5" fill="var(--th-accent)" opacity="0.9"/>
            </svg>`;
        } else if (theme === 'rose') {
            // Pink rose petal
            return `<svg viewBox="0 0 50 50" class="w-full h-full">
                <path d="M25 5 C38 5, 45 18, 45 32 C45 45, 25 48, 25 48 C25 48, 5 45, 5 32 C5 18, 12 5, 25 5 Z" fill="var(--th-primary)" opacity="0.75"/>
            </svg>`;
        } else if (theme === 'ocean') {
            // Blue teardrop water leaf / bubble
            return `<svg viewBox="0 0 50 50" class="w-full h-full">
                <path d="M25 5 C35 15, 40 28, 40 38 C40 45, 33 48, 25 48 C17 48, 10 45, 10 38 C10 28, 15 15, 25 5 Z" fill="var(--th-primary)" opacity="0.7"/>
            </svg>`;
        } else if (theme === 'peach') {
            // Autumn leaf
            return `<svg viewBox="0 0 50 50" class="w-full h-full">
                <path d="M25 2 C38 12, 45 30, 25 48 C5 30, 12 12, 25 2 Z" fill="var(--th-primary)" opacity="0.8"/>
                <path d="M25 2 L25 48" stroke="var(--th-accent)" stroke-width="1.5" opacity="0.4"/>
            </svg>`;
        } else {
            // Sage (Default): Sage Leaf
            return `<svg viewBox="0 0 50 50" class="w-full h-full">
                <path d="M25 5 C38 15, 38 35, 25 45 C12 35, 12 15, 25 5 Z" fill="var(--th-primary)" opacity="0.8"/>
                <path d="M25 5 L25 45" stroke="var(--th-accent)" stroke-width="1.5" opacity="0.4"/>
            </svg>`;
        }
    }

    function spawnLeaf() {
        const theme = getTheme();
        const size = 16 + Math.random() * 16; // 16px - 32px (elegant and small)
        const startX = Math.random() * 100; // Left offset %
        const duration = 6 + Math.random() * 6; // 6s - 12s drift time
        const delay = Math.random() * 0.2;
        const driftAnim = Math.random() > 0.5 ? 'petal-drift-right' : 'petal-drift-left';
        const swayAnim = Math.random() > 0.5 ? 'petal-sway-wide' : 'petal-sway-narrow';
        const wobbleDur = 2.5 + Math.random() * 2.5;

        const el = document.createElement('div');
        el.className = 'petal-wrapper';
        el.style.cssText = `
            left: ${startX}%;
            top: -40px;
            width: ${size}px;
            height: ${size}px;
            animation: ${driftAnim} ${duration}s linear ${delay}s forwards;
        `;

        const inner = document.createElement('div');
        inner.className = 'petal-inner';
        inner.style.cssText = `
            animation: ${swayAnim} ${wobbleDur}s ease-in-out infinite;
        `;
        inner.innerHTML = getPetalSVG(theme);
        el.appendChild(inner);

        canvas.appendChild(el);

        // Remove from DOM when animation ends
        setTimeout(() => {
            el.remove();
        }, (duration + delay) * 1000 + 100);
    }

    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Calculate Y scroll delta
        if (scrollTop > lastScrollTop) {
            const delta = scrollTop - lastScrollTop;
            
            // Only spawn past the hero section
            const heroSection = document.querySelector('section');
            const heroHeight = heroSection ? heroSection.offsetHeight : 600;

            if (scrollTop > heroHeight - 120) {
                scrollAccumulator += delta;
                
                // Spawn one leaf for every 80px scrolled down
                const scrollStep = 80;
                if (scrollAccumulator >= scrollStep) {
                    const count = Math.min(3, Math.floor(scrollAccumulator / scrollStep)); // Cap at 3 per scroll tick for performance
                    scrollAccumulator %= scrollStep;
                    
                    for (let i = 0; i < count; i++) {
                        setTimeout(() => spawnLeaf(), i * 150);
                    }
                }
            }
        } else {
            // Decay accumulator when scrolling up
            scrollAccumulator = Math.max(0, scrollAccumulator + (scrollTop - lastScrollTop));
        }
        
        lastScrollTop = scrollTop;
    }, { passive: true });
})();
</script>
@endsection
