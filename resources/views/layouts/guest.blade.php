<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="nature" data-color-theme="sage">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'MindAssess') }} — Wellness Companion</title>

        <script>
            (function(){
                const dm = localStorage.getItem('ma_dark');
                const ct = localStorage.getItem('ma_color_theme') || 'sage';
                if(dm === 'true') document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-color-theme', ct);
            })();
        </script>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|lora:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased min-h-screen flex" style="background:var(--th-bg);color:var(--th-text)">

        {{-- Left Panel: Decorative --}}
        <div class="hidden lg:flex lg:w-1/2 bg-nature-gradient relative overflow-hidden items-center justify-center">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-20 left-10 w-64 h-64 rounded-full" style="background:radial-gradient(circle, var(--th-glow), transparent 70%); animation: breathe 6s ease-in-out infinite;"></div>
                <div class="absolute bottom-20 right-10 w-80 h-80 rounded-full" style="background:radial-gradient(circle, var(--th-glow), transparent 70%); animation: breathe 6s ease-in-out infinite; animation-delay: 3s;"></div>
            </div>

            <div class="relative z-10 max-w-md px-12 text-center">
                <div class="w-16 h-16 mx-auto mb-6 rounded-2xl flex items-center justify-center" style="background:linear-gradient(135deg, var(--th-primary), var(--th-accent))">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <h2 class="text-3xl font-serif font-bold t-text mb-4">Your Wellness Journey Starts Here</h2>
                <p class="t-muted leading-relaxed">Take clinically validated self-assessments, track your mood, and receive personalized AI-powered wellness guidance — all in a safe, private space.</p>
                <div class="mt-8 flex justify-center gap-8">
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl flex items-center justify-center" style="background:var(--th-primary-light)">
                            <svg class="w-5 h-5" style="color:var(--th-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p class="text-xs t-muted font-medium">Assessments</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl flex items-center justify-center" style="background:var(--th-primary-light)">
                            <svg class="w-5 h-5" style="color:var(--th-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <p class="text-xs t-muted font-medium">Tracking</p>
                    </div>
                    <div class="text-center">
                        <div class="w-10 h-10 mx-auto mb-2 rounded-xl flex items-center justify-center" style="background:var(--th-primary-light)">
                            <svg class="w-5 h-5" style="color:var(--th-primary)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <p class="text-xs t-muted font-medium">AI Insights</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel: Form --}}
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center px-6 py-12">
            <div class="w-full max-w-md">
                {{-- Mobile logo --}}
                <div class="lg:hidden text-center mb-8">
                    <a href="/" class="inline-flex items-center gap-2">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg, var(--th-primary), var(--th-accent))">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <span class="text-xl font-serif font-bold t-text">MindAssess</span>
                    </a>
                </div>

                <div class="glass-card-solid p-8 md:p-10">
                    {{ $slot }}
                </div>

                <p class="text-center text-xs t-muted mt-6">
                    <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Private & confidential · Not a diagnostic tool
                </p>
            </div>
        </div>
    </body>
</html>
