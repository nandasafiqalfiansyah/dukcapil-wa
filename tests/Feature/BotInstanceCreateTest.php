<?php

use App\Models\BotInstance;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'services.fonnte.api_url' => 'https://md.fonnte.com',
        'services.fonnte.token' => 'test_fonnte_token',
    ]);
});

test('initialize bot with fonnte token validates device info using GET request', function () {
    // Mock the HTTP GET request to /device endpoint
    Http::fake([
        'https://md.fonnte.com/device' => Http::response([
            'device' => '628123456789',
            'status' => 'connected',
        ], 200),
    ]);

    $service = new WhatsAppService;
    $result = $service->initializeBot('test-bot-1', 'Test Bot', 'custom_token');

    expect($result['success'])->toBeTrue();
    expect($result['bot'])->toBeInstanceOf(BotInstance::class);
    
    // Verify that the request was a GET request
    Http::assertSent(function ($request) {
        return $request->url() === 'https://md.fonnte.com/device' &&
               $request->method() === 'GET' &&
               $request->hasHeader('Authorization', 'custom_token');
    });
});

test('initialize bot fails with invalid fonnte token', function () {
    // Mock the HTTP GET request to return error
    Http::fake([
        'https://md.fonnte.com/device' => Http::response([
            'reason' => 'Invalid token',
        ], 401),
    ]);

    $service = new WhatsAppService;
    $result = $service->initializeBot('test-bot-1', 'Test Bot', 'invalid_token');

    expect($result['success'])->toBeFalse();
    expect($result['error'])->toContain('Invalid token');
});

test('initialize bot creates bot instance with device info', function () {
    // Mock the HTTP GET request to /device endpoint
    Http::fake([
        'https://md.fonnte.com/device' => Http::response([
            'device' => '628123456789',
            'status' => 'connected',
        ], 200),
    ]);

    $service = new WhatsAppService;
    $result = $service->initializeBot('test-bot-2', 'Test Bot 2', 'custom_token_2');

    expect($result['success'])->toBeTrue();
    
    // Verify bot instance was created in database
    $this->assertDatabaseHas('bot_instances', [
        'bot_id' => 'test-bot-2',
        'name' => 'Test Bot 2',
        'phone_number' => '628123456789',
        'status' => 'connected',
        'is_active' => true,
    ]);
});
