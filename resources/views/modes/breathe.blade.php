@extends('layouts.main')

@section('content')
<section class="relative min-h-screen overflow-hidden flex items-center justify-center" style="background: linear-gradient(135deg, var(--th-gradient-from) 0%, var(--th-gradient-via) 50%, var(--th-gradient-to) 100%);"
    x-data="breatheApp()" x-init="init()">

    {{-- Ambient floating particles --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="breathe-particle" style="--delay: 0s; --x: 15%; --y: 20%;"></div>
        <div class="breathe-particle" style="--delay: 3s; --x: 75%; --y: 30%;"></div>
        <div class="breathe-particle" style="--delay: 6s; --x: 40%; --y: 70%;"></div>
        <div class="breathe-particle" style="--delay: 9s; --x: 85%; --y: 65%;"></div>
        <div class="breathe-particle" style="--delay: 2s; --x: 25%; --y: 85%;"></div>
        <div class="breathe-particle" style="--delay: 7s; --x: 60%; --y: 15%;"></div>
    </div>

    <div class="relative z-10 w-full max-w-2xl mx-auto px-4 py-8">

        {{-- Navigation --}}
        <div class="mb-8">
            <button @click="(isRunning || isPaused || isComplete) ? reset() : window.location.href='{{ route('modes.index') }}'"
                    class="flex items-center gap-2 t-text opacity-80 hover:opacity-100 transition-opacity"
                    style="color: var(--th-text-muted);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="font-medium text-sm" x-text="(isRunning || isPaused || isComplete) ? 'Back to Setup' : 'Back to Modes'"></span>
            </button>
        </div>

        {{-- Header --}}
        <div class="text-center mb-10" data-aos="fade-up" x-show="!isRunning && !isPaused">
            <h1 class="text-3xl sm:text-4xl font-serif font-bold mb-3" style="color: var(--th-text);">Breathe</h1>
            <p class="text-base" style="color: var(--th-text-muted);">Follow the rhythm. Let your breathing guide you to calm.</p>
        </div>

        {{-- Pattern Selector --}}
        <div class="flex flex-wrap justify-center gap-2 mb-8" x-show="!isRunning && !isPaused" data-aos="fade-up" data-aos-delay="100">
            <template x-for="(pat, key) in patterns" :key="key">
                <button @click="selectedPattern = key"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                    :style="selectedPattern === key
                        ? 'background: var(--th-primary); color: white; box-shadow: 0 4px 16px ' + 'var(--th-glow)'
                        : 'background: color-mix(in srgb, var(--th-surface) 60%, transparent); color: var(--th-text-muted); border: 1px solid var(--th-border);'"
                    x-text="pat.name">
                </button>
            </template>
        </div>

        {{-- Duration Selector --}}
        <div class="flex justify-center gap-2 mb-10" x-show="!isRunning && !isPaused" data-aos="fade-up" data-aos-delay="150">
            <template x-for="d in durations" :key="d">
                <button @click="selectedDuration = d"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300"
                    :style="selectedDuration === d
                        ? 'background: var(--th-accent); color: white;'
                        : 'background: color-mix(in srgb, var(--th-surface) 40%, transparent); color: var(--th-text-light); border: 1px solid var(--th-border);'"
                    x-text="d + ' min'">
                </button>
            </template>
        </div>

        {{-- Breathing Circle Visual --}}
        <div class="flex flex-col items-center justify-center mb-10" data-aos="fade-up" data-aos-delay="200">

            {{-- The Circle --}}
            <div class="relative flex items-center justify-center" style="width: 280px; height: 280px;">

                {{-- Outer ambient ring --}}
                <div class="absolute inset-0 rounded-full transition-all"
                    :class="isRunning ? 'breathe-ambient-ring' : ''"
                    :style="'border: 2px solid color-mix(in srgb, var(--th-primary) 30%, transparent); opacity: ' + (isRunning ? '1' : '0.3')">
                </div>

                {{-- Secondary ambient ring --}}
                <div class="absolute rounded-full transition-all"
                    :class="isRunning ? 'breathe-ambient-ring-delayed' : ''"
                    style="inset: -12px; border: 1px solid color-mix(in srgb, var(--th-accent) 20%, transparent);"
                    :style="'opacity: ' + (isRunning ? '0.6' : '0.1')">
                </div>

                {{-- Main breathing circle --}}
                <div class="breathe-main-circle rounded-full flex items-center justify-center"
                    :style="`--circle-color: ${dynamicColor}; --dynamic-glow: ${glowSize}px; width: ${circleSize}px; height: ${circleSize}px;`">

                    {{-- Inner content --}}
                    <div class="text-center">
                        {{-- Phase label --}}
                        <div class="text-white font-medium text-xl mb-1 tracking-wide transition-all duration-500"
                            x-text="isRunning || isPaused ? phaseLabel : '●'"
                            :class="isRunning ? 'opacity-100' : 'opacity-60'">
                        </div>
                        {{-- Phase time --}}
                        <div class="text-white/70 text-sm font-light"
                            x-show="isRunning || isPaused"
                            x-text="phaseTimeLeft + 's'"
                            x-transition>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cycle counter --}}
            <div class="mt-6 text-center" x-show="isRunning || isPaused" x-transition>
                <span class="text-sm font-medium" style="color: var(--th-text-muted);"
                    x-text="'Cycle ' + currentCycle + ' of ' + totalCycles">
                </span>
                <div class="flex items-center justify-center gap-1.5 mt-2">
                    <template x-for="i in totalCycles" :key="i">
                        <div class="w-2 h-2 rounded-full transition-all duration-300"
                            :style="i <= currentCycle
                                ? 'background: var(--th-primary); box-shadow: 0 0 6px var(--th-glow);'
                                : 'background: var(--th-border-strong);'">
                        </div>
                    </template>
                </div>
            </div>

            {{-- Total time remaining --}}
            <div class="mt-3 text-center" x-show="isRunning || isPaused" x-transition>
                <span class="text-xs" style="color: var(--th-text-light);"
                    x-text="formatTime(totalTimeRemaining) + ' remaining'">
                </span>
            </div>
        </div>

        {{-- Controls --}}
        <div class="flex flex-col items-center gap-4">
            {{-- Main action button --}}
            <div class="flex items-center gap-3">
                <button x-show="!isRunning && !isPaused" @click="start()" id="breathe-start-btn"
                    class="btn-nature !px-10 !py-3.5 !text-base !rounded-2xl shadow-lg hover:shadow-xl transition-all">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21" fill="currentColor"/></svg>
                        Begin Session
                    </span>
                </button>

                <button x-show="isRunning" @click="pause()" x-transition
                    class="btn-nature-outline !px-8 !py-3 !rounded-2xl">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                        Pause
                    </span>
                </button>

                <button x-show="isPaused" @click="resume()" x-transition
                    class="btn-nature !px-8 !py-3 !rounded-2xl">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21" fill="currentColor"/></svg>
                        Resume
                    </span>
                </button>

                <button x-show="isRunning || isPaused" @click="reset()" x-transition
                    class="px-6 py-3 rounded-2xl text-sm font-medium transition-all duration-300"
                    style="color: var(--th-text-muted); background: color-mix(in srgb, var(--th-surface) 50%, transparent); border: 1px solid var(--th-border);">
                    Reset
                </button>
            </div>

            {{-- Sound toggle --}}
            <label class="flex items-center gap-2 cursor-pointer mt-2 mb-2" x-show="!isRunning && !isPaused">
                <div class="relative">
                    <input type="checkbox" x-model="soundEnabled" class="sr-only">
                    <div class="w-10 h-5 rounded-full transition-colors duration-300 flex items-center"
                        :style="soundEnabled ? 'background: var(--th-primary);' : 'background: var(--th-border-strong);'">
                    <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow-md transition-transform duration-300"
                        :style="soundEnabled ? 'transform: translateX(20px);' : ''">
                    </span>
                    </div>
                </div>
                <span class="text-sm" style="color: var(--th-text-muted);">Transition chimes</span>
            </label>
        </div>

        {{-- Pattern info card --}}
        <div class="mt-12 text-center" x-show="!isRunning && !isPaused" data-aos="fade-up" data-aos-delay="300">
            <div class="inline-block px-8 py-5 rounded-2xl max-w-md" style="background: color-mix(in srgb, var(--th-surface) 45%, transparent); backdrop-filter: blur(16px); border: 1px solid var(--th-border);">
                <h3 class="font-serif font-semibold mb-2" style="color: var(--th-text);" x-text="patterns[selectedPattern].name"></h3>
                <p class="text-sm leading-relaxed mb-3" style="color: var(--th-text-muted);" x-text="patterns[selectedPattern].description"></p>
                <div class="flex items-center justify-center gap-3 text-xs" style="color: var(--th-text-light);">
                    <span x-text="'Inhale: ' + patterns[selectedPattern].inhale + 's'"></span>
                    <span x-show="patterns[selectedPattern].hold1 > 0" x-text="'Hold: ' + patterns[selectedPattern].hold1 + 's'"></span>
                    <span x-text="'Exhale: ' + patterns[selectedPattern].exhale + 's'"></span>
                    <span x-show="patterns[selectedPattern].hold2 > 0" x-text="'Hold: ' + patterns[selectedPattern].hold2 + 's'"></span>
                </div>
            </div>
        </div>

        {{-- Completion overlay --}}
        <div x-show="isComplete" x-transition.opacity.duration.800ms
            class="fixed inset-0 z-50 flex items-center justify-center"
            style="background: color-mix(in srgb, var(--th-bg) 90%, transparent); backdrop-filter: blur(20px);">
            <div class="text-center px-8" data-aos="zoom-in">
                <div class="w-20 h-20 rounded-full mx-auto mb-6 flex items-center justify-center"
                    style="background: linear-gradient(135deg, var(--th-primary), var(--th-accent)); box-shadow: 0 0 40px var(--th-glow);">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="text-3xl font-serif font-bold mb-3" style="color: var(--th-text);">Session Complete</h2>
                <p class="text-lg mb-2" style="color: var(--th-text-muted);">Beautiful work. You completed <span x-text="totalCycles"></span> breathing cycles.</p>
                <p class="text-sm mb-8" style="color: var(--th-text-light);">Take a moment to notice how you feel.</p>
                <div class="flex items-center justify-center gap-3">
                    <button @click="reset()" class="btn-nature !px-8 !py-3 !rounded-2xl">New Session</button>
                    <a href="{{ route('modes.index') }}" class="btn-nature-outline !px-8 !py-3 !rounded-2xl">Back to Modes</a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Breathing circle smooth transitions */
    .breathe-main-circle {
        background-color: var(--circle-color, var(--th-primary));
        box-shadow: 0 0 var(--dynamic-glow) calc(var(--dynamic-glow)/2) var(--circle-color, var(--th-primary)), inset 0 0 40px rgba(255,255,255,0.2);
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1),
                    height 0.8s cubic-bezier(0.4, 0, 0.2, 1),
                    background-color 1.5s ease-in-out,
                    box-shadow 1.5s ease-in-out;
    }

    /* Ambient ring pulse */
    .breathe-ambient-ring {
        animation: ambient-pulse 4s ease-in-out infinite;
    }
    .breathe-ambient-ring-delayed {
        animation: ambient-pulse 4s ease-in-out infinite 2s;
    }
    @keyframes ambient-pulse {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.05); opacity: 0.6; }
    }

    /* Floating particles */
    .breathe-particle {
        position: absolute;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--th-primary);
        opacity: 0.15;
        left: var(--x);
        top: var(--y);
        animation: particle-float 15s ease-in-out infinite var(--delay);
    }
    @keyframes particle-float {
        0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.15; }
        25% { transform: translate(30px, -40px) scale(1.5); opacity: 0.25; }
        50% { transform: translate(-20px, -80px) scale(1); opacity: 0.1; }
        75% { transform: translate(40px, -30px) scale(1.3); opacity: 0.2; }
    }
