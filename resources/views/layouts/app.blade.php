<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    </head>
    <body class="font-sans antialiased bg-whatsapp-50">
        <div x-data="{ 
            sidebarOpen: false,
            isMobile: true,
            init() {
                this.checkScreenSize();
                window.addEventListener('resize', () => this.checkScreenSize());
            },
            checkScreenSize() {
                this.isMobile = window.innerWidth < 1024;
                // Sidebar is closed by default on all screen sizes
            }
        }" class="min-h-screen bg-gradient-to-br from-whatsapp-50 via-white to-whatsapp-100">
            @include('layouts.navigation')

            <div class="flex relative">
                <!-- Mobile Overlay -->
                <div x-show="sidebarOpen && isMobile" 
                     x-transition:enter="transition-opacity ease-linear duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="sidebarOpen = false"
                     class="fixed inset-0 bg-gray-600 bg-opacity-75 z-30 lg:hidden"></div>
                
                <!-- Sidebar -->
                @include('layouts.sidebar')

                <!-- Main Content Area -->
                <div class="flex-1 transition-all duration-300">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-whatsapp-600 shadow-lg">
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
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>
