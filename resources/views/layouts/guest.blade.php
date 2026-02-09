<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-100 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#0f172a] bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-[#0f172a] to-black">
            <div>
                <a href="/" wire:navigate class="flex flex-col items-center">
                    <x-application-logo class="w-24 h-24 fill-current text-indigo-500 drop-shadow-lg" />
                    <span class="mt-4 text-2xl font-bold tracking-wider text-white">DELIVERY<span class="text-indigo-500">PRO</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-8 py-8 bg-slate-800/50 backdrop-blur-xl border border-white/10 shadow-2xl overflow-hidden sm:rounded-2xl ring-1 ring-white/5">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-slate-500 text-sm">
                &copy; {{ date('Y') }} Delivery Platform. Tous droits réservés.
            </div>
        </div>
    </body>
</html>
