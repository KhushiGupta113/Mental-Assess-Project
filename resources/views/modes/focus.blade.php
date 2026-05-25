@extends('layouts.main')

@section('content')
{{-- ═══ Focus Mode — Pomodoro Timer ═══ --}}
<div class="min-h-screen py-8 md:py-12" x-data="focusTimer()" x-init="init()" @keydown.space.prevent="toggleTimer()">

    {{-- Toast Notification --}}
    <div x-show="toast.show" x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
         class="fixed top-20 left-1/2 -translate-x-1/2 z-50 max-w-md w-full px-4" style="pointer-events:none;">
        <div class="glass-card-premium px-6 py-4 flex items-center gap-3 shadow-2xl" style="pointer-events:auto;">
            <span class="text-2xl" x-text="toast.icon"></span>
            <p class="text-sm font-medium t-text flex-1" x-text="toast.message"></p>
            <button @click="toast.show = false" class="t-muted hover:t-text transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Navigation --}}
        <div class="mb-4">
            <button @click="window.location.href='{{ route('modes.index') }}'"
                    class="flex items-center gap-2 t-text opacity-80 hover:opacity-100 transition-opacity">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="font-medium text-sm">Back to Modes</span>
            </button>
        </div>

        {{-- Header --}}
        <div class="text-center mb-8 md:mb-12" data-aos="fade-down">
            <span class="badge-nature mb-3 inline-flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Focus Mode
            </span>
            <h1 class="section-heading mb-3">Deep Focus Timer</h1>
            <p class="section-subheading mx-auto">Structured work sessions with mindful breaks. Choose your rhythm and get in the zone.</p>
        </div>

        {{-- Technique Selector --}}
        <div class="flex flex-wrap justify-center gap-2 mb-8" data-aos="fade-up" data-aos-delay="100">
            <template x-for="(tech, key) in techniques" :key="key">
                <button @click="selectTechnique(key)"
                    :class="activeTechnique === key
                        ? 'text-white shadow-lg scale-105'
                        : 'hover:scale-102'"
                    :style="activeTechnique === key
                        ? 'background:linear-gradient(135deg, var(--th-primary), var(--th-accent));'
                        : 'background:var(--th-surface);border:1px solid var(--th-border-strong);color:var(--th-text-muted);'"
                    class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300"
                    :disabled="state !== 'idle' && state !== 'complete'"
                    x-text="tech.label"
                    :id="'technique-' + key">
                </button>
            </template>
        </div>

        {{-- Custom Technique Inputs --}}
        <div x-show="activeTechnique === 'custom'" x-transition x-cloak
             class="max-w-xl mx-auto mb-8" data-aos="fade-up" data-aos-delay="150">
            <div class="glass-card-premium p-6">
                <h3 class="font-serif font-semibold t-text text-lg mb-4 text-center">Custom Timer Settings</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-xs font-medium t-muted mb-1 block">Work (min)</label>
                        <input type="number" x-model.number="customSettings.work" min="1" max="120"
                               class="input-nature text-center !py-2 text-sm" id="custom-work-input"
                               :disabled="state !== 'idle' && state !== 'complete'">
                    </div>
                    <div>
                        <label class="text-xs font-medium t-muted mb-1 block">Short Break</label>
                        <input type="number" x-model.number="customSettings.shortBreak" min="1" max="60"
                               class="input-nature text-center !py-2 text-sm" id="custom-short-break-input"
                               :disabled="state !== 'idle' && state !== 'complete'">
                    </div>
                    <div>
                        <label class="text-xs font-medium t-muted mb-1 block">Long Break</label>
                        <input type="number" x-model.number="customSettings.longBreak" min="1" max="60"
                               class="input-nature text-center !py-2 text-sm" id="custom-long-break-input"
                               :disabled="state !== 'idle' && state !== 'complete'">
                    </div>
                    <div>
                        <label class="text-xs font-medium t-muted mb-1 block">Cycles</label>
                        <input type="number" x-model.number="customSettings.cycles" min="1" max="12"
                               class="input-nature text-center !py-2 text-sm" id="custom-cycles-input"
                               :disabled="state !== 'idle' && state !== 'complete'">
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Layout: 2-column flex on large screens --}}
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">

            {{-- ═══ LEFT/TOP: Timer ═══ --}}
            <div class="w-full lg:w-3/5" data-aos="fade-up" data-aos-delay="200">
                <div class="glass-card-premium p-6 md:p-10 w-full">

                    {{-- Session Complete Summary --}}
                    <template x-if="state === 'complete'">
                        <div class="text-center py-4" x-transition>
                            <div class="w-20 h-20 mx-auto mb-5 rounded-full flex items-center justify-center text-4xl"
                                 style="background:var(--th-primary-light);">
                                🎉
                            </div>
                            <h2 class="font-serif font-bold text-2xl md:text-3xl t-text mb-2">Session Complete!</h2>
                            <p class="t-muted text-sm mb-8" x-text="motivationalMessage"></p>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                                <div class="rounded-2xl p-4 text-center" style="background:var(--th-primary-lighter);border:1px solid var(--th-border);">
                                    <p class="text-2xl font-bold" style="color:var(--th-primary);" x-text="formatDuration(sessionStats.focusTime)"></p>
                                    <p class="text-xs t-muted mt-1">Focus Time</p>
                                </div>
                                <div class="rounded-2xl p-4 text-center" style="background:var(--th-accent-light);border:1px solid var(--th-border);">
                                    <p class="text-2xl font-bold" style="color:var(--th-accent);" x-text="formatDuration(sessionStats.breakTime)"></p>
                                    <p class="text-xs t-muted mt-1">Break Time</p>
                                </div>
                                <div class="rounded-2xl p-4 text-center" style="background:var(--th-primary-lighter);border:1px solid var(--th-border);">
                                    <p class="text-2xl font-bold" style="color:var(--th-primary);" x-text="currentCycle"></p>
                                    <p class="text-xs t-muted mt-1">Cycles Done</p>
                                </div>
                                <div class="rounded-2xl p-4 text-center" style="background:var(--th-accent-light);border:1px solid var(--th-border);">
                                    <p class="text-2xl font-bold" style="color:var(--th-accent);" x-text="tasks.filter(t => t.done).length + '/' + tasks.length"></p>
                                    <p class="text-xs t-muted mt-1">Tasks Done</p>
                                </div>
                            </div>

                            <button @click="resetSession()"
                                    class="btn-nature !rounded-full !px-8" id="focus-restart-btn">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Start New Session
                            </button>
                        </div>
                    </template>

                    {{-- Timer Display --}}
                    <template x-if="state !== 'complete'">
                        <div>
                            {{-- Circular Timer --}}
                            <div class="flex justify-center mb-6">
                                <div class="relative" style="width:280px;height:280px;" id="focus-timer-ring">
                                    {{-- SVG Ring --}}
                                    <svg class="w-full h-full" viewBox="0 0 280 280" style="transform:rotate(-90deg);">
                                        {{-- Background track --}}
                                        <circle cx="140" cy="140" r="124"
                                                fill="none" stroke-width="10"
                                                style="stroke:var(--th-border);opacity:0.5;" />
                                        {{-- Progress ring --}}
                                        <circle cx="140" cy="140" r="124"
                                                fill="none" stroke-width="10" stroke-linecap="round"
                                                :style="'stroke:' + phaseColor + ';stroke-dasharray:' + ringCircumference + ';stroke-dashoffset:' + ringOffset + ';transition:stroke-dashoffset 0.5s ease, stroke 0.5s ease;'"
                                                id="focus-progress-ring" />
                                        {{-- Glow filter --}}
                                        <circle cx="140" cy="140" r="124"
                                                fill="none" stroke-width="10" stroke-linecap="round"
                                                :style="'stroke:' + phaseColor + ';stroke-dasharray:' + ringCircumference + ';stroke-dashoffset:' + ringOffset + ';opacity:0.3;filter:blur(8px);transition:stroke-dashoffset 0.5s ease, stroke 0.5s ease;'" />
                                    </svg>

                                    {{-- Center Content --}}
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        {{-- Time --}}
                                        <span class="text-5xl md:text-6xl font-bold tracking-tight tabular-nums"
                                              style="color:var(--th-text);font-variant-numeric:tabular-nums;"
                                              x-text="displayTime"
                                              id="focus-time-display">
                                        </span>
                                        {{-- Phase --}}
                                        <span class="text-sm font-bold uppercase tracking-widest mt-2 px-3 py-1 rounded-full"
                                              :style="'color:' + phaseColor + ';background:' + phaseColor + '15;'"
                                              x-text="phaseLabel"
                                              id="focus-phase-label">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Cycle Progress Dots --}}
                            <div class="flex justify-center gap-2 mb-6" id="focus-cycle-dots">
                                <template x-for="i in totalCycles" :key="'cycle-'+i">
                                    <div class="w-3 h-3 rounded-full transition-all duration-300"
                                         :style="i <= currentCycle
                                            ? 'background:var(--th-primary);box-shadow:0 0 8px var(--th-glow);'
                                            : 'background:var(--th-border-strong);'"
                                         :class="i <= currentCycle ? 'scale-110' : ''">
                                    </div>
                                </template>
                            </div>

                            {{-- Controls --}}
                            <div class="flex flex-wrap justify-center gap-3 mb-6" id="focus-controls">
                                {{-- Start / Pause --}}
                                <button @click="toggleTimer()"
                                        class="inline-flex items-center gap-2 px-8 py-3 rounded-full font-semibold text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 active:scale-95"
                                        :style="isRunning
                                            ? 'background:linear-gradient(135deg, #f59e0b, #d97706);'
                                            : 'background:linear-gradient(135deg, var(--th-primary), var(--th-accent));'"
                                        id="focus-start-pause-btn">
                                    <template x-if="!isRunning">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </template>
                                    <template x-if="isRunning">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/></svg>
                                    </template>
                                    <span x-text="isRunning ? 'Pause' : (state === 'idle' ? 'Start' : 'Resume')"></span>
                                </button>

                                {{-- Skip Phase --}}
                                <button @click="skipPhase()"
                                        :disabled="state === 'idle'"
                                        class="inline-flex items-center gap-2 px-5 py-3 rounded-full font-medium text-sm transition-all duration-300 hover:scale-105 active:scale-95"
                                        :class="state === 'idle' ? 'opacity-40 cursor-not-allowed' : ''"
                                        style="background:var(--th-surface);color:var(--th-text-muted);border:1px solid var(--th-border-strong);"
                                        id="focus-skip-btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5l7 7-7 7"/></svg>
                                    Skip
                                </button>

                                {{-- Reset --}}
                                <button @click="resetSession()"
                                        :disabled="state === 'idle'"
                                        class="inline-flex items-center gap-2 px-5 py-3 rounded-full font-medium text-sm transition-all duration-300 hover:scale-105 active:scale-95"
                                        :class="state === 'idle' ? 'opacity-40 cursor-not-allowed' : ''"
                                        style="background:var(--th-surface);color:var(--th-text-muted);border:1px solid var(--th-border-strong);"
                                        id="focus-reset-btn">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    Reset
                                </button>
                            </div>

                            {{-- Ambient Sound Selector --}}
                            <div class="mt-8 pt-6 border-t border-gray-200/20 dark:border-gray-700/30">
                                <label class="block text-center text-xs font-semibold t-muted uppercase tracking-wider mb-3">Ambient Sound</label>
                                <div class="flex flex-wrap justify-center gap-2">
                                    <template x-for="(sound, key) in ambientSounds" :key="key">
                                        <button
                                            @click="ambientSound = key; toggleAmbientSound()"
                                            class="meditation-pill px-3 py-1.5 rounded-full text-xs font-medium border transition-all duration-300"
                                            :class="ambientSound === key ? 'text-white shadow-md scale-105' : 'hover:scale-105'"
                                            :style="ambientSound === key
                                                ? 'background: linear-gradient(135deg, var(--th-primary), var(--th-accent)); border-color: var(--th-primary);'
                                                : 'background: var(--th-surface); border-color: var(--th-border-strong); color: var(--th-text-muted);'">
                                            <span x-text="sound.label"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            {{-- Keyboard hint --}}
                            <p class="text-center text-xs t-light mt-4 hidden md:block">
                                Press <kbd class="px-1.5 py-0.5 rounded text-xs font-mono" style="background:var(--th-surface);border:1px solid var(--th-border);">Space</kbd> to start/pause
                            </p>
                        </div>
                    </template>

                </div>
            </div>

            {{-- ═══ RIGHT/BOTTOM: Tasks ═══ --}}
            <div class="w-full lg:w-2/5 flex flex-col gap-6" data-aos="fade-up" data-aos-delay="300">
                <div class="glass-card-premium p-6 md:p-8">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="font-serif font-bold t-text text-xl flex items-center gap-2">
                            <svg class="w-5 h-5" style="color:var(--th-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Tasks
                        </h2>
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full"
                              style="background:var(--th-primary-light);color:var(--th-primary);"
                              x-text="tasks.filter(t => t.done).length + '/' + tasks.length">
                        </span>
                    </div>

                    {{-- Add Task Input --}}
                    <form @submit.prevent="addTask()" class="flex gap-2 mb-5">
                        <input type="text" x-model="newTask" placeholder="Add a task..."
                               class="input-nature flex-1 !py-2.5 text-sm" id="focus-task-input"
                               maxlength="120">
                        <button type="submit" :disabled="!newTask.trim()"
                                class="btn-nature !py-2.5 !px-4 !rounded-xl flex items-center gap-1 text-sm"
                                :class="!newTask.trim() ? 'opacity-50 cursor-not-allowed' : ''"
                                id="focus-add-task-btn">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add
                        </button>
                    </form>

                    {{-- Task List --}}
                    <div class="space-y-2 max-h-[380px] overflow-y-auto pr-1" id="focus-task-list"
                         style="scrollbar-width:thin;scrollbar-color:var(--th-border-strong) transparent;">
                        <template x-if="tasks.length === 0">
                            <div class="text-center py-10">
                                <svg class="w-12 h-12 mx-auto mb-3" style="color:var(--th-text-light)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                <p class="t-light text-sm">No tasks yet</p>
                                <p class="t-light text-xs mt-1">Add tasks to track your focus session</p>
                            </div>
                        </template>

                        <template x-for="(task, idx) in tasks" :key="task.id">
                            <div class="group flex items-center gap-3 p-3 rounded-xl transition-all duration-200"
                                 :style="task.done
                                    ? 'background:var(--th-primary-lighter);'
                                    : 'background:var(--th-surface);'"
                                 style="border:1px solid var(--th-border);">
                                {{-- Checkbox --}}
                                <button @click="toggleTask(idx)"
                                        class="w-5 h-5 rounded-md border-2 flex-shrink-0 flex items-center justify-center transition-all duration-200"
                                        :style="task.done
                                            ? 'background:var(--th-primary);border-color:var(--th-primary);'
                                            : 'border-color:var(--th-border-strong);'"
                                        :id="'task-check-' + task.id">
                                    <svg x-show="task.done" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </button>

                                {{-- Task Text --}}
                                <span class="flex-1 text-sm transition-all duration-200"
                                      :class="task.done ? 'line-through' : ''"
                                      :style="task.done ? 'color:var(--th-text-light);' : 'color:var(--th-text);'"
                                      x-text="task.text"></span>

                                {{-- Delete --}}
                                <button @click="removeTask(idx)"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200 hover:scale-110"
                                        style="color:var(--th-text-light);"
                                        :id="'task-del-' + task.id">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
            </div>
        </div>

    </div>
