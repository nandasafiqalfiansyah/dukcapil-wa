<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
            
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            
            .gradient-blue {
                background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden gradient-blue">
            <!-- Decorative background elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation" style="animation-delay: 2s;"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-10 float-animation" style="animation-delay: 4s;"></div>
            </div>
            
            <div class="relative z-10">
                <a href="/" class="block text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-2xl hover:shadow-3xl transition-shadow duration-300">
                        <span class="text-4xl">🏛️</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white mt-4 drop-shadow-lg">
                        DUKCAPIL Ponorogo
                    </h1>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/95 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl relative z-10">
                {{ $slot }}
            </div>
            
            <div class="mt-6 text-center relative z-10">
                <a href="/" class="text-sm text-white hover:text-blue-100 transition duration-150 ease-in-out drop-shadow">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </body>
</html>
