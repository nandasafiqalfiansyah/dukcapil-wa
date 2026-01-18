<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
            {{ __('Dashboard Admin DUKCAPIL Ponorogo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Actions -->
            <div class="bg-gradient-to-r from-whatsapp-600 to-whatsapp-700 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Quick Actions
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.chatbot.index') }}" class="bg-white hover:bg-whatsapp-50 rounded-lg p-4 transition duration-150 group">
                        <div class="flex items-center">
                            <div class="bg-whatsapp-100 group-hover:bg-whatsapp-200 rounded-lg p-3 transition">
                                <svg class="h-6 w-6 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-900">Test Chatbot</p>
                                <p class="text-xs text-gray-500">Try the bot</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.chat-config.index') }}" class="bg-white hover:bg-blue-50 rounded-lg p-4 transition duration-150 group">
                        <div class="flex items-center">
                            <div class="bg-blue-100 group-hover:bg-blue-200 rounded-lg p-3 transition">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-900">Bot Settings</p>
                                <p class="text-xs text-gray-500">Configure bot</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('welcome') }}#try-chatbot" target="_blank" class="bg-white hover:bg-purple-50 rounded-lg p-4 transition duration-150 group">
                        <div class="flex items-center">
                            <div class="bg-purple-100 group-hover:bg-purple-200 rounded-lg p-3 transition">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-900">Public Demo</p>
                                <p class="text-xs text-gray-500">Preview page</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('admin.auto-replies.index') }}" class="bg-white hover:bg-yellow-50 rounded-lg p-4 transition duration-150 group">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 group-hover:bg-yellow-200 rounded-lg p-3 transition">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-900">Auto Replies</p>
                                <p class="text-xs text-gray-500">Manage replies</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-whatsapp-500 transform hover:scale-105 transition duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-whatsapp-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm text-gray-500 font-medium">WhatsApp Devices</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['connected_bots'] }}/{{ $stats['total_bots'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-whatsapp-600 font-semibold">✓ {{ $stats['connected_bots'] }} Connected</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-blue-500 transform hover:scale-105 transition duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm text-gray-500 font-medium">Total Users</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-blue-600 font-semibold">✓ {{ $stats['verified_users'] }} Verified</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-yellow-500 transform hover:scale-105 transition duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm text-gray-500 font-medium">Pending Requests</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_requests'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-gray-600 font-semibold">of {{ $stats['total_requests'] }} total</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-red-500 transform hover:scale-105 transition duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm text-gray-500 font-medium">Escalated</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['escalated_requests'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-red-600 font-semibold">⚠ Needs attention</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-whatsapp-600 transform hover:scale-105 transition duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-whatsapp-600 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm text-gray-500 font-medium">Today's Chats</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $stats['conversations_today'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-whatsapp-600 font-semibold">↔ In & Out messages</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Chat Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-blue-500">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h10" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm text-gray-500 font-medium">Messages Received (WA)</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['wa_messages_received'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-purple-500">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 8h4a2 2 0 012 2v6a2 2 0 01-2 2h-6l-5 4v-4H7" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm text-gray-500 font-medium">Messages Sent (WA)</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['wa_messages_sent'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-green-500">
                    <div class="p-6 flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4a2 2 0 00-2-2H4a2 2 0 00-2 2v16h5" />
                            </svg>
                        </div>
                        <div class="ml-5">
                            <p class="text-sm text-gray-500 font-medium">Total Conversations (WA)</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['wa_total_conversations'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bot Instances Tracking -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                <div class="bg-gradient-to-r from-whatsapp-500 to-whatsapp-600 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            WhatsApp Device Status
                        </h3>
                        <a href="{{ route('admin.bots.index') }}" class="text-sm text-white hover:text-whatsapp-100 font-medium bg-whatsapp-700 px-4 py-2 rounded-lg">
                            Manage Devices →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($botInstances->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-whatsapp-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Device Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Phone Number</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Last Connected</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($botInstances as $bot)
                                        <tr class="hover:bg-whatsapp-50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-whatsapp-100 flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-whatsapp-600" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-bold text-gray-900">{{ $bot->name }}</div>
                                                        <div class="text-sm text-gray-500 font-mono">{{ $bot->bot_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                                {{ $bot->phone_number ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                                <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full border-2 {{ $color }}">
                                                    @if($bot->status === 'connected')
                                                        <span class="flex items-center">
                                                            <span class="h-2 w-2 rounded-full bg-whatsapp-500 mr-1 animate-pulse"></span>
                                                            Connected
                                                        </span>
                                                    @else
                                                        {{ ucwords(str_replace('_', ' ', $bot->status)) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $bot->last_connected_at ? $bot->last_connected_at->diffForHumans() : 'Never' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.bots.show', $bot) }}" class="text-whatsapp-600 hover:text-whatsapp-700 font-bold">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">No WhatsApp Devices</h3>
                            <p class="text-gray-500 mb-4">Connect your first WhatsApp device to start managing messages.</p>
                            <a href="{{ route('admin.bots.create') }}" class="inline-block bg-whatsapp-500 hover:bg-whatsapp-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition duration-150">
                                Add Your First Device
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available WhatsApp Links -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            Available WhatsApp Links
                        </h3>
                        <span class="text-sm text-white font-medium bg-green-700 px-3 py-1 rounded-full">
                            {{ $whatsappLinks->count() }} Available
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @if($whatsappLinks->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($whatsappLinks as $link)
                                <div class="bg-gradient-to-br from-white to-green-50 rounded-lg border border-green-200 p-4 hover:shadow-md transition duration-150">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 mb-1">{{ $link['name'] }}</h4>
                                            <p class="text-xs text-gray-600 font-mono">{{ $link['phone_number'] }}</p>
                                        </div>
                                        <div class="bg-green-500 rounded-full p-2">
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    @if($link['message'])
                                        <p class="text-xs text-gray-600 mb-3 bg-white px-2 py-1 rounded border border-green-100">
                                            <strong>Pesan:</strong> {{ Str::limit($link['message'], 50) }}
                                        </p>
                                    @endif
                                    
                                    <div class="flex gap-2">
                                        <a href="{{ $link['link'] }}" target="_blank" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-3 rounded text-center text-sm transition duration-150 flex items-center justify-center gap-1">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                            Chat
                                        </a>
                                        <a href="{{ route('admin.bots.show', $link['id']) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-3 rounded text-center text-sm transition duration-150">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.658 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            <h3 class="text-lg font-bold text-gray-700 mb-2">No WhatsApp Links Available</h3>
                            <p class="text-gray-500">Connect and configure your WhatsApp devices to show links here.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Chat Logs -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                <div class="bg-gradient-to-r from-whatsapp-600 to-whatsapp-700 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            Recent Chat Logs
                        </h3>
                        <a href="{{ route('admin.chatbot.index') }}" class="text-sm text-white hover:text-whatsapp-100 font-medium bg-whatsapp-800 px-4 py-2 rounded-lg">
                            Open Chatbot →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto overflow-y-auto max-h-96">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-whatsapp-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Direction</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Message</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Intent</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Confidence</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-whatsapp-700 uppercase tracking-wider">Session</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentChatLogs as $msg)
                                    <tr class="hover:bg-whatsapp-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $msg->created_at->format('H:i:s') }}
                                            <div class="text-xs text-gray-400">{{ $msg->created_at->format('d M') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $isBot = $msg->role === 'bot';
                                                $badgeClass = $isBot ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800';
                                                $label = $isBot ? 'Outgoing (AI)' : 'Incoming (User)';
                                            @endphp
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeClass }}">
                                                {{ $label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="max-w-md truncate">{{ $msg->message }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($msg->role === 'bot' && $msg->intent)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    {{ $msg->intent }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $confidencePercent = $msg->confidence ? round($msg->confidence * 100) : null;
                                                $colorClass = $confidencePercent >= 70 ? 'bg-green-100 text-green-800' : ($confidencePercent >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                            @endphp
                                            @if($confidencePercent !== null)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                    {{ $confidencePercent }}%
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            #{{ $msg->chat_session_id }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                            </svg>
                                            No chat logs yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                <div class="bg-gradient-to-r from-gray-700 to-gray-800 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white">Recent Requests</h3>
                        <a href="{{ route('admin.service-requests.index') }}" class="text-sm text-white hover:text-gray-200 font-medium bg-gray-600 px-4 py-2 rounded-lg">
                            View All →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Request Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Service Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentRequests as $request)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('admin.service-requests.show', $request) }}" class="text-sm font-bold text-whatsapp-600 hover:text-whatsapp-700">
                                                {{ $request->request_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            {{ $request->whatsappUser->name ?? $request->whatsappUser->phone_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                            {{ strtoupper($request->service_type) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full border-2
                                                @if($request->status === 'pending') bg-yellow-100 text-yellow-800 border-yellow-500
                                                @elseif($request->status === 'completed') bg-whatsapp-100 text-whatsapp-800 border-whatsapp-500
                                                @elseif($request->status === 'rejected') bg-red-100 text-red-800 border-red-500
                                                @else bg-blue-100 text-blue-800 border-blue-500
                                                @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs font-bold rounded-full border-2
                                                @if($request->priority === 'urgent') bg-red-100 text-red-800 border-red-500
                                                @elseif($request->priority === 'high') bg-orange-100 text-orange-800 border-orange-500
                                                @else bg-gray-100 text-gray-800 border-gray-500
                                                @endif">
                                                {{ ucfirst($request->priority) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $request->created_at->format('d M Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            No requests found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- All Chat Logs -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            All Chat Logs
                        </h3>
                        <a href="{{ route('admin.nlp-logs.index') }}" class="text-sm text-white hover:text-purple-100 font-medium bg-purple-800 px-4 py-2 rounded-lg">
                            View All Logs →
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto overflow-y-auto max-h-96">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-purple-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">Message</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">Intent</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">Confidence</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-purple-700 uppercase tracking-wider">Session</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentNlpLogs as $log)
                                    <tr class="hover:bg-purple-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->created_at->format('H:i:s') }}
                                            <div class="text-xs text-gray-400">{{ $log->created_at->format('d M') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="flex items-center space-x-2 max-w-md">
                                                @php $isBot = $log->role === 'bot'; @endphp
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $isBot ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ $isBot ? 'AI' : 'User' }}
                                                </span>
                                                <span class="truncate">{{ $log->message }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($log->role === 'bot' && $log->intent)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    {{ $log->intent }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $confidencePercent = $log->confidence ? round($log->confidence * 100) : null;
                                                $colorClass = $confidencePercent >= 70 ? 'bg-green-100 text-green-800' : ($confidencePercent >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                            @endphp
                                            @if($confidencePercent !== null)
                                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                                    {{ $confidencePercent }}%
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                            #{{ $log->chat_session_id }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                            No chat logs yet
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
