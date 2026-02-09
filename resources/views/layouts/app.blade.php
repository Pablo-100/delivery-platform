<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DeliveryPro') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            body { font-family: 'Outfit', sans-serif; }
            .glass-effect {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
            }
            .sidebar-gradient {
                background: linear-gradient(180deg, #1e1e2d 0%, #2d2d44 100%);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex relative">
            
            <!-- Mobile Sidebar Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-40 md:hidden"></div>

            <!-- Sidebar (Mobile) -->
            <aside x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-64 sidebar-gradient text-white z-50 md:hidden overflow-y-auto">
                <div class="p-6 flex items-center justify-between border-b border-gray-700">
                    <span class="text-2xl font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">
                        DELIVERY<span class="font-light text-white">PRO</span>
                    </span>
                    <button @click="sidebarOpen = false" class="text-gray-400 hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="py-6 px-4 space-y-2">
                    <div class="text-xs uppercase text-gray-500 font-semibold mb-2 px-2">Menu Principal</div>
                    <livewire:layout.sidebar-navigation />
                </div>
                
                <!-- User Info Mobile -->
                <div class="absolute bottom-0 w-full p-4 border-t border-gray-700 bg-black/20">
                     <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 flex items-center justify-center font-bold text-sm">
                            {{ substr(auth()->user()->prenom ?? 'U', 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium">{{ auth()->user()->prenom ?? 'User' }}</div>
                            <div class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', auth()->user()->role ?? 'Guest') }}</div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Sidebar (Desktop) -->
            <aside class="w-64 sidebar-gradient text-white hidden md:block fixed h-full shadow-2xl z-50">
                <div class="p-6 flex items-center justify-center border-b border-gray-700">
                    <span class="text-2xl font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">
                        DELIVERY<span class="font-light text-white">PRO</span>
                    </span>
                </div>
                
                <div class="py-6 px-4 space-y-2">
                    <div class="text-xs uppercase text-gray-500 font-semibold mb-2 px-2">Menu Principal</div>
                    <livewire:layout.sidebar-navigation />
                </div>

                <div class="absolute bottom-0 w-full p-4 border-t border-gray-700 bg-black/20">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 flex items-center justify-center font-bold text-sm">
                            {{ substr(auth()->user()->prenom ?? 'U', 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium">{{ auth()->user()->prenom ?? 'User' }}</div>
                            <div class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', auth()->user()->role ?? 'Guest') }}</div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 md:ml-64 flex flex-col min-h-screen transition-all duration-300 w-full">
                <!-- Topbar (Mobile & Desktop) -->
                <header class="glass-effect sticky top-0 z-40 border-b border-gray-200 shadow-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                        <div class="flex items-center md:hidden">
                            <!-- Mobile Menu Button -->
                            <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 focus:outline-none p-2 rounded-md hover:bg-gray-100 mr-2">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <span class="font-bold text-gray-800 text-lg">DELIVERY<span class="text-indigo-600">PRO</span></span>
                        </div>
                        
                        <!-- Page Title in Header -->
                        <div class="hidden md:block">
                            <h1 class="text-xl font-semibold text-gray-800">
                                @if (isset($header)) {{ $header }} @else Dashboard @endif
                            </h1>
                        </div>

                        <div class="flex items-center space-x-4">
                            <!-- User Dropdown / Logout -->
                             <livewire:layout.navigation />
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-4 sm:p-6 overflow-y-auto">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
