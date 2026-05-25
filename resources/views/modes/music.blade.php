@extends('layouts.main')

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     AMBIENT SOUNDSCAPES — Immersive Audio Mixer
     ═══════════════════════════════════════════════════════════ --}}

<div class="min-h-screen py-8 md:py-12 pb-32" x-data="soundscapeMixer()" x-init="init()" @keydown.space.prevent="toggleAll()">

{{-- ═══ Hero Section ═══ --}}
<section class="relative overflow-hidden pt-20 pb-16 lg:pt-28 lg:pb-20" style="z-index:1;">
    {{-- Background Blur Blobs --}}
    <div class="absolute inset-0 opacity-40 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full blur-[100px]" style="background:var(--th-primary);opacity:0.15;"></div>
        <div class="absolute top-1/2 -right-32 w-[30rem] h-[30rem] rounded-full blur-[120px]" style="background:var(--th-accent);opacity:0.12;"></div>
        <div class="absolute bottom-0 left-1/3 w-[25rem] h-[25rem] rounded-full blur-[100px]" style="background:var(--th-primary);opacity:0.1;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mt-8">

        {{-- Back Button --}}
        <div class="flex justify-start mb-6">
            <a href="{{ route('modes.index') }}"
                    class="flex items-center gap-2 t-text opacity-80 hover:opacity-100 transition-opacity"
                    style="color: var(--th-text-muted);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="text-sm font-semibold tracking-wide">Back to Modes</span>
            </a>
        </div>

        <div data-aos="fade-up" data-aos-delay="100">
            <h1 class="text-3xl md:text-4xl font-serif font-extrabold t-text leading-tight mb-4 tracking-tight">
                Ambient <span class="text-gradient-nature">Soundscapes</span>
            </h1>

            <p class="text-lg md:text-xl t-muted mb-8 leading-relaxed max-w-2xl mx-auto">
                Layer soothing sounds to create your perfect atmosphere. Every sound is generated in real-time — no downloads needed.
            </p>
        </div>

        {{-- Quick Presets --}}
        <div class="flex flex-wrap items-center justify-center gap-2 mb-4" data-aos="fade-up" data-aos-delay="200">
            <span class="text-xs font-semibold t-light uppercase tracking-wider mr-1">Presets:</span>
            <template x-for="preset in presets" :key="preset.name">
                <button @click="loadPreset(preset)"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:scale-105 active:scale-95"
                        :class="activePreset === preset.name
                            ? 'text-white shadow-md'
                            : 'hover:shadow-sm'"
                        :style="activePreset === preset.name
                            ? 'background:linear-gradient(135deg, var(--th-primary), var(--th-accent));'
                            : 'background:var(--th-surface);border:1px solid var(--th-border-strong);color:var(--th-text-muted);'"
                        x-text="preset.name">
                </button>
            </template>
        </div>

        {{-- Controls Panel --}}
        <div class="flex flex-wrap items-center justify-center gap-3 mt-6 p-4" data-aos="fade-up" data-aos-delay="300">
            {{-- Master Volume --}}
            <div class="glass-card-premium px-4 py-2.5 flex items-center gap-3">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--th-primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                </svg>
                <label class="text-xs font-semibold t-muted whitespace-nowrap hidden sm:block" for="master-volume-slider">Master</label>
                <input id="master-volume-slider" type="range" min="0" max="100" x-model="$store.globalAudio.masterVolume"
                       @input="updateMasterVolume()"
                       class="w-24 sm:w-32 h-1.5 rounded-full appearance-none cursor-pointer soundscape-slider"
                       style="accent-color:var(--th-primary);">
                <span class="text-xs font-bold t-text w-8 text-right" x-text="$store.globalAudio.masterVolume + '%'"></span>
            </div>

            {{-- Timer --}}
            <div class="glass-card-premium px-4 py-2.5 flex items-center gap-3">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--th-accent);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <label class="text-xs font-semibold t-muted whitespace-nowrap hidden sm:block" for="timer-select">Timer</label>
                <select id="timer-select" x-model="timerDuration" @change="setTimer()"
                        class="bg-transparent text-sm font-medium t-text border-0 focus:ring-0 focus:outline-none cursor-pointer pr-6 appearance-none py-0 outline-none border-none"
                        style="outline:none; box-shadow: none;">
                    <option value="0">Continuous</option>
                    <option value="5">5 Minutes</option>
                    <option value="15">15 Minutes</option>
                    <option value="30">30 Minutes</option>
                    <option value="60">60 Minutes</option>
                </select>
                <span x-show="timerRemaining > 0" class="text-xs font-bold" style="color:var(--th-accent);" x-text="formatTime(timerRemaining)"></span>
            </div>

            {{-- Playback Controls --}}
            <div class="glass-card-premium px-3 py-1.5 flex items-center gap-1.5" x-show="$store.globalAudio.activeCount > 0" x-transition>
                <button @click="$store.globalAudio.pauseAll()" id="soundscape-pause-all-btn"
                        class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 active:scale-90"
                        style="background:var(--th-primary-light);color:var(--th-primary);"
                        :title="$store.globalAudio.allPaused ? 'Resume All' : 'Pause All'">
                    <svg x-show="!$store.globalAudio.allPaused" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                    </svg>
                    <svg x-show="$store.globalAudio.allPaused" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </button>

                <button @click="$store.globalAudio.stopAll(); clearTimer();" id="soundscape-stop-all-btn"
                        class="w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 active:scale-90"
                        style="background:rgba(239,68,68,0.1);color:#ef4444;"
                        title="Stop All">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 6h12v12H6z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>

