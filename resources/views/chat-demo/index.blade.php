<x-guest-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-whatsapp-600 to-whatsapp-700 text-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <a href="{{ route('welcome') }}" class="text-white hover:text-whatsapp-100">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-lg sm:text-2xl font-bold">Chat Demo</h1>
                            <p class="text-xs sm:text-sm text-whatsapp-100 hidden sm:block">Try our chatbot - No login required</p>
                        </div>
                    </div>
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-2 sm:space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-white text-whatsapp-600 rounded-lg hover:bg-whatsapp-50 transition text-sm sm:text-base">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-3 py-1.5 sm:px-4 sm:py-2 bg-white text-whatsapp-600 rounded-lg hover:bg-whatsapp-50 transition text-sm sm:text-base">
                                    Login
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Chat Container -->
        <div class="max-w-6xl mx-auto px-2 sm:px-4 lg:px-8 py-4 sm:py-8">
            <div class="bg-white rounded-lg sm:rounded-2xl shadow-2xl overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3">
                    <!-- Info Panel -->
                    <div class="lg:col-span-1 bg-gray-900 text-white p-4 sm:p-6 hidden lg:block">
                        <h3 class="text-xl font-bold mb-4">About This Demo</h3>
                        <div class="space-y-4 text-sm text-gray-300">
                            <p>Welcome to our AI-powered chatbot demo! This is a public demonstration of our customer service assistant.</p>
                            
                            <div class="bg-gray-800 rounded-lg p-4">
                                <h4 class="font-semibold text-white mb-2">Try asking about:</h4>
                                <ul class="space-y-2">
                                    <li class="flex items-start">
                                        <span class="text-whatsapp-400 mr-2">•</span>
                                        <span>KTP services</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-whatsapp-400 mr-2">•</span>
                                        <span>Family card (KK)</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-whatsapp-400 mr-2">•</span>
                                        <span>Birth certificate</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="text-whatsapp-400 mr-2">•</span>
                                        <span>Document requirements</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-blue-900/50 rounded-lg p-4">
                                <h4 class="font-semibold text-white mb-2 flex items-center">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    NLP Intelligence
                                </h4>
                                <p class="text-xs">The bot uses Natural Language Processing to understand your questions and provide relevant answers.</p>
                            </div>

                            <button id="resetBtn" class="w-full mt-6 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center justify-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset Chat
                            </button>
                        </div>
                    </div>

                    <!-- Chat Panel -->
                    <div class="lg:col-span-2 flex flex-col h-[calc(100vh-180px)] sm:h-[calc(100vh-250px)] min-h-[400px] sm:min-h-[500px]">
                        <!-- Chat Header -->
                        <div class="bg-gradient-to-r from-whatsapp-500 to-whatsapp-600 text-white p-3 sm:p-4 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white rounded-full flex items-center justify-center text-xl sm:text-2xl mr-2 sm:mr-3">
                                    🤖
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold">DUKCAPIL Assistant</h3>
                                    <p class="text-xs text-whatsapp-100">
                                        <span id="statusIndicator" class="inline-block w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                        Always Online
                                    </p>
                                </div>
                            </div>
                            <button id="infoToggleBtn" class="lg:hidden text-white hover:text-whatsapp-100 p-2">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Messages Area -->
                        <div id="messagesArea" class="flex-1 overflow-y-auto p-2 sm:p-4 space-y-3 sm:space-y-4 bg-gray-50">
                            @if($currentSession && $messages->count() > 0)
                                @foreach($messages as $message)
                                    @if($message->role === 'user')
                                        <!-- User Message -->
                                        <div class="flex justify-end">
                                            <div class="max-w-[75%] sm:max-w-xs lg:max-w-md">
                                                <div class="bg-whatsapp-500 text-white rounded-2xl rounded-tr-none px-4 py-3 shadow-md">
                                                    <p class="text-sm">{{ $message->message }}</p>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1 text-right">{{ $message->created_at->format('H:i') }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Bot Message -->
                                        <div class="flex justify-start">
                                            <div class="max-w-[75%] sm:max-w-xs lg:max-w-md">
                                                <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-4 py-3 shadow-md">
                                                    <p class="text-sm text-gray-800">{{ $message->message }}</p>
                                                    @if($message->intent)
                                                        <div class="mt-2 flex items-center space-x-2">
                                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                                {{ $message->intent }}
                                                            </span>
                                                            @if($message->confidence)
                                                                <span class="text-xs text-gray-500">
                                                                    {{ round($message->confidence * 100) }}% confidence
                                                                </span>
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
                                        <div class="w-20 h-20 bg-whatsapp-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="h-10 w-10 text-whatsapp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-700 mb-2">Start a conversation</h3>
                                        <p class="text-gray-500">Type a message below to begin chatting with our bot</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Input Area -->
                        <div class="border-t border-gray-200 p-2 sm:p-4 bg-white">
                            <form id="messageForm" class="flex space-x-2">
                                <input type="hidden" id="currentSessionId" value="{{ $currentSession?->id ?? '' }}">
                                <input 
                                    type="text" 
                                    id="messageInput" 
                                    placeholder="Type your message..." 
                                    class="flex-1 rounded-full border-gray-300 focus:border-whatsapp-500 focus:ring focus:ring-whatsapp-200 px-3 py-2 sm:px-4 sm:py-3 text-sm sm:text-base"
                                    autocomplete="off"
                                >
                                <button 
                                    type="submit" 
                                    id="sendBtn"
                                    class="bg-whatsapp-600 hover:bg-whatsapp-700 text-white font-bold p-2 sm:p-3 rounded-full transition duration-150 disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0"
                                >
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-2 text-center hidden sm:block">
                                This is a demo. For real support, please contact our official channels.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Info Panel (Hidden by default) -->
            <div id="mobileInfoPanel" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden lg:hidden">
                <div class="absolute inset-y-0 right-0 w-full sm:w-80 bg-gray-900 text-white p-6 overflow-y-auto transform transition-transform">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">About This Demo</h3>
                        <button id="closeInfoBtn" class="text-white hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4 text-sm text-gray-300">
                        <p>Welcome to our AI-powered chatbot demo! This is a public demonstration of our customer service assistant.</p>
                        
                        <div class="bg-gray-800 rounded-lg p-4">
                            <h4 class="font-semibold text-white mb-2">Try asking about:</h4>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <span class="text-whatsapp-400 mr-2">•</span>
                                    <span>KTP services</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-whatsapp-400 mr-2">•</span>
                                    <span>Family card (KK)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-whatsapp-400 mr-2">•</span>
                                    <span>Birth certificate</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-whatsapp-400 mr-2">•</span>
                                    <span>Document requirements</span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-blue-900/50 rounded-lg p-4">
                            <h4 class="font-semibold text-white mb-2 flex items-center">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                NLP Intelligence
                            </h4>
                            <p class="text-xs">The bot uses Natural Language Processing to understand your questions and provide relevant answers.</p>
                        </div>

                        <button id="mobileResetBtn" class="w-full mt-6 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center justify-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset Chat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentSessionId = document.getElementById('currentSessionId').value;

        // Initialize session if none exists
        if (!currentSessionId) {
            createSession();
        }

        async function createSession() {
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
                    currentSessionId = data.session.id;
                    document.getElementById('currentSessionId').value = currentSessionId;
                } else {
                    console.error('Failed to create session:', data);
                }
            } catch (error) {
                console.error('Error creating session:', error);
            }
        }

        // Send message
        document.getElementById('messageForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (!message) return;
            
            if (!currentSessionId) {
                await createSession();
            }
            
            // Add user message to UI immediately
            appendMessage('user', message);
            messageInput.value = '';
            
            // Show typing indicator
            const typingIndicator = appendTypingIndicator();
            
            try {
                const response = await fetch('/chat-demo/messages', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        session_id: currentSessionId,
                        message: message
                    })
                });
                
                // Check if response is OK (status 200-299)
                if (!response.ok) {
                    console.error('HTTP error:', response.status, response.statusText);
                    typingIndicator.remove();
                    
                    // Try to get error message from response
                    let errorMessage = 'Failed to send message. Please try again.';
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
                    appendMessage('bot', data.bot_message.message, data.intent, data.confidence);
                } else {
                    // Handle case where success is false
                    console.error('Request failed:', data);
                    const errorMessage = data.error || data.message || 'Failed to send message. Please try again.';
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error sending message:', error);
                typingIndicator.remove();
                alert('Failed to send message. Please try again.');
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
                    <div class="max-w-[75%] sm:max-w-xs lg:max-w-md">
                        <div class="bg-whatsapp-500 text-white rounded-2xl rounded-tr-none px-3 py-2 sm:px-4 sm:py-3 shadow-md">
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
                        <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-3 py-2 sm:px-4 sm:py-3 shadow-md">
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

        function appendTypingIndicator() {
            const messagesArea = document.getElementById('messagesArea');
            const indicatorDiv = document.createElement('div');
            indicatorDiv.className = 'flex justify-start typing-indicator';
            indicatorDiv.innerHTML = `
                <div class="max-w-[75%] sm:max-w-xs lg:max-w-md">
                    <div class="bg-white border border-gray-200 rounded-2xl rounded-tl-none px-3 py-2 sm:px-4 sm:py-3 shadow-md">
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
        document.getElementById('resetBtn').addEventListener('click', async () => {
            if (!confirm('Are you sure you want to reset the chat? All messages will be cleared.')) {
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
                alert('Failed to reset chat');
            }
        });

        // Mobile info panel toggle
        const infoToggleBtn = document.getElementById('infoToggleBtn');
        const mobileInfoPanel = document.getElementById('mobileInfoPanel');
        const closeInfoBtn = document.getElementById('closeInfoBtn');
        const mobileResetBtn = document.getElementById('mobileResetBtn');

        if (infoToggleBtn) {
            infoToggleBtn.addEventListener('click', () => {
                mobileInfoPanel.classList.remove('hidden');
            });
        }

        if (closeInfoBtn) {
            closeInfoBtn.addEventListener('click', () => {
                mobileInfoPanel.classList.add('hidden');
            });
        }

        if (mobileResetBtn) {
            mobileResetBtn.addEventListener('click', async () => {
                if (!confirm('Are you sure you want to reset the chat? All messages will be cleared.')) {
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
                    alert('Failed to reset chat');
                }
            });
        }

        // Auto-scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', () => {
            const messagesArea = document.getElementById('messagesArea');
            messagesArea.scrollTop = messagesArea.scrollHeight;
        });
    </script>
    @endpush
</x-guest-layout>
