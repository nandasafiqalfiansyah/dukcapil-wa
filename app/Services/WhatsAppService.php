<?php

namespace App\Services;

use App\Models\WhatsAppUser;
use App\Models\ConversationLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl;
    protected string $accessToken;
    protected string $phoneNumberId;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', 'https://graph.facebook.com/v18.0');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
    }

    public function sendMessage(string $to, string $message, array $options = []): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => [
                'body' => $message,
            ],
        ];

        return $this->makeApiRequest('messages', $payload);
    }

    public function sendTemplateMessage(string $to, string $templateName, array $components = []): array
    {
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

    public function sendInteractiveMessage(string $to, array $interactive): array
    {
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'interactive',
            'interactive' => $interactive,
        ];

        return $this->makeApiRequest('messages', $payload);
    }

    public function processIncomingMessage(array $data): void
    {
        try {
            $entry = $data['entry'][0] ?? null;
            $changes = $entry['changes'][0] ?? null;
            $value = $changes['value'] ?? null;

            if (!$value || !isset($value['messages'])) {
                return;
            }

            foreach ($value['messages'] as $message) {
                $this->handleMessage($message, $value['contacts'][0] ?? []);
            }
        } catch (\Exception $e) {
            Log::error('Error processing incoming WhatsApp message', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
        }
    }

    protected function handleMessage(array $message, array $contact): void
    {
        $phoneNumber = $message['from'];
        $messageId = $message['id'];
        $messageType = $message['type'];

        $whatsappUser = WhatsAppUser::firstOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'name' => $contact['profile']['name'] ?? null,
                'status' => 'pending',
            ]
        );

        $messageContent = $this->extractMessageContent($message);

        ConversationLog::create([
            'whatsapp_user_id' => $whatsappUser->id,
            'message_id' => $messageId,
            'direction' => 'incoming',
            'message_content' => $messageContent,
            'message_type' => $messageType,
            'metadata' => $message,
            'status' => 'delivered',
        ]);
    }

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

    public function logOutgoingMessage(WhatsAppUser $user, string $message, string $messageType = 'text', ?string $messageId = null): ConversationLog
    {
        return ConversationLog::create([
            'whatsapp_user_id' => $user->id,
            'message_id' => $messageId ?? uniqid('msg_'),
            'direction' => 'outgoing',
            'message_content' => $message,
            'message_type' => $messageType,
            'status' => 'sent',
        ]);
    }

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

    public function verifyWebhook(string $mode, string $token, string $challenge): ?string
    {
        $verifyToken = config('services.whatsapp.verify_token');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return $challenge;
        }

        return null;
    }
}
