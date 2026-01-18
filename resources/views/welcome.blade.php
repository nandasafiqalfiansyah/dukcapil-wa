<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Sistem Informasi Dukcapil Ponorogo - WhatsApp Chatbot</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Tailwind CSS styles will be included here if vite is not available */
                @import url('https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css');
            </style>
        @endif
        
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px) translateZ(0); }
                50% { transform: translateY(-20px) translateZ(10px); }
            }
            
            @keyframes pulse-slow {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.8; }
            }
            
            .float-animation {
                animation: float 6s ease-in-out infinite;
            }
            
            .card-3d {
                transform-style: preserve-3d;
                transition: transform 0.6s;
            }
            
            @media (min-width: 768px) {
                .card-3d:hover {
                    transform: rotateY(5deg) rotateX(5deg) scale(1.05);
                }
            }
            
            .gradient-bg {
                background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            }
            
            .gradient-whatsapp {
                background: linear-gradient(135deg, #25D366 0%, #075E54 100%);
            }
            
            .text-shadow {
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            }
            
            .chat-bubble {
                position: relative;
                background: white;
                border-radius: 20px;
                padding: 15px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            
            @media (min-width: 640px) {
                .chat-bubble {
                    padding: 20px;
                }
            }
            
            .chat-bubble::before {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 30px;
                width: 20px;
                height: 20px;
                background: white;
                transform: rotate(45deg);
            }

            /* Animations for WhatsApp Links Section */
            @keyframes fade-in {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            @keyframes slide-up {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes bounce-slow {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-8px);
                }
            }

            @keyframes bounce-slower {
                0%, 100% {
                    transform: translateY(0) rotate(0deg);
                }
                50% {
                    transform: translateY(-6px) rotate(5deg);
                }
            }

            @keyframes bounce-fast {
                0%, 100% {
                    transform: translateX(0);
                }
                25% {
                    transform: translateX(-3px);
                }
                75% {
                    transform: translateX(3px);
                }
            }

            .animate-fade-in {
                animation: fade-in 0.6s ease-out;
            }

            .animate-slide-up {
                animation: slide-up 0.6s ease-out forwards;
                opacity: 0;
            }

            .animate-bounce-slow {
                animation: bounce-slow 2.5s ease-in-out infinite;
            }

            .animate-bounce-slower {
                animation: bounce-slower 3s ease-in-out infinite;
            }

            .animate-bounce-fast {
                animation: bounce-fast 0.4s ease-in-out;
            }
        </style>
    </head>
    <body class="bg-white font-sans antialiased">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo/Brand -->
                    <div class="flex items-center">
                       <div class="flex items-center flex-shrink-0 gap-2">
                        <img src="{{ asset('image.png') }}"
                            alt="Logo DUKCAPIL Ponorogo"
                            class="h-8 w-auto">

                        <h1 class="text-xl font-bold text-whatsapp-600">
                            DUKCAPIL Ponorogo
                        </h1>
                    </div>
                    </div>

                    @if (Route::has('login'))
                        <!-- Desktop Navigation -->
                        <div class="hidden md:flex items-center space-x-4">
                            @auth
                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="px-6 py-2 text-sm font-medium text-white bg-whatsapp-600 hover:bg-whatsapp-700 rounded-lg transition duration-150 ease-in-out shadow-md"
                                >
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-whatsapp-600 transition duration-150 ease-in-out"
                                >
                                    Masuk
                                </a>

                                @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="px-6 py-2 text-sm font-medium text-white bg-whatsapp-600 hover:bg-whatsapp-700 rounded-lg transition duration-150 ease-in-out shadow-md"
                                    >
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        </div>

                        <!-- Mobile menu button -->
                        <div class="md:hidden">
                            <button
                                type="button"
                                id="mobile-menu-button"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-whatsapp-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-whatsapp-500"
                                aria-controls="mobile-menu"
                                aria-expanded="false"
                            >
                                <span class="sr-only">Buka menu</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            @if (Route::has('login'))
                <!-- Mobile menu -->
                <div class="md:hidden hidden" id="mobile-menu">
                    <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="block px-3 py-2 rounded-md text-base font-medium text-white bg-whatsapp-600 hover:bg-whatsapp-700"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-whatsapp-600 hover:bg-gray-50"
                            >
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="block px-3 py-2 rounded-md text-base font-medium text-white bg-whatsapp-600 hover:bg-whatsapp-700"
                                >
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endif
        </nav>

        <main class="flex-1">
            <!-- Hero Section with 3D Elements -->
            <div class="relative gradient-whatsapp text-white overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-40 -right-40 w-80 h-80 bg-whatsapp-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 float-animation"></div>
                    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-whatsapp-700 rounded-full mix-blend-multiply filter blur-xl opacity-30 float-animation" style="animation-delay: 2s;"></div>
                </div>
                
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20 xl:py-32">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                        <!-- Left Content -->
                        <div class="text-center lg:text-left">
                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6 text-shadow">
                                Sistem Informasi<br>Dukcapil Ponorogo
                            </h1>
                            <p class="text-lg sm:text-xl lg:text-2xl mb-6 sm:mb-8 text-whatsapp-100">
                                Layanan Administrasi Kependudukan Melalui WhatsApp Chatbot - Cepat, Mudah, dan Terpercaya
                            </p>
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start">
                                <a
                                    href="#try-chatbot"
                                    class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border border-transparent text-base sm:text-lg font-medium rounded-lg text-whatsapp-700 bg-white hover:bg-whatsapp-50 transition duration-150 ease-in-out shadow-xl"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    Coba Chatbot Sekarang
                                </a>
                                @if (Route::has('login'))
                                    @auth
                                        <a
                                            href="{{ url('/dashboard') }}"
                                            class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border-2 border-white text-base sm:text-lg font-medium rounded-lg text-white hover:bg-white hover:text-whatsapp-700 transition duration-150 ease-in-out"
                                        >
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Dashboard
                                        </a>
                                    @else
                                        <a
                                            href="{{ route('login') }}"
                                            class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border-2 border-white text-base sm:text-lg font-medium rounded-lg text-white hover:bg-white hover:text-whatsapp-700 transition duration-150 ease-in-out"
                                        >
                                            Login Admin
                                        </a>
                                    @endauth
                                @endif
                            </div>
                        </div>

                        <!-- Right Content - 3D WhatsApp Chat Illustration -->
                        <div class="hidden md:block">
                            <div class="relative mx-auto max-w-md lg:max-w-none">
                                <div class="absolute inset-0 bg-gradient-to-r from-whatsapp-400 to-whatsapp-600 rounded-3xl blur-2xl opacity-30"></div>
                                <div class="relative bg-white rounded-2xl lg:rounded-3xl p-4 sm:p-6 lg:p-8 shadow-2xl">
                                    <div class="flex items-center mb-4 sm:mb-6">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-whatsapp-500 rounded-full flex items-center justify-center text-xl sm:text-2xl">
                                            💬
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-bold text-gray-900 text-sm sm:text-base">CS DUKCAPIL</div>
                                            <div class="text-xs sm:text-sm text-whatsapp-500">● Online</div>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3 sm:space-y-4">
                                        <div class="chat-bubble bg-gray-100 ml-0">
                                            <p class="text-gray-800 text-sm sm:text-base">Halo! Selamat datang di layanan DUKCAPIL Ponorogo 👋</p>
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <div class="bg-whatsapp-500 text-white rounded-2xl px-4 sm:px-6 py-2 sm:py-3 max-w-xs shadow-lg">
                                                <p class="text-sm sm:text-base">Saya ingin mengurus KTP</p>
                                            </div>
                                        </div>
                                        
                                        <div class="chat-bubble bg-gray-100 ml-0">
                                            <p class="text-gray-800 text-sm sm:text-base">Baik, saya akan membantu proses pengurusan KTP Anda. Silakan kirim foto KK dan foto selfie untuk verifikasi 📄</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section with 3D Cards -->
            <div class="bg-gray-50 py-12 sm:py-16 lg:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12 sm:mb-16">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                            Layanan WhatsApp Chatbot
                        </h2>
                        <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto px-4">
                            Akses layanan administrasi kependudukan 24/7 melalui WhatsApp dengan mudah dan cepat
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                        <!-- Feature 1 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-6 sm:p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-whatsapp-100 rounded-2xl flex items-center justify-center mb-4 sm:mb-6">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">
                                Pelayanan KTP
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                                Pengurusan Kartu Tanda Penduduk elektronik dengan proses yang cepat dan mudah melalui WhatsApp
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-6 sm:p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-whatsapp-100 rounded-2xl flex items-center justify-center mb-4 sm:mb-6">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">
                                Kartu Keluarga
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                                Layanan pembuatan dan perubahan data Kartu Keluarga secara online dengan mudah
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-6 sm:p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-whatsapp-100 rounded-2xl flex items-center justify-center mb-4 sm:mb-6">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">
                                Akta Kelahiran
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                                Pengurusan akta kelahiran untuk bayi baru lahir dengan proses yang praktis
                            </p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-6 sm:p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-whatsapp-100 rounded-2xl flex items-center justify-center mb-4 sm:mb-6">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">
                                Konsultasi Online
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                                Tanya jawab seputar persyaratan dan prosedur administrasi kependudukan
                            </p>
                        </div>

                        <!-- Feature 5 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-6 sm:p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-whatsapp-100 rounded-2xl flex items-center justify-center mb-4 sm:mb-6">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">
                                Tracking Status
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                                Cek status permohonan dokumen Anda secara real-time melalui chatbot
                            </p>
                        </div>

                        <!-- Feature 6 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-6 sm:p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-whatsapp-100 rounded-2xl flex items-center justify-center mb-4 sm:mb-6">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">
                                Layanan 24/7
                            </h3>
                            <p class="text-sm sm:text-base text-gray-600 leading-relaxed">
                                Akses layanan kapan saja, dimana saja tanpa harus datang ke kantor
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How It Works Section -->
            <div class="bg-white py-12 sm:py-16 lg:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12 sm:mb-16">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                            Cara Menggunakan Layanan
                        </h2>
                        <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto px-4">
                            Mudah dan sederhana, hanya dengan WhatsApp Anda
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                        <div class="text-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-whatsapp-600 text-white rounded-full flex items-center justify-center text-2xl sm:text-3xl font-bold mx-auto mb-3 sm:mb-4 shadow-lg">
                                1
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Hubungi WhatsApp</h3>
                            <p class="text-sm sm:text-base text-gray-600 px-2">Kirim pesan ke nomor WhatsApp resmi DUKCAPIL Ponorogo</p>
                        </div>

                        <div class="text-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-whatsapp-600 text-white rounded-full flex items-center justify-center text-2xl sm:text-3xl font-bold mx-auto mb-3 sm:mb-4 shadow-lg">
                                2
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Pilih Layanan</h3>
                            <p class="text-sm sm:text-base text-gray-600 px-2">Chatbot akan memandu Anda memilih layanan yang dibutuhkan</p>
                        </div>

                        <div class="text-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-whatsapp-600 text-white rounded-full flex items-center justify-center text-2xl sm:text-3xl font-bold mx-auto mb-3 sm:mb-4 shadow-lg">
                                3
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Upload Dokumen</h3>
                            <p class="text-sm sm:text-base text-gray-600 px-2">Kirim dokumen persyaratan sesuai petunjuk chatbot</p>
                        </div>

                        <div class="text-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-whatsapp-600 text-white rounded-full flex items-center justify-center text-2xl sm:text-3xl font-bold mx-auto mb-3 sm:mb-4 shadow-lg">
                                4
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Selesai</h3>
                            <p class="text-sm sm:text-base text-gray-600 px-2">Dokumen Anda akan diproses dan Anda bisa tracking statusnya</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Try Chatbot Section -->
            <div id="try-chatbot" class="bg-gradient-to-b from-gray-50 to-white py-12 sm:py-16 lg:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-8 sm:mb-12">
                        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                            Coba Chatbot Sekarang!
                        </h2>
                        <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto px-4">
                            Rasakan pengalaman berinteraksi dengan chatbot DUKCAPIL Ponorogo
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 mb-8 sm:mb-12">
                        <!-- Tutorial Panel -->
                        <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                            <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-6">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 flex items-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Panduan Penggunaan
                                </h3>
                                <div class="space-y-3 sm:space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 bg-whatsapp-100 text-whatsapp-600 rounded-full flex items-center justify-center font-bold mr-2 sm:mr-3 text-sm sm:text-base">
                                            1
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Mulai Percakapan</h4>
                                            <p class="text-xs sm:text-sm text-gray-600">Ketik pesan Anda di kotak chat di sebelah kanan</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 bg-whatsapp-100 text-whatsapp-600 rounded-full flex items-center justify-center font-bold mr-2 sm:mr-3 text-sm sm:text-base">
                                            2
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Bot Akan Merespon</h4>
                                            <p class="text-xs sm:text-sm text-gray-600">Chatbot akan memahami pertanyaan Anda dengan NLP</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-7 h-7 sm:w-8 sm:h-8 bg-whatsapp-100 text-whatsapp-600 rounded-full flex items-center justify-center font-bold mr-2 sm:mr-3 text-sm sm:text-base">
                                            3
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-1 text-sm sm:text-base">Ikuti Instruksi</h4>
                                            <p class="text-xs sm:text-sm text-gray-600">Bot akan memandu proses layanan yang Anda butuhkan</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 sm:p-6">
                                <h4 class="font-bold text-blue-900 mb-2 flex items-center text-sm sm:text-base">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    Contoh Pertanyaan:
                                </h4>
                                <ul class="space-y-1 sm:space-y-2 text-xs sm:text-sm text-blue-800">
                                    <li class="flex items-start">
                                        <span class="mr-2">•</span>
                                        <span>"Bagaimana cara mengurus KTP?"</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="mr-2">•</span>
                                        <span>"Dokumen apa saja yang diperlukan untuk KK?"</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="mr-2">•</span>
                                        <span>"Syarat membuat akta kelahiran?"</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="mr-2">•</span>
                                        <span>"Berapa lama proses pembuatan KTP?"</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4 sm:p-6">
                                <h4 class="font-bold text-yellow-900 mb-2 flex items-center text-sm sm:text-base">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Catatan
                                </h4>
                                <p class="text-xs sm:text-sm text-yellow-800">
                                    Ini adalah <strong>demo</strong> chatbot. Untuk layanan resmi, silakan hubungi WhatsApp DUKCAPIL Ponorogo di bawah ini.
                                </p>
                            </div>
                        </div>

                        <!-- Chat Demo Panel -->
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[80vh]" style="height: 600px;">
                                <!-- Chat Header -->
                                <div class="bg-gradient-to-r from-whatsapp-500 to-whatsapp-600 text-white p-3 sm:p-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full flex items-center justify-center text-xl sm:text-2xl mr-2 sm:mr-3">
                                            🤖
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-sm sm:text-base">DUKCAPIL Assistant</h3>
                                            <p class="text-xs text-whatsapp-100">
                                                <span id="statusIndicator" class="inline-block w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                                Always Online
                                            </p>
                                        </div>
                                    </div>
                                    <button id="demoResetBtn" class="text-white hover:text-whatsapp-100 p-2" title="Reset Chat">
                                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Messages Area -->
                                <div id="demoMessagesArea" class="flex-1 overflow-y-auto p-3 sm:p-4 space-y-3 sm:space-y-4 bg-gray-50" style="height: calc(100% - 140px);">
                                    <div class="flex items-center justify-center h-full">
                                        <div class="text-center">
                                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-whatsapp-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-base sm:text-lg font-medium text-gray-700 mb-2">Selamat Datang!</h3>
                                            <p class="text-sm sm:text-base text-gray-500">Ketik pesan di bawah untuk memulai percakapan</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Area -->
                                <div class="border-t border-gray-200 p-3 sm:p-4 bg-white">
                                    <form id="demoMessageForm" class="flex space-x-2">
                                        @csrf
                                        <input type="hidden" id="demoSessionId" value="">
                                        <input 
                                            type="text" 
                                            id="demoMessageInput" 
                                            placeholder="Ketik pesan Anda..." 
                                            class="flex-1 rounded-full border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200 px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base"
                                            autocomplete="off"
                                            required
                                        >
                                        <button 
                                            type="submit" 
                                            id="demoSendBtn"
                                            class="bg-whatsapp-600 hover:bg-whatsapp-700 text-white font-bold p-2 sm:p-3 rounded-full transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available WhatsApp Links Section with Animation -->
            @if($whatsappLinks && $whatsappLinks->count() > 0)
                <div class="bg-gradient-to-b from-white to-gray-50 py-12 sm:py-16 lg:py-20 animate-fade-in">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-12 sm:mb-16 animate-slide-up" style="animation-delay: 0.1s;">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 mr-3 text-whatsapp-600 animate-bounce-slow" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                Hubungi Layanan DUKCAPIL
                            </h2>
                            <p class="text-lg sm:text-xl text-gray-600">Pilih layanan yang Anda butuhkan di bawah</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                            @foreach($whatsappLinks as $index => $link)
                                <div class="animate-slide-up" style="animation-delay: {{ (0.15 + ($index * 0.1)) }}s;">
                                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden border-t-4 border-whatsapp-500 transform hover:scale-105 hover:-translate-y-2 duration-300">
                                        <div class="bg-gradient-to-r from-whatsapp-50 to-white p-6">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">{{ $link['name'] }}</h3>
                                                    <p class="text-xs sm:text-sm text-whatsapp-600 font-mono mt-1">
                                                        <span class="inline-block w-2 h-2 bg-whatsapp-500 rounded-full mr-2 animate-pulse"></span>
                                                        {{ $link['phone_number'] }}
                                                    </p>
                                                </div>
                                                <div class="bg-whatsapp-100 rounded-lg p-2 animate-bounce-slower">
                                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-whatsapp-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                                    </svg>
                                                </div>
                                            </div>

                                            @if($link['message'])
                                                <div class="bg-blue-50 border-l-4 border-blue-500 rounded p-3 mb-4">
                                                    <p class="text-xs sm:text-sm text-blue-800">
                                                        <strong>Pesan:</strong> {{ Str::limit($link['message'], 60) }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="p-6 pt-4">
                                            <a href="{{ $link['link'] }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-3 sm:py-4 bg-gradient-to-r from-whatsapp-500 to-whatsapp-600 text-white font-bold rounded-lg hover:shadow-lg transform hover:scale-105 transition duration-150 ease-in-out text-sm sm:text-base group">
                                                <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 group-hover:animate-bounce-fast" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                                </svg>
                                                Hubungi Sekarang
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- CTA WhatsApp Section -->
            <div class="gradient-whatsapp text-white py-12 sm:py-16">
                <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6">
                        Siap Menggunakan Layanan Resmi?
                    </h2>
                    <p class="text-lg sm:text-xl mb-6 sm:mb-8 text-whatsapp-100">
                        Hubungi WhatsApp resmi DUKCAPIL Ponorogo untuk layanan sebenarnya
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                        {{-- TODO: Replace with actual WhatsApp number: https://wa.me/62XXXXXXXXXXX --}}
                        <a href="https://wa.me/6281234567890?text=Halo%20DUKCAPIL%20Ponorogo" class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border-2 border-white text-base sm:text-lg font-medium rounded-lg text-white hover:bg-white hover:text-whatsapp-600 transition duration-150 ease-in-out">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span class="break-words">Hubungi via WhatsApp Resmi</span>
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-8 sm:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 sm:gap-8">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">DUKCAPIL Ponorogo</h3>
                        <p class="text-sm sm:text-base text-gray-400">
                            Dinas Kependudukan dan Pencatatan Sipil Kabupaten Ponorogo
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Kontak</h3>
                        <p class="text-sm sm:text-base text-gray-400">
                            Email: dukcapil@ponorogo.go.id<br>
                            Telepon: (0352) 123456<br>
                            WhatsApp: 0812-3456-7890 {{-- TODO: Replace with actual number --}}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Jam Layanan</h3>
                        <p class="text-sm sm:text-base text-gray-400">
                            Senin - Jumat: 08:00 - 16:00<br>
                            WhatsApp Chatbot: 24/7
                        </p>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-6 sm:mt-8 pt-6 sm:pt-8 text-center text-sm sm:text-base text-gray-400">
                    <p>&copy; {{ date('Y') }} DUKCAPIL Ponorogo. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <script>
            // Mobile menu toggle
            document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('hidden');
            });

            // Chat Demo Functionality
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            let demoSessionId = '';

            // Create session on page load
            async function createDemoSession() {
                try {
                    const response = await fetch('/chat-demo/sessions', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    
                    if (!response.ok) {
                        console.error('Failed to create session. HTTP error:', response.status, response.statusText);
                        return;
                    }
                    
                    const data = await response.json();
                    if (data.success) {
                        demoSessionId = data.session.id;
                        document.getElementById('demoSessionId').value = demoSessionId;
                    } else {
                        console.error('Failed to create session:', data);
                    }
                } catch (error) {
                    console.error('Error creating session:', error);
                }
            }

            // Initialize session
            createDemoSession();

            // Send message
            document.getElementById('demoMessageForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const messageInput = document.getElementById('demoMessageInput');
                const message = messageInput.value.trim();
                
                if (!message) return;
                
                if (!demoSessionId) {
                    await createDemoSession();
                }
                
                // Add user message to UI immediately
                appendDemoMessage('user', message);
                messageInput.value = '';
                
                // Show typing indicator
                const typingIndicator = appendDemoTypingIndicator();
                
                try {
                    // Create AbortController for timeout
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
                    
                    const response = await fetch('/chat-demo/messages', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            session_id: demoSessionId,
                            message: message
                        }),
                        signal: controller.signal
                    });
                    
                    clearTimeout(timeoutId);
                    
                    // Check if response is OK (status 200-299)
                    if (!response.ok) {
                        console.error('HTTP error:', response.status, response.statusText);
                        typingIndicator.remove();
                        
                        // Try to get error message from response
                        let errorMessage = 'Gagal mengirim pesan. Silakan coba lagi.';
                        try {
                            const errorData = await response.json();
                            if (errorData.error) {
                                errorMessage = errorData.error;
                            } else if (errorData.message) {
                                errorMessage = errorData.message;
                            }
                        } catch (e) {
                            // If response is not JSON, use default message
                        }
                        
                        alert(errorMessage);
                        return;
                    }
                    
                    const data = await response.json();
                    
                    // Remove typing indicator
                    typingIndicator.remove();
                    
                    if (data.success) {
                        appendDemoMessage('bot', data.bot_message.message, data.intent, data.confidence);
                    } else {
                        // Handle case where success is false
                        console.error('Request failed:', data);
                        const errorMessage = data.error || data.message || 'Gagal mengirim pesan. Silakan coba lagi.';
                        alert(errorMessage);
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    typingIndicator.remove();
                    
                    let errorMessage = 'Gagal mengirim pesan. Silakan coba lagi.';
                    if (error.name === 'AbortError') {
                        errorMessage = 'Permintaan timeout. Server membutuhkan waktu terlalu lama untuk merespons. Silakan coba lagi.';
                    } else if (error.message && error.message.includes('Failed to fetch')) {
                        errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
                    }
                    
                    alert(errorMessage);
                }
            });

            // Append message to chat
            function appendDemoMessage(role, message, intent = null, confidence = null) {
                const messagesArea = document.getElementById('demoMessagesArea');
                
                // Remove welcome message if exists
                const welcomeMsg = messagesArea.querySelector('.text-center');
                if (welcomeMsg) {
                    messagesArea.innerHTML = '';
                }
                
                const messageDiv = document.createElement('div');
                messageDiv.className = role === 'user' ? 'flex justify-end' : 'flex justify-start';
                
                const now = new Date();
                const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
                
                if (role === 'user') {
                    messageDiv.innerHTML = `
                        <div class="max-w-[75%] sm:max-w-xs lg:max-w-md">
                            <div class="bg-whatsapp-500 text-white rounded-2xl rounded-tr-none px-4 py-3 shadow-md">
                                <p class="text-sm">${escapeHtml(message)}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-right">${time}</p>
                        </div>
                    `;
                } else {
                    let intentBadge = '';
                    if (intent) {
                        const confidencePercent = confidence ? Math.round(confidence * 100) : 0;
                        intentBadge = `
                            <div class="mt-2 flex items-center space-x-2">
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">${escapeHtml(intent)}</span>
                                ${confidence ? `<span class="text-xs text-gray-500">${confidencePercent}% confidence</span>` : ''}
                            </div>
                        `;
                    }
                    
                    messageDiv.innerHTML = `
                        <div class="max-w-[75%] sm:max-w-xs lg:max-w-md">
                            <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-4 py-3 shadow-md">
                                <p class="text-sm text-gray-800 whitespace-pre-line">${escapeHtml(message)}</p>
                                ${intentBadge}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">${time}</p>
                        </div>
                    `;
                }
                
                messagesArea.appendChild(messageDiv);
                messagesArea.scrollTop = messagesArea.scrollHeight;
            }

            function appendDemoTypingIndicator() {
                const messagesArea = document.getElementById('demoMessagesArea');
                const indicatorDiv = document.createElement('div');
                indicatorDiv.className = 'flex justify-start typing-indicator';
                indicatorDiv.innerHTML = `
                    <div class="max-w-xs lg:max-w-md">
                        <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-4 py-3 shadow-md">
                            <div class="flex space-x-2">
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                            </div>
                        </div>
                    </div>
                `;
                messagesArea.appendChild(indicatorDiv);
                messagesArea.scrollTop = messagesArea.scrollHeight;
                return indicatorDiv;
            }

            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }

            // Reset chat
            document.getElementById('demoResetBtn').addEventListener('click', async () => {
                if (!confirm('Yakin ingin mereset chat? Semua pesan akan dihapus.')) {
                    return;
                }
                
                try {
                    const response = await fetch('/chat-demo/reset', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Error resetting chat:', error);
                    alert('Gagal mereset chat');
                }
            });

            // Smooth scroll to chat demo
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>
    </body>
</html>
