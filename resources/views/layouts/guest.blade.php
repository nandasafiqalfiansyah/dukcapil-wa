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
            
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes scaleIn {
                from {
                    opacity: 0;
                    transform: scale(0.9);
                }
                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }
            
            @keyframes pulse-glow {
                0%, 100% {
                    box-shadow: 0 0 20px rgba(37, 211, 102, 0.4);
                }
                50% {
                    box-shadow: 0 0 40px rgba(37, 211, 102, 0.6);
                }
            }
            
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
            
            .slide-up-animation {
                animation: slideUp 0.6s ease-out;
            }
            
            .scale-in-animation {
                animation: scaleIn 0.5s ease-out;
            }
            
            .pulse-glow-animation {
                animation: pulse-glow 2s ease-in-out infinite;
            }
            
            .gradient-whatsapp {
                background: linear-gradient(135deg, #25D366 0%, #075E54 100%);
            }
            
            .card-3d {
                transform-style: preserve-3d;
                transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            }
            
            .card-3d:hover {
                transform: translateY(-5px) rotateX(2deg);
            }
            
            .input-3d {
                transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
            }
            
            .input-3d:focus {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(37, 211, 102, 0.15), 0 5px 10px rgba(0, 0, 0, 0.05);
            }
            
            .button-3d {
                transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
                position: relative;
                overflow: hidden;
            }
            
            .button-3d::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.2);
                transform: translate(-50%, -50%);
                transition: width 0.6s, height 0.6s;
            }
            
            .button-3d:hover::before {
                width: 20rem;
                height: 20rem;
            }
            
            .button-3d:hover {
                transform: translateY(-3px) scale(1.02);
                box-shadow: 0 15px 35px rgba(37, 211, 102, 0.3), 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            
            .button-3d:active {
                transform: translateY(-1px) scale(0.98);
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
            
            <!-- Logo removed as per user request -->

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/95 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl relative z-10 card-3d slide-up-animation">
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
