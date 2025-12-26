<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center">
            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            {{ __('Chat Bot Testing') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl">
                <div class="flex h-[calc(100vh-200px)]">
                    <!-- Sidebar - Chat Sessions -->
                    <div class="w-64 bg-gray-900 text-white flex flex-col">
                        <!-- New Chat Button -->
                        <div class="p-4 border-b border-gray-700">
                            <button id="newChatBtn" class="w-full flex items-center justify-center space-x-2 bg-whatsapp-600 hover:bg-whatsapp-700 text-white font-bold py-3 px-4 rounded-lg transition duration-150">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>New Chat</span>
                            </button>
                        </div>

                        <!-- Chat Sessions List -->
                        <div id="sessionsList" class="flex-1 overflow-y-auto p-2 space-y-1">
                            @foreach($sessions as $session)
                                <div class="session-item p-3 rounded-lg cursor-pointer hover:bg-gray-800 transition duration-150 {{ $currentSession && $currentSession->id == $session->id ? 'bg-gray-800' : '' }}" 
                                     data-session-id="{{ $session->id }}"
                                     onclick="loadSession({{ $session->id }})">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium truncate">{{ $session->title }}</p>
                                            <p class="text-xs text-gray-400">{{ $session->updated_at->diffForHumans() }}</p>
                                        </div>
                                        <button onclick="deleteSession({{ $session->id }}, event)" class="text-gray-400 hover:text-red-500 ml-2">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    @if($session->is_connected_to_whatsapp)
                                        <div class="mt-1 flex items-center space-x-1 text-xs text-whatsapp-400">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                            <span>WA Connected</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Main Chat Area -->
                    <div class="flex-1 flex flex-col">
                        <!-- Chat Header -->
                        <div class="bg-gradient-to-r from-whatsapp-600 to-whatsapp-700 text-white p-4 border-b border-whatsapp-800 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold" id="chatTitle">
                                    @if($currentSession)
                                        {{ $currentSession->title }}
                                    @else
                                        Select or create a chat
                                    @endif
                                </h3>
                                <p class="text-xs text-whatsapp-100" id="chatSubtitle">Test your bot responses</p>
                            </div>
                            @if($currentSession)
                                <button onclick="openSettingsModal()" class="text-white hover:text-whatsapp-100 p-2 rounded-lg hover:bg-whatsapp-800 transition duration-150">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                        <!-- Messages Area -->
                        <div id="messagesArea" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
                            @if($currentSession && $messages->count() > 0)
                                @foreach($messages as $message)
                                    @if($message->role === 'user')
                                        <!-- User Message -->
                                        <div class="flex justify-end">
                                            <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                                                <div class="bg-whatsapp-500 text-white rounded-2xl rounded-tr-none px-4 py-3 shadow-md">
                                                    <p class="text-sm">{{ $message->message }}</p>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1 text-right">{{ $message->created_at->format('H:i') }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Bot Message -->
                                        <div class="flex justify-start">
                                            <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                                                <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-4 py-3 shadow-md">
                                                    <p class="text-sm text-gray-800">{{ $message->message }}</p>
                                                    @if($message->intent)
                                                        <div class="mt-2 flex items-center space-x-2">
                                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $message->intent }}</span>
                                                            @if($message->confidence)
                                                                <span class="text-xs text-gray-500">{{ round($message->confidence * 100) }}%</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <div class="text-center">
                                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-700 mb-2">No messages yet</h3>
                                        <p class="text-gray-500">Start a conversation with the bot!</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Input Area -->
                        <div class="border-t border-gray-200 p-4 bg-white">
                            <form id="messageForm" class="flex space-x-2">
                                <input type="hidden" id="currentSessionId" value="{{ $currentSession?->id ?? '' }}">
                                <input 
                                    type="text" 
                                    id="messageInput" 
                                    placeholder="Type your message..." 
                                    class="flex-1 rounded-full border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200 px-4 py-3"
                                    {{ !$currentSession ? 'disabled' : '' }}
                                >
                                <button 
                                    type="submit" 
                                    id="sendBtn"
                                    class="bg-whatsapp-600 hover:bg-whatsapp-700 text-white font-bold p-3 rounded-full transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ !$currentSession ? 'disabled' : '' }}
                                >
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <!-- Settings Modal -->
    <div id="settingsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Chat Settings</h3>
                <form id="settingsForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chat Title</label>
                        <input type="text" id="sessionTitle" class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200" value="{{ $currentSession?->title ?? '' }}">
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="connectWhatsApp" class="rounded border-gray-300 text-whatsapp-600 focus:ring-whatsapp-500" {{ $currentSession?->is_connected_to_whatsapp ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Connect to WhatsApp</span>
                        </label>
                    </div>
                    
                    <div id="whatsappSettings" class="{{ $currentSession?->is_connected_to_whatsapp ? '' : 'hidden' }}">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
                            <input type="text" id="whatsappNumber" placeholder="628123456789" class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200" value="{{ $currentSession?->whatsapp_number ?? '' }}">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bot Instance</label>
                            <select id="botInstance" class="w-full rounded-lg border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200">
                                <option value="">Select Bot</option>
                                @foreach($botInstances as $bot)
                                    <option value="{{ $bot->id }}" {{ $currentSession?->bot_instance_id == $bot->id ? 'selected' : '' }}>{{ $bot->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeSettingsModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-whatsapp-600 text-white rounded-lg hover:bg-whatsapp-700">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Create new chat session
        document.getElementById('newChatBtn').addEventListener('click', async () => {
            try {
                const response = await fetch('{{ route("admin.chatbot.sessions.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.href = '{{ route("admin.chatbot.index") }}?session=' + data.session.id;
                }
            } catch (error) {
                console.error('Error creating session:', error);
                alert('Failed to create new chat session');
            }
        });

        // Send message
        document.getElementById('messageForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            const sessionId = document.getElementById('currentSessionId').value;
            
            if (!message || !sessionId) return;
            
            // Add user message to UI immediately
            appendMessage('user', message);
            messageInput.value = '';
            
            // Show typing indicator
            const typingIndicator = appendTypingIndicator();
            
            try {
                const response = await fetch('{{ route("admin.chatbot.messages.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        message: message
                    })
                });
                
                const data = await response.json();
                
                // Remove typing indicator
                typingIndicator.remove();
                
                if (data.success) {
                    appendMessage('bot', data.bot_message.message, data.intent, data.confidence);
                }
            } catch (error) {
                console.error('Error sending message:', error);
                typingIndicator.remove();
                alert('Failed to send message');
            }
        });

        // Append message to chat
        function appendMessage(role, message, intent = null, confidence = null) {
            const messagesArea = document.getElementById('messagesArea');
            const messageDiv = document.createElement('div');
            messageDiv.className = role === 'user' ? 'flex justify-end' : 'flex justify-start';
            
            const now = new Date();
            const time = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
            
            if (role === 'user') {
                messageDiv.innerHTML = `
                    <div class="max-w-xs lg:max-w-md xl:max-w-lg">
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
                            ${confidence ? `<span class="text-xs text-gray-500">${confidencePercent}%</span>` : ''}
                        </div>
                    `;
                }
                
                messageDiv.innerHTML = `
                    <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                        <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-4 py-3 shadow-md">
                            <p class="text-sm text-gray-800">${escapeHtml(message).replace(/\n/g, '<br>')}</p>
                            ${intentBadge}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">${time}</p>
                    </div>
                `;
            }
            
            messagesArea.appendChild(messageDiv);
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }

        function appendTypingIndicator() {
            const messagesArea = document.getElementById('messagesArea');
            const indicatorDiv = document.createElement('div');
            indicatorDiv.className = 'flex justify-start typing-indicator';
            indicatorDiv.innerHTML = `
                <div class="max-w-xs lg:max-w-md xl:max-w-lg">
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

        // Load session
        function loadSession(sessionId) {
            window.location.href = '{{ route("admin.chatbot.index") }}?session=' + sessionId;
        }

        // Delete session
        async function deleteSession(sessionId, event) {
            event.stopPropagation();
            
            if (!confirm('Are you sure you want to delete this chat?')) return;
            
            try {
                const response = await fetch(`/admin/chatbot/sessions/${sessionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.href = '{{ route("admin.chatbot.index") }}';
                }
            } catch (error) {
                console.error('Error deleting session:', error);
                alert('Failed to delete chat');
            }
        }

        // Settings modal
        function openSettingsModal() {
            document.getElementById('settingsModal').classList.remove('hidden');
        }

        function closeSettingsModal() {
            document.getElementById('settingsModal').classList.add('hidden');
        }

        // Toggle WhatsApp settings
        document.getElementById('connectWhatsApp').addEventListener('change', (e) => {
            const whatsappSettings = document.getElementById('whatsappSettings');
            if (e.target.checked) {
                whatsappSettings.classList.remove('hidden');
            } else {
                whatsappSettings.classList.add('hidden');
            }
        });

        // Save settings
        document.getElementById('settingsForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const sessionId = document.getElementById('currentSessionId').value;
            const title = document.getElementById('sessionTitle').value;
            const connectWhatsApp = document.getElementById('connectWhatsApp').checked;
            const whatsappNumber = document.getElementById('whatsappNumber').value;
            const botInstanceId = document.getElementById('botInstance').value;
            
            try {
                const response = await fetch(`/admin/chatbot/sessions/${sessionId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        title: title,
                        is_connected_to_whatsapp: connectWhatsApp,
                        whatsapp_number: whatsappNumber,
                        bot_instance_id: botInstanceId || null
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    closeSettingsModal();
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error saving settings:', error);
                alert('Failed to save settings');
            }
        });

        // Auto-scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', () => {
            const messagesArea = document.getElementById('messagesArea');
            messagesArea.scrollTop = messagesArea.scrollHeight;
        });
    </script>
    @endpush
</x-app-layout>
