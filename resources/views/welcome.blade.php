<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
            
            .card-3d:hover {
                transform: rotateY(5deg) rotateX(5deg) scale(1.05);
            }
            
            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            
            .gradient-blue {
                background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            }
            
            .text-shadow {
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            }
            
            .chat-bubble {
                position: relative;
                background: white;
                border-radius: 20px;
                padding: 20px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
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

                        <h1 class="text-xl font-bold text-blue-600">
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
                                    class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-150 ease-in-out shadow-md"
                                >
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 transition duration-150 ease-in-out"
                                >
                                    Masuk
                                </a>

                                @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition duration-150 ease-in-out shadow-md"
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
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
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
                                class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700"
                            >
                                Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50"
                            >
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="block px-3 py-2 rounded-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700"
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
            <div class="relative gradient-blue text-white overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 float-animation"></div>
                    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 float-animation" style="animation-delay: 2s;"></div>
                </div>
                
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24 lg:py-32">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <!-- Left Content -->
                        <div class="text-center lg:text-left">
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 text-shadow">
                                Sistem Informasi<br>Dukcapil Ponorogo
                            </h1>
                            <p class="text-xl sm:text-2xl mb-8 text-blue-100">
                                Layanan Administrasi Kependudukan Melalui WhatsApp Chatbot - Cepat, Mudah, dan Terpercaya
                            </p>
                            @if (Route::has('login'))
                                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                    @auth
                                        <a
                                            href="{{ url('/dashboard') }}"
                                            class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 transition duration-150 ease-in-out shadow-xl"
                                        >
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            Buka Dashboard
                                        </a>
                                    @else
                                        <a
                                            href="{{ route('login') }}"
                                            class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 transition duration-150 ease-in-out shadow-xl"
                                        >
                                            Masuk ke Sistem
                                        </a>
                                    @endauth
                                </div>
                            @endif
                        </div>

                        <!-- Right Content - 3D WhatsApp Chat Illustration -->
                        <div class="hidden lg:block">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-500 rounded-3xl blur-2xl opacity-30"></div>
                                <div class="relative bg-white rounded-3xl p-8 shadow-2xl">
                                    <div class="flex items-center mb-6">
                                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white text-2xl">
                                            💬
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-bold text-gray-900">CS DUKCAPIL</div>
                                            <div class="text-sm text-green-500">● Online</div>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div class="chat-bubble bg-gray-100 ml-0">
                                            <p class="text-gray-800">Halo! Selamat datang di layanan DUKCAPIL Ponorogo 👋</p>
                                        </div>
                                        
                                        <div class="flex justify-end">
                                            <div class="bg-blue-500 text-white rounded-2xl px-6 py-3 max-w-xs shadow-lg">
                                                Saya ingin mengurus KTP
                                            </div>
                                        </div>
                                        
                                        <div class="chat-bubble bg-gray-100 ml-0">
                                            <p class="text-gray-800">Baik, saya akan membantu proses pengurusan KTP Anda. Silakan kirim foto KK dan foto selfie untuk verifikasi 📄</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section with 3D Cards -->
            <div class="bg-gray-50 py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">
                            Layanan WhatsApp Chatbot
                        </h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                            Akses layanan administrasi kependudukan 24/7 melalui WhatsApp dengan mudah dan cepat
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                Pelayanan KTP
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Pengurusan Kartu Tanda Penduduk elektronik dengan proses yang cepat dan mudah melalui WhatsApp
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                Kartu Keluarga
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Layanan pembuatan dan perubahan data Kartu Keluarga secara online dengan mudah
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                Akta Kelahiran
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Pengurusan akta kelahiran untuk bayi baru lahir dengan proses yang praktis
                            </p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                Konsultasi Online
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Tanya jawab seputar persyaratan dan prosedur administrasi kependudukan
                            </p>
                        </div>

                        <!-- Feature 5 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                Tracking Status
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Cek status permohonan dokumen Anda secara real-time melalui chatbot
                            </p>
                        </div>

                        <!-- Feature 6 -->
                        <div class="card-3d bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition duration-300">
                            <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mb-6">
                                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                Layanan 24/7
                            </h3>
                            <p class="text-gray-600 leading-relaxed">
                                Akses layanan kapan saja, dimana saja tanpa harus datang ke kantor
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How It Works Section -->
            <div class="bg-white py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-4xl font-bold text-gray-900 mb-4">
                            Cara Menggunakan Layanan
                        </h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                            Mudah dan sederhana, hanya dengan WhatsApp Anda
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4 shadow-lg">
                                1
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Hubungi WhatsApp</h3>
                            <p class="text-gray-600">Kirim pesan ke nomor WhatsApp resmi DUKCAPIL Ponorogo</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4 shadow-lg">
                                2
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Pilih Layanan</h3>
                            <p class="text-gray-600">Chatbot akan memandu Anda memilih layanan yang dibutuhkan</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4 shadow-lg">
                                3
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Upload Dokumen</h3>
                            <p class="text-gray-600">Kirim dokumen persyaratan sesuai petunjuk chatbot</p>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4 shadow-lg">
                                4
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Selesai</h3>
                            <p class="text-gray-600">Dokumen Anda akan diproses dan Anda bisa tracking statusnya</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="gradient-blue text-white py-16">
                <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-6">
                        Mulai Gunakan Layanan Kami Sekarang
                    </h2>
                    <p class="text-xl mb-8 text-blue-100">
                        Layanan administrasi kependudukan yang modern, cepat, dan mudah diakses
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        {{-- TODO: Replace with actual WhatsApp number: https://wa.me/62XXXXXXXXXXX --}}
                        <a href="https://wa.me/6281234567890?text=Halo%20DUKCAPIL%20Ponorogo" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-lg font-medium rounded-lg text-white hover:bg-white hover:text-blue-600 transition duration-150 ease-in-out">
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Hubungi via WhatsApp
                        </a>
                        @if (Route::has('login'))
                            @guest
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-medium rounded-lg text-blue-600 bg-white hover:bg-blue-50 transition duration-150 ease-in-out shadow-xl">
                                    Login Admin
                                </a>
                            @endguest
                        @endif
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-xl font-bold mb-4">DUKCAPIL Ponorogo</h3>
                        <p class="text-gray-400">
                            Dinas Kependudukan dan Pencatatan Sipil Kabupaten Ponorogo
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-4">Kontak</h3>
                        <p class="text-gray-400">
                            Email: dukcapil@ponorogo.go.id<br>
                            Telepon: (0352) 123456<br>
                            WhatsApp: 0812-3456-7890 {{-- TODO: Replace with actual number --}}
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-4">Jam Layanan</h3>
                        <p class="text-gray-400">
                            Senin - Jumat: 08:00 - 16:00<br>
                            WhatsApp Chatbot: 24/7
                        </p>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
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
        </script>
    </body>
</html>
