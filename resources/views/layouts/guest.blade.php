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
            
            .gradient-whatsapp {
                background: linear-gradient(135deg, #25D366 0%, #075E54 100%);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden gradient-whatsapp">
            <!-- Decorative background elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-whatsapp-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-whatsapp-700 rounded-full mix-blend-multiply filter blur-xl opacity-20 float-animation" style="animation-delay: 2s;"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-whatsapp-500 rounded-full mix-blend-multiply filter blur-xl opacity-10 float-animation" style="animation-delay: 4s;"></div>
            </div>
            
            <div class="relative z-10">
                <a href="/" class="block text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-2xl hover:shadow-3xl transition-shadow duration-300">
                        <svg class="w-12 h-12 text-whatsapp-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
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
                <a href="/" class="text-sm text-white hover:text-whatsapp-100 transition duration-150 ease-in-out drop-shadow">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </body>
</html>
