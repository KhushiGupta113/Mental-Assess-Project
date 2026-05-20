@extends('layouts.main')

@section('content')
<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-8 px-4 bg-hero-gradient">
    <div class="w-full max-w-2xl" x-data="{
        step: 1,
        totalSteps: 4,
        age_group: '',
        gender: '',
        country: '',
        occupation: '',
        concerns: [],
        avatar: '🍃',

        avatars: ['🍃', '🌸', '🌊', '☀️', '🌙', '⭐', '🦊', '🐻', '🐼', '🐨', '🦁', '🐯', '🐶', '🐱', '🐰', '🦉', '🦋', '🍄', '🌻'],

        toggleConcern(c) {
            const idx = this.concerns.indexOf(c);
            if (idx > -1) this.concerns.splice(idx, 1);
            else if (this.concerns.length < 5) this.concerns.push(c);
        },

        canProceed() {
            switch(this.step) {
                case 1: return this.age_group && this.gender;
                case 2: return this.country.length >= 2;
                case 3: return this.concerns.length >= 1;
                case 4: return true;
                default: return false;
            }
        }
    }">
        {{-- Progress --}}
        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <span class="text-xs font-medium t-muted">Step <span x-text="step"></span> of 4</span>
                <span class="text-xs t-light" x-text="Math.round((step / totalSteps) * 100) + '% complete'"></span>
            </div>
            <div class="w-full bg-sage-200/50 rounded-full h-1.5">
                <div class="bg-gradient-to-r from-sage-400 to-teal-500 h-1.5 rounded-full transition-all duration-500" :style="'width:' + ((step / totalSteps) * 100) + '%'"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('onboarding.store') }}">
            @csrf
            <input type="hidden" name="age_group" x-model="age_group">
            <input type="hidden" name="gender" x-model="gender">
            <input type="hidden" name="country" x-model="country">
            <input type="hidden" name="occupation" x-model="occupation">
            <input type="hidden" name="avatar" x-model="avatar">
            <template x-for="c in concerns" :key="c">
                <input type="hidden" name="concerns[]" :value="c">
            </template>

            <div class="glass-card-solid overflow-hidden">

                {{-- ═══ Step 1: About You ═══ --}}
                <div x-show="step === 1" x-transition x-cloak class="p-8 md:p-10">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sage-400 to-teal-500 flex items-center justify-center text-3xl mx-auto mb-4">👋</div>
                        <h2 class="text-2xl md:text-3xl font-serif font-bold t-text mb-2">Welcome to MindAssess</h2>
                        <p class="t-muted text-sm">Let's personalize your wellness journey. This takes less than a minute.</p>
                    </div>

                    <div class="space-y-5 max-w-md mx-auto">
                        <div>
                            <label class="block text-sm font-semibold t-text mb-2">Age Group</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach(['under_18' => 'Under 18', '18_24' => '18-24', '25_34' => '25-34', '35_44' => '35-44', '45_54' => '45-54', '55_plus' => '55+'] as $val => $label)
                                <button type="button" @click="age_group = '{{ $val }}'"
                                    :class="age_group === '{{ $val }}' ? 'border-sage-500 t-surface ring-2 ring-sage-200' : 'border-th-border hover:border-th-border-strong'"
                                    class="py-2.5 px-3 rounded-xl border-2 text-sm font-medium t-text transition-all text-center">{{ $label }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold t-text mb-2">Gender</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach(['male' => '♂️ Male', 'female' => '♀️ Female', 'non_binary' => '⚧️ Non-binary', 'prefer_not_to_say' => '🤐 Prefer not to say'] as $val => $label)
                                <button type="button" @click="gender = '{{ $val }}'"
                                    :class="gender === '{{ $val }}' ? 'border-sage-500 t-surface ring-2 ring-sage-200' : 'border-th-border hover:border-th-border-strong'"
                                    class="py-2.5 px-3 rounded-xl border-2 text-sm font-medium t-text transition-all text-center">{{ $label }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ═══ Step 2: Location ═══ --}}
                <div x-show="step === 2" x-transition x-cloak class="p-8 md:p-10">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center text-3xl mx-auto mb-4">🌍</div>
                        <h2 class="text-2xl font-serif font-bold t-text mb-2">Where are you located?</h2>
                        <p class="t-muted text-sm">This helps us suggest relevant crisis helplines and local mental health resources.</p>
                    </div>

                    <div class="space-y-5 max-w-md mx-auto">
                        <div>
                            <label class="block text-sm font-semibold t-text mb-2">Country</label>
                            <select x-model="country" class="input-nature">
                                <option value="">Select your country...</option>
                                <option value="India">🇮🇳 India</option>
                                <option value="USA">🇺🇸 United States</option>
                                <option value="UK">🇬🇧 United Kingdom</option>
                                <option value="Canada">🇨🇦 Canada</option>
                                <option value="Australia">🇦🇺 Australia</option>
                                <option value="Germany">🇩🇪 Germany</option>
                                <option value="France">🇫🇷 France</option>
                                <option value="Japan">🇯🇵 Japan</option>
                                <option value="Brazil">🇧🇷 Brazil</option>
                                <option value="South Africa">🇿🇦 South Africa</option>
                                <option value="Nigeria">🇳🇬 Nigeria</option>
                                <option value="Pakistan">🇵🇰 Pakistan</option>
                                <option value="Bangladesh">🇧🇩 Bangladesh</option>
                                <option value="Philippines">🇵🇭 Philippines</option>
                                <option value="Mexico">🇲🇽 Mexico</option>
                                <option value="Other">🌐 Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold t-text mb-2">Occupation <span class="t-light font-normal">(optional)</span></label>
                            <select x-model="occupation" class="input-nature">
                                <option value="">Select...</option>
                                <option value="student">📚 Student</option>
                                <option value="employed">💼 Employed</option>
                                <option value="self_employed">🏠 Self-employed</option>
                                <option value="unemployed">🔍 Looking for work</option>
                                <option value="homemaker">🏡 Homemaker</option>
                                <option value="retired">🌅 Retired</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="bg-teal-50 border border-teal-100 rounded-xl p-4 flex items-start gap-3">
                            <span class="text-lg mt-0.5">🔒</span>
                            <p class="text-xs text-teal-700">Your location is used <strong>only</strong> to personalize helpline recommendations. It is never shared with third parties.</p>
                        </div>
                    </div>
                </div>

                {{-- ═══ Step 3: Concerns ═══ --}}
                <div x-show="step === 3" x-transition x-cloak class="p-8 md:p-10">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-3xl mx-auto mb-4">💭</div>
                        <h2 class="text-2xl font-serif font-bold t-text mb-2">What brings you here?</h2>
                        <p class="t-muted text-sm">Select your primary areas of concern. We'll tailor your experience accordingly.</p>
                        <p class="text-xs t-light mt-1">Select 1 to 5 topics</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 max-w-lg mx-auto">
                        @php
                        $concernOptions = [
                            'anxiety' => ['icon' => '😰', 'label' => 'Anxiety'],
                            'depression' => ['icon' => '💙', 'label' => 'Depression'],
                            'stress' => ['icon' => '🔥', 'label' => 'Stress'],
                            'sleep' => ['icon' => '🌙', 'label' => 'Sleep Issues'],
                            'burnout' => ['icon' => '😮‍💨', 'label' => 'Burnout'],
                            'adhd' => ['icon' => '⚡', 'label' => 'ADHD/Focus'],
                            'relationships' => ['icon' => '💛', 'label' => 'Relationships'],
                            'self_esteem' => ['icon' => '🪞', 'label' => 'Self-Esteem'],
                            'grief' => ['icon' => '🕊️', 'label' => 'Grief / Loss'],
                            'loneliness' => ['icon' => '🧍', 'label' => 'Loneliness'],
                            'anger' => ['icon' => '😤', 'label' => 'Anger'],
                            'trauma' => ['icon' => '🛡️', 'label' => 'Trauma / PTSD'],
                        ];
                        @endphp

                        @foreach($concernOptions as $key => $opt)
                        <button type="button" @click="toggleConcern('{{ $key }}')"
                            :class="concerns.includes('{{ $key }}') ? 'border-indigo-400 bg-indigo-50 ring-2 ring-indigo-200 shadow-sm' : 'border-th-border hover:border-th-border-strong hover:t-surface/50'"
                            class="flex items-center gap-2 p-3 rounded-xl border-2 text-left transition-all">
                            <span class="text-xl">{{ $opt['icon'] }}</span>
                            <span class="text-sm font-medium t-text">{{ $opt['label'] }}</span>
                        </button>
                        @endforeach
                    </div>

                    <p class="text-center text-xs t-light mt-4"><span x-text="concerns.length"></span> / 5 selected</p>
                </div>

                {{-- ═══ Step 4: Avatar & Summary ═══ --}}
                <div x-show="step === 4" x-transition x-cloak class="p-8 md:p-10">
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-sage-100 to-teal-100 flex items-center justify-center text-4xl mx-auto mb-4 ring-4 ring-sage-200/50" x-text="avatar"></div>
                        <h2 class="text-2xl font-serif font-bold t-text mb-2">Choose Your Avatar</h2>
                        <p class="t-muted text-sm">Pick an emoji that represents you on this journey.</p>
                    </div>

                    <div class="flex flex-wrap justify-center gap-2 max-w-sm mx-auto mb-8">
                        <template x-for="a in avatars" :key="a">
                            <button type="button" @click="avatar = a"
                                :class="avatar === a ? 'ring-2 ring-sage-400 t-surface scale-110' : 'hover:scale-110 opacity-70 hover:opacity-100'"
                                class="w-10 h-10 rounded-full bg-th-surface-alt flex items-center justify-center text-xl transition-all"
                                x-text="a"></button>
                        </template>
                    </div>

                    {{-- Summary --}}
                    <div class="t-surface rounded-xl p-5 max-w-sm mx-auto space-y-2">
                        <h3 class="text-sm font-semibold t-text mb-3 text-center">Your Profile Summary</h3>
                        <div class="flex justify-between text-sm">
                            <span class="t-muted">Age Group</span>
                            <span class="font-medium t-text" x-text="age_group.replace('_', '-').replace('plus', '+')"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="t-muted">Gender</span>
                            <span class="font-medium t-text" x-text="gender.replace('_', ' ')"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="t-muted">Country</span>
                            <span class="font-medium t-text" x-text="country"></span>
                        </div>
                        <div class="flex justify-between text-sm" x-show="occupation">
                            <span class="t-muted">Occupation</span>
                            <span class="font-medium t-text" x-text="occupation.replace('_', ' ')"></span>
                        </div>
                        <div class="pt-2 border-t border-th-border-strong">
                            <span class="text-xs t-muted">Concerns:</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                <template x-for="c in concerns" :key="c">
                                    <span class="badge-indigo text-[10px]" x-text="c.replace('_', ' ')"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="t-surface/60 px-6 py-4 flex justify-between items-center border-t border-th-border">
                    <div>
                        <button type="button" @click="step--" x-show="step > 1"
                            class="inline-flex items-center t-muted hover:t-text font-medium text-sm transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Back
                        </button>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="step++" x-show="step < totalSteps" :disabled="!canProceed()"
                            :class="canProceed() ? '' : 'opacity-50 cursor-not-allowed'"
                            class="btn-teal !py-2.5 !px-6 !text-sm !rounded-xl">
                            Continue
                            <svg class="w-4 h-4 ml-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <button type="submit" x-show="step === totalSteps"
                            class="btn-nature !py-2.5 !px-6 !text-sm !rounded-xl">
                            Start My Journey 🌱
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

