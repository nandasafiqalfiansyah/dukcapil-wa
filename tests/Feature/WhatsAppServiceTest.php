<?php

use App\Models\ConversationLog;
use App\Models\WhatsAppUser;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'services.whatsapp.api_url' => 'https://graph.facebook.com/v18.0',
        'services.whatsapp.access_token' => 'test_token',
        'services.whatsapp.phone_number_id' => '123456789',
        'services.whatsapp.verify_token' => 'test_verify_token',
    ]);
});

test('webhook verification succeeds with correct token', function () {
    $service = new WhatsAppService;

    $result = $service->verifyWebhook('subscribe', 'test_verify_token', 'challenge_string');

    expect($result)->toBe('challenge_string');
});

test('webhook verification fails with incorrect token', function () {
    $service = new WhatsAppService;

    $result = $service->verifyWebhook('subscribe', 'wrong_token', 'challenge_string');

    expect($result)->toBeNull();
});

test('incoming message creates whatsapp user and conversation log', function () {
    $service = new WhatsAppService;

    $webhookData = [
        'entry' => [
            [
                'changes' => [
                    [
                        'value' => [
                            'contacts' => [
                                ['profile' => ['name' => 'Test User']],
                            ],
                            'messages' => [
                                [
                                    'from' => '+628123456789',
                                    'id' => 'msg_123',
                                    'type' => 'text',
                                    'text' => ['body' => 'Hello'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    $service->processIncomingMessage($webhookData);

    $this->assertDatabaseHas('whatsapp_users', [
        'phone_number' => '+628123456789',
        'name' => 'Test User',
    ]);

    $this->assertDatabaseHas('conversation_logs', [
        'message_id' => 'msg_123',
        'direction' => 'incoming',
        'message_content' => 'Hello',
        'message_type' => 'text',
    ]);
});

test('outgoing message is logged', function () {
    $user = WhatsAppUser::factory()->create();
    $service = new WhatsAppService;

    $log = $service->logOutgoingMessage($user, 'Test message');

    expect($log)->toBeInstanceOf(ConversationLog::class);
    expect($log->direction)->toBe('outgoing');
    expect($log->message_content)->toBe('Test message');
    expect($log->whatsapp_user_id)->toBe($user->id);
});

test('send message makes correct api request', function () {
    Http::fake([
        '*' => Http::response(['success' => true], 200),
    ]);

    $service = new WhatsAppService;
    $result = $service->sendMessage('+628123456789', 'Hello World');

    expect($result['success'])->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://graph.facebook.com/v18.0/123456789/messages' &&
               $request['to'] === '+628123456789' &&
               $request['text']['body'] === 'Hello World';
    });
});
