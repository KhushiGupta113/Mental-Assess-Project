<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="nature">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="AI-assisted emotional wellness companion with clinically inspired self-assessments and personalized support guidance.">

        <title>{{ config('app.name', 'MindAssess') }} — Wellness Companion</title>

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
    <body class="font-sans antialiased bg-base-100 min-h-screen flex flex-col">

        {{-- ═══ Navigation ═══ --}}
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-sage-100/60" x-data="{ mobileOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    {{-- Logo --}}
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        <div class="w-9 h-9 bg-gradient-to-br from-sage-400 to-teal-500 rounded-xl flex items-center justify-center shadow-sm group-hover:shadow-md transition-all duration-300 group-hover:scale-105">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <span class="text-lg font-serif font-bold text-sage-800">MindAssess</span>
                    </a>

                    {{-- Desktop Nav --}}
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('assessments.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-sage-50 {{ request()->routeIs('assessments.*') ? 'nav-link-active' : '' }}">Assessments</a>
                        <a href="{{ route('resources.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-sage-50 {{ request()->routeIs('resources.*') ? 'nav-link-active' : '' }}">Resources</a>
                        <a href="{{ route('about') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-sage-50 {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">About</a>
                        <a href="{{ route('crisis.index') }}" class="text-red-500 hover:text-red-600 font-semibold text-sm px-3 py-2 rounded-lg hover:bg-red-50 transition-colors">Crisis Help</a>
                    </div>

                    {{-- Auth Buttons --}}
                    <div class="hidden md:flex items-center space-x-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-sage-50 {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">Dashboard</a>
                            <a href="{{ route('journal.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-sage-50 {{ request()->routeIs('journal.*') ? 'nav-link-active' : '' }}">Journal</a>
                            <a href="{{ route('mood.index') }}" class="nav-link px-3 py-2 rounded-lg hover:bg-sage-50 {{ request()->routeIs('mood.*') ? 'nav-link-active' : '' }}">Mood</a>

                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-sage-50 hover:bg-sage-100 transition-colors">
                                    <span class="text-lg">{{ Auth::user()->avatar_emoji ?? '🌱' }}</span>
                                    <span class="text-sm font-medium text-sage-700">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-sage-400" fill="none" stroke="currentColor" viewBox="0 0 20 20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7l5 5 5-5"/></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lift border border-sage-100 py-2 z-50">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-sage-700 hover:bg-sage-50">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-sage-700 hover:bg-sage-50">Log Out</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="nav-link px-3 py-2">Log in</a>
                            <a href="{{ route('register') }}" class="btn-nature text-sm !py-2 !px-5 !rounded-xl">Start Journey</a>
                        @endauth
                    </div>

                    {{-- Mobile Hamburger --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-sage-50">
                        <svg class="w-6 h-6 text-sage-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div x-show="mobileOpen" x-transition class="md:hidden bg-white border-t border-sage-100 px-4 py-4 space-y-2">
                <a href="{{ route('assessments.index') }}" class="block px-3 py-2 rounded-lg text-sage-700 hover:bg-sage-50">Assessments</a>
                <a href="{{ route('resources.index') }}" class="block px-3 py-2 rounded-lg text-sage-700 hover:bg-sage-50">Resources</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-sage-700 hover:bg-sage-50">About</a>
                <a href="{{ route('crisis.index') }}" class="block px-3 py-2 rounded-lg text-red-500 font-semibold hover:bg-red-50">Crisis Help</a>
                @auth
                    <hr class="border-sage-100">
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sage-700 hover:bg-sage-50">Dashboard</a>
                    <a href="{{ route('journal.index') }}" class="block px-3 py-2 rounded-lg text-sage-700 hover:bg-sage-50">Journal</a>
                    <a href="{{ route('mood.index') }}" class="block px-3 py-2 rounded-lg text-sage-700 hover:bg-sage-50">Mood Tracker</a>
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-sage-700 hover:bg-sage-50">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full text-left px-3 py-2 rounded-lg text-sage-700 hover:bg-sage-50">Log Out</button></form>
                @else
                    <hr class="border-sage-100">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sage-700 hover:bg-sage-50">Log In</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg btn-nature text-center">Start Journey</a>
                @endauth
            </div>
        </nav>

        {{-- ═══ Main Content ═══ --}}
        <main class="flex-grow">
            @yield('content')
        </main>

        {{-- ═══ Footer ═══ --}}
        <footer class="bg-sage-800 text-sage-200 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-sage-400 to-teal-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </div>
                            <span class="font-serif font-bold text-white text-lg">MindAssess</span>
                        </div>
                        <p class="text-sage-300 text-sm max-w-md leading-relaxed">AI-assisted emotional wellness companion with clinically inspired self-assessments and personalized support guidance. For educational and self-assessment purposes only.</p>
                        <p class="mt-4 text-xs text-sage-400">⚠️ This platform is not a medical diagnosis system.</p>
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
                            <li><span class="text-sage-400">India: 1800-599-0019</span></li>
                            <li><span class="text-sage-400">USA: 988</span></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-sage-700 mt-8 pt-6 text-center text-xs text-sage-400">
                    <p>© {{ date('Y') }} MindAssess Platform. All rights reserved.</p>
                </div>
            </div>
        </footer>

        {{-- ═══ AI Chatbot Widget ═══ --}}
        <div x-data="chatbot()" x-cloak class="fixed bottom-6 right-6 z-50">
            {{-- Chat Window --}}
            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                class="mb-4 w-[360px] max-w-[calc(100vw-3rem)] bg-white rounded-2xl shadow-2xl border border-sage-100 overflow-hidden flex flex-col" style="height: 480px;">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-sage-500 to-teal-600 text-white px-5 py-3.5 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-lg">🌿</div>
                        <div>
                            <p class="font-semibold text-sm">MindAssess Buddy</p>
                            <p class="text-[10px] text-white/70">AI Wellness Companion</p>
                        </div>
                    </div>
                    <button @click="open = false" class="w-7 h-7 rounded-lg hover:bg-white/20 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Messages --}}
                <div class="flex-1 overflow-y-auto p-4 space-y-3" x-ref="messages" style="scroll-behavior: smooth;">
                    <template x-for="(msg, i) in messages" :key="i">
                        <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                            <div :class="msg.role === 'user'
                                ? 'bg-sage-500 text-white rounded-2xl rounded-br-md px-4 py-2.5 max-w-[85%]'
                                : 'bg-sage-50 text-sage-700 rounded-2xl rounded-bl-md px-4 py-2.5 max-w-[85%] border border-sage-100'">
                                <div class="text-sm leading-relaxed chat-content" x-html="formatMessage(msg.text)"></div>
                            </div>
                        </div>
                    </template>

                    {{-- Typing indicator --}}
                    <div x-show="loading" class="flex justify-start">
                        <div class="bg-sage-50 border border-sage-100 rounded-2xl rounded-bl-md px-4 py-3">
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
                        <button @click="sendQuick('I feel anxious')" class="text-[11px] px-2.5 py-1 rounded-full border border-sage-200 text-sage-600 hover:bg-sage-50 transition-colors">😰 Anxiety help</button>
                        <button @click="sendQuick('I feel stressed')" class="text-[11px] px-2.5 py-1 rounded-full border border-sage-200 text-sage-600 hover:bg-sage-50 transition-colors">🔥 Stress relief</button>
                        <button @click="sendQuick('I can\'t sleep')" class="text-[11px] px-2.5 py-1 rounded-full border border-sage-200 text-sage-600 hover:bg-sage-50 transition-colors">🌙 Sleep tips</button>
                        <button @click="sendQuick('Help me breathe')" class="text-[11px] px-2.5 py-1 rounded-full border border-sage-200 text-sage-600 hover:bg-sage-50 transition-colors">🫧 Breathing</button>
                    </div>
                </div>

                {{-- Input --}}
                <div class="p-3 border-t border-sage-100 flex-shrink-0">
                    <form @submit.prevent="send()" class="flex gap-2">
                        <input x-model="input" type="text" placeholder="Type your message..." maxlength="1000"
                            class="flex-1 px-4 py-2.5 bg-sage-50 border border-sage-100 rounded-xl text-sm focus:border-sage-300 focus:ring-1 focus:ring-sage-200 outline-none transition-all"
                            :disabled="loading">
                        <button type="submit" :disabled="loading || !input.trim()"
                            :class="input.trim() && !loading ? 'bg-sage-500 hover:bg-sage-600' : 'bg-sage-300 cursor-not-allowed'"
                            class="w-10 h-10 rounded-xl text-white flex items-center justify-center transition-colors flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>
                    </form>
                    <p class="text-[9px] text-sage-400 mt-1.5 text-center">AI companion — not a substitute for professional help</p>
                </div>
            </div>

            {{-- Toggle Button --}}
            <button @click="open = !open; if(open && messages.length === 0) greet();"
                :class="open ? 'bg-sage-600 hover:bg-sage-700' : 'bg-gradient-to-r from-sage-500 to-teal-600 hover:shadow-xl hover:scale-105'"
                class="w-14 h-14 rounded-full text-white shadow-lift flex items-center justify-center transition-all duration-200 active:scale-95">
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
                    this.messages.push({ role: 'bot', text: "Hello! 🌿 I'm your MindAssess Buddy. I'm here to listen, offer wellness tips, and guide you to helpful resources.\n\nHow are you feeling today?" });
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
                            body: JSON.stringify({ message: text }),
                        });

                        const data = await resp.json();
                        this.messages.push({ role: 'bot', text: data.reply || "I'm sorry, I couldn't process that. Please try again." });
                    } catch (e) {
                        this.messages.push({ role: 'bot', text: "I'm having trouble connecting right now. Please try again in a moment. 🌿" });
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
        <script>AOS.init({ duration: 700, once: true, offset: 80 });</script>
    </body>
</html>
