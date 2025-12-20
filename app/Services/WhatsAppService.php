<?php

namespace App\Services;

use App\Models\BotInstance;
use App\Models\ConversationLog;
use App\Models\WhatsAppUser;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $botServerUrl;

    protected string $botApiToken;

    public function __construct()
    {
        $this->botServerUrl = config('services.whatsapp_bot.server_url', 'http://localhost:3000');
        $this->botApiToken = config('services.whatsapp_bot.api_token', 'default-token');
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
     * Send a message using a specific bot instance.
     */
    public function sendMessage(string $to, string $message, ?BotInstance $bot = null): array
    {
        $bot = $bot ?? $this->getAvailableBot();

        if (!$bot) {
            Log::error('No connected bot available for sending message');

            return [
                'success' => false,
                'error' => 'No connected bot available',
            ];
        }

        try {
            $response = Http::withToken($this->botApiToken)
                ->post("{$this->botServerUrl}/bot/{$bot->bot_id}/send", [
                    'to' => $to,
                    'message' => $message,
                    'type' => 'text',
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Find or create WhatsApp user
                $phoneNumber = $this->extractPhoneNumber($to);
                $whatsappUser = WhatsAppUser::firstOrCreate(
                    ['phone_number' => $phoneNumber],
                    ['status' => 'active']
                );

                // Log the outgoing message
                ConversationLog::create([
                    'bot_instance_id' => $bot->id,
                    'whatsapp_user_id' => $whatsappUser->id,
                    'message_id' => $data['message_id'] ?? uniqid('msg_'),
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

            Log::error('Bot server request failed', [
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Bot server request exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process incoming message from bot webhook.
     */
    public function processIncomingMessage(array $data): void
    {
        try {
            $botId = $data['bot_id'] ?? null;
            $message = $data['message'] ?? null;

            if (!$botId || !$message) {
                Log::warning('Invalid webhook payload: missing bot_id or message');

                return;
            }

            // Find bot instance
            $bot = BotInstance::where('bot_id', $botId)->first();
            if (!$bot) {
                Log::warning("Bot instance not found: {$botId}");

                return;
            }

            // Extract phone number
            $phoneNumber = $this->extractPhoneNumber($message['from']);

            // Find or create WhatsApp user
            $whatsappUser = WhatsAppUser::firstOrCreate(
                ['phone_number' => $phoneNumber],
                ['status' => 'active']
            );

            // Create conversation log
            ConversationLog::create([
                'bot_instance_id' => $bot->id,
                'whatsapp_user_id' => $whatsappUser->id,
                'message_id' => $message['id'],
                'direction' => 'incoming',
                'message_content' => $message['body'] ?? '',
                'message_type' => $message['type'] ?? 'text',
                'metadata' => $message,
                'status' => 'delivered',
            ]);

            // Handle auto-reply for ping messages
            $this->handleAutoReply($bot, $message);
        } catch (\Exception $e) {
            Log::error('Error processing incoming message from bot', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Handle auto-reply for specific keywords like ping, test, etc.
     */
    protected function handleAutoReply(BotInstance $bot, array $message): void
    {
        // Skip if message is from bot itself
        if (($message['fromMe'] ?? false) === true) {
            return;
        }

        $messageBody = strtolower(trim($message['body'] ?? ''));
        
        // Define auto-reply triggers and responses
        $autoReplies = [
            'ping' => "🤖 *Pong!*\n\nBot DUKCAPIL Ponorogo aktif dan berfungsi dengan baik.\n\nWaktu: " . now()->format('d/m/Y H:i:s'),
            'test' => "✅ *Bot Aktif*\n\nBot WhatsApp DUKCAPIL Ponorogo sedang online dan siap melayani Anda.\n\nUntuk bantuan, kirim pesan atau hubungi kantor kami.",
            'halo' => "👋 *Halo!*\n\nSelamat datang di layanan WhatsApp DUKCAPIL Ponorogo.\n\nKami siap membantu Anda dengan:\n- Informasi layanan kependudukan\n- Status dokumen\n- Pertanyaan umum\n\nSilakan sampaikan kebutuhan Anda.",
            'hai' => "👋 *Hai!*\n\nSelamat datang di layanan WhatsApp DUKCAPIL Ponorogo.\n\nKami siap membantu Anda dengan:\n- Informasi layanan kependudukan\n- Status dokumen\n- Pertanyaan umum\n\nSilakan sampaikan kebutuhan Anda.",
            'help' => "ℹ️ *Bantuan*\n\nLayanan yang tersedia:\n\n1. *KTP* - Informasi e-KTP\n2. *KK* - Informasi Kartu Keluarga\n3. *Akta* - Informasi Akta Kelahiran/Kematian\n4. *Status* - Cek status dokumen\n\nKirim kata kunci di atas untuk informasi lebih lanjut.",
            'info' => "ℹ️ *Informasi DUKCAPIL Ponorogo*\n\n📍 Alamat: [Alamat Kantor]\n📞 Telepon: [Nomor Telepon]\n🕐 Jam Layanan: Senin-Jumat, 08:00-15:00\n\nUntuk pertanyaan, silakan kirim pesan atau hubungi langsung.",
        ];

        // Check if message matches any auto-reply trigger
        foreach ($autoReplies as $trigger => $response) {
            if ($messageBody === $trigger) {
                // Send auto-reply
                $this->sendMessage($message['from'], $response, $bot);
                
                Log::info('Auto-reply sent', [
                    'bot_id' => $bot->bot_id,
                    'trigger' => $trigger,
                    'to' => $message['from'],
                ]);
                
                break;
            }
        }
    }

    /**
     * Handle bot events (connection, disconnection, etc.).
     */
    public function handleBotEvent(string $event, array $data): void
    {
        try {
            $botId = $data['bot_id'] ?? null;
            if (!$botId) {
                return;
            }

            $bot = BotInstance::firstOrCreate(
                ['bot_id' => $botId],
                ['name' => $data['bot_name'] ?? "Bot {$botId}"]
            );

            switch ($event) {
                case 'qr_generated':
                    $bot->update([
                        'status' => 'qr_generated',
                        'qr_code' => $data['qr_code'] ?? null,
                    ]);
                    break;

                case 'connected':
                    $bot->update([
                        'status' => 'connected',
                        'phone_number' => $data['phone_number'] ?? null,
                        'platform' => $data['platform'] ?? null,
                        'qr_code' => null,
                        'last_connected_at' => now(),
                    ]);
                    break;

                case 'disconnected':
                    $bot->update([
                        'status' => 'disconnected',
                        'qr_code' => null,
                        'last_disconnected_at' => now(),
                    ]);
                    break;

                case 'auth_failed':
                    $bot->update([
                        'status' => 'auth_failed',
                        'qr_code' => null,
                    ]);
                    break;
            }

            Log::info("Bot event handled: {$event}", ['bot_id' => $botId]);
        } catch (\Exception $e) {
            Log::error('Error handling bot event', [
                'event' => $event,
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Initialize a new bot instance.
     */
    public function initializeBot(string $botId, string $botName): array
    {
        try {
            $response = Http::withToken($this->botApiToken)
                ->post("{$this->botServerUrl}/bot/initialize", [
                    'bot_id' => $botId,
                    'bot_name' => $botName,
                ]);

            if ($response->successful()) {
                // Create or update bot instance in database
                BotInstance::updateOrCreate(
                    ['bot_id' => $botId],
                    [
                        'name' => $botName,
                        'status' => 'initializing',
                        'is_active' => true,
                    ]
                );

                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('Error initializing bot', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get bot status from server.
     */
    public function getBotStatus(string $botId): array
    {
        try {
            $response = Http::withToken($this->botApiToken)
                ->get("{$this->botServerUrl}/bot/{$botId}/status");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Disconnect a bot.
     */
    public function disconnectBot(string $botId): array
    {
        try {
            $response = Http::withToken($this->botApiToken)
                ->post("{$this->botServerUrl}/bot/{$botId}/disconnect");

            if ($response->successful()) {
                $bot = BotInstance::where('bot_id', $botId)->first();
                if ($bot) {
                    $bot->update([
                        'status' => 'disconnected',
                        'last_disconnected_at' => now(),
                    ]);
                }

                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Logout a bot (removes session).
     */
    public function logoutBot(string $botId): array
    {
        try {
            $response = Http::withToken($this->botApiToken)
                ->post("{$this->botServerUrl}/bot/{$botId}/logout");

            if ($response->successful()) {
                $bot = BotInstance::where('bot_id', $botId)->first();
                if ($bot) {
                    $bot->update([
                        'status' => 'not_initialized',
                        'phone_number' => null,
                        'platform' => null,
                        'qr_code' => null,
                    ]);
                }

                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
