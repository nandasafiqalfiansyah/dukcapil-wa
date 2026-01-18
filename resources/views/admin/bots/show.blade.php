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
                <!-- Connection Status (Main) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-whatsapp-500 to-whatsapp-600 p-6 text-white">
                            <h3 class="text-2xl font-bold flex items-center">
                                <svg class="h-8 w-8 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                                WhatsApp Business API
                            </h3>
                            <p class="mt-2 text-whatsapp-100">Manage your WhatsApp messages using Meta's official API</p>
                        </div>
                        
                        <div class="p-8">
                            @if($bot->isConnected())
                                <div class="text-center py-12">
                                    <div class="bg-whatsapp-100 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                                        <svg class="h-20 w-20 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-3xl font-bold text-whatsapp-600 mb-2">Connected!</h3>
                                    <p class="text-gray-600 text-lg">
                                        @if(isset($bot->metadata['api_type']) && $bot->metadata['api_type'] === 'fonnte')
                                            Your WhatsApp is connected via Fonnte API and ready to use.
                                        @else
                                            Your WhatsApp Business API is configured and ready to use.
                                        @endif
                                    </p>
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
                                        Configure your WhatsApp connection using Fonnte API.
                                    </p>
                                    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 max-w-lg mx-auto text-left">
                                        <p class="font-bold mb-2">Required Configuration:</p>
                                        <ul class="list-disc list-inside space-y-1 text-sm">
                                            <li>Fonnte Token (from your Fonnte dashboard)</li>
                                        </ul>
                                        <p class="mt-3 text-sm">
                                            Get your token from <a href="https://fonnte.com" target="_blank" class="underline font-semibold">Fonnte.com</a>
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Configuration Guide -->
                    <div class="mt-6 bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-blue-600 p-4 text-white">
                            <h3 class="text-lg font-bold">Setup Guide - Fonnte API</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4 text-gray-700">
                                <div class="flex items-start">
                                    <span class="bg-whatsapp-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 font-bold">1</span>
                                    <div>
                                        <p class="font-semibold">Register at Fonnte</p>
                                        <p class="text-sm text-gray-600">Visit <a href="https://fonnte.com" target="_blank" class="text-blue-600 underline">Fonnte.com</a> and create a free account</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-whatsapp-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 font-bold">2</span>
                                    <div>
                                        <p class="font-semibold">Connect Your WhatsApp</p>
                                        <p class="text-sm text-gray-600">Scan the QR code in Fonnte dashboard with your WhatsApp to connect your number</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-whatsapp-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 font-bold">3</span>
                                    <div>
                                        <p class="font-semibold">Get Your Token</p>
                                        <p class="text-sm text-gray-600">Copy your API token from the Fonnte dashboard</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-whatsapp-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 font-bold">4</span>
                                    <div>
                                        <p class="font-semibold">Configure Bot</p>
                                        <p class="text-sm text-gray-600">Create a new bot and enter your Fonnte token, or add it to your .env file: <code class="bg-gray-100 px-2 py-1 rounded">FONNTE_TOKEN=your_token</code></p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <span class="bg-whatsapp-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 flex-shrink-0 font-bold">5</span>
                                    <div>
                                        <p class="font-semibold">Start Messaging</p>
                                        <p class="text-sm text-gray-600">Your bot is now ready to send and receive WhatsApp messages!</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4">
                                <p class="font-bold mb-2">✨ Benefits of Fonnte API:</p>
                                <ul class="list-disc list-inside space-y-1 text-sm">
                                    <li>No Facebook Business account required</li>
                                    <li>Easy setup with QR code scanning</li>
                                    <li>Affordable pricing for small businesses</li>
                                    <li>Webhook support for incoming messages</li>
                                    <li>Multi-device support</li>
                                </ul>
                            </div>
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
                                <div>
                                    @php
                                        $statusColors = [
                                            'connected' => 'bg-whatsapp-100 text-whatsapp-800 border-whatsapp-500',
                                            'disconnected' => 'bg-gray-100 text-gray-800 border-gray-500',
                                            'not_initialized' => 'bg-gray-100 text-gray-800 border-gray-500',
                                        ];
                                        $color = $statusColors[$bot->status] ?? 'bg-gray-100 text-gray-800 border-gray-500';
                                    @endphp
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full border-2 {{ $color }}">
                                        {{ ucwords(str_replace('_', ' ', $bot->status)) }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Phone Number</label>
                                <div class="flex items-center gap-2">
                                    <p class="text-gray-900 font-mono text-sm flex-1">{{ $bot->phone_number ?? 'Not set' }}</p>
                                    <button type="button" class="text-blue-500 hover:text-blue-700" onclick="openPhoneModal()">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">WhatsApp Link</label>
                                <div class="flex items-center gap-2">
                                    @if($bot->metadata['wa_link'] ?? null)
                                        <a href="{{ $bot->metadata['wa_link'] }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline text-sm flex-1 truncate">
                                            {{ $bot->metadata['wa_link'] }}
                                        </a>
                                    @else
                                        <p class="text-gray-500 text-sm flex-1">Not configured</p>
                                    @endif
                                    <button type="button" class="text-blue-500 hover:text-blue-700" onclick="openLinkModal()">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase">Platform</label>
                                <p class="text-gray-900">
                                    @if(isset($bot->metadata['api_type']) && $bot->metadata['api_type'] === 'fonnte')
                                        Fonnte WhatsApp API
                                    @else
                                        WhatsApp Business API
                                    @endif
                                </p>
                            </div>

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
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $bot->phone_number ?? '') }}{{ isset($bot->metadata['wa_message']) ? '?text=' . urlencode($bot->metadata['wa_message']) : '' }}" target="_blank" class="w-full block bg-whatsapp-500 hover:bg-whatsapp-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150 text-center">
                                    <span class="flex items-center justify-center">
                                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        Start Chat
                                    </span>
                                </a>

                                <form action="{{ route('admin.bots.disconnect', $bot) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                        Deactivate
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.bots.reinitialize', $bot) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                        Activate
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.bots.logout', $bot) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure? This will remove the bot configuration.')">
                                @csrf
                                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                    Remove Configuration
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

                    <!-- Statistics Card -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-whatsapp-600 p-4 text-white">
                            <h3 class="text-lg font-bold">Statistics</h3>
                        </div>
                        
                        <div class="p-4 space-y-3">
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600">Messages Received</span>
                                <span class="font-bold text-gray-900">{{ $bot->conversationLogs()->where('direction', 'incoming')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b">
                                <span class="text-gray-600">Messages Sent</span>
                                <span class="font-bold text-gray-900">{{ $bot->conversationLogs()->where('direction', 'outgoing')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Total Conversations</span>
                                <span class="font-bold text-gray-900">{{ $bot->conversationLogs()->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit Phone Number -->
            <div id="phoneModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Edit Phone Number</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closePhoneModal()">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.bots.updatePhone', $bot) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number (format: 62812345678)
                            </label>
                            <input type="text" name="phone_number" id="phoneInput" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-whatsapp-500" 
                                   value="{{ $bot->phone_number ?? '' }}" 
                                   placeholder="62812345678"
                                   pattern="[0-9]+"
                                   required>
                            <p class="mt-2 text-xs text-gray-500">
                                Masukkan nomor tanpa kode negara (+) atau tanda pemisah. Contoh: 62812345678
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" 
                                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-150"
                                    onclick="closePhoneModal()">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="flex-1 bg-whatsapp-500 hover:bg-whatsapp-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Edit WhatsApp Link -->
            <div id="linkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Edit WhatsApp Link</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeLinkModal()">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.bots.updateLink', $bot) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                WhatsApp Link (wa.me format)
                            </label>
                            <input type="url" name="wa_link" id="linkInput" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-whatsapp-500" 
                                   value="{{ $bot->metadata['wa_link'] ?? '' }}" 
                                   placeholder="https://wa.me/62812345678"
                                   required>
                            <p class="mt-2 text-xs text-gray-500">
                                Contoh: https://wa.me/62812345678 atau https://wa.me/62812345678?text=Hello
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pesan Default (Opsional)
                            </label>
                            <textarea name="wa_message" id="messageInput" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-whatsapp-500" 
                                      rows="3"
                                      placeholder="Pesan otomatis yang akan dikirim saat user membuka chat">{{ $bot->metadata['wa_message'] ?? '' }}</textarea>
                        </div>

                        <div class="flex gap-3">
                            <button type="button" 
                                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition duration-150"
                                    onclick="closeLinkModal()">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="flex-1 bg-whatsapp-500 hover:bg-whatsapp-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Scripts for Modals -->
            <script>
                function openPhoneModal() {
                    document.getElementById('phoneModal').classList.remove('hidden');
                    document.getElementById('phoneInput').focus();
                }

                function closePhoneModal() {
                    document.getElementById('phoneModal').classList.add('hidden');
                }

                function openLinkModal() {
                    document.getElementById('linkModal').classList.remove('hidden');
                    document.getElementById('linkInput').focus();
                }

                function closeLinkModal() {
                    document.getElementById('linkModal').classList.add('hidden');
                }

                // Close modal when clicking outside
                window.addEventListener('click', function(event) {
                    const phoneModal = document.getElementById('phoneModal');
                    const linkModal = document.getElementById('linkModal');
                    
                    if (event.target === phoneModal) {
                        closePhoneModal();
                    }
                    if (event.target === linkModal) {
                        closeLinkModal();
                    }
                });

                // Allow closing with Escape key
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        closePhoneModal();
                        closeLinkModal();
                    }
                });
            </script>
        </div>
    </div>
</x-app-layout>
