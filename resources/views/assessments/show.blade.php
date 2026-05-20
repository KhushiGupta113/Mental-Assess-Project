@extends('layouts.main')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12" data-aos="fade-in">
    <div class="mb-6 text-center">
        <a href="{{ route('assessments.index') }}" class="inline-flex items-center text-sm font-medium t-muted hover:t-text mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Assessments
        </a>
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-3xl bg-{{ $assessment->color ?? 'sage' }}-100 flex items-center justify-center text-{{ $assessment->color ?? 'sage' }}-600 shadow-sm border border-{{ $assessment->color ?? 'sage' }}-200">
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
        </div>
        <h1 class="text-2xl md:text-3xl font-bold font-serif t-text mb-1">{{ $assessment->title }}</h1>
        <p class="t-muted text-sm max-w-lg mx-auto">{{ $assessment->description }}</p>
        <div class="flex justify-center gap-3 mt-3">
            <span class="badge-nature text-xs">{{ count($assessment->questions) }} questions</span>
            <span class="badge-teal text-xs">~{{ $assessment->estimated_minutes ?? 5 }} min</span>
        </div>
        <div class="mt-3 bg-amber-50 text-amber-700 px-3 py-1.5 rounded-lg text-xs inline-block font-medium">⚠️ Not a medical diagnosis system.</div>
    </div>

    <div class="glass-card-solid overflow-hidden"
         x-data="{
            step: 1,
            totalSteps: {{ count($assessment->questions) }},
            answers: {},
            advancing: false,
            nextStep() { if (this.step < this.totalSteps) this.step++ },
            prevStep() { if (this.step > 1) this.step-- },
            selectAnswer(questionId, score) {
                this.answers[questionId] = score;
                // Auto-advance with debounce to prevent double-fire
                if (this.step < this.totalSteps && !this.advancing) {
                    this.advancing = true;
                    setTimeout(() => {
                        this.nextStep();
                        this.advancing = false;
                    }, 400);
                }
            },
            isAnswered(questionId, score) {
                return this.answers[questionId] === score;
            },
            get allAnswered() {
                return Object.keys(this.answers).length === this.totalSteps;
            }
         }">

        {{-- Progress Bar --}}
        <div class="w-full h-1.5" style="background:var(--th-primary-light)">
            <div class="h-1.5 transition-all duration-500 rounded-r-full" style="background:linear-gradient(to right, var(--th-primary), var(--th-accent))" :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
        </div>

        <form action="{{ route('assessments.store', $assessment) }}" method="POST">
            @csrf
            <div class="px-6 py-8 md:px-10 md:py-10">
                @foreach($assessment->questions as $index => $question)
                <div x-show="step === {{ $index + 1 }}"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    <div class="max-w-xl mx-auto">
                        <div class="text-center mb-6">
                            <span class="badge-nature mb-2 inline-block text-xs">Question {{ $index + 1 }} of {{ count($assessment->questions) }}</span>
                            <h2 class="text-lg md:text-xl font-serif t-text leading-relaxed">{{ $question->text }}</h2>
                        </div>

                        <div class="space-y-2.5">
                            @foreach($question->options as $option)
                            <label class="flex items-center p-3.5 md:p-4 border-2 rounded-xl cursor-pointer transition-all duration-200"
                                   :class="isAnswered('{{ $question->_id }}', {{ $option['score'] }})
                                        ? 'shadow-sm'
                                        : ''"
                                   :style="isAnswered('{{ $question->_id }}', {{ $option['score'] }})
                                        ? 'border-color:var(--th-primary); background:var(--th-primary-light)'
                                        : 'border-color:var(--th-border)'"
                                   @click.prevent="selectAnswer('{{ $question->_id }}', {{ $option['score'] }})">
                                <input type="radio"
                                       name="answers[{{ $question->_id }}]"
                                       value="{{ $option['score'] }}"
                                       class="w-4 h-4 border-2 focus:ring-2"
                                       style="color:var(--th-primary); border-color:var(--th-border-strong); --tw-ring-color:var(--th-primary-light)"
                                       :checked="isAnswered('{{ $question->_id }}', {{ $option['score'] }})"
                                       required>
                                <span class="ml-3 text-sm md:text-base font-medium t-text select-none">{{ $option['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Navigation Footer --}}
            <div class="t-surface/60 px-6 py-4 flex justify-between items-center border-t border-th-border">
                <div>
                    <button type="button" @click="prevStep()" x-show="step > 1"
                            class="inline-flex items-center t-muted hover:t-text font-medium text-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Previous
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs t-light hidden sm:inline" x-text="Math.round((step / totalSteps) * 100) + '% complete'"></span>

                    <button type="button" @click="nextStep()" x-show="step < totalSteps"
                            class="btn-teal !py-2.5 !px-5 !text-sm !rounded-xl">
                        Next
                        <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>

                    <button type="submit" x-show="step === totalSteps"
                            class="btn-nature !py-2.5 !px-5 !text-sm !rounded-xl">
                        Submit Assessment
                        <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

