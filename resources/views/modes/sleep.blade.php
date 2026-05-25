@extends('layouts.main')

@section('content')

{{-- ═══ Floating Particles Background ═══ --}}
<style>
    @keyframes float-particle-sleep {
        0%, 100% { transform: translateY(0) translateX(0) scale(1); opacity: 0; }
        10% { opacity: 0.4; }
        50% { transform: translateY(-30vh) translateX(15px) scale(1.1); opacity: 0.2; }
        90% { opacity: 0.05; }
        100% { transform: translateY(-60vh) translateX(-10px) scale(0.9); opacity: 0; }
    }
    @keyframes pulse-glow-sleep {
        0%, 100% { opacity: 0.15; transform: scale(1); }
        50% { opacity: 0.3; transform: scale(1.05); }
    }
    .particle-sleep {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        will-change: transform, opacity;
        background: #8b8be0;
    }
    .particle-s1 { width: 5px; height: 5px; bottom: 5%; left: 15%; animation: float-particle-sleep 15s ease-in-out infinite; animation-delay: 0s; opacity: 0.2; }
    .particle-s2 { width: 3px; height: 3px; bottom: 12%; left: 35%; animation: float-particle-sleep 18s ease-in-out infinite; animation-delay: 3s; background: #c5c5f0; opacity: 0.15; }
    .particle-s3 { width: 6px; height: 6px; bottom: 8%; left: 55%; animation: float-particle-sleep 14s ease-in-out infinite; animation-delay: 1s; opacity: 0.25; }
    .particle-s4 { width: 4px; height: 4px; bottom: 3%; left: 75%; animation: float-particle-sleep 17s ease-in-out infinite; animation-delay: 5s; background: #c5c5f0; opacity: 0.2; }
    .particle-s5 { width: 7px; height: 7px; bottom: 10%; left: 85%; animation: float-particle-sleep 16s ease-in-out infinite; animation-delay: 2s; opacity: 0.15; }

    .sleep-pill {
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .sleep-pill:hover {
        transform: translateY(-1px);
    }
    .instruction-fade {
        animation: fade-in-out 8s ease-in-out infinite;
    }
    @keyframes fade-in-out {
        0% { opacity: 0; transform: translateY(4px) scale(0.98); }
        15% { opacity: 1; transform: translateY(0) scale(1); }
        85% { opacity: 1; transform: translateY(0) scale(1); }
        100% { opacity: 0; transform: translateY(-4px) scale(1.02); }
    }
</style>

<section class="relative min-h-[calc(100vh-4rem)] flex items-center justify-center overflow-hidden py-8 px-4"
    style="background: radial-gradient(ellipse at bottom, #1b2735 0%, #090a0f 100%);"
    x-data="sleepApp()" x-init="init()">

    {{-- Starry Background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true" style="background-image: radial-gradient(white, rgba(255,255,255,.2) 2px, transparent 4px), radial-gradient(white, rgba(255,255,255,.15) 1px, transparent 3px), radial-gradient(white, rgba(255,255,255,.1) 2px, transparent 4px), radial-gradient(rgba(255,255,255,.4), rgba(255,255,255,.1) 2px, transparent 3px); background-size: 550px 550px, 350px 350px, 250px 250px, 150px 150px; background-position: 0 0, 40px 60px, 130px 270px, 70px 100px; animation: float-particle-sleep 120s linear infinite;">
    </div>

    {{-- ═══ Main Sleep Card ═══ --}}
    <div class="relative z-10 w-full max-w-xl flex flex-col" data-aos="fade-up" x-cloak>

        {{-- Navigation --}}
        <div class="mb-4 self-start">
            <button @click="isRunning ? stopSession(false) : window.location.href='{{ route('modes.index') }}'"
                    class="flex items-center gap-2 t-text opacity-80 hover:opacity-100 transition-opacity"
                    style="color: #a0a0c0;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span class="font-medium text-sm" x-text="isRunning ? 'Back to Setup' : 'Back to Modes'"></span>
            </button>
        </div>

        <div class="p-6 sm:p-10 rounded-3xl" style="background: rgba(25, 25, 45, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 20px 40px rgba(0,0,0,0.3);">

            {{-- Header --}}
            <div class="text-center mb-6" data-aos="fade-up" data-aos-delay="100">
                <h1 class="text-2xl sm:text-3xl mb-2 font-serif font-bold text-transparent bg-clip-text" style="background-image: linear-gradient(to right, #e0e0ff, #a0a0c0);">
                    Sleep
                </h1>
                <p class="text-sm sm:text-base" style="color: #9090b0;">Close your eyes and let the voice guide you.</p>
            </div>

            {{-- ═══ Setup Panel ═══ --}}
            <div x-show="!isRunning" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                {{-- Technique Selector --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color: #8080a0;">Technique</label>
                    <div class="flex flex-col gap-2">
                        <template x-for="(tech, key) in techniques" :key="key">
                            <button
                                @click="selectedTechnique = key"
                                class="sleep-pill text-left px-4 py-3 rounded-xl border w-full flex items-center justify-between"
                                :style="selectedTechnique === key
                                    ? 'background: rgba(139, 139, 224, 0.15); border-color: #8b8be0; color: #e0e0ff;'
                                    : 'background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.05); color: #a0a0c0;'">
                                <div>
                                    <span class="font-medium text-sm block mb-0.5" x-text="tech.label"></span>
                                    <span class="text-xs" style="color: #707090;" x-text="tech.description"></span>
                                </div>
                                <svg x-show="selectedTechnique === key" class="w-5 h-5" style="color: #8b8be0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Duration Selector --}}
                <div class="mb-6">
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color: #8080a0;">Duration</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="dur in durations" :key="dur">
                            <button
                                @click="selectedDuration = dur"
                                class="sleep-pill px-4 py-2 rounded-full text-sm font-medium border"
                                :style="selectedDuration === dur
                                    ? 'background: rgba(139, 139, 224, 0.2); border-color: #8b8be0; color: #e0e0ff;'
                                    : 'background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.05); color: #8080a0;'">
                                <span x-text="dur + ' min'"></span>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Voice & Sound Options --}}
                <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Voice Toggle --}}
                    <button @click="toggleVoice()"
                            class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl border text-sm font-medium transition-colors shadow-sm"
                            :style="voiceEnabled ? 'background: rgba(139,139,224,0.15); border-color: #8b8be0; color: #e0e0ff;' : 'background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.05); color: #8080a0;'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                        <span x-text="voiceEnabled ? 'Voice Guidance: ON' : 'Voice Guidance: OFF'"></span>
                    </button>
                    
                    {{-- Sound Toggle & Selector --}}
                    <div class="flex flex-col gap-2">
                        <button @click="toggleSound()"
                                class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl border text-sm font-medium transition-colors shadow-sm"
                                :style="soundEnabled ? 'background: rgba(139,139,224,0.15); border-color: #8b8be0; color: #e0e0ff;' : 'background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.05); color: #8080a0;'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                            <span x-text="soundEnabled ? 'Background Sound: ON' : 'Background Sound: OFF'"></span>
                        </button>
                        
                        <select x-show="soundEnabled" x-model="selectedSound" @change="changeSound()"
                                class="w-full bg-black/30 border border-white/10 rounded-xl px-3 py-2 text-sm text-[#e0e0ff] focus:outline-none focus:border-[#8b8be0] transition-colors appearance-none cursor-pointer text-center">
                            <template x-for="(audio, key) in ambientSounds" :key="key">
                                <option :value="key" x-text="audio.label"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Start Button --}}
                <button
                    @click="startSession()"
                    class="w-full text-center text-base py-3.5 rounded-xl font-medium transition-all duration-300 hover:shadow-lg flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #5b5b9a, #4a4a8a); color: white; border: 1px solid #6c5b7b;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    Begin Wind Down
                </button>
            </div>

            {{-- ═══ Active Session Panel ═══ --}}
            <div x-show="isRunning" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="py-8">

                <div class="text-center mb-8 flex flex-col items-center justify-center min-h-[120px]">
                    <div class="text-3xl sm:text-4xl font-serif text-white tracking-wider instruction-fade"
                         :key="currentWord"
                         x-text="currentWord">
                    </div>
                </div>

                <div class="text-center mb-8">
                    <p class="text-xs tracking-widest uppercase mb-2" style="color: #606080;">Time Remaining</p>
                    <div class="text-2xl font-light" style="color: #a0a0c0;" x-text="formatTime(remainingSeconds)"></div>
                </div>

                {{-- Controls --}}
                <div class="flex flex-col items-center justify-center gap-6">
                    
                    {{-- Active Session Audio Toggles --}}
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <button @click="toggleVoice()"
                                class="flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg border text-xs font-medium transition-colors"
                                :style="voiceEnabled ? 'background: rgba(139,139,224,0.15); border-color: #8b8be0; color: #e0e0ff;' : 'background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.05); color: #8080a0;'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                            <span x-text="voiceEnabled ? 'Voice: ON' : 'Voice: OFF'"></span>
                        </button>
                        
                        <div class="flex items-center gap-1.5">
                            <button @click="toggleSound()"
                                    class="flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg border text-xs font-medium transition-colors"
                                    :style="soundEnabled ? 'background: rgba(139,139,224,0.15); border-color: #8b8be0; color: #e0e0ff;' : 'background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.05); color: #8080a0;'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                                <span x-text="soundEnabled ? 'Sound: ON' : 'Sound: OFF'"></span>
                            </button>
                            <select x-show="soundEnabled" x-model="selectedSound" @change="changeSound()"
                                    class="bg-black/30 border border-white/10 rounded-lg px-2 py-1 text-xs text-[#e0e0ff] focus:outline-none focus:border-[#8b8be0] transition-colors appearance-none cursor-pointer">
                                <template x-for="(audio, key) in ambientSounds" :key="key">
                                    <option :value="key" x-text="audio.label"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <button
                        @click="stopSession()"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-colors duration-300 hover:bg-white/10"
                        style="background: rgba(255,255,255,0.05); color: #a0a0c0; border: 1px solid rgba(255,255,255,0.1);">
                        End Session Early
                    </button>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
function sleepApp() {
    return {
        // State
        isRunning: false,
        selectedTechnique: 'shuffle',
        selectedDuration: 15,
        durations: [10, 15, 20, 30],

        // Timer
        totalSeconds: 0,
        remainingSeconds: 0,
        timerInterval: null,

        // Content
        currentWord: '',
        wordInterval: null,
        voiceEnabled: true,
        soundEnabled: true,
        selectedSound: 'drone',
        ambientSounds: {
            drone: { label: 'Deep Sleep Drone', generator: 'brownNoise' },
            rain: { label: 'Gentle Rain', generator: 'externalAudio', url: '/audio/light-rain.mp3' },
            ocean: { label: 'Ocean Waves', generator: 'externalAudio', url: '/audio/ocean-waves.mp3' },
            forest: { label: 'Night Forest', generator: 'externalAudio', url: '/audio/forest.mp3' },
            crickets: { label: 'Crickets', generator: 'crickets' },
            bowls: { label: 'Tibetan Bowls', generator: 'externalAudio', url: '/audio/tibetan-bowl.mp3' },
            binaural: { label: 'Ethereal Pad', generator: 'binauralBeats' },
            lullaby: { label: 'Sleep Lullaby', generator: 'externalAudio', url: '/audio/sleep-lullaby.mp3' },
            asmr: { label: 'Sleep Rain ASMR', generator: 'externalAudio', url: '/audio/sleep-rain-asmr.mp3' },
            ambient_forest: { label: 'Ambient Forest Rain', generator: 'externalAudio', url: '/audio/ambient-forest-rain.mp3' },
        },

        // Audio
        audioCtx: null,
        droneOscillator: null,
        droneGain: null,

        techniques: {
            shuffle: {
                label: 'Cognitive Shuffle',
                description: 'Random neutral words to scramble thoughts and induce sleep.',
            },
            pmr: {
                label: 'Progressive Muscle Relaxation',
                description: 'Guided focus through muscle groups to release physical tension.',
            }
        },

        shuffleWords: [
            'Apple', 'Blanket', 'Cloud', 'Desk', 'Elephant', 'Forest', 'Guitar', 'House', 'Island', 'Jacket',
            'Kite', 'Lantern', 'Mountain', 'Notebook', 'Ocean', 'Pencil', 'Quilt', 'River', 'Star', 'Train',
            'Umbrella', 'Valley', 'Window', 'Yarn', 'Zebra', 'Breeze', 'Candle', 'Dawn', 'Echo', 'Feather',
            'Garden', 'Horizon', 'Ivy', 'Journey', 'Leaf', 'Meadow', 'Nest', 'Owl', 'Pebble', 'Quiet'
        ],

        pmrSteps: [
            'Tense the muscles in your feet... hold... and release.',
            'Tense your calves... hold... and completely relax.',
            'Tense your thighs and hips... hold... let it go.',
            'Tense your stomach and chest... hold... breathe out and release.',
            'Pull your shoulders up to your ears... hold... drop them down.',
            'Tense your arms and clench your fists... hold... let them fall loose.',
            'Scrunch the muscles in your face... hold... and soften your face.',
            'Your whole body is now heavy and relaxed.',
            'Breathe naturally. Let yourself drift.'
        ],

        init() {
            // Setup logic if needed
        },

        startSession() {
            this.totalSeconds = this.selectedDuration * 60;
            this.remainingSeconds = this.totalSeconds;
            this.isRunning = true;
            this.currentWord = 'Close your eyes and breathe...';
            this.speak(this.currentWord);

            if (this.soundEnabled) {
                this.startAmbientSound();
            }

            // Timer
            this.timerInterval = setInterval(() => {
                this.remainingSeconds--;
                if (this.remainingSeconds <= 0) {
                    this.stopSession(true);
                }
            }, 1000);

            // First transition after 6 seconds
            setTimeout(() => {
                if (!this.isRunning) return;
                this.cycleContent();
                // Then every 8 seconds
                this.wordInterval = setInterval(() => {
                    this.cycleContent();
                }, 8000);
            }, 6000);
        },

        speak(text) {
            if ('speechSynthesis' in window && this.voiceEnabled) {
                window.speechSynthesis.cancel(); // cancel previous
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.rate = 0.8; // Slow
                utterance.pitch = 0.9; // Deep, relaxing
                utterance.volume = 0.6;
                window.speechSynthesis.speak(utterance);
            }
        },

        cycleContent() {
            if (this.selectedTechnique === 'shuffle') {
                const randIndex = Math.floor(Math.random() * this.shuffleWords.length);
                this.currentWord = this.shuffleWords[randIndex];
            } else {
                // PMR
                if (!this._pmrIndex) this._pmrIndex = 0;
                this.currentWord = this.pmrSteps[this._pmrIndex];
                this._pmrIndex = (this._pmrIndex + 1) % this.pmrSteps.length;
            }
            this.speak(this.currentWord);
        },

        stopSession(completed = false) {
            this.isRunning = false;
            clearInterval(this.timerInterval);
            clearInterval(this.wordInterval);
            this.stopAmbientSound();
            this._pmrIndex = 0;
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }

            if (completed) {
                // Return to menu silently
            }
        },

        formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        },

        toggleVoice() {
            this.voiceEnabled = !this.voiceEnabled;
            if (!this.voiceEnabled && 'speechSynthesis' in window) {
                window.speechSynthesis.cancel();
            }
        },

        toggleSound() {
            this.soundEnabled = !this.soundEnabled;
            if (this.isRunning) {
                if (this.soundEnabled) {
                    this.startAmbientSound();
                } else {
                    this.stopAmbientSound();
                }
            }
        },

        changeSound() {
            if (this.isRunning && this.soundEnabled) {
                this.stopAmbientSound();
                this.startAmbientSound();
            }
        },

        ensureAudioContext() {
            if (!this.audioCtx) {
                this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                this.masterGain = this.audioCtx.createGain();
                this.masterGain.connect(this.audioCtx.destination);
            }
            if (this.audioCtx.state === 'suspended') {
                this.audioCtx.resume();
            }
        },

        startAmbientSound() {
            this.ensureAudioContext();
            this.stopAmbientSound(); // Ensure old stops
            
            const generatorName = this.ambientSounds[this.selectedSound].generator;
            const url = this.ambientSounds[this.selectedSound].url;
            if (!generatorName || !window.SoundscapeGenerators[generatorName]) return;

            this.ambientGain = this.audioCtx.createGain();
            this.ambientGain.gain.value = 0.05; // Fade in volume
            this.ambientGain.connect(this.masterGain);

            this.ambientGenerator = window.SoundscapeGenerators[generatorName](this.audioCtx, this.ambientGain, url);

            // Fade up
            this.ambientGain.gain.setTargetAtTime(0.4, this.audioCtx.currentTime, 2);
        },

        stopAmbientSound() {
            if (this.ambientGenerator && this.ambientGenerator.stop) {
                this.ambientGenerator.stop();
            }
            if (this.ambientGain) {
                this.ambientGain.disconnect();
            }
            this.ambientGenerator = null;
            this.ambientGain = null;
        }
    };
}
</script>
@endsection