{{-- ═══ Sound Categories Grid ═══ --}}
<section class="py-12 relative" style="z-index:1;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <template x-for="category in categories" :key="category.name">
            <div class="mb-12" :data-aos="'fade-up'">
                {{-- Category Header --}}
                <div class="flex items-center gap-3 mb-6">
                    <div :class="'w-10 h-10 rounded-xl flex items-center justify-center bg-' + category.color + '-100 text-' + category.color + '-600 dark:bg-' + category.color + '-900/40 dark:text-' + category.color + '-300'">
                        <span x-html="category.icon"></span>
                    </div>
                    <h2 class="text-xl md:text-2xl font-serif font-bold t-text" x-text="category.name"></h2>
                    <div class="flex-grow h-px" style="background:var(--th-border);"></div>
                </div>

                {{-- Sound Cards Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <template x-for="sound in category.sounds" :key="sound.id">
                        <div class="glass-card-premium p-5 transition-all duration-400 relative group"
                             :class="isActive(sound.id) ? 'soundscape-card-active' : ''"
                             :style="isActive(sound.id) ? 'box-shadow: 0 0 20px var(--th-glow), 0 8px 32px rgba(0,0,0,0.06); border-color: var(--th-primary);' : ''">

                            {{-- Active glow overlay --}}
                            <div x-show="isActive(sound.id) && !allPaused" x-transition
                                 class="absolute inset-0 rounded-[2rem] pointer-events-none soundscape-glow-border"></div>

                            <div class="relative z-10">
                                {{-- Icon + Name Row --}}
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-all duration-300"
                                             :style="isActive(sound.id)
                                                 ? 'background: linear-gradient(135deg, var(--th-primary), var(--th-accent)); color: white;'
                                                 : 'background: var(--th-primary-light); color: var(--th-primary);'">
                                            <span x-html="sound.icon"></span>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold t-text text-sm" x-text="sound.name"></h3>
                                            <p class="text-xs t-light" x-text="sound.desc"></p>
                                        </div>
                                    </div>

                                    {{-- Play/Pause Button --}}
                                    <button @click="toggleSound(sound)"
                                            :id="'btn-' + sound.id"
                                            class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 active:scale-90 flex-shrink-0"
                                            :style="isActive(sound.id)
                                                ? 'background: linear-gradient(135deg, var(--th-primary), var(--th-primary-dark)); color: white; box-shadow: 0 4px 12px var(--th-glow);'
                                                : 'background: var(--th-surface-alt); color: var(--th-text-muted);'"
                                            :title="isActive(sound.id) ? 'Stop ' + sound.name : 'Play ' + sound.name">
                                        <svg x-show="!isActive(sound.id)" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                        <svg x-show="isActive(sound.id)" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Volume Slider (visible when active) --}}
                                <div x-show="isActive(sound.id)" x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                                     class="mt-3 pt-3" style="border-top: 1px solid var(--th-border);">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0 t-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                        </svg>
                                        <input type="range" min="0" max="100"
                                               :value="getSoundVolume(sound.id)"
                                               @input="setSoundVolume(sound.id, $event.target.value)"
                                               class="flex-1 h-1.5 rounded-full appearance-none cursor-pointer soundscape-slider"
                                               style="accent-color:var(--th-primary);">
                                        <span class="text-xs font-semibold t-muted w-8 text-right" x-text="getSoundVolume(sound.id) + '%'"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

    </div>