</div>

{{-- ═══ Focus Timer Alpine Component ═══ --}}
<script>
function focusTimer() {
    return {
        // ─── Technique presets ───
        techniques: {
            pomodoro: { label: 'Pomodoro Classic', work: 25, shortBreak: 5, longBreak: 15, cycles: 4 },
            deepWork: { label: 'Deep Work', work: 50, shortBreak: 10, longBreak: 30, cycles: 3 },
            sprint:   { label: 'Sprint', work: 15, shortBreak: 3, longBreak: 10, cycles: 6 },
            custom:   { label: 'Custom', work: 25, shortBreak: 5, longBreak: 15, cycles: 4 },
        },
        activeTechnique: 'pomodoro',
        customSettings: { work: 25, shortBreak: 5, longBreak: 15, cycles: 4 },

        // ─── State machine ───
        // idle | working | shortBreak | longBreak | paused | complete
        state: 'idle',
        previousState: null, // used when pausing
        isRunning: false,

        // ─── Timer internals ───
        phaseDuration: 0,   // total seconds for this phase
        remaining: 0,       // seconds remaining
        intervalId: null,
        startTimestamp: null,  // absolute ms when started / resumed
        pausedRemaining: 0,   // seconds left when paused

        // ─── Cycle tracking ───
        currentCycle: 0,
        totalCycles: 4,
        workPhasesDone: 0, // within the current full session

        // ─── Session stats ───
        sessionStats: { focusTime: 0, breakTime: 0 },
        phaseStartRemaining: 0, // remaining at phase start (for stats delta)

        // ─── SVG ring ───
        ringCircumference: 2 * Math.PI * 124, // ~779.17

        // ─── Tasks ───
        tasks: [],
        newTask: '',
        nextTaskId: 1,

        // ─── Ambient sound ───
        ambientSounds: {
            none: { label: 'None', generator: null },
            rain: { label: 'Rain', generator: 'lightRain' },
            forest: { label: 'Forest', generator: 'forest' },
            ocean: { label: 'Ocean', generator: 'oceanWaves' },
            river: { label: 'River', generator: 'riverStream' },
            fire: { label: 'Campfire', generator: 'campfire' },
            bowls: { label: 'Bowls', generator: 'singingBowls' },
            binaural: { label: 'Binaural', generator: 'binauralBeats' },
            white: { label: 'White Noise', generator: 'whiteNoise' },
            brown: { label: 'Brown Noise', generator: 'brownNoise' },
            pink: { label: 'Pink Noise', generator: 'pinkNoise' },
        },
        ambientSound: 'none',
        ambientPlaying: false,
        audioCtx: null,
        ambientNode: null,
        gainNode: null,

        // ─── Toast ───
        toast: { show: false, message: '', icon: '' },
        toastTimeout: null,

        // ─── Tab title ───
        originalTitle: document.title,

        // ─── Motivational messages ───
        motivationalMessages: [
            "You did amazing! Every focused minute builds your mental strength. 💪",
            "Incredible focus session! Your dedication is inspiring. 🌟",
            "Well done! Consistency is the key to greatness. 🔑",
            "Your focus was on fire today! Keep nurturing that discipline. 🔥",
            "Beautiful session! Remember: progress, not perfection. 🌱",
            "You showed up and gave it your all. That's what matters! 🏆",
        ],
        motivationalMessage: '',

        // ─── Computed ───
        get displayTime() {
            const m = Math.floor(this.remaining / 60);
            const s = this.remaining % 60;
            return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        },

        get phaseLabel() {
            if (this.state === 'idle') return 'READY';
            if (this.state === 'working' || (this.state === 'paused' && this.previousState === 'working')) return 'FOCUS';
            if (this.state === 'shortBreak' || (this.state === 'paused' && this.previousState === 'shortBreak')) return 'SHORT BREAK';
            if (this.state === 'longBreak' || (this.state === 'paused' && this.previousState === 'longBreak')) return 'LONG BREAK';
            return 'READY';
        },

        get phaseColor() {
            const label = this.phaseLabel;
            if (label === 'FOCUS') return 'var(--th-primary)';
            if (label === 'SHORT BREAK' || label === 'LONG BREAK') return 'var(--th-accent)';
            return 'var(--th-text-light)';
        },

        get ringOffset() {
            if (this.state === 'idle') return this.ringCircumference;
            if (this.phaseDuration === 0) return this.ringCircumference;
            const progress = 1 - (this.remaining / this.phaseDuration);
            return this.ringCircumference * (1 - progress);
        },

        get currentTechniqueConfig() {
            if (this.activeTechnique === 'custom') {
                return {
                    work: Math.max(1, this.customSettings.work || 25),
                    shortBreak: Math.max(1, this.customSettings.shortBreak || 5),
                    longBreak: Math.max(1, this.customSettings.longBreak || 15),
                    cycles: Math.max(1, this.customSettings.cycles || 4),
                };
            }
            return this.techniques[this.activeTechnique];
        },

        // ─── Init ───
        init() {
            const config = this.currentTechniqueConfig;
            this.totalCycles = config.cycles;
            this.phaseDuration = config.work * 60;
            this.remaining = this.phaseDuration;

            // Visibility change handler for drift correction
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden && this.isRunning && this.startTimestamp) {
                    this.correctDrift();
                }
            });
        },

        // ─── Technique selection ───
        selectTechnique(key) {
            if (this.state !== 'idle' && this.state !== 'complete') return;
            this.activeTechnique = key;
            this.resetSession();
        },

        // ─── Timer controls ───
        toggleTimer() {
            if (this.state === 'complete') return;

            if (this.isRunning) {
                this.pauseTimer();
            } else {
                this.startTimer();
            }
        },

        startTimer() {
            if (this.state === 'idle') {
                // Fresh start — begin first work phase
                this.requestNotificationPermission();
                const config = this.currentTechniqueConfig;
                this.totalCycles = config.cycles;
                this.currentCycle = 0;
                this.workPhasesDone = 0;
                this.sessionStats = { focusTime: 0, breakTime: 0 };
                this.startWorkPhase();
            } else if (this.state === 'paused') {
                // Resume
                this.state = this.previousState;
                this.remaining = this.pausedRemaining;
                this.startTimestamp = Date.now() - ((this.phaseDuration - this.remaining) * 1000);
            }

            this.isRunning = true;
            this.phaseStartRemaining = this.remaining;
            this.startTimestamp = Date.now() - ((this.phaseDuration - this.remaining) * 1000);

            this.intervalId = setInterval(() => {
                this.tick();
            }, 250); // tick every 250ms for drift correction
        },

        pauseTimer() {
            this.isRunning = false;
            this.previousState = this.state;
            this.pausedRemaining = this.remaining;
            this.state = 'paused';
            clearInterval(this.intervalId);
            this.intervalId = null;

            // Record elapsed time in stats
            const elapsed = this.phaseStartRemaining - this.remaining;
            if (this.previousState === 'working') {
                this.sessionStats.focusTime += elapsed;
            } else {
                this.sessionStats.breakTime += elapsed;
            }
            document.title = this.originalTitle;
        },

        tick() {
            if (!this.isRunning) return;
            this.correctDrift();

            if (this.remaining <= 0) {
                this.onPhaseComplete();
            }

            // Update tab title
            document.title = '🍅 ' + this.displayTime + ' — ' + this.phaseLabel + ' | MindAssess';
        },

        correctDrift() {
            if (!this.startTimestamp) return;
            const elapsed = (Date.now() - this.startTimestamp) / 1000;
            this.remaining = Math.max(0, Math.round(this.phaseDuration - elapsed));
        },

        // ─── Phase transitions ───
        startWorkPhase() {
            const config = this.currentTechniqueConfig;
            this.state = 'working';
            this.phaseDuration = config.work * 60;
            this.remaining = this.phaseDuration;
            this.startTimestamp = Date.now();
            this.phaseStartRemaining = this.remaining;
        },

        startShortBreak() {
            const config = this.currentTechniqueConfig;
            this.state = 'shortBreak';
            this.phaseDuration = config.shortBreak * 60;
            this.remaining = this.phaseDuration;
            this.startTimestamp = Date.now();
            this.phaseStartRemaining = this.remaining;
        },

        startLongBreak() {
            const config = this.currentTechniqueConfig;
            this.state = 'longBreak';
            this.phaseDuration = config.longBreak * 60;
            this.remaining = this.phaseDuration;
            this.startTimestamp = Date.now();
            this.phaseStartRemaining = this.remaining;
        },

        onPhaseComplete() {
            // Record elapsed time
            if (this.state === 'working') {
                this.sessionStats.focusTime += this.phaseStartRemaining;
            } else {
                this.sessionStats.breakTime += this.phaseStartRemaining;
            }

            if (this.state === 'working') {
                this.workPhasesDone++;
                this.currentCycle = this.workPhasesDone;

                if (this.workPhasesDone >= this.totalCycles) {
                    // All cycles complete
                    this.completeSession();
                    return;
                }

                // Determine break type
                if (this.workPhasesDone % this.totalCycles === 0) {
                    this.sendNotification('Time for a long break! 🎉 You\'ve earned it.', '☕');
                    this.startLongBreak();
                } else {
                    this.sendNotification('Time for a break! 🎉 You\'ve earned it.', '🎉');
                    this.startShortBreak();
                }
            } else {
                // Break just ended
                this.sendNotification('Break\'s over! 💪 Let\'s focus.', '💪');
                this.startWorkPhase();
            }
        },

        completeSession() {
            this.isRunning = false;
            clearInterval(this.intervalId);
            this.intervalId = null;
            this.state = 'complete';
            this.motivationalMessage = this.motivationalMessages[Math.floor(Math.random() * this.motivationalMessages.length)];
            this.sendNotification('🏆 Session complete! Amazing work!', '🏆');
            document.title = this.originalTitle;
            this.stopAmbientSound();
        },

        skipPhase() {
            if (this.state === 'idle' || this.state === 'complete') return;

            // If paused, restore previous state context
            if (this.state === 'paused') {
                this.state = this.previousState;
                this.remaining = this.pausedRemaining;
            }

            // Force remaining to 0 and handle phase complete
            this.remaining = 0;
            this.onPhaseComplete();

            // If still running, reset the start timestamp
            if (this.isRunning) {
                this.startTimestamp = Date.now();
            } else {
                // Was paused, start the new phase paused
                this.pausedRemaining = this.remaining;
                this.previousState = this.state;
                this.state = 'paused';
            }
        },

        resetSession() {
            this.isRunning = false;
            clearInterval(this.intervalId);
            this.intervalId = null;
            this.state = 'idle';
            this.previousState = null;
            this.currentCycle = 0;
            this.workPhasesDone = 0;
            this.sessionStats = { focusTime: 0, breakTime: 0 };

            const config = this.currentTechniqueConfig;
            this.totalCycles = config.cycles;
            this.phaseDuration = config.work * 60;
            this.remaining = this.phaseDuration;
            this.startTimestamp = null;

            document.title = this.originalTitle;
            this.stopAmbientSound();
        },

        // ─── Notifications ───
        requestNotificationPermission() {
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }
        },

        sendNotification(message, icon) {
            // Browser notification
            if ('Notification' in window && Notification.permission === 'granted') {
                try {
                    new Notification('MindAssess Focus', {
                        body: message,
                        icon: '/favicon.ico',
                        badge: '/favicon.ico',
                    });
                } catch (e) { /* fallback below */ }
            }

            // Always show in-page toast
            this.showToast(message, icon);
        },

        showToast(message, icon) {
            if (this.toastTimeout) clearTimeout(this.toastTimeout);
            this.toast = { show: true, message, icon };
            this.toastTimeout = setTimeout(() => {
                this.toast.show = false;
            }, 5000);
        },

        // ─── Tasks ───
        addTask() {
            const text = this.newTask.trim();
            if (!text) return;
            this.tasks.push({ id: this.nextTaskId++, text, done: false });
            this.newTask = '';
        },

        toggleTask(idx) {
            this.tasks[idx].done = !this.tasks[idx].done;
        },

        removeTask(idx) {
            this.tasks.splice(idx, 1);
        },

        // ─── Ambient Sound (Web Audio API) ───
        toggleAmbientSound() {
            if (this.ambientSound !== 'none') {
                this.startAmbientSound();
            } else {
                this.stopAmbientSound();
            }
        },

        startAmbientSound() {
            if (this.ambientSound === 'none') return;
            this.stopAmbientSound(); // Stop existing if switching
            
            try {
                if (!this.audioCtx) {
                    this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (this.audioCtx.state === 'suspended') {
                    this.audioCtx.resume();
                }
                
                const generatorName = this.ambientSounds[this.ambientSound].generator;
                if (!generatorName || !window.SoundscapeGenerators) return;

                this.gainNode = this.audioCtx.createGain();
                this.gainNode.gain.setValueAtTime(0, this.audioCtx.currentTime);
                this.gainNode.gain.linearRampToValueAtTime(0.3, this.audioCtx.currentTime + 1);
                this.gainNode.connect(this.audioCtx.destination);

                this.ambientNode = window.SoundscapeGenerators[generatorName](this.audioCtx, this.gainNode);
                this.ambientPlaying = true;
            } catch (e) {
                console.warn('Web Audio API not available:', e);
                this.ambientSound = 'none';
                this.ambientPlaying = false;
            }
        },

        stopAmbientSound() {
            if (this.gainNode && this.audioCtx) {
                try {
                    this.gainNode.gain.linearRampToValueAtTime(0, this.audioCtx.currentTime + 0.5);
                    setTimeout(() => {
                        if (this.ambientNode && this.ambientNode.stop) { this.ambientNode.stop(); }
                        if (this.gainNode) { this.gainNode.disconnect(); this.gainNode = null; }
                        this.ambientNode = null;
                        this.ambientPlaying = false;
                    }, 600);
                } catch (e) {
                    this.ambientNode = null; this.gainNode = null; this.ambientPlaying = false;
                }
            } else {
                this.ambientPlaying = false;
            }
        },

        // ─── Helpers ───
        formatDuration(seconds) {
            const m = Math.floor(seconds / 60);
            if (m < 60) return m + 'm';
            const h = Math.floor(m / 60);
            const rm = m % 60;
            return h + 'h ' + rm + 'm';
        },
    };
}
</script>

<style>
    /* Focus page specific styles */
    @keyframes focus-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    #focus-timer-ring {
        animation: focus-pulse 4s ease-in-out infinite paused;
    }
    .is-running #focus-timer-ring {
        animation-play-state: running;
    }

    /* Custom scrollbar for task list */
    #focus-task-list::-webkit-scrollbar {
        width: 4px;
    }
    #focus-task-list::-webkit-scrollbar-track {
        background: transparent;
    }
    #focus-task-list::-webkit-scrollbar-thumb {
        background: var(--th-border-strong);
        border-radius: 4px;
    }
    #focus-task-list::-webkit-scrollbar-thumb:hover {
        background: var(--th-text-light);
    }
</style>
@endsection
