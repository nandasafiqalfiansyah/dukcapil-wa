<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
                <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                {{ $bot->name }}
            </h2>
            <a href="{{ route('admin.bots.index') }}" class="text-white hover:text-whatsapp-100 font-medium flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Devices
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-whatsapp-100 border-l-4 border-whatsapp-500 text-whatsapp-700 px-4 py-3 rounded shadow-md" role="alert">
                    <span class="block sm:inline">✓ {{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded shadow-md" role="alert">
                    @foreach($errors->all() as $error)
                        <span class="block sm:inline">✗ {{ $error }}</span>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- QR Code / Connection Status (Main) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-whatsapp-500 to-whatsapp-600 p-6 text-white">
                            <h3 class="text-2xl font-bold flex items-center">
                                <svg class="h-8 w-8 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                Device Connection
                            </h3>
                            <p class="mt-2 text-whatsapp-100">Connect your WhatsApp to start managing messages</p>
                        </div>
                        
                        <div id="qr-code-container" class="p-8">
                            @if($bot->status === 'qr_generated' && $bot->qr_code)
                                <div class="text-center">
                                    <div class="bg-white p-6 rounded-xl shadow-inner inline-block mb-4">
                                        <img src="{{ $bot->qr_code }}" alt="QR Code" class="w-64 h-64 mx-auto">
                                    </div>
                                    <div class="bg-whatsapp-50 border-2 border-whatsapp-500 rounded-xl p-6 max-w-lg mx-auto">
                                        <h4 class="text-lg font-bold text-whatsapp-700 mb-4 flex items-center justify-center">
                                            <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                            </svg>
                                            How to Connect
                                        </h4>
                                        <ol class="text-left text-whatsapp-700 space-y-3">
                                            <li class="flex items-start">
                                                <span class="bg-whatsapp-500 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 font-bold">1</span>
                                                <span>Open <strong>WhatsApp</strong> on your phone</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="bg-whatsapp-500 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 font-bold">2</span>
                                                <span>Tap <strong>Menu</strong> (⋮) or <strong>Settings</strong></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="bg-whatsapp-500 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 font-bold">3</span>
                                                <span>Select <strong>Linked Devices</strong></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="bg-whatsapp-500 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 font-bold">4</span>
                                                <span>Tap <strong>Link a Device</strong></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="bg-whatsapp-500 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 font-bold">5</span>
                                                <span>Point your camera at this <strong>QR code</strong></span>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            @elseif($bot->isConnected())
                                <div class="text-center py-12">
                                    <div class="bg-whatsapp-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                                        <svg class="h-20 w-20 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-3xl font-bold text-whatsapp-600 mb-2">Connected!</h3>
                                    <p class="text-gray-600 text-lg">
                                        Your WhatsApp device is connected and ready to use.
                                    </p>
                                </div>
                            @elseif($bot->status === 'initializing')
                                <div class="text-center py-12">
                                    <div class="bg-blue-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                                        <svg class="animate-spin h-20 w-20 text-blue-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-3xl font-bold text-blue-600 mb-2">Initializing...</h3>
                                    <p class="text-gray-600 text-lg">
                                        Starting up the connection. QR code will appear shortly.
                                    </p>
                                </div>
                            @elseif($bot->status === 'auth_failed')
                                <div class="text-center py-12">
                                    <div class="bg-red-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                                        <svg class="h-20 w-20 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-3xl font-bold text-red-600 mb-2">Authentication Failed</h3>
                                    <p class="text-gray-600 text-lg mb-6">
                                        Unable to authenticate. Please try reinitializing the device.
                                    </p>
                                    <form action="{{ route('admin.bots.reinitialize', $bot) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-whatsapp-500 hover:bg-whatsapp-600 text-white font-bold py-3 px-8 rounded-lg transition duration-150">
                                            Reinitialize Device
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="bg-gray-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                                        <svg class="h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-3xl font-bold text-gray-700 mb-2">Not Connected</h3>
                                    <p class="text-gray-600 text-lg mb-6">
                                        Generate a QR code to connect your WhatsApp device.
                                    </p>
                                    <form action="{{ route('admin.bots.reinitialize', $bot) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-whatsapp-500 hover:bg-whatsapp-600 text-white font-bold py-3 px-8 rounded-lg transition duration-150">
                                            Generate QR Code
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Device Information Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Device Info Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-whatsapp-600 p-4 text-white">
                            <h3 class="text-lg font-bold">Device Information</h3>
                        </div>
                        
                        <div class="p-4 space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Device Name</label>
                                <p class="text-gray-900 font-medium">{{ $bot->name }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Device ID</label>
                                <p class="text-gray-900 font-mono text-sm">{{ $bot->bot_id }}</p>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Status</label>
                                <div id="bot-status">
                                    @php
                                        $statusColors = [
                                            'connected' => 'bg-whatsapp-100 text-whatsapp-800 border-whatsapp-500',
                                            'qr_generated' => 'bg-yellow-100 text-yellow-800 border-yellow-500',
                                            'initializing' => 'bg-blue-100 text-blue-800 border-blue-500',
                                            'disconnected' => 'bg-gray-100 text-gray-800 border-gray-500',
                                            'auth_failed' => 'bg-red-100 text-red-800 border-red-500',
                                            'not_initialized' => 'bg-gray-100 text-gray-800 border-gray-500',
                                        ];
                                        $color = $statusColors[$bot->status] ?? 'bg-gray-100 text-gray-800 border-gray-500';
                                    @endphp
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full border-2 {{ $color }}">
                                        {{ ucwords(str_replace('_', ' ', $bot->status)) }}
                                    </span>
                                </div>
                            </div>

                            @if($bot->phone_number)
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Phone Number</label>
                                    <p class="text-gray-900 font-mono">{{ $bot->phone_number }}</p>
                                </div>
                            @endif

                            @if($bot->platform)
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Platform</label>
                                    <p class="text-gray-900">{{ $bot->platform }}</p>
                                </div>
                            @endif

                            @if($bot->last_connected_at)
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Last Connected</label>
                                    <p class="text-gray-900">{{ $bot->last_connected_at->diffForHumans() }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-whatsapp-600 p-4 text-white">
                            <h3 class="text-lg font-bold">Device Actions</h3>
                        </div>
                        
                        <div class="p-4 space-y-3">
                            @if($bot->isConnected())
                                <form action="{{ route('admin.bots.disconnect', $bot) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                        Disconnect
                                    </button>
                                </form>
                            @endif

                            @if($bot->needsQrScan())
                                <form action="{{ route('admin.bots.reinitialize', $bot) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                        Reinitialize
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.bots.logout', $bot) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure? You will need to scan QR code again.')">
                                @csrf
                                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                    Logout Device
                                </button>
                            </form>

                            <form action="{{ route('admin.bots.destroy', $bot) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this device? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                    Delete Device
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh bot status every 5 seconds
        let refreshInterval;
        
        function refreshBotStatus() {
            fetch('{{ route('admin.bots.status', $bot) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.bot) {
                        const bot = data.bot;
                        
                        // Update status badge
                        const statusColors = {
                            'connected': 'bg-whatsapp-100 text-whatsapp-800 border-whatsapp-500',
                            'qr_generated': 'bg-yellow-100 text-yellow-800 border-yellow-500',
                            'initializing': 'bg-blue-100 text-blue-800 border-blue-500',
                            'disconnected': 'bg-gray-100 text-gray-800 border-gray-500',
                            'auth_failed': 'bg-red-100 text-red-800 border-red-500',
                            'not_initialized': 'bg-gray-100 text-gray-800 border-gray-500',
                        };
                        
                        const statusElement = document.getElementById('bot-status');
                        const color = statusColors[bot.status] || 'bg-gray-100 text-gray-800 border-gray-500';
                        const statusText = bot.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        statusElement.innerHTML = `<span class="inline-flex px-3 py-1 text-xs font-bold rounded-full border-2 ${color}">${statusText}</span>`;
                        
                        // Update QR code container
                        const qrContainer = document.getElementById('qr-code-container');
                        if (bot.status === 'qr_generated' && bot.qr_code) {
                            qrContainer.innerHTML = `
                                <div class="text-center">
                                    <div class="bg-white p-6 rounded-xl shadow-inner inline-block mb-4">
                                        <img src="${bot.qr_code}" alt="QR Code" class="w-64 h-64 mx-auto">
                                    </div>
                                    <div class="bg-whatsapp-50 border-2 border-whatsapp-500 rounded-xl p-6 max-w-lg mx-auto">
                                        <h4 class="text-lg font-bold text-whatsapp-700 mb-4 flex items-center justify-center">
                                            <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                            </svg>
                                            How to Connect
                                        </h4>
                                        <ol class="text-left text-whatsapp-700 space-y-3">
                                            <li class="flex items-start">
                                                <span class="bg-whatsapp-500 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 font-bold">1</span>
                                                <span>Open <strong>WhatsApp</strong> on your phone</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="bg-whatsapp-500 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 font-bold">2</span>
                                                <span>Tap <strong>Menu</strong> (⋮) or <strong>Settings</strong></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="bg-whatsapp-500 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 font-bold">3</span>
                                                <span>Select <strong>Linked Devices</strong></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="bg-whatsapp-500 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 font-bold">4</span>
                                                <span>Tap <strong>Link a Device</strong></span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="bg-whatsapp-500 text-white rounded-full w-6 h-6 flex items-center justify-center mr-3 flex-shrink-0 font-bold">5</span>
                                                <span>Point your camera at this <strong>QR code</strong></span>
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                            `;
                        } else if (bot.connected) {
                            qrContainer.innerHTML = `
                                <div class="text-center py-12">
                                    <div class="bg-whatsapp-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                                        <svg class="h-20 w-20 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-3xl font-bold text-whatsapp-600 mb-2">Connected!</h3>
                                    <p class="text-gray-600 text-lg">Your WhatsApp device is connected and ready to use.</p>
                                </div>
                            `;
                            // Stop refreshing when connected
                            clearInterval(refreshInterval);
                        }
                    }
                })
                .catch(error => console.error('Error refreshing bot status:', error));
        }

        // Start auto-refresh
        refreshInterval = setInterval(refreshBotStatus, 5000);
        
        // Clean up on page unload
        window.addEventListener('beforeunload', () => {
            clearInterval(refreshInterval);
        });
    </script>
</x-app-layout>