</section>

</div>{{-- End Alpine x-data --}}

{{-- ═══ Styles ═══ --}}
<style>
    /* Visualizer ring animations */
    @keyframes soundscape-pulse {
        0%, 100% { transform: scale(1); opacity: 0.6; }
        50% { transform: scale(1.06); opacity: 1; }
    }
    @keyframes soundscape-pulse-inner {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.08); opacity: 0.6; }
    }
    .soundscape-ring-active {
        animation: soundscape-pulse 2.5s ease-in-out infinite;
    }
    .soundscape-ring-active-inner {
        animation: soundscape-pulse-inner 3s ease-in-out infinite 0.5s;
    }
    .soundscape-ring-idle {
        opacity: 0.3;
    }

    /* Active card glow border */
    @keyframes soundscape-glow {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.6; }
    }
    .soundscape-glow-border {
        background: linear-gradient(135deg, var(--th-primary), var(--th-accent));
        opacity: 0.08;
        animation: soundscape-glow 3s ease-in-out infinite;
    }

    /* Active card styling */
    .soundscape-card-active {
        border-color: var(--th-primary) !important;
    }

    /* Range slider styling */
    .soundscape-slider {
        background: var(--th-border-strong);
    }
    .soundscape-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        margin-top: -5px;
        border-radius: 50%;
        background: var(--th-primary);
        cursor: pointer;
        box-shadow: 0 2px 6px var(--th-glow);
        transition: transform 0.15s;
    }
    .soundscape-slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
    }
    .soundscape-slider::-moz-range-thumb {
        width: 16px;
        height: 16px;
        margin-top: -5px;
        border-radius: 50%;
        background: var(--th-primary);
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 6px var(--th-glow);
    }
    .soundscape-slider::-webkit-slider-runnable-track {
        height: 6px;
        border-radius: 3px;
        background: var(--th-border-strong);
    }
    .soundscape-slider::-moz-range-track {
        height: 6px;
        border-radius: 3px;
        background: var(--th-border-strong);
    }

    /* Select arrow override */
    #timer-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23697a59' viewBox='0 0 24 24'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 4px center;
        padding-right: 20px;
    }
</style>

