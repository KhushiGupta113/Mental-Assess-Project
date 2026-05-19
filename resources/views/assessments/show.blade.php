@extends('layouts.main')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12" data-aos="fade-in">
    <div class="mb-6 text-center">
        <a href="{{ route('assessments.index') }}" class="inline-flex items-center text-sm font-medium text-sage-500 hover:text-sage-700 mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Assessments
        </a>
        <div class="flex justify-center mb-3">
            <div class="w-14 h-14 rounded-2xl bg-{{ $assessment->color ?? 'sage' }}-100 flex items-center justify-center text-2xl">{{ $assessment->icon ?? '💚' }}</div>
        </div>
        <h1 class="text-2xl md:text-3xl font-bold font-serif text-sage-800 mb-1">{{ $assessment->title }}</h1>
        <p class="text-sage-500 text-sm max-w-lg mx-auto">{{ $assessment->description }}</p>
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
            nextStep() { if (this.step < this.totalSteps) this.step++ },
            prevStep() { if (this.step > 1) this.step-- },
            selectAnswer(questionId, score) {
                this.answers[questionId] = score;
                if (this.step < this.totalSteps) {
                    setTimeout(() => this.nextStep(), 300);
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
        <div class="w-full bg-sage-100 h-1.5">
            <div class="bg-gradient-to-r from-sage-400 to-teal-500 h-1.5 transition-all duration-500 rounded-r-full" :style="'width: ' + ((step / totalSteps) * 100) + '%'"></div>
        </div>

        <form action="{{ route('assessments.store', $assessment) }}" method="POST">
            @csrf
            {{-- Question Container — NOT absolute positioned, uses show/hide --}}
            <div class="px-6 py-8 md:px-10 md:py-10">
                @foreach($assessment->questions as $index => $question)
                <div x-show="step === {{ $index + 1 }}"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak>
                    <div class="max-w-xl mx-auto">
                        {{-- Question Header --}}
                        <div class="text-center mb-6">
                            <span class="badge-nature mb-2 inline-block text-xs">Question {{ $index + 1 }} of {{ count($assessment->questions) }}</span>
                            <h2 class="text-lg md:text-xl font-serif text-sage-800 leading-relaxed">{{ $question->text }}</h2>
                        </div>

                        {{-- Options --}}
                        <div class="space-y-2.5">
                            @foreach($question->options as $option)
                            <label class="flex items-center p-3.5 md:p-4 border-2 rounded-xl cursor-pointer transition-all duration-200"
                                   :class="isAnswered('{{ $question->_id }}', {{ $option['score'] }})
                                        ? 'border-sage-500 bg-sage-50 shadow-sm'
                                        : 'border-sage-100 hover:bg-sage-50/50 hover:border-sage-200'"
                                   @click="selectAnswer('{{ $question->_id }}', {{ $option['score'] }})">
                                <input type="radio"
                                       name="answers[{{ $question->_id }}]"
                                       value="{{ $option['score'] }}"
                                       class="w-4 h-4 text-sage-600 border-sage-300 focus:ring-sage-400"
                                       :checked="isAnswered('{{ $question->_id }}', {{ $option['score'] }})"
                                       required>
                                <span class="ml-3 text-sm md:text-base font-medium text-sage-700 select-none">{{ $option['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Navigation Footer --}}
            <div class="bg-sage-50/60 px-6 py-4 flex justify-between items-center border-t border-sage-100">
                <div>
                    <button type="button" @click="prevStep()" x-show="step > 1"
                            class="inline-flex items-center text-sage-500 hover:text-sage-700 font-medium text-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Previous
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-sage-400 hidden sm:inline" x-text="Math.round((step / totalSteps) * 100) + '% complete'"></span>

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