</style>

<script>
function breatheApp() {
    return {
        // State
        isRunning: false,
        isPaused: false,
        isComplete: false,
        soundEnabled: true,

        // Pattern selection
        selectedPattern: 'relaxation',
        selectedDuration: 5,
        durations: [2, 5, 10],

        // Breathing patterns
        patterns: {
            relaxation: {
                name: '4-7-8 Relaxation',
                description: 'Activates the parasympathetic nervous system. Ideal for winding down and preparing for sleep.',
                inhale: 4, hold1: 7, exhale: 8, hold2: 0,
            },
            box: {
                name: 'Box Breathing',
                description: 'Used by Navy SEALs for focus and grounding. Equal phases create balance and clarity.',
                inhale: 4, hold1: 4, exhale: 4, hold2: 4,
            },
            energizing: {
                name: 'Energizing Breath',
                description: 'Quick, balanced breaths to boost alertness and energy. Perfect for a mid-day reset.',
                inhale: 3, hold1: 0, exhale: 3, hold2: 0,
            },
            calm: {
                name: 'Calm Down',
                description: 'Extended exhale activates your vagus nerve, reducing anxiety and heart rate naturally.',
                inhale: 4, hold1: 2, exhale: 6, hold2: 0,
            },
        },

        // Runtime state
        currentPhase: 'inhale', // inhale, hold1, exhale, hold2
        phaseTimeLeft: 0,
        currentCycle: 1,
        totalCycles: 0,
        totalTimeRemaining: 0,
        intervalId: null,
        audioCtx: null,

        // Circle sizes
        minSize: 100,
        maxSize: 220,

        get circleSize() {
            if (!this.isRunning && !this.isPaused) return 140;
            const pat = this.patterns[this.selectedPattern];
            const phaseTotal = this.getCurrentPhaseDuration();

            if (this.currentPhase === 'inhale') {
                const progress = 1 - (this.phaseTimeLeft / phaseTotal);
                return this.minSize + (this.maxSize - this.minSize) * progress;
            } else if (this.currentPhase === 'exhale') {
                const progress = this.phaseTimeLeft / phaseTotal;
                return this.minSize + (this.maxSize - this.minSize) * progress;
            } else if (this.currentPhase === 'hold1') {
                return this.maxSize;
            } else {
                return this.minSize;
            }
        },

        get glowSize() {
            const ratio = (this.circleSize - this.minSize) / (this.maxSize - this.minSize);
            return 20 + ratio * 40;
        },

        get dynamicColor() {
            const colors = {
                inhale: '#3b82f6', // Light Blue
                hold1: '#8b5cf6', // Violet
                exhale: '#10b981', // Emerald
                hold2: '#6366f1', // Indigo
            };
            return (this.isRunning || this.isPaused) ? colors[this.currentPhase] : 'var(--th-primary)';
        },

        get phaseLabel() {
            const labels = {
                inhale: 'Breathe In',
                hold1: 'Hold',
                exhale: 'Breathe Out',
                hold2: 'Hold',
            };
            return labels[this.currentPhase] || '';
        },

        init() {
            // Pre-calculate
        },

        getCurrentPhaseDuration() {
            const pat = this.patterns[this.selectedPattern];
            const map = { inhale: pat.inhale, hold1: pat.hold1, exhale: pat.exhale, hold2: pat.hold2 };
            return map[this.currentPhase] || 0;
        },

        getCycleLength() {
            const pat = this.patterns[this.selectedPattern];
            return pat.inhale + pat.hold1 + pat.exhale + pat.hold2;
        },

        start() {
            const cycleLen = this.getCycleLength();
            this.totalCycles = Math.max(1, Math.round((this.selectedDuration * 60) / cycleLen));
            this.currentCycle = 1;
            this.currentPhase = 'inhale';
            this.phaseTimeLeft = this.patterns[this.selectedPattern].inhale;
            this.totalTimeRemaining = this.selectedDuration * 60;
            this.isRunning = true;
            this.isPaused = false;
            this.isComplete = false;
            this.tick();
            this.playTransitionSound();
        },

        pause() {
            this.isRunning = false;
            this.isPaused = true;
            if (this.intervalId) { clearInterval(this.intervalId); this.intervalId = null; }
        },

        resume() {
            this.isRunning = true;
            this.isPaused = false;
            this.tick();
        },

        reset() {
            this.isRunning = false;
            this.isPaused = false;
            this.isComplete = false;
            this.currentPhase = 'inhale';
            this.phaseTimeLeft = 0;
            this.currentCycle = 1;
            this.totalTimeRemaining = 0;
            if (this.intervalId) { clearInterval(this.intervalId); this.intervalId = null; }
        },

        tick() {
            if (this.intervalId) clearInterval(this.intervalId);
            this.intervalId = setInterval(() => {
                if (!this.isRunning) return;

                this.phaseTimeLeft--;
                this.totalTimeRemaining--;

                if (this.phaseTimeLeft <= 0) {
                    this.advancePhase();
                }
            }, 1000);
        },

        advancePhase() {
            const pat = this.patterns[this.selectedPattern];
            const phases = ['inhale', 'hold1', 'exhale', 'hold2'];
            const durations = [pat.inhale, pat.hold1, pat.exhale, pat.hold2];

            let idx = phases.indexOf(this.currentPhase);
            // Move to next non-zero phase
            do {
                idx = (idx + 1) % 4;
                if (idx === 0) {
                    // Completed a full cycle
                    this.currentCycle++;
                    if (this.currentCycle > this.totalCycles) {
                        this.complete();
                        return;
                    }
                }
            } while (durations[idx] === 0);

            this.currentPhase = phases[idx];
            this.phaseTimeLeft = durations[idx];
            this.playTransitionSound();
        },

        complete() {
            this.isRunning = false;
            this.isPaused = false;
            this.isComplete = true;
            if (this.intervalId) { clearInterval(this.intervalId); this.intervalId = null; }
            this.playCompletionSound();
        },

        playTransitionSound() {
            if (!this.soundEnabled) return;
            try {
                if (!this.audioCtx) this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const ctx = this.audioCtx;
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(this.currentPhase === 'inhale' ? 440 : this.currentPhase === 'exhale' ? 330 : 392, ctx.currentTime);
                gain.gain.setValueAtTime(0.08, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.5);
            } catch(e) { /* silent fail */ }
        },

        playCompletionSound() {
            if (!this.soundEnabled) return;
            try {
                if (!this.audioCtx) this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const ctx = this.audioCtx;
                [440, 554, 659].forEach((freq, i) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime);
                    gain.gain.setValueAtTime(0, ctx.currentTime + i * 0.2);
                    gain.gain.linearRampToValueAtTime(0.1, ctx.currentTime + i * 0.2 + 0.05);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.2 + 1);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(ctx.currentTime + i * 0.2);
                    osc.stop(ctx.currentTime + i * 0.2 + 1);
                });
            } catch(e) { /* silent fail */ }
        },

        formatTime(seconds) {
            if (seconds <= 0) return '0:00';
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return m + ':' + (s < 10 ? '0' : '') + s;
        },
    };
}
</script>
@endsection
