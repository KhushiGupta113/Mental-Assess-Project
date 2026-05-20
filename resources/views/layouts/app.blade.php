<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="nature" data-color-theme="sage">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Prevent FOUC: apply dark/theme immediately --}}
        <script>
            (function(){
                const dm = localStorage.getItem('ma_dark');
                const ct = localStorage.getItem('ma_color_theme') || 'sage';
                if(dm === 'true') document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-color-theme', ct);
            })();
        </script>
    </head>
    <body class="font-sans antialiased min-h-screen flex flex-col" style="background:var(--th-bg);color:var(--th-text)">
        <div class="min-h-screen" style="background:var(--th-bg);">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="t-card shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>


