<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
            <svg class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
            </svg>
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

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                        <h3 class="text-xl font-bold text-white">Requests by Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($requestsByStatus as $status => $count)
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-sm font-semibold text-gray-700">{{ ucfirst($status) }}</span>
                                        <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-whatsapp-500 h-3 rounded-full transition-all duration-300" style="width: {{ $stats['total_requests'] > 0 ? ($count / $stats['total_requests']) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                    <div class="bg-gradient-to-r from-whatsapp-500 to-whatsapp-600 p-4">
                        <h3 class="text-xl font-bold text-white">Requests by Service Type</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($requestsByType as $type => $count)
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-sm font-semibold text-gray-700">{{ strtoupper($type) }}</span>
                                        <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-blue-500 h-3 rounded-full transition-all duration-300" style="width: {{ $stats['total_requests'] > 0 ? ($count / $stats['total_requests']) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
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
        </div>
    </div>
</x-app-layout>
