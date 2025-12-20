<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $bot->name }}
            </h2>
            <a href="{{ route('admin.bots.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                ← Back to Bots
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    @foreach($errors->all() as $error)
                        <span class="block sm:inline">{{ $error }}</span>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bot Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Bot Information</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Bot ID</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $bot->bot_id }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</label>
                                <div id="bot-status">
                                    @php
                                        $statusColors = [
                                            'connected' => 'bg-green-100 text-green-800',
                                            'qr_generated' => 'bg-yellow-100 text-yellow-800',
                                            'initializing' => 'bg-blue-100 text-blue-800',
                                            'disconnected' => 'bg-gray-100 text-gray-800',
                                            'auth_failed' => 'bg-red-100 text-red-800',
                                            'not_initialized' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $statusColors[$bot->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                        {{ ucwords(str_replace('_', ' ', $bot->status)) }}
                                    </span>
                                </div>
                            </div>

                            @if($bot->phone_number)
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</label>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $bot->phone_number }}</p>
                                </div>
                            @endif

                            @if($bot->platform)
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Platform</label>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $bot->platform }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Active</label>
                                <p class="text-gray-900 dark:text-gray-100">{{ $bot->is_active ? 'Yes' : 'No' }}</p>
                            </div>

                            @if($bot->last_connected_at)
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Connected</label>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $bot->last_connected_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            @endif

                            @if($bot->last_disconnected_at)
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Disconnected</label>
                                    <p class="text-gray-900 dark:text-gray-100">{{ $bot->last_disconnected_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 space-y-2">
                            @if($bot->isConnected())
                                <form action="{{ route('admin.bots.disconnect', $bot) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        Disconnect Bot
                                    </button>
                                </form>
                            @endif

                            @if($bot->needsQrScan())
                                <form action="{{ route('admin.bots.reinitialize', $bot) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                        Reinitialize Bot
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.bots.logout', $bot) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to logout this bot? This will remove the session and you will need to scan QR code again.')">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-white bg-orange-600 hover:bg-orange-700 focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Logout Bot
                                </button>
                            </form>

                            <form action="{{ route('admin.bots.destroy', $bot) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this bot? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                    Delete Bot
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- QR Code Display -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Connection</h3>
                        
                        <div id="qr-code-container" class="text-center">
                            @if($bot->status === 'qr_generated' && $bot->qr_code)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        Scan this QR code with WhatsApp on your phone to connect the bot.
                                    </p>
                                    <img src="{{ $bot->qr_code }}" alt="QR Code" class="mx-auto border-4 border-gray-200 dark:border-gray-700 rounded-lg">
                                </div>
                            @elseif($bot->isConnected())
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">Bot Connected!</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Your bot is connected and ready to send/receive messages.
                                    </p>
                                </div>
                            @elseif($bot->status === 'initializing')
                                <div class="text-center py-8">
                                    <svg class="animate-spin mx-auto h-16 w-16 text-blue-500" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">Initializing...</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Bot is starting up. QR code will appear shortly.
                                    </p>
                                </div>
                            @elseif($bot->status === 'auth_failed')
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-16 w-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">Authentication Failed</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Failed to authenticate. Please try reinitializing the bot.
                                    </p>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">Bot Not Connected</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Click "Reinitialize Bot" to generate a new QR code.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2">
                                📱 How to connect:
                            </h4>
                            <ol class="text-sm text-blue-700 dark:text-blue-400 list-decimal list-inside space-y-1">
                                <li>Open WhatsApp on your phone</li>
                                <li>Tap Menu or Settings and select Linked Devices</li>
                                <li>Tap on Link a Device</li>
                                <li>Point your phone at the QR code on this screen</li>
                            </ol>
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
                            'connected': 'bg-green-100 text-green-800',
                            'qr_generated': 'bg-yellow-100 text-yellow-800',
                            'initializing': 'bg-blue-100 text-blue-800',
                            'disconnected': 'bg-gray-100 text-gray-800',
                            'auth_failed': 'bg-red-100 text-red-800',
                            'not_initialized': 'bg-gray-100 text-gray-800',
                        };
                        
                        const statusElement = document.getElementById('bot-status');
                        const color = statusColors[bot.status] || 'bg-gray-100 text-gray-800';
                        const statusText = bot.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        statusElement.innerHTML = `<span class="inline-flex px-2 py-1 text-xs leading-5 font-semibold rounded-full ${color}">${statusText}</span>`;
                        
                        // Update QR code if available
                        const qrContainer = document.getElementById('qr-code-container');
                        if (bot.status === 'qr_generated' && bot.qr_code) {
                            qrContainer.innerHTML = `
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        Scan this QR code with WhatsApp on your phone to connect the bot.
                                    </p>
                                    <img src="${bot.qr_code}" alt="QR Code" class="mx-auto border-4 border-gray-200 dark:border-gray-700 rounded-lg">
                                </div>
                            `;
                        } else if (bot.connected) {
                            qrContainer.innerHTML = `
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-100">Bot Connected!</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Your bot is connected and ready to send/receive messages.
                                    </p>
                                </div>
                            `;
                            // Stop polling when connected
                            clearInterval(refreshInterval);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error refreshing bot status:', error);
                });
        }
        
        // Start polling if not connected
        @if(!$bot->isConnected())
            refreshInterval = setInterval(refreshBotStatus, 5000);
        @endif
        
        // Stop polling when page is hidden
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                clearInterval(refreshInterval);
            } else if (!{{ $bot->isConnected() ? 'true' : 'false' }}) {
                refreshInterval = setInterval(refreshBotStatus, 5000);
            }
        });
    </script>
</x-app-layout>