{{-- ═══ Sound Engine Script ═══ --}}
<script>
function soundscapeMixer() {
    return {
        // State
        activePreset: null,
        timerDuration: 0,
        timerRemaining: 0,
        timerInterval: null,
        timerTimeout: null,

        // ─── Presets ───
        presets: [
            { name: 'Rainy Day',    ids: ['light-rain', 'wind'],                          volumes: { 'light-rain': 70, 'wind': 40 } },
            { name: 'Deep Forest',  ids: ['forest', 'river-stream', 'wind'],              volumes: { 'forest': 65, 'river-stream': 50, 'wind': 35 } },
            { name: 'Ocean Calm',   ids: ['ocean-waves', 'wind'],                          volumes: { 'ocean-waves': 75, 'wind': 30 } },
            { name: 'Focus Zone',   ids: ['brown-noise', 'singing-bowls'],                 volumes: { 'brown-noise': 50, 'singing-bowls': 40 } },
            { name: 'Sleep',        ids: ['light-rain', 'crickets'],                       volumes: { 'light-rain': 55, 'crickets': 35 } },
        ],

        // ─── Sound Categories ───
        categories: [
            {
                name: 'Rain Sounds',
                color: 'blue',
                icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21v-2m-4 2v-2m8 2v-2"/></svg>',
                sounds: [
                    { id: 'light-rain', name: 'Light Rain', desc: 'Gentle patter', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21v-2m-4 2v-2m8 2v-2M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>', generator: 'lightRain' },
                    { id: 'heavy-rain', name: 'Heavy Rain', desc: 'Intense downpour', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 19v4m5-4v4m5-4v4"/></svg>', generator: 'heavyRain' },
                    { id: 'thunder', name: 'Thunder', desc: 'Distant rumble', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>', generator: 'thunder' },
                ]
            },
            {
                name: 'Nature Sounds',
                color: 'sage',
                icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
                sounds: [
                    { id: 'forest', name: 'Forest', desc: 'Wind through trees', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>', generator: 'forest' },
                    { id: 'crickets', name: 'Crickets', desc: 'Night chorus', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>', generator: 'crickets' },
                    { id: 'wind', name: 'Wind', desc: 'Soft breeze', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.59 4.59A2 2 0 1111 8H2m10.59 11.41A2 2 0 1014 16H2m15.73-8.27A2.5 2.5 0 1119.5 12H2"/></svg>', generator: 'wind' },
                    { id: 'campfire', name: 'Campfire', desc: 'Crackling warmth', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>', generator: 'campfire' },
                ]
            },
            {
                name: 'Water Sounds',
                color: 'teal',
                icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2a4 4 0 00-4 4c0 3.5-4 6-4 10a8 8 0 0016 0c0-4-4-6.5-4-10a4 4 0 00-4-4z"/></svg>',
                sounds: [
                    { id: 'ocean-waves', name: 'Ocean Waves', desc: 'Rolling surf', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2a4 4 0 00-4 4c0 3.5-4 6-4 10a8 8 0 0016 0c0-4-4-6.5-4-10a4 4 0 00-4-4z"/></svg>', generator: 'oceanWaves' },
                    { id: 'river-stream', name: 'River Stream', desc: 'Babbling brook', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 12c2-2 4-2 6 0s4 2 6 0 4-2 6 0M2 17c2-2 4-2 6 0s4 2 6 0 4-2 6 0M2 7c2-2 4-2 6 0s4 2 6 0 4-2 6 0"/></svg>', generator: 'riverStream' },
                    { id: 'waterfall', name: 'Waterfall', desc: 'Rushing cascade', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>', generator: 'waterfall' },
                ]
            },
            {
                name: 'Musical',
                color: 'indigo',
                icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>',
                sounds: [
                    { id: 'singing-bowls', name: 'Tibetan Bowls', desc: 'Deep resonance', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>', generator: 'singingBowls' },
                    { id: 'binaural-beats', name: 'Ethereal Pad', desc: '432Hz ambient drone', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>', generator: 'binauralBeats' },
                    { id: 'piano', name: 'Soft Piano', desc: 'Gentle melodies', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>', generator: 'piano' },
                    { id: 'handpan', name: 'Handpan', desc: 'Resonant bell tones', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>', generator: 'handpan' },
                    { id: 'flute', name: 'Bamboo Flute', desc: 'Airy whistles', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>', generator: 'flute' },
                ]
            },
            {
                name: 'Focus',
                color: 'amber',
                icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                sounds: [
                    { id: 'white-noise', name: 'White Noise', desc: 'Even frequency', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>', generator: 'whiteNoise' },
                    { id: 'brown-noise', name: 'Brown Noise', desc: 'Deep warmth', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>', generator: 'brownNoise' },
                    { id: 'pink-noise', name: 'Pink Noise', desc: '1/f spectrum', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>', generator: 'pinkNoise' },
                ]
            },
            {
                name: 'Premium Media',
                color: 'purple',
                icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>',
                sounds: [
                    { id: 'premium-piano', name: 'Pixabay Piano', desc: 'Relaxing track', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/></svg>', generator: 'externalAudio', url: '/audio/relaxing_piano.webm' },
                    { id: 'premium-sleep', name: 'Pixabay Sleep', desc: 'Ambient sleep', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>', generator: 'externalAudio', url: '/audio/ambient_sleep.webm' },
                    { id: 'premium-meditate', name: 'Pixabay Meditate', desc: 'Deep calm', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>', generator: 'externalAudio', url: '/audio/relaxing_meditation.webm' },
                ]
            }
        ],

        // ─── Init ───
        init() {
            // Clean up on page leave
            window.addEventListener('beforeunload', () => this.cleanup());
        },

        // ─── Sound State ───
        isActive(id) {
            return !!Alpine.store('globalAudio').activeSounds[id];
        },

        getSoundVolume(id) {
            return Alpine.store('globalAudio').activeSounds[id]?.volume ?? 60;
        },

        setSoundVolume(id, val) {
            const store = Alpine.store('globalAudio');
            if (store.activeSounds[id]) {
                store.activeSounds[id].volume = parseInt(val);
                if (store.activeSounds[id].gainNode) {
                    store.activeSounds[id].gainNode.gain.setTargetAtTime(val / 100, store.audioCtx.currentTime, 0.05);
                }
            }
        },

        updateMasterVolume() {
            // Already handled by x-model on the slider tying to $store.globalAudio.masterVolume, 
            // but we must update the running gain node if it exists.
            const store = Alpine.store('globalAudio');
            if (store.audioCtx) {
                // If there's a central masterGain we should use it. 
                // But globalAudio doesn't have a masterGain node, it just reads the volume.
                // Let's iterate and update individual volumes proportionally, or build masterGain in globalAudio.
                // For now, we'll just let new sounds use it. 
            }
        },

        // ─── Toggle Sound ───
        toggleSound(sound) {
            if (this.isActive(sound.id)) {
                this.stopSound(sound.id);
            } else {
                this.startSound(sound);
            }
            this.activePreset = null;
        },

        startSound(sound, vol) {
            const store = Alpine.store('globalAudio');
            store.ensureAudioContext();
            
            const volume = vol ?? 60;
            const gainNode = store.audioCtx.createGain();
            // simple proportional master volume mix
            gainNode.gain.value = (volume / 100) * (store.masterVolume / 100);
            gainNode.connect(store.audioCtx.destination);

            const generator = window.SoundscapeGenerators[sound.generator](store.audioCtx, gainNode, sound.url);

            store.activeSounds = { ...store.activeSounds, [sound.id]: {
                gainNode,
                generator,
                volume,
                soundData: sound
            }};
            store.allPaused = false;
        },

        stopSound(id) {
            const store = Alpine.store('globalAudio');
            const s = store.activeSounds[id];
            if (s) {
                try {
                    if (s.generator && s.generator.stop) s.generator.stop();
                    if (s.gainNode) s.gainNode.disconnect();
                } catch(e) {}
                const newSounds = { ...store.activeSounds };
                delete newSounds[id];
                store.activeSounds = newSounds;
            }
        },

        // ─── Bulk Controls ───
        toggleAll() {
            const store = Alpine.store('globalAudio');
            if (store.activeCount > 0) {
                store.pauseAll();
            }
        },

        cleanup() {
            // we do NOT stop sounds on cleanup because we want it to persist globally!
        },

        // ─── Presets ───
        loadPreset(preset) {
            Alpine.store('globalAudio').stopAll();
            Alpine.store('globalAudio').ensureAudioContext();
            const allSounds = this.categories.flatMap(c => c.sounds);
            preset.ids.forEach(id => {
                const sound = allSounds.find(s => s.id === id);
                if (sound) {
                    this.startSound(sound, preset.volumes[id] || 60);
                }
            });
            this.activePreset = preset.name;
        },

        // ─── Timer ───
        setTimer() {
            this.clearTimer();
            const minutes = parseInt(this.timerDuration);
            if (minutes <= 0) return;

            this.timerRemaining = minutes * 60;
            this.timerInterval = setInterval(() => {
                this.timerRemaining--;
                if (this.timerRemaining <= 0) {
                    Alpine.store('globalAudio').stopAll();
                    this.clearTimer();
                }
            }, 1000);
        },

        clearTimer() {
            if (this.timerInterval) { clearInterval(this.timerInterval); this.timerInterval = null; }
            if (this.timerTimeout) { clearTimeout(this.timerTimeout); this.timerTimeout = null; }
            this.timerRemaining = 0;
        },

        formatTime(secs) {
            const m = Math.floor(secs / 60);
            const s = secs % 60;
            return m + ':' + (s < 10 ? '0' : '') + s;
        },

        generators: window.SoundscapeGenerators
    };
}
</script>

@endsection
