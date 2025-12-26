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
    protected ?string $accessToken;
    protected ?string $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', 'https://graph.facebook.com/v18.0');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }

    /**
     * Get a connected bot instance or the default one.
     */
    public function getAvailableBot(): ?BotInstance
    {
        return BotInstance::active()->connected()->first();
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
     * Send a message using WhatsApp Business API.
     */
    public function sendMessage(string $to, string $message, ?BotInstance $bot = null, array $options = []): array
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
                ->post("{$this->apiUrl}/{$this->phoneNumberId}/messages", $payload);

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
     * Process incoming message from WhatsApp Business API webhook.
     */
    public function processIncomingMessage(array $data): void
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

                $this->handleMessage($message, $contacts[0] ?? []);
            }
        } catch (\Exception $e) {
            Log::error('Error processing incoming WhatsApp message', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Handle individual message from webhook.
     */
    protected function handleMessage(array $message, array $contact): void
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
        // Skip if message is from bot itself
        if (($message['fromMe'] ?? false) === true) {
            return;
        }

        $messageBody = trim($message['text']['body'] ?? '');

        // Get active auto-reply configurations ordered by priority
        $autoReplies = AutoReplyConfig::active()
            ->byPriority()
            ->get();

        // Check if message matches any auto-reply trigger
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

                break;
            }
        }
    }

    /**
     * Initialize a bot instance (for Business API, this means setting up credentials).
     */
    public function initializeBot(string $botId, string $botName): array
    {
        try {
            // For Business API, we just need to create/update the bot record
            $bot = BotInstance::updateOrCreate(
                ['bot_id' => $botId],
                [
                    'name' => $botName,
                    'status' => 'connected', // Business API is always connected if configured
                    'is_active' => true,
                    'phone_number' => $this->phoneNumberId,
                    'last_connected_at' => now(),
                ]
            );

            return [
                'success' => true,
                'message' => 'Bot instance configured successfully',
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
     * Verify webhook for WhatsApp Business API.
     */
    public function verifyWebhook(string $mode, string $token, string $challenge): ?string
    {
        $verifyToken = config('services.whatsapp.verify_token');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return $challenge;
        }

        return null;
    }
}
