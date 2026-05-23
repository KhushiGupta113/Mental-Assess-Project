<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="nature" data-color-theme="sage">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="AI-assisted emotional wellness companion with clinically inspired self-assessments and personalized support guidance.">

        <title>{{ config('app.name', 'MindAssess') }} — Wellness Companion</title>

        {{-- Prevent FOUC: apply dark/theme immediately --}}
        <script>
            (function(){
                const dm = localStorage.getItem('ma_dark');
                const ct = localStorage.getItem('ma_color_theme') || 'sage';
                if(dm === 'true') document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-color-theme', ct);
            })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|lora:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

        <!-- AOS -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <!-- Marked.js for chatbot markdown -->
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased min-h-screen flex flex-col" style="background:var(--th-bg);color:var(--th-text)">

        {{-- ═══ Navigation ═══ --}}
        <nav class="sticky top-0 z-50 backdrop-blur-lg border-b" style="background:var(--th-nav-bg);border-color:var(--th-border)" x-data="{ mobileOpen: false, themePicker: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-all duration-300 group-hover:scale-105" style="background:linear-gradient(135deg, var(--th-primary), var(--th-accent))">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <span class="text-lg font-serif font-bold t-text">MindAssess</span>
                    </a>

                    {{-- Desktop Nav --}}
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('assessments.index') }}" class="nav-link px-3 py-2 rounded-lg {{ request()->routeIs('assessments.*') ? 'nav-link-active' : '' }}">Assessments</a>
                        <a href="{{ route('resources.index') }}" class="nav-link px-3 py-2 rounded-lg {{ request()->routeIs('resources.*') ? 'nav-link-active' : '' }}">Resources</a>
                        <a href="{{ route('about') }}" class="nav-link px-3 py-2 rounded-lg {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">About</a>
                        <a href="{{ route('crisis.index') }}" class="text-red-500 hover:text-red-600 font-semibold text-sm px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">Crisis Help</a>
                    </div>

                    {{-- Theme & Auth --}}
                    <div class="hidden md:flex items-center space-x-3">
                        {{-- Dark Mode Toggle --}}
                        <button onclick="toggleDarkMode()" class="p-2 rounded-xl t-muted hover:t-surface dark:hover:bg-th-primary-dark transition-colors" title="Toggle Dark Mode">
                            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        </button>

                        {{-- Theme Picker --}}
                        <div class="relative" x-data="{ themeOpen: false }">
                            <button @click="themeOpen = !themeOpen" class="p-2 rounded-xl t-muted hover:t-surface dark:hover:bg-th-primary-dark transition-colors" title="Choose Theme">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            </button>
                            <div x-show="themeOpen" @click.away="themeOpen = false" x-transition class="absolute right-0 mt-2 p-3 t-card dark:bg-th-primary-dark rounded-xl shadow-lift border border-th-border dark:border-gray-700 z-50 flex gap-2">
                                <button onclick="setTheme('sage')" class="w-6 h-6 rounded-full bg-[#697a59] hover:scale-110 transition-transform" title="Sage Garden"></button>
                                <button onclick="setTheme('lavender')" class="w-6 h-6 rounded-full bg-[#7c6fae] hover:scale-110 transition-transform" title="Lavender Dream"></button>
                                <button onclick="setTheme('rose')" class="w-6 h-6 rounded-full bg-[#c06c84] hover:scale-110 transition-transform" title="Rose Garden"></button>
                                <button onclick="setTheme('ocean')" class="w-6 h-6 rounded-full bg-[#4a90a4] hover:scale-110 transition-transform" title="Ocean Breeze"></button>
                                <button onclick="setTheme('peach')" class="w-6 h-6 rounded-full bg-[#d98a6a] hover:scale-110 transition-transform" title="Sunset Peach"></button>
                            </div>
                        </div>

                        <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 mx-1"></div>

                        @auth
                            <a href="{{ route('dashboard') }}" class="nav-link px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                            <a href="{{ route('journal.index') }}" class="nav-link px-3 py-2 rounded-lg {{ request()->routeIs('journal.*') ? 'nav-link-active' : '' }}">Journal</a>
                            <a href="{{ route('mood.index') }}" class="nav-link px-3 py-2 rounded-lg {{ request()->routeIs('mood.*') ? 'nav-link-active' : '' }}">Mood</a>

                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 px-3 py-1.5 rounded-xl t-surface border border-transparent hover:border-nature transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-th-primary-light text-th-primary-dark flex items-center justify-center">
                                        <x-avatar :type="Auth::user()->avatar ?? 'leaf'" class="w-5 h-5" />
                                    </div>
                                    <span class="text-sm font-medium t-text">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 t-muted" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7l5 5 5-5"/></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 t-card rounded-xl shadow-lift border border-nature py-2 z-50">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm t-text hover:bg-black/5 dark:hover:t-card/5">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm t-text hover:bg-black/5 dark:hover:t-card/5">Log Out</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="nav-link px-3 py-2">Log in</a>
                            <a href="{{ route('register') }}" class="btn-nature text-sm !py-2 !px-5 !rounded-xl">Start Journey</a>
                        @endauth
                    </div>

                    {{-- Mobile Hamburger --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:t-surface">
                        <svg class="w-6 h-6 t-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div x-show="mobileOpen" x-transition class="md:hidden t-card border-t border-th-border px-4 py-4 space-y-2">
                <a href="{{ route('assessments.index') }}" class="block px-3 py-2 rounded-lg t-text hover:t-surface">Assessments</a>
                <a href="{{ route('resources.index') }}" class="block px-3 py-2 rounded-lg t-text hover:t-surface">Resources</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg t-text hover:t-surface">About</a>
                <a href="{{ route('crisis.index') }}" class="block px-3 py-2 rounded-lg text-red-500 font-semibold hover:bg-red-50">Crisis Help</a>
                @auth
                    <hr class="border-th-border">
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg t-text hover:t-surface">Dashboard</a>
                    <a href="{{ route('journal.index') }}" class="block px-3 py-2 rounded-lg t-text hover:t-surface">Journal</a>
                    <a href="{{ route('mood.index') }}" class="block px-3 py-2 rounded-lg t-text hover:t-surface">Mood Tracker</a>
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg t-text hover:t-surface">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full text-left px-3 py-2 rounded-lg t-text hover:t-surface">Log Out</button></form>
                @else
                    <hr class="border-th-border">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg t-text hover:t-surface">Log In</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg btn-nature text-center">Start Journey</a>
                @endauth
            </div>
        </nav>

        {{-- ═══ Main Content ═══ --}}
        <main class="flex-grow">
            @yield('content')
        </main>

        {{-- ═══ Footer ═══ --}}
        <footer class="mt-auto relative z-20" style="background:var(--th-footer-bg);color:var(--th-footer-text);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm" style="background:linear-gradient(135deg, var(--th-primary), var(--th-accent))">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <span class="font-serif font-bold text-white text-lg">MindAssess</span>
                        </div>
                        <p class="t-light text-sm max-w-md leading-relaxed">AI-assisted emotional wellness companion with clinically inspired self-assessments and personalized support guidance. For educational and self-assessment purposes only.</p>
                        <p class="mt-4 text-xs t-light">⚠️ This platform is not a medical diagnosis system.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Quick Links</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('assessments.index') }}" class="hover:text-white transition-colors">Assessments</a></li>
                            <li><a href="{{ route('resources.index') }}" class="hover:text-white transition-colors">Resources</a></li>
                            <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Get Help</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('crisis.index') }}" class="text-red-300 hover:text-red-200 font-medium transition-colors">🚨 Crisis Support</a></li>
                            <li><span class="t-light">India: 1800-599-0019</span></li>
                            <li><span class="t-light">USA: 988</span></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-th-border mt-8 pt-6 text-center text-xs opacity-70">
                    <p>© {{ date('Y') }} MindAssess Platform. All rights reserved.</p>
                </div>
            </div>
        </footer>

        {{-- ═══ AI Chatbot Widget ═══ --}}
        <div x-data="chatbot()" x-cloak class="fixed bottom-6 right-6 z-50">
            {{-- Chat Window --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="mb-4 w-[360px] max-w-[calc(100vw-3rem)] t-card rounded-2xl shadow-2xl border border-th-border overflow-hidden flex flex-col" style="height: 480px;">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-sage-500 to-teal-600 text-white px-5 py-3.5 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center p-1 overflow-hidden shadow-sm">
                            <img src="{{ asset('images/therapist_chat.png') }}" alt="Bot Avatar" class="w-full h-full object-cover rounded-full">
                        </div>
                        <div>
                            <p class="font-semibold text-sm">MindAssess Buddy</p>
                            <p class="text-[10px] text-white/70">AI Wellness Companion</p>
                        </div>
                    </div>
                    <button @click="open = false" class="w-7 h-7 rounded-lg hover:t-card/20 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Messages --}}
                <div class="flex-1 overflow-y-auto p-4 space-y-3" x-ref="messages" style="scroll-behavior: smooth;">
                    <template x-for="(msg, i) in messages" :key="i">
                        <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="msg.role === 'user'
                                ? 'rounded-2xl rounded-br-md px-4 py-2.5 max-w-[85%]'
                                : 't-surface t-text rounded-2xl rounded-bl-md px-4 py-2.5 max-w-[85%] border border-th-border'"
                                 :style="msg.role === 'user' ? 'background:var(--th-primary);color:#ffffff;' : ''">
                                <div class="text-sm leading-relaxed chat-content font-medium" x-html="formatMessage(msg.text)"></div>
                            </div>
                        </div>
                    </template>

                    {{-- Typing indicator --}}
                    <div x-show="loading" class="flex justify-start">
                        <div class="t-surface border border-th-border rounded-2xl rounded-bl-md px-4 py-3">
                            <div class="flex gap-1.5">
                                <span class="w-2 h-2 bg-sage-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                                <span class="w-2 h-2 bg-sage-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                                <span class="w-2 h-2 bg-sage-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div x-show="messages.length <= 1" class="px-4 pb-2 flex-shrink-0">
                    <div class="flex flex-wrap gap-1.5">
                        <button @click="sendQuick('I feel anxious')" class="text-[11px] px-2.5 py-1 rounded-full border border-th-border-strong t-muted hover:t-surface transition-colors flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Anxiety help</button>
                        <button @click="sendQuick('I feel stressed')" class="text-[11px] px-2.5 py-1 rounded-full border border-th-border-strong t-muted hover:t-surface transition-colors flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Stress relief</button>
                        <button @click="sendQuick('I can\'t sleep')" class="text-[11px] px-2.5 py-1 rounded-full border border-th-border-strong t-muted hover:t-surface transition-colors flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg> Sleep tips</button>
                        <button @click="sendQuick('Help me breathe')" class="text-[11px] px-2.5 py-1 rounded-full border border-th-border-strong t-muted hover:t-surface transition-colors flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Breathing</button>
                    </div>
                </div>

                {{-- Input --}}
                <div class="p-3 border-t border-th-border flex-shrink-0">
                    <form @submit.prevent="send()" class="flex gap-2">
                        <input x-model="input" type="text" placeholder="Type your message..." maxlength="1000"
                            class="flex-1 px-4 py-2.5 t-surface border border-th-border rounded-xl text-sm focus:border-th-border-strong focus:ring-1 focus:ring-sage-200 outline-none transition-all"
                            :disabled="loading">
                            <button type="submit" :disabled="loading || !input.trim()"
                                :class="input.trim() && !loading ? 'hover:brightness-110' : 'opacity-50 cursor-not-allowed'"
                                class="w-10 h-10 rounded-xl text-white flex items-center justify-center transition-all flex-shrink-0"
                                style="background:var(--th-primary);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                    </form>
                    <p class="text-[9px] t-light mt-1.5 text-center">AI companion — not a substitute for professional help</p>
                </div>
            </div>

            {{-- Toggle Button --}}
            <button @click="open = !open; if(open && messages.length === 0) greet();"
                class="w-14 h-14 rounded-full text-white shadow-lift flex items-center justify-center transition-all duration-200 active:scale-95 hover:shadow-xl hover:scale-105"
                style="background: linear-gradient(135deg, var(--th-primary), var(--th-accent));">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>

        {{-- Chatbot Script --}}
        <script>
        function chatbot() {
            return {
                open: false,
                loading: false,
                input: '',
                messages: [],

                greet() {
                    this.messages.push({ role: 'bot', text: "Hello! I'm your MindAssess Buddy. I'm here to listen, offer wellness tips, and guide you to helpful resources.\n\nHow are you feeling today?" });
                },

                formatMessage(text) {
                    if (typeof marked !== 'undefined') {
                        return marked.parse(text || '');
                    }
                    return (text || '').replace(/\n/g, '<br>');
                },

                sendQuick(msg) {
                    this.input = msg;
                    this.send();
                },

                async send() {
                    const text = this.input.trim();
                    if (!text || this.loading) return;

                    this.messages.push({ role: 'user', text });
                    this.input = '';
                    this.loading = true;

                    this.$nextTick(() => {
                        this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
                    });

                    try {
                        const resp = await fetch('{{ route("chatbot.respond") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ 
                                message: text,
                                history: this.messages.slice(0, -1)
                            }),
                        });

                        const data = await resp.json();
                        this.messages.push({ role: 'bot', text: data.reply || "I'm sorry, I couldn't process that. Please try again." });
                    } catch (e) {
                        this.messages.push({ role: 'bot', text: "I'm having trouble connecting right now. Please try again in a moment." });
                    }

                    this.loading = false;

                    this.$nextTick(() => {
                        this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight;
                    });
                }
            };
        }
        </script>

        {{-- ═══ Floating Crisis Button ═══ --}}
        <a href="{{ route('crisis.index') }}" class="fixed bottom-6 left-6 z-50 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-full p-3 shadow-lift hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-200" title="Need Immediate Help?">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </a>

        <!-- AOS -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({ duration: 700, once: true, offset: 80 });

            // Theme Logic
            function toggleDarkMode() {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('ma_dark', 'false');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('ma_dark', 'true');
                }
            }

            function setTheme(theme) {
                document.documentElement.setAttribute('data-color-theme', theme);
                localStorage.setItem('ma_color_theme', theme);
                
                // Optionally send to server if user is logged in (fire and forget)
                @auth
                fetch('{{ route("profile.update") }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ preferences: { theme: theme } })
                }).catch(e => console.log('Theme sync error', e));
                @endauth
            }
        </script>

        {{-- ═══ Dynamic Custom Cursor ═══ --}}
        <div id="custom-cursor-dot" class="fixed top-0 left-0 pointer-events-none z-[100000] rounded-full transition-transform duration-100 ease-out shadow-sm" style="width: 8px; height: 8px; background: var(--th-primary); display: none;"></div>
        <div id="custom-cursor-ring" class="fixed top-0 left-0 pointer-events-none z-[99999] rounded-full transition-all duration-300 ease-out border-2" style="width: 40px; height: 40px; border-color: var(--th-accent); opacity: 1; display: none;"></div>

        <style>
            /* Hide default cursor universally */
            *, *::before, *::after {
                cursor: none !important;
            }
            /* Exception for touch devices where custom cursor isn't useful */
            @media (pointer: coarse) {
                *, *::before, *::after {
                    cursor: auto !important;
                }
                .cursor-pointer, a, button {
                    cursor: pointer !important;
                }
            }
        </style>

        <script>
            // Only initialize custom cursor on devices with a fine pointer (mouse)
            if (window.matchMedia("(pointer: fine)").matches) {
                const dot = document.getElementById('custom-cursor-dot');
                const ring = document.getElementById('custom-cursor-ring');
                
                dot.style.display = 'block';
                ring.style.display = 'block';

                let mouseX = window.innerWidth / 2;
                let mouseY = window.innerHeight / 2;
                let ringX = mouseX;
                let ringY = mouseY;
                let dotScale = 1;

                document.addEventListener('mousemove', (e) => {
                    mouseX = e.clientX;
                    mouseY = e.clientY;
                    dot.style.transform = `translate3d(calc(${mouseX}px - 50%), calc(${mouseY}px - 50%), 0) scale(${dotScale})`;
                });

                document.addEventListener('mouseover', (e) => {
                    const target = e.target.closest('a, button, input, textarea, select, .cursor-pointer, [role="button"]');
                    if (target) {
                        ring.style.width = '60px';
                        ring.style.height = '60px';
                        ring.style.backgroundColor = 'var(--th-primary-light)';
                        ring.style.opacity = '0.8';
                        dotScale = 1.5;
                        dot.style.transform = `translate3d(calc(${mouseX}px - 50%), calc(${mouseY}px - 50%), 0) scale(${dotScale})`;
                    } else {
                        ring.style.width = '40px';
                        ring.style.height = '40px';
                        ring.style.backgroundColor = 'transparent';
                        ring.style.opacity = '1';
                        dotScale = 1;
                        dot.style.transform = `translate3d(calc(${mouseX}px - 50%), calc(${mouseY}px - 50%), 0) scale(${dotScale})`;
                    }
                });

                function animateCursor() {
                    ringX += (mouseX - ringX) * 0.2;
                    ringY += (mouseY - ringY) * 0.2;
                    ring.style.transform = `translate3d(calc(${ringX}px - 50%), calc(${ringY}px - 50%), 0)`;
                    requestAnimationFrame(animateCursor);
                }
                animateCursor();
            }
        </script>
    </body>
</html>



