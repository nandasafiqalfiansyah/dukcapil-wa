<?php

namespace App\Services;

use App\Models\AutoReplyConfig;
use App\Models\BotInstance;
use App\Models\ConversationLog;
use App\Models\WhatsAppUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl;
    protected ?string $token;
    protected ?string $accessToken;
    protected ?string $phoneNumberId;

    public function __construct()
    {
        // Fonnte API Configuration (Primary)
        $this->apiUrl = config('services.fonnte.api_url', 'https://api.fonnte.com');
        $this->token = config('services.fonnte.token');
        
        // Legacy WhatsApp Business API Configuration (Fallback)
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }
    
    /**
     * Check if using Fonnte API or legacy WhatsApp Business API
     */
    protected function isFonnte(): bool
    {
        return !empty($this->token);
    }

    /**
     * Get a connected bot instance or the default one.
     */
    public function getAvailableBot(): ?BotInstance
    {
        return BotInstance::active()->connected()->first();
    }

    /**
     * Get Fonnte token from bot instance metadata or fallback to .env
     */
    protected function getTokenForBot(?BotInstance $bot = null): ?string
    {
        if ($bot && !empty($bot->metadata['token'])) {
            return $bot->metadata['token'];
        }
        
        return $this->token;
    }

    /**
     * Extract phone number from WhatsApp ID.
     * Handles @c.us (individual), @g.us (groups), @s.whatsapp.net formats.
     */
    protected function extractPhoneNumber(string $whatsappId): string
    {
        // Remove common WhatsApp suffixes
        return preg_replace('/@(c\.us|g\.us|s\.whatsapp\.net)$/', '', $whatsappId);
    }

    /**
     * Send a message using Fonnte or WhatsApp Business API.
     */
    public function sendMessage(string $to, string $message, ?BotInstance $bot = null, array $options = []): array
    {
        if ($this->isFonnte()) {
            return $this->sendMessageViaFonnte($to, $message, $bot, $options);
        }
        
        return $this->sendMessageViaBusinessAPI($to, $message, $bot, $options);
    }
    
    /**
     * Send a message using Fonnte API.
     */
    protected function sendMessageViaFonnte(string $to, string $message, ?BotInstance $bot = null, array $options = []): array
    {
        $bot = $bot ?? $this->getAvailableBot();

        // Get token and API URL from bot instance metadata or fallback
        $token = $this->getTokenForBot($bot);
        $apiUrl = $bot?->metadata['api_url'] ?? $this->apiUrl;
        
        if (empty($token)) {
            Log::error('Fonnte token not available for bot', ['bot_id' => $bot?->bot_id]);
            return [
                'success' => false,
                'error' => 'Fonnte token not configured for this bot. Please ensure the bot has a valid token configured.',
            ];
        }

        // Clean phone number (ensure it has only digits)
        $phoneNumber = preg_replace('/[^0-9]/', '', $to);
        
        // Fonnte expects international format without + prefix
        $to = $phoneNumber;

        try {
            $payload = [
                'target' => $to,
                'message' => $message,
            ];
            
            // Add additional options if provided
            if (!empty($options['url'])) {
                $payload['url'] = $options['url'];
            }
            if (!empty($options['filename'])) {
                $payload['filename'] = $options['filename'];
            }

            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post("{$apiUrl}/send", $payload);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] !== true) {
                    Log::error('Fonnte API responded with failure status', [
                        'response' => $data,
                        'to' => $to,
                    ]);

                    return [
                        'success' => false,
                        'error' => $data['reason'] ?? $data['message'] ?? 'Fonnte API returned failure status',
                    ];
                }

                // Find or create WhatsApp user (use cleaned number without +)
                $whatsappUser = WhatsAppUser::firstOrCreate(
                    ['phone_number' => $phoneNumber],
                    ['status' => 'active']
                );

                // Log the outgoing message
                ConversationLog::create([
                    'bot_instance_id' => $bot?->id,
                    'whatsapp_user_id' => $whatsappUser->id,
                    'message_id' => $data['id'] ?? uniqid('msg_'),
                    'direction' => 'outgoing',
                    'message_content' => $message,
                    'message_type' => 'text',
                    'status' => 'sent',
                    'metadata' => $data,
                ]);

                return [
                    'success' => true,
                    'data' => $data,
                ];
            }

            Log::error('Fonnte API request failed', [
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => $response->json()['reason'] ?? $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte API request exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Send a message using WhatsApp Business API (Legacy).
     */
    protected function sendMessageViaBusinessAPI(string $to, string $message, ?BotInstance $bot = null, array $options = []): array
    {
        if (empty($this->accessToken) || empty($this->phoneNumberId)) {
            Log::error('WhatsApp Business API not configured');
            return [
                'success' => false,
                'error' => 'WhatsApp Business API not configured. Please set WHATSAPP_ACCESS_TOKEN and WHATSAPP_PHONE_NUMBER_ID in .env',
            ];
        }

        $bot = $bot ?? $this->getAvailableBot();

        // Clean phone number (remove + and ensure it starts with country code)
        $to = preg_replace('/[^0-9]/', '', $to);

        try {
            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ],
            ];

            $response = Http::withToken($this->accessToken)
                ->post("https://graph.facebook.com/v18.0/{$this->phoneNumberId}/messages", $payload);

            if ($response->successful()) {
                $data = $response->json();

                // Find or create WhatsApp user
                $whatsappUser = WhatsAppUser::firstOrCreate(
                    ['phone_number' => $to],
                    ['status' => 'active']
                );

                // Log the outgoing message
                ConversationLog::create([
                    'bot_instance_id' => $bot?->id,
                    'whatsapp_user_id' => $whatsappUser->id,
                    'message_id' => $data['messages'][0]['id'] ?? uniqid('msg_'),
                    'direction' => 'outgoing',
                    'message_content' => $message,
                    'message_type' => 'text',
                    'status' => 'sent',
                ]);

                return [
                    'success' => true,
                    'data' => $data,
                ];
            }

            Log::error('WhatsApp API request failed', [
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp API request exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send template message using WhatsApp Business API.
     */
    public function sendTemplateMessage(string $to, string $templateName, array $components = []): array
    {
        if (empty($this->accessToken) || empty($this->phoneNumberId)) {
            return [
                'success' => false,
                'error' => 'WhatsApp Business API not configured',
            ];
        }

        $to = preg_replace('/[^0-9]/', '', $to);

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => 'id'],
                'components' => $components,
            ],
        ];

        return $this->makeApiRequest('messages', $payload);
    }

    /**
     * Process incoming message from Fonnte or WhatsApp Business API webhook.
     */
    public function processIncomingMessage(array $data): void
    {
        // Detect webhook format
        if ($this->isFonnteWebhook($data)) {
            $this->processFonnteWebhook($data);
        } else {
            $this->processMetaWebhook($data);
        }
    }
    
    /**
     * Check if webhook data is from Fonnte.
     */
    protected function isFonnteWebhook(array $data): bool
    {
        // Fonnte webhooks have 'device' and 'message' or 'messages' field
        return isset($data['device']) || isset($data['message']);
    }
    
    /**
     * Process Fonnte webhook.
     */
    protected function processFonnteWebhook(array $data): void
    {
        Log::info('Processing Fonnte webhook', ['data' => $data]);
        
        try {
            // Fonnte can send single message or multiple messages
            if (isset($data['message'])) {
                // Single message format
                Log::info('Handling single Fonnte message');
                $this->handleFonnteMessage($data);
            } elseif (isset($data['messages']) && is_array($data['messages'])) {
                // Multiple messages format
                Log::info('Handling multiple Fonnte messages', ['count' => count($data['messages'])]);
                foreach ($data['messages'] as $message) {
                    $this->handleFonnteMessage($message);
                }
            } else {
                Log::warning('Fonnte webhook: unrecognized format', ['data' => $data]);
            }
        } catch (\Exception $e) {
            Log::error('Error processing Fonnte webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);
        }
    }
    
    /**
     * Handle individual Fonnte message.
     */
    protected function handleFonnteMessage(array $data): void
    {
        Log::info('Handling Fonnte message', ['raw_data' => $data]);
        
        // Extract phone number (remove @ suffix if present)
        $phoneNumber = preg_replace('/@.*$/', '', $data['from'] ?? $data['sender'] ?? '');
        
        if (empty($phoneNumber)) {
            Log::warning('Fonnte webhook: missing phone number', ['data' => $data]);
            return;
        }
        
        Log::info('Extracted phone number', ['phone' => $phoneNumber]);
        
        // Get message content
        $messageContent = $data['message'] ?? $data['text'] ?? '';
        $messageType = $data['type'] ?? 'text';
        $messageId = $data['id'] ?? uniqid('fonnte_');
        
        // Create or find WhatsApp user
        $whatsappUser = WhatsAppUser::firstOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'name' => $data['name'] ?? $data['pushname'] ?? null,
                'status' => 'active',
            ]
        );
        
        // Get the default bot instance
        $bot = $this->getAvailableBot();
        
        // Log incoming message
        ConversationLog::create([
            'bot_instance_id' => $bot?->id,
            'whatsapp_user_id' => $whatsappUser->id,
            'message_id' => $messageId,
            'direction' => 'incoming',
            'message_content' => $messageContent,
            'message_type' => $messageType,
            'metadata' => $data,
            'status' => 'delivered',
        ]);
        
        // Handle auto-reply for text messages
        if ($messageType === 'text' && !empty($messageContent)) {
            $this->handleFonnteAutoReply($phoneNumber, $messageContent, $bot);
        }
    }
    
    /**
     * Handle auto-reply for Fonnte messages.
     */
    protected function handleFonnteAutoReply(string $phoneNumber, string $message, ?BotInstance $bot): void
    {
        Log::info('Handling Fonnte auto-reply', [
            'phone' => $phoneNumber,
            'message' => $message,
            'bot_id' => $bot?->id,
        ]);
        
        try {
            // Validate token before processing
            $token = $this->getTokenForBot($bot);
            if (empty($token)) {
                Log::error('Cannot process auto-reply: Token not configured', [
                    'phone' => $phoneNumber,
                    'bot_id' => $bot?->id,
                ]);
                return;
            }
            
            $messageBody = trim($message);
            
            // Get active auto-reply configurations ordered by priority
            $autoReplies = AutoReplyConfig::active()
                ->byPriority()
                ->get();
            
            // Check if message matches any auto-reply trigger
            $matched = false;
            foreach ($autoReplies as $autoReply) {
                $trigger = $autoReply->trigger;
                $matches = false;
                
                if ($autoReply->case_sensitive) {
                    $matches = $messageBody === $trigger;
                } else {
                    $matches = strtolower($messageBody) === strtolower($trigger);
                }
                
                if ($matches) {
                    // Replace dynamic placeholders in response
                    $response = str_replace(
                        ['{{timestamp}}', '{{date}}', '{{time}}'],
                        [
                            now()->format('d/m/Y H:i:s'),
                            now()->format('d/m/Y'),
                            now()->format('H:i:s'),
                        ],
                        $autoReply->response
                    );
                    
                    // Send auto-reply
                    $sendResult = $this->sendMessage($phoneNumber, $response, $bot);
                    
                    if ($sendResult['success']) {
                        Log::info('Fonnte auto-reply sent successfully', [
                            'trigger' => $trigger,
                            'to' => $phoneNumber,
                            'response' => $response,
                        ]);
                    } else {
                        Log::error('Fonnte auto-reply failed to send', [
                            'trigger' => $trigger,
                            'to' => $phoneNumber,
                            'error' => $sendResult['error'] ?? 'Unknown error',
                        ]);
                    }
                    
                    $matched = true;
                    break;
                }
            }
            
            // If no auto-reply matched, send default "unknown" response
            if (!$matched && !empty($messageBody)) {
                $defaultResponse = "Maaf, saya belum memahami pesan Anda. Silakan kirim pertanyaan dengan kata kunci yang jelas atau hubungi kantor DUKCAPIL Ponorogo untuk bantuan langsung.\n\n📞 Kontak: (0352) 461019";
                
                $sendResult = $this->sendMessage($phoneNumber, $defaultResponse, $bot);
                
                if ($sendResult['success']) {
                    Log::info('Fonnte default response sent successfully', [
                        'message' => $messageBody,
                        'to' => $phoneNumber,
                    ]);
                } else {
                    Log::error('Fonnte default response failed to send', [
                        'message' => $messageBody,
                        'to' => $phoneNumber,
                        'error' => $sendResult['error'] ?? 'Unknown error',
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in Fonnte auto-reply', [
                'error' => $e->getMessage(),
                'phone' => $phoneNumber,
                'message' => $message,
            ]);
            
            // Send error response to user
            try {
                $errorResponse = "Maaf, terjadi kesalahan dalam memproses pesan Anda. Mohon coba lagi atau hubungi petugas kami.";
                $this->sendMessage($phoneNumber, $errorResponse, $bot);
            } catch (\Exception $sendError) {
                Log::error('Failed to send error response', [
                    'error' => $sendError->getMessage(),
                ]);
            }
        }
    }
    
    /**
     * Process Meta WhatsApp Business API webhook (Legacy).
     */
    protected function processMetaWebhook(array $data): void
    {
        try {
            // Validate webhook payload structure
            if (!isset($data['entry']) || !is_array($data['entry'])) {
                Log::warning('Invalid webhook payload: missing or invalid entry field', ['data' => $data]);
                return;
            }

            $entry = $data['entry'][0] ?? null;
            if (!$entry || !isset($entry['changes']) || !is_array($entry['changes'])) {
                Log::warning('Invalid webhook payload: missing or invalid changes field');
                return;
            }

            $changes = $entry['changes'][0] ?? null;
            if (!$changes || !isset($changes['value'])) {
                return;
            }

            $value = $changes['value'];

            if (!isset($value['messages']) || !is_array($value['messages'])) {
                return;
            }

            // Validate contacts field if present
            $contacts = isset($value['contacts']) && is_array($value['contacts']) ? $value['contacts'] : [];

            foreach ($value['messages'] as $message) {
                if (!is_array($message) || !isset($message['from'], $message['id'], $message['type'])) {
                    Log::warning('Invalid message format in webhook payload', ['message' => $message]);
                    continue;
                }

                $this->handleMetaMessage($message, $contacts[0] ?? []);
            }
        } catch (\Exception $e) {
            Log::error('Error processing Meta WhatsApp webhook', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }
    
    /**
     * Handle individual message from Meta webhook (Legacy).
     */
    protected function handleMetaMessage(array $message, array $contact): void
    {
        $phoneNumber = $message['from'];
        $messageId = $message['id'];
        $messageType = $message['type'];

        $whatsappUser = WhatsAppUser::firstOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'name' => $contact['profile']['name'] ?? null,
                'status' => 'active',
            ]
        );

        $messageContent = $this->extractMessageContent($message);

        // Get the default bot instance
        $bot = $this->getAvailableBot();

        ConversationLog::create([
            'bot_instance_id' => $bot?->id,
            'whatsapp_user_id' => $whatsappUser->id,
            'message_id' => $messageId,
            'direction' => 'incoming',
            'message_content' => $messageContent,
            'message_type' => $messageType,
            'metadata' => $message,
            'status' => 'delivered',
        ]);

        // Handle auto-reply
        $this->handleAutoReply($message, $bot);
    }

    /**
     * Extract message content based on type.
     */
    protected function extractMessageContent(array $message): string
    {
        return match ($message['type']) {
            'text' => $message['text']['body'] ?? '',
            'image' => $message['image']['caption'] ?? 'Image received',
            'document' => $message['document']['filename'] ?? 'Document received',
            'audio' => 'Audio message received',
            'video' => $message['video']['caption'] ?? 'Video received',
            'location' => sprintf(
                'Location: %s, %s',
                $message['location']['latitude'] ?? '',
                $message['location']['longitude'] ?? ''
            ),
            'interactive' => $message['interactive']['button_reply']['title'] ?? $message['interactive']['list_reply']['title'] ?? 'Interactive response',
            default => 'Unsupported message type',
        };
    }

    /**
     * Handle auto-reply for specific keywords.
     */
    protected function handleAutoReply(array $message, ?BotInstance $bot): void
    {
        try {
            // Skip if message is from bot itself
            if (($message['fromMe'] ?? false) === true) {
                return;
            }

            $messageBody = trim($message['text']['body'] ?? '');
            
            if (empty($messageBody)) {
                return;
            }

            // Get active auto-reply configurations ordered by priority
            $autoReplies = AutoReplyConfig::active()
                ->byPriority()
                ->get();

            // Check if message matches any auto-reply trigger
            $matched = false;
            foreach ($autoReplies as $autoReply) {
                $trigger = $autoReply->trigger;
                $matches = false;

                if ($autoReply->case_sensitive) {
                    $matches = $messageBody === $trigger;
                } else {
                    $matches = strtolower($messageBody) === strtolower($trigger);
                }

                if ($matches) {
                    // Replace dynamic placeholders in response
                    $response = str_replace(
                        ['{{timestamp}}', '{{date}}', '{{time}}'],
                        [
                            now()->format('d/m/Y H:i:s'),
                            now()->format('d/m/Y'),
                            now()->format('H:i:s'),
                        ],
                        $autoReply->response
                    );

                    // Send auto-reply
                    $this->sendMessage($message['from'], $response, $bot);

                    Log::info('Auto-reply sent', [
                        'trigger' => $trigger,
                        'to' => $message['from'],
                    ]);

                    $matched = true;
                    break;
                }
            }
            
            // If no auto-reply matched, send default "unknown" response
            if (!$matched) {
                $defaultResponse = "Maaf, saya belum memahami pesan Anda. Silakan kirim pertanyaan dengan kata kunci yang jelas atau hubungi kantor DUKCAPIL Ponorogo untuk bantuan langsung.\n\n📞 Kontak: (0352) 461019";
                
                $this->sendMessage($message['from'], $defaultResponse, $bot);
                
                Log::info('Default response sent', [
                    'message' => $messageBody,
                    'to' => $message['from'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error in auto-reply handler', [
                'error' => $e->getMessage(),
                'message_from' => $message['from'] ?? 'unknown',
            ]);
            
            // Send error response to user
            try {
                $errorResponse = "Maaf, terjadi kesalahan dalam memproses pesan Anda. Mohon coba lagi atau hubungi petugas kami.";
                $this->sendMessage($message['from'], $errorResponse, $bot);
            } catch (\Exception $sendError) {
                Log::error('Failed to send error response', [
                    'error' => $sendError->getMessage(),
                ]);
            }
        }
    }

    /**
     * Initialize a bot instance (for Fonnte API, this means setting up token).
     */
    public function initializeBot(string $botId, string $botName, ?string $customToken = null, ?string $apiUrl = null, ?string $webhookUrl = null): array
    {
        try {
            // Always require custom token or fallback to .env
            $token = $customToken ?? $this->token;
            $apiUrl = $apiUrl ?? $this->apiUrl;
            
            if (empty($token)) {
                return [
                    'success' => false,
                    'error' => 'Fonnte token is required. Please enter your Fonnte token in the form. Get your token at fonnte.com',
                ];
            }
            
            // Validate Fonnte token by checking device info
            $deviceInfo = $this->getFonnteDeviceInfo($token, $apiUrl);
            
            if (!$deviceInfo['success']) {
                return [
                    'success' => false,
                    'error' => $deviceInfo['error'] ?? 'Failed to validate Fonnte token',
                ];
            }
            
            $phoneNumber = $deviceInfo['data']['device'] ?? null;
            
            // Create/update bot instance with full token stored
            $bot = BotInstance::updateOrCreate(
                ['bot_id' => $botId],
                [
                    'name' => $botName,
                    'status' => 'connected',
                    'is_active' => true,
                    'phone_number' => $phoneNumber,
                    'last_connected_at' => now(),
                    'metadata' => [
                        'api_type' => 'fonnte',
                        'token' => $token, // Store full token for later use
                        'api_url' => $apiUrl,
                        'webhook_url' => $webhookUrl,
                        'token_validated_at' => now()->toIso8601String(),
                    ],
                ]
            );
            
            return [
                'success' => true,
                'message' => 'Bot instance configured successfully with Fonnte API',
                'bot' => $bot,
            ];
        } catch (\Exception $e) {
            Log::error('Error initializing bot', [
                'bot_id' => $botId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Get Fonnte device information to validate token.
     */
    protected function getFonnteDeviceInfo(?string $token = null, ?string $apiUrl = null): array
    {
        $token = $token ?? $this->token;
        $apiUrl = $apiUrl ?? $this->apiUrl;
        
        if (empty($token)) {
            return [
                'success' => false,
                'error' => 'Fonnte token not provided. Please enter your Fonnte token or set FONNTE_TOKEN in .env file. Get your token at fonnte.com',
            ];
        }
        
        try {
            // Fonnte uses POST method for get-devices endpoint
            $response = Http::timeout(10)->withHeaders([
                'Authorization' => $token,
            ])->post("{$apiUrl}/get-devices");
            
            $responseData = $response->json();
            $statusCode = $response->status();
            
            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === true) {
                // Success response from Fonnte
                if (isset($responseData['data']) && is_array($responseData['data']) && count($responseData['data']) > 0) {
                    $firstDevice = $responseData['data'][0];
                    
                    return [
                        'success' => true,
                        'data' => [
                            'device' => $firstDevice['device'] ?? null,
                            'status' => $firstDevice['status'] ?? 'unknown',
                        ],
                    ];
                }
                
                // No devices connected
                Log::warning('Fonnte token valid but no devices connected', [
                    'response' => $responseData,
                ]);
                
                return [
                    'success' => false,
                    'error' => 'No WhatsApp device connected to your Fonnte account. Please connect a device at fonnte.com first.',
                ];
            }
            
            // Handle error responses
            $reason = $responseData['reason'] ?? 'Unknown error';
            
            Log::error('Fonnte device info request failed', [
                'status_code' => $statusCode,
                'response' => $responseData,
                'api_url' => $this->apiUrl,
            ]);
            
            // Provide specific error messages
            if ($reason === 'unknown user' || $statusCode === 401) {
                return [
                    'success' => false,
                    'error' => 'Invalid or expired Fonnte token. Please get a new token from fonnte.com dashboard and try again.',
                ];
            } elseif ($statusCode === 403) {
                return [
                    'success' => false,
                    'error' => 'Access forbidden. Your Fonnte account may not have permission. Please check your account status at fonnte.com',
                ];
            } elseif ($statusCode >= 500) {
                return [
                    'success' => false,
                    'error' => 'Fonnte API server error. Please try again in a few minutes.',
                ];
            }
            
            return [
                'success' => false,
                'error' => "Failed to validate Fonnte token: {$reason}. Please check your token at fonnte.com",
            ];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Fonnte device info connection failed', [
                'error' => $e->getMessage(),
                'api_url' => $this->apiUrl,
            ]);
            
            return [
                'success' => false,
                'error' => 'Cannot connect to Fonnte API. Please check your internet connection and try again.',
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte device info request exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'error' => 'Error connecting to Fonnte: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get bot status.
     */
    public function getBotStatus(string $botId): array
    {
        try {
            $bot = BotInstance::where('bot_id', $botId)->first();

            if (!$bot) {
                return [
                    'success' => false,
                    'error' => 'Bot not found',
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'bot_id' => $botId,
                    'status' => $bot->status,
                    'phone_number' => $bot->phone_number,
                    'connected' => $bot->isConnected(),
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Disconnect a bot (for Business API, this means deactivating it).
     */
    public function disconnectBot(string $botId): array
    {
        try {
            $bot = BotInstance::where('bot_id', $botId)->first();

            if ($bot) {
                $bot->update([
                    'is_active' => false,
                    'status' => 'disconnected',
                    'last_disconnected_at' => now(),
                ]);
            }

            return [
                'success' => true,
                'message' => 'Bot disconnected',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Logout a bot (for Business API, this removes the configuration).
     */
    public function logoutBot(string $botId): array
    {
        try {
            $bot = BotInstance::where('bot_id', $botId)->first();

            if ($bot) {
                $bot->delete();
            }

            return [
                'success' => true,
                'message' => 'Bot removed successfully',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Make API request to WhatsApp Business API.
     */
    protected function makeApiRequest(string $endpoint, array $payload): array
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->post("{$this->apiUrl}/{$this->phoneNumberId}/{$endpoint}", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('WhatsApp API request failed', [
                'endpoint' => $endpoint,
                'payload' => $payload,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp API request exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify webhook for WhatsApp Business API (Meta only).
     * Fonnte doesn't require webhook verification.
     */
    public function verifyWebhook(?string $mode, ?string $token, ?string $challenge): ?string
    {
        if ($this->isFonnte()) {
            // Fonnte doesn't need webhook verification
            return $challenge ?: 'OK';
        }
        
        // Meta webhook verification
        $verifyToken = config('services.whatsapp.verify_token');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return $challenge;
        }

        return null;
    }
}
