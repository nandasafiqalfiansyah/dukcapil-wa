<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
                <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                {{ __('WhatsApp Device Management') }}
            </h2>
            <a href="{{ route('admin.bots.create') }}" class="bg-white hover:bg-whatsapp-50 text-whatsapp-600 font-bold py-2 px-6 rounded-lg shadow-lg transition duration-150 ease-in-out">
                <span class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Add New Device') }}
                </span>
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

            @if($bots->count() > 0)
                <!-- Grid View for Bots -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($bots as $bot)
                        <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition duration-300 overflow-hidden border-2 {{ $bot->isConnected() ? 'border-whatsapp-500' : 'border-gray-200' }}">
                            <div class="bg-gradient-to-r {{ $bot->isConnected() ? 'from-whatsapp-500 to-whatsapp-600' : 'from-gray-400 to-gray-500' }} p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-white rounded-full p-2">
                                            <svg class="h-8 w-8 {{ $bot->isConnected() ? 'text-whatsapp-600' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-white font-bold text-lg">{{ $bot->name }}</h3>
                                            <p class="text-white text-xs opacity-90">ID: {{ $bot->bot_id }}</p>
                                        </div>
                                    </div>
                                    @php
                                        $statusIcons = [
                                            'connected' => '✓',
                                            'qr_generated' => '◷',
                                            'initializing' => '⟳',
                                            'disconnected' => '✕',
                                            'auth_failed' => '⚠',
                                            'not_initialized' => '○',
                                        ];
                                        $icon = $statusIcons[$bot->status] ?? '○';
                                    @endphp
                                    <div class="bg-white rounded-full w-10 h-10 flex items-center justify-center">
                                        <span class="text-2xl">{{ $icon }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 font-medium">Status</span>
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
                                    <span class="px-3 py-1 text-xs font-bold rounded-full border-2 {{ $color }}">
                                        {{ ucwords(str_replace('_', ' ', $bot->status)) }}
                                    </span>
                                </div>
                                
                                @if($bot->phone_number)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600 font-medium">Phone</span>
                                        <span class="text-sm text-gray-900 font-mono">{{ $bot->phone_number }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 font-medium">Last Active</span>
                                    <span class="text-sm text-gray-900">{{ $bot->last_connected_at ? $bot->last_connected_at->diffForHumans() : 'Never' }}</span>
                                </div>
                                
                                <div class="pt-3 border-t flex space-x-2">
                                    <a href="{{ route('admin.bots.show', $bot) }}" class="flex-1 bg-whatsapp-500 hover:bg-whatsapp-600 text-white font-bold py-2 px-4 rounded-lg text-center transition duration-150">
                                        Manage
                                    </a>
                                    @if($bot->needsQrScan())
                                        <form action="{{ route('admin.bots.reinitialize', $bot) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150">
                                                Scan QR
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $bots->links() }}
                </div>
            @else
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">No Devices Connected</h3>
                    <p class="text-gray-500 mb-6">Connect your WhatsApp device to start managing conversations</p>
                    <a href="{{ route('admin.bots.create') }}" class="inline-block bg-whatsapp-500 hover:bg-whatsapp-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-150 ease-in-out">
                        <span class="flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Your First Device
                        </span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
