@extends('layouts.main')

@section('content')

{{-- ═══ Floating Particles Background (CSS-only) ═══ --}}
<style>
    @keyframes float-particle {
        0%, 100% { transform: translateY(0) translateX(0) scale(1); opacity: 0; }
        10% { opacity: 0.6; }
        50% { transform: translateY(-40vh) translateX(20px) scale(1.2); opacity: 0.4; }
        90% { opacity: 0.1; }
        100% { transform: translateY(-80vh) translateX(-15px) scale(0.8); opacity: 0; }
    }
    @keyframes float-particle-alt {
        0%, 100% { transform: translateY(0) translateX(0) rotate(0deg); opacity: 0; }
        15% { opacity: 0.5; }
        50% { transform: translateY(-50vh) translateX(-30px) rotate(180deg); opacity: 0.3; }
        85% { opacity: 0.1; }
        100% { transform: translateY(-90vh) translateX(10px) rotate(360deg); opacity: 0; }
    }
    @keyframes pulse-glow {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.05); }
    }
    @keyframes fade-instruction {
        0% { opacity: 0; transform: translateY(8px); }
        15% { opacity: 1; transform: translateY(0); }
        85% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(-8px); }
    }
    .particle {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        will-change: transform, opacity;
    }
    .particle-1 { width: 6px; height: 6px; bottom: 5%; left: 10%; animation: float-particle 12s ease-in-out infinite; animation-delay: 0s; background: var(--th-primary); opacity: 0.3; }
    .particle-2 { width: 4px; height: 4px; bottom: 10%; left: 25%; animation: float-particle-alt 15s ease-in-out infinite; animation-delay: 2s; background: var(--th-accent); opacity: 0.25; }
    .particle-3 { width: 8px; height: 8px; bottom: 3%; left: 45%; animation: float-particle 18s ease-in-out infinite; animation-delay: 4s; background: var(--th-primary); opacity: 0.2; }
    .particle-4 { width: 5px; height: 5px; bottom: 8%; left: 60%; animation: float-particle-alt 14s ease-in-out infinite; animation-delay: 1s; background: var(--th-accent); opacity: 0.3; }
    .particle-5 { width: 7px; height: 7px; bottom: 2%; left: 75%; animation: float-particle 16s ease-in-out infinite; animation-delay: 3s; background: var(--th-primary); opacity: 0.2; }
    .particle-6 { width: 3px; height: 3px; bottom: 12%; left: 88%; animation: float-particle-alt 20s ease-in-out infinite; animation-delay: 5s; background: var(--th-accent); opacity: 0.35; }
    .particle-7 { width: 5px; height: 5px; bottom: 6%; left: 35%; animation: float-particle 13s ease-in-out infinite; animation-delay: 7s; background: var(--th-primary); opacity: 0.15; }
    .particle-8 { width: 4px; height: 4px; bottom: 15%; left: 55%; animation: float-particle-alt 17s ease-in-out infinite; animation-delay: 6s; background: var(--th-accent); opacity: 0.25; }
    .particle-9 { width: 6px; height: 6px; bottom: 4%; left: 18%; animation: float-particle 19s ease-in-out infinite; animation-delay: 8s; background: var(--th-primary); opacity: 0.2; }
    .particle-10 { width: 3px; height: 3px; bottom: 9%; left: 92%; animation: float-particle-alt 11s ease-in-out infinite; animation-delay: 9s; background: var(--th-accent); opacity: 0.3; }

    .timer-ring-bg { transition: stroke 0.4s ease; }
    .timer-ring-progress {
        transition: stroke-dashoffset 0.3s linear;
        filter: drop-shadow(0 0 6px var(--th-primary));
    }
    .instruction-text {
        animation: fade-instruction 1s ease-in-out;
    }
    .meditation-pill {
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .meditation-pill:hover {
        transform: translateY(-1px);
    }
    .completion-overlay {
        animation: fade-instruction 0.8s ease-out forwards;
    }
</style>

<section class="relative min-h-[calc(100vh-4rem)] flex items-center justify-center overflow-hidden py-8 px-4"
    style="background: linear-gradient(135deg, var(--th-gradient-from) 0%, var(--th-gradient-via) 50%, var(--th-gradient-to) 100%);">

    {{-- Floating Particles --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="particle particle-1"></div>
        <div class="particle particle-2"></div>
        <div class="particle particle-3"></div>
        <div class="particle particle-4"></div>
        <div class="particle particle-5"></div>
        <div class="particle particle-6"></div>
        <div class="particle particle-7"></div>
        <div class="particle particle-8"></div>
        <div class="particle particle-9"></div>
        <div class="particle particle-10"></div>
    </div>

    {{-- Soft radial glow behind the card --}}
    <div class="absolute inset-0 pointer-events-none flex items-center justify-center" aria-hidden="true">
        <div class="w-[600px] h-[600px] rounded-full" style="background: radial-gradient(circle, var(--th-glow) 0%, transparent 70%); animation: pulse-glow 6s ease-in-out infinite;"></div>
    </div>

    {{-- ═══ Main Meditation Card ═══ --}}
    <div class="relative z-10 w-full max-w-xl flex flex-col" data-aos="fade-up"
         x-data="meditationApp()" x-cloak>

        {{-- Navigation --}}
        <div class="self-start mb-4">
            <button @click="(isRunning || isPaused || isComplete) ? resetMeditation() : window.location.href='{{ route('modes.index') }}'"
                    class="flex items-center gap-2 t-text opacity-80 hover:opacity-100 transition-opacity">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="font-medium text-sm" x-text="(isRunning || isPaused || isComplete) ? 'Back to Setup' : 'Back to Modes'"></span>
            </button>
        </div>

        <div class="glass-card-premium p-6 sm:p-10">

            {{-- Header --}}
            <div class="text-center mb-6" data-aos="fade-up" data-aos-delay="100">
                <h1 class="section-heading text-2xl sm:text-3xl mb-2">
                    <span class="text-gradient-nature">Meditate</span>
                </h1>
                <p class="section-subheading text-sm sm:text-base">Find stillness, clarity, and inner peace</p>
            </div>

            {{-- ═══ Setup Panel (shown when not running and not complete) ═══ --}}
            <div x-show="!isRunning && !isPaused && !isComplete" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                {{-- Meditation Type Selector --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold t-muted uppercase tracking-wider mb-2">Meditation Type</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(type, key) in meditationTypes" :key="key">
                            <button
                                :id="'meditate-type-' + key"
                                @click="selectType(key)"
                                class="meditation-pill px-4 py-2 rounded-full text-sm font-medium border"
                                :class="selectedType === key
                                    ? 'text-white shadow-md'
                                    : 'hover:shadow-sm'"
                                :style="selectedType === key
                                    ? 'background: linear-gradient(135deg, var(--th-primary), var(--th-primary-dark)); border-color: var(--th-primary);'
                                    : 'background: var(--th-surface); border-color: var(--th-border-strong); color: var(--th-text-muted);'">
                                <span x-text="type.label"></span>
                            </button>
                        </template>
                    </div>
                    <p class="mt-2 text-xs t-light" x-text="meditationTypes[selectedType].description"></p>
                </div>

                {{-- Duration Selector --}}
                <div class="mb-6">
                    <label class="block text-xs font-semibold t-muted uppercase tracking-wider mb-2">Duration</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="dur in meditationTypes[selectedType].durations" :key="dur">
                            <button
                                :id="'meditate-dur-' + dur"
                                @click="selectedDuration = dur"
                                class="meditation-pill px-4 py-2 rounded-full text-sm font-medium border"
                                :class="selectedDuration === dur
                                    ? 'text-white shadow-md'
                                    : 'hover:shadow-sm'"
                                :style="selectedDuration === dur
                                    ? 'background: linear-gradient(135deg, var(--th-accent), var(--th-primary)); border-color: var(--th-accent);'
                                    : 'background: var(--th-surface); border-color: var(--th-border-strong); color: var(--th-text-muted);'">
                                <span x-text="dur + ' min'"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Ambient Sound Selector --}}
                <div class="mb-6">
                    <label class="block text-xs font-semibold t-muted uppercase tracking-wider mb-2">Ambient Sound</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(sound, key) in ambientSounds" :key="key">
                            <button
                                @click="ambientSound = key"
                                class="meditation-pill px-4 py-2 rounded-full text-sm font-medium border"
                                :class="ambientSound === key ? 'text-white shadow-md' : 'hover:shadow-sm'"
                                :style="ambientSound === key
                                    ? 'background: linear-gradient(135deg, var(--th-primary), var(--th-accent)); border-color: var(--th-primary);'
                                    : 'background: var(--th-surface); border-color: var(--th-border-strong); color: var(--th-text-muted);'">
                                <span x-text="sound.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Start Button --}}
                <button id="meditate-start-btn"
                    @click="startMeditation()"
                    class="btn-nature w-full text-center text-base py-3.5 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Begin Meditation
                </button>
            </div>

            {{-- ═══ Active Meditation Panel ═══ --}}
            <div x-show="isRunning || isPaused" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                {{-- Current type badge --}}
                <div class="text-center mb-4">
                    <span class="badge-nature text-xs" x-text="meditationTypes[selectedType].label"></span>
                </div>

                {{-- SVG Circular Timer Ring --}}
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <svg width="250" height="250" viewBox="0 0 250 250" class="transform -rotate-90" id="meditate-timer-svg">
                            {{-- Background ring --}}
                            <circle
                                cx="125" cy="125" r="110"
                                fill="none"
                                stroke-width="8"
                                class="timer-ring-bg"
                                style="stroke: var(--th-border-strong); opacity: 0.3;"
                            />
                            {{-- Progress ring --}}
                            <circle
                                cx="125" cy="125" r="110"
                                fill="none"
                                stroke-width="8"
                                stroke-linecap="round"
                                class="timer-ring-progress"
                                style="stroke: var(--th-primary);"
                                :stroke-dasharray="circumference"
                                :stroke-dashoffset="dashOffset"
                            />
                            {{-- Accent glow ring --}}
                            <circle
                                cx="125" cy="125" r="110"
                                fill="none"
                                stroke-width="2"
                                stroke-linecap="round"
                                style="stroke: var(--th-accent); opacity: 0.3;"
                                :stroke-dasharray="circumference"
                                :stroke-dashoffset="dashOffset"
                            />
                        </svg>
                        {{-- Time Display (centered over SVG) --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center transform rotate-0">
                            <span class="text-5xl font-serif font-bold t-text tracking-tight" x-text="formatTime(remainingSeconds)" id="meditate-timer-display"></span>
                            <span class="text-xs t-muted mt-1 uppercase tracking-widest" x-text="isPaused ? 'Paused' : 'Remaining'"></span>
                        </div>
                    </div>
                </div>

                {{-- Guided Instruction Text --}}
                <div class="text-center mb-6 min-h-[3.5rem] flex items-center justify-center px-4">
                    <p class="text-base sm:text-lg font-serif t-text leading-relaxed instruction-text"
                       :key="currentInstruction"
                       x-text="currentInstruction"
                       id="meditate-instruction-text">
                    </p>
                </div>

                {{-- Controls --}}
                <div class="flex items-center justify-center gap-3 flex-wrap">
                    {{-- Pause / Resume --}}
                    <button id="meditate-pause-btn"
                        @click="togglePause()"
                        class="btn-nature px-6 py-2.5 text-sm flex items-center gap-2">
                        <template x-if="isPaused">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            </svg>
                        </template>
                        <template x-if="!isPaused">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </template>
                        <span x-text="isPaused ? 'Resume' : 'Pause'"></span>
                    </button>

                    {{-- Reset --}}
                    <button id="meditate-reset-btn"
                        @click="resetMeditation()"
                        class="btn-nature-outline px-6 py-2.5 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>

                    {{-- Ambient Sound Toggle (mini) --}}
                    <button id="meditate-sound-btn"
                        x-show="ambientSound !== 'none'"
                        @click="toggleAmbientDuring()"
                        class="p-2.5 rounded-xl border transition-all duration-300"
                        :style="ambientPlaying
                            ? 'background: var(--th-primary-light); border-color: var(--th-primary); color: var(--th-primary);'
                            : 'background: var(--th-surface); border-color: var(--th-border-strong); color: var(--th-text-light);'"
                        :title="ambientPlaying ? 'Mute ambient sound' : 'Enable ambient sound'">
                        <svg x-show="ambientPlaying" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                        </svg>
                        <svg x-show="!ambientPlaying" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ═══ Completion Screen ═══ --}}
            <div x-show="isComplete"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="text-center py-4">

                {{-- Completion glow --}}
                <div class="flex justify-center mb-5">
                    <div class="w-24 h-24 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, var(--th-primary-light), var(--th-accent-light)); animation: pulse-glow 3s ease-in-out infinite;">
                        <svg class="w-12 h-12" style="color: var(--th-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <h2 class="section-heading text-2xl mb-2">
                    <span class="text-gradient-nature">Session Complete</span>
                </h2>
                <p class="t-muted text-sm mb-2">Beautiful. You took time for yourself today.</p>
                <p class="text-xs t-light mb-6">
                    <span x-text="meditationTypes[selectedType].label"></span> •
                    <span x-text="selectedDuration + ' minutes'"></span>
                </p>

                {{-- Closing wisdom --}}
                <div class="rounded-xl p-4 mb-6" style="background: var(--th-primary-light); border: 1px solid var(--th-border);">
                    <p class="text-sm font-serif t-text italic leading-relaxed" x-text="completionQuote"></p>
                </div>

                <button id="meditate-new-session-btn"
                    @click="resetMeditation()"
                    class="btn-nature-outline px-8 py-2.5 text-sm">
                    Start New Session
                </button>
            </div>

        </div>{{-- /glass-card-premium --}}
    </div>
</section>

{{-- ═══ Meditation Alpine.js Component ═══ --}}
<script>
function meditationApp() {
    return {
        // ─── State ───
        selectedType: 'mindfulness',
        selectedDuration: 10,
        ambientEnabled: true,
        isRunning: false,
        isPaused: false,
        isComplete: false,

        // ─── Timer internals ───
        totalSeconds: 0,
        remainingSeconds: 0,
        intervalId: null,
        startTime: null,
        pausedElapsed: 0,

        // ─── SVG ring ───
        radius: 110,
        get circumference() { return 2 * Math.PI * this.radius; },
        get dashOffset() {
            if (this.totalSeconds === 0) return 0;
            const progress = this.remainingSeconds / this.totalSeconds;
            return this.circumference * (1 - progress);
        },

        // ─── Options ───
        selectedType: 'bodyscan',
        selectedDuration: 10,
        
        ambientSounds: {
            none: { label: 'None', generator: null },
            rain: { label: 'Rain', generator: 'lightRain' },
            forest: { label: 'Nature', generator: 'forest' },
            river: { label: 'Water', generator: 'riverStream' },
            pad: { label: 'Ethereal Pad', generator: 'binauralBeats' },
            bowls: { label: 'Tibetan Bowls', generator: 'singingBowls' },
            piano: { label: 'Pixabay Piano', generator: 'externalAudio', url: '/audio/relaxing_piano.webm' },
            ambient_sleep: { label: 'Pixabay Sleep', generator: 'externalAudio', url: '/audio/ambient_sleep.webm' },
            meditation: { label: 'Pixabay Meditate', generator: 'externalAudio', url: '/audio/relaxing_meditation.webm' },
        },
        ambientSound: 'none',
        ambientPlaying: false,
        ambientNode: null,
        audioCtx: null,

        // ─── Completion quotes ───
        completionQuotes: [
            '"The mind is like water. When it\'s turbulent, it\'s difficult to see. When it\'s calm, everything becomes clear." — Prasad Mahes',
            '"Feelings come and go like clouds in a windy sky. Conscious breathing is my anchor." — Thích Nhất Hạnh',
            '"In today\'s rush, we all think too much, seek too much, want too much. How about simply pausing and just breathing?" — Rumi',
            '"Almost everything will work again if you unplug it for a few minutes, including you." — Anne Lamott',
            '"Within you, there is a stillness and a sanctuary to which you can retreat at any time." — Hermann Hesse',
            '"The present moment is the only moment available to us, and it is the door to all moments." — Thích Nhất Hạnh',
        ],
        completionQuote: '',

        // ─── Meditation Types ───
        meditationTypes: {
            mindfulness: {
                label: 'Mindfulness',
                description: 'Body awareness & present-moment focus',
                durations: [5, 10, 15, 20],
                instructions: [
                    'Close your eyes gently and settle into a comfortable position…',
                    'Take a deep breath in through your nose… and slowly exhale through your mouth…',
                    'Notice the weight of your body against the surface beneath you…',
                    'Bring your attention to the sensations in your feet… feel them grounded and stable…',
                    'Let your awareness drift upward through your legs… notice any warmth or tension…',
                    'Observe the rhythm of your breathing… no need to change it, simply notice…',
                    'If your mind wanders, gently acknowledge the thought and return to this moment…',
                    'Feel the air touching your skin… the subtle sounds around you…',
                    'Notice how your hands feel right now… the gentle pulse of life within them…',
                    'You are fully here, fully present. This moment is enough…',
                    'With each exhale, release any tightness you are holding…',
                    'Embrace the stillness within you… let it expand and fill your entire being…',
                ]
            },
            bodyscan: {
                label: 'Body Scan',
                description: 'Progressive relaxation from head to toe',
                durations: [10, 15, 20],
                instructions: [
                    'Settle in and close your eyes… take three slow, deep breaths to arrive…',
                    'Bring your attention to the top of your head… feel a gentle warmth spreading…',
                    'Let the warmth flow down to your forehead… feel the muscles around your eyes soften…',
                    'Relax your jaw… unclench your teeth… let your tongue rest gently…',
                    'Notice your neck and shoulders… breathe into any areas of tightness…',
                    'Feel the tension melting from your shoulders, flowing down your arms…',
                    'Bring awareness to your chest… notice your heartbeat, steady and calm…',
                    'Let your belly soften completely… release any holding there…',
                    'Feel warmth spreading through your lower back… each breath easing tension…',
                    'Bring your attention to your hips and thighs… let them feel heavy and relaxed…',
                    'Notice your knees, your calves… let gravity hold them completely…',
                    'Feel your feet — every toe, the arches, the heels — grounded and at peace…',
                ]
            },
            lovingkindness: {
                label: 'Loving Kindness',
                description: 'Compassion-focused phrases for self and others',
                durations: [5, 10, 15],
                instructions: [
                    'Close your eyes and place a hand over your heart… feel its gentle rhythm…',
                    'Picture yourself surrounded by a warm, golden light of compassion…',
                    'Silently repeat: "May I be happy. May I be healthy. May I be at peace."',
                    'Feel the warmth of these words settle deep within you…',
                    'Now bring to mind someone you love… see their smile…',
                    'Send them kindness: "May you be happy. May you be healthy. May you be at peace."',
                    'Think of someone neutral — a stranger, perhaps… extend compassion to them too…',
                    '"May you be happy. May you be free from suffering. May you find joy."',
                    'Now widen your circle to include all beings everywhere…',
                    '"May all beings be happy. May all beings be safe. May all beings live with ease."',
                    'Feel this boundless compassion radiating outward from your heart…',
                    'You are a source of kindness in this world. Rest in that knowing…',
                ]
            },
            breathfocus: {
                label: 'Breath Focus',
                description: 'Simple breath counting for calm & clarity',
                durations: [5, 10],
                instructions: [
                    'Close your eyes and find a natural breathing rhythm… no effort needed…',
                    'Breathe in slowly… 1… 2… 3… 4… hold gently…',
                    'Now exhale slowly… 1… 2… 3… 4… 5… 6… let it all go…',
                    'Count each exhale silently: one… breathe in… two… breathe in…',
                    'If you lose count, simply start again from one… no judgment…',
                    'Notice the cool air entering your nostrils… the warm air leaving…',
                    'Feel your lungs expand like gentle waves… and contract softly…',
                    'Your breath is your anchor — always here, always steady…',
                    'With each breath, imagine drawing in calm… and releasing tension…',
                    'You are breathing. You are alive. That is something beautiful…',
                ]
            }
        },

        // ─── Methods ───
        selectType(key) {
            this.selectedType = key;
            const durations = this.meditationTypes[key].durations;
            if (!durations.includes(this.selectedDuration)) {
                this.selectedDuration = durations[0];
            }
        },

        startMeditation() {
            this.totalSeconds = this.selectedDuration * 60;
            this.remainingSeconds = this.totalSeconds;
            this.isRunning = true;
            this.isPaused = false;
            this.isComplete = false;
            this.instructionIndex = 0;
            this.pausedElapsed = 0;

            // Play start bell
            this.playBell();

            // Start ambient sound
            this.ambientPlaying = (this.ambientSound !== 'none');
            if (this.ambientPlaying) {
                this.startAmbientSound();
            }

            // Begin the timer
            this.startTime = Date.now();
            this.intervalId = setInterval(() => {
                const elapsed = Math.floor((Date.now() - this.startTime) / 1000) + this.pausedElapsed;
                this.remainingSeconds = Math.max(0, this.totalSeconds - elapsed);

                if (this.remainingSeconds <= 0) {
                    this.completeMeditation();
                }
            }, 250);

            // Start cycling instructions
            this.showInstruction();
            const instructionInterval = this.getInstructionInterval();
            this.instructionIntervalId = setInterval(() => {
                this.nextInstruction();
            }, instructionInterval);
        },

        getInstructionInterval() {
            const instructions = this.meditationTypes[this.selectedType].instructions;
            const totalMs = this.totalSeconds * 1000;
            // Spread instructions evenly, clamped between 15s and 60s
            const interval = Math.floor(totalMs / instructions.length);
            return Math.max(15000, Math.min(60000, interval));
        },

        showInstruction() {
            const instructions = this.meditationTypes[this.selectedType].instructions;
            this.currentInstruction = instructions[this.instructionIndex % instructions.length];
        },

        nextInstruction() {
            this.instructionIndex++;
            this.showInstruction();
        },

        togglePause() {
            if (this.isPaused) {
                // Resume
                this.isPaused = false;
                this.startTime = Date.now();
                this.intervalId = setInterval(() => {
                    const elapsed = Math.floor((Date.now() - this.startTime) / 1000) + this.pausedElapsed;
                    this.remainingSeconds = Math.max(0, this.totalSeconds - elapsed);
                    if (this.remainingSeconds <= 0) {
                        this.completeMeditation();
                    }
                }, 250);

                const instructionInterval = this.getInstructionInterval();
                this.instructionIntervalId = setInterval(() => {
                    this.nextInstruction();
                }, instructionInterval);

                if (this.ambientPlaying) {
                    this.startAmbientSound();
                }
            } else {
                // Pause
                this.isPaused = true;
                this.pausedElapsed = this.totalSeconds - this.remainingSeconds;
                clearInterval(this.intervalId);
                clearInterval(this.instructionIntervalId);
                this.stopAmbientSound();
            }
        },

        completeMeditation() {
            clearInterval(this.intervalId);
            clearInterval(this.instructionIntervalId);
            this.isRunning = false;
            this.isPaused = false;
            this.isComplete = true;
            this.remainingSeconds = 0;
            this.stopAmbientSound();

            // Play end bell
            this.playBell();

            // Pick random completion quote
            this.completionQuote = this.completionQuotes[Math.floor(Math.random() * this.completionQuotes.length)];
        },

        resetMeditation() {
            clearInterval(this.intervalId);
            clearInterval(this.instructionIntervalId);
            this.isRunning = false;
            this.isPaused = false;
            this.isComplete = false;
            this.remainingSeconds = 0;
            this.totalSeconds = 0;
            this.pausedElapsed = 0;
            this.instructionIndex = 0;
            this.currentInstruction = '';
            this.stopAmbientSound();
        },

        formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        },

        // ─── Web Audio API: Bell ───
        playBell() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.type = 'sine';
                osc.frequency.setValueAtTime(400, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(280, ctx.currentTime + 0.5);

                gain.gain.setValueAtTime(0.35, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 1.2);

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 1.2);

                // Add a second harmonic for richness
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(800, ctx.currentTime);
                osc2.frequency.exponentialRampToValueAtTime(560, ctx.currentTime + 0.5);
                gain2.gain.setValueAtTime(0.12, ctx.currentTime);
                gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 1.0);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(ctx.currentTime);
                osc2.stop(ctx.currentTime + 1.0);
            } catch (e) {
                // Web Audio not available — silently fail
            }
        },

        // ─── Web Audio API: Ambient Soundscape ───
        startAmbientSound() {
            if (this.ambientSound === 'none') return;
            try {
                if (this.ambientNode) return; // Already playing

                if (!this.audioCtx) {
                    this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (this.audioCtx.state === 'suspended') {
                    this.audioCtx.resume();
                }

                const generatorName = this.ambientSounds[this.ambientSound].generator;
                const url = this.ambientSounds[this.ambientSound].url;
                if (!generatorName || !window.SoundscapeGenerators) return;

                const gainNode = this.audioCtx.createGain();
                gainNode.gain.setValueAtTime(0, this.audioCtx.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.4, this.audioCtx.currentTime + 2); // Soft volume
                gainNode.connect(this.audioCtx.destination);

                this.ambientNode = window.SoundscapeGenerators[generatorName](this.audioCtx, gainNode, url);
                this.ambientGain = gainNode;
            } catch (e) {
                // Web Audio not available
            }
        },

        stopAmbientSound() {
            try {
                if (this.ambientGain) {
                    this.ambientGain.gain.linearRampToValueAtTime(0.001, this.audioCtx.currentTime + 1);
                }
                setTimeout(() => {
                    try {
                        if (this.ambientNode && this.ambientNode.stop) { 
                            this.ambientNode.stop(); 
                        }
                        this.ambientNode = null;
                        this.ambientGain = null;
                    } catch (e) {}
                }, 1200);
            } catch (e) {
                this.ambientNode = null;
                this.ambientGain = null;
            }
        },

        toggleAmbientDuring() {
            this.ambientPlaying = !this.ambientPlaying;
            if (this.ambientPlaying && (this.isRunning && !this.isPaused)) {
                this.startAmbientSound();
            } else {
                this.stopAmbientSound();
            }
        },

        // Cleanup on leave
        destroy() {
            clearInterval(this.intervalId);
            clearInterval(this.instructionIntervalId);
            this.stopAmbientSound();
        }
    };
}
</script>

@endsection
