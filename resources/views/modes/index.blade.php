@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12" data-aos="fade-down">
        <span class="badge-nature mb-3 inline-flex items-center">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg> 
            Wellness Toolkit
        </span>
        <h1 class="section-heading mb-4">Mindfulness & Focus Modes</h1>
        <p class="section-subheading mx-auto">Interactive tools designed to calm your mind, sharpen your focus, and cultivate inner peace — anytime, anywhere.</p>
    </div>

    {{-- Mode Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
        
        {{-- Breathe Mode --}}
        <a href="{{ route('modes.breathe') }}" class="assessment-card" data-aos="fade-up" data-aos-delay="0">
            <div class="p-6 h-full flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="card-icon bg-sage-100 text-sage-600 dark:bg-sage-900/40 dark:text-sage-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2C12 2 8 6 8 10c0 2 1 4 4 4s4-2 4-4c0-4-4-8-4-8z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14v4m-3 2c0-1.5 1.5-2 3-2s3 .5 3 2"/>
                            <circle cx="12" cy="10" r="1" fill="currentColor"/>
                        </svg>
                    </div>
                    <span class="text-xs t-muted font-medium">~2-10 min</span>
                </div>
                <span class="badge-nature mb-3">Relaxation</span>
                <h3 class="font-serif font-bold t-text text-lg mb-2">Breathe</h3>
                <p class="t-muted text-sm line-clamp-3 mb-4 flex-grow">Guided breathing exercises with visual rhythms. Choose from 4-7-8, Box Breathing, and more.</p>
                <div class="flex items-center t-light text-sm font-medium group-hover:t-muted transition-colors mt-auto">
                    Open Mode
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>

        {{-- Meditate Mode --}}
        <a href="{{ route('modes.meditate') }}" class="assessment-card" data-aos="fade-up" data-aos-delay="60">
            <div class="p-6 h-full flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="card-icon bg-teal-100 text-teal-600 dark:bg-teal-900/40 dark:text-teal-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2a3 3 0 100 6 3 3 0 000-6zM12 8c-4 0-6 3-6 6 0 1 .5 2 1.5 2.5M12 8c4 0 6 3 6 6 0 1-.5 2-1.5 2.5M9 22c0-2 1.5-3 3-3s3 1 3 3"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 14l-3 2m13-2l3 2"/>
                        </svg>
                    </div>
                    <span class="text-xs t-muted font-medium">~5-20 min</span>
                </div>
                <span class="badge-teal mb-3">Mindfulness</span>
                <h3 class="font-serif font-bold t-text text-lg mb-2">Meditate</h3>
                <p class="t-muted text-sm line-clamp-3 mb-4 flex-grow">Guided meditation sessions with timed instructions. Mindfulness, body scan, and loving kindness.</p>
                <div class="flex items-center t-light text-sm font-medium group-hover:t-muted transition-colors mt-auto">
                    Open Mode
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>

        {{-- Focus Mode --}}
        <a href="{{ route('modes.focus') }}" class="assessment-card" data-aos="fade-up" data-aos-delay="120">
            <div class="p-6 h-full flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="card-icon bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="1.5"/>
                            <circle cx="12" cy="12" r="6" stroke-width="1.5"/>
                            <circle cx="12" cy="12" r="2" fill="currentColor"/>
                        </svg>
                    </div>
                    <span class="text-xs t-muted font-medium">Custom</span>
                </div>
                <span class="badge-indigo mb-3">Productivity</span>
                <h3 class="font-serif font-bold t-text text-lg mb-2">Focus</h3>
                <p class="t-muted text-sm line-clamp-3 mb-4 flex-grow">Pomodoro timer with task tracking and break alerts. Stay productive with structured work sessions.</p>
                <div class="flex items-center t-light text-sm font-medium group-hover:t-muted transition-colors mt-auto">
                    Open Mode
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>

        {{-- Music Mode --}}
        <a href="{{ route('modes.music') }}" class="assessment-card" data-aos="fade-up" data-aos-delay="180">
            <div class="p-6 h-full flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="card-icon bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs t-muted font-medium">Continuous</span>
                </div>
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-800 mb-3 w-max">Audio</span>
                <h3 class="font-serif font-bold t-text text-lg mb-2">Music</h3>
                <p class="t-muted text-sm line-clamp-3 mb-4 flex-grow">Ambient soundscapes to soothe or focus. Mix rain, nature, piano, and more — plays globally.</p>
                <div class="flex items-center t-light text-sm font-medium group-hover:t-muted transition-colors mt-auto">
                    Open Mode
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>

        {{-- Sleep Mode --}}
        <a href="{{ route('modes.sleep') }}" class="assessment-card" data-aos="fade-up" data-aos-delay="240">
            <div class="p-6 h-full flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="card-icon bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11l1 1 3-3"/>
                        </svg>
                    </div>
                    <span class="text-xs t-muted font-medium">Wind Down</span>
                </div>
                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded-full dark:bg-purple-900 dark:text-purple-300 border border-purple-200 dark:border-purple-800 mb-3 w-max">Rest</span>
                <h3 class="font-serif font-bold t-text text-lg mb-2">Sleep</h3>
                <p class="t-muted text-sm line-clamp-3 mb-4 flex-grow">Wind down with proven sleep techniques — muscle relaxation, cognitive shuffle, and calming sounds.</p>
                <div class="flex items-center t-light text-sm font-medium group-hover:t-muted transition-colors mt-auto">
                    Open Mode
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
