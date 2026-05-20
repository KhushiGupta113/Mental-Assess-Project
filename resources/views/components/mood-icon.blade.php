@props(['score' => 3, 'class' => 'w-8 h-8'])

@php
    // Score 1-5 mapping
    // 1: Angry (Red)
    // 2: Sad (Blue)
    // 3: Neutral (Gray)
    // 4: Happy (Teal)
    // 5: Very Happy (Amber)
@endphp

<svg class="{{ $class }}" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    @if($score == 1)
        <!-- Angry -->
        <circle cx="50" cy="50" r="50" fill="#ef4444" />
        <path d="M 28 35 L 42 43 M 72 35 L 58 43" stroke="white" stroke-width="6" stroke-linecap="round" />
        <circle cx="35" cy="48" r="5" fill="white" />
        <circle cx="65" cy="48" r="5" fill="white" />
        <path d="M 35 70 Q 50 55 65 70" stroke="white" stroke-width="6" stroke-linecap="round" fill="none" />
    @elseif($score == 2)
        <!-- Sad -->
        <circle cx="50" cy="50" r="50" fill="#3b82f6" />
        <circle cx="35" cy="42" r="6" fill="white" />
        <circle cx="65" cy="42" r="6" fill="white" />
        <path d="M 35 70 Q 50 55 65 70" stroke="white" stroke-width="6" stroke-linecap="round" fill="none" />
    @elseif($score == 3)
        <!-- Neutral -->
        <circle cx="50" cy="50" r="50" fill="#9ca3af" />
        <circle cx="35" cy="42" r="6" fill="white" />
        <circle cx="65" cy="42" r="6" fill="white" />
        <path d="M 35 65 L 65 65" stroke="white" stroke-width="6" stroke-linecap="round" fill="none" />
    @elseif($score == 4)
        <!-- Happy -->
        <circle cx="50" cy="50" r="50" fill="#10b981" />
        <circle cx="35" cy="42" r="6" fill="white" />
        <circle cx="65" cy="42" r="6" fill="white" />
        <path d="M 30 60 Q 50 80 70 60" stroke="white" stroke-width="6" stroke-linecap="round" fill="none" />
    @else
        <!-- Very Happy -->
        <circle cx="50" cy="50" r="50" fill="#f59e0b" />
        <path d="M 28 42 Q 35 32 42 42 M 72 42 Q 65 32 58 42" stroke="white" stroke-width="6" stroke-linecap="round" fill="none" />
        <path d="M 28 55 Q 50 85 72 55 Z" fill="white" />
    @endif
</svg>
