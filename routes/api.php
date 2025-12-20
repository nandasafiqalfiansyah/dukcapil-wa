<?php

use App\Http\Controllers\Api\BotWebhookController;
use App\Http\Controllers\Api\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

// Bot webhook endpoints
Route::prefix('bot')->group(function () {
    Route::post('webhook', [BotWebhookController::class, 'webhook'])
        ->middleware('throttle:120,1'); // 120 requests per minute for bot messages
    Route::post('event', [BotWebhookController::class, 'event'])
        ->middleware('throttle:120,1');
});

// Legacy WhatsApp Business API webhook
Route::prefix('webhook')->group(function () {
    Route::get('whatsapp', [WhatsAppWebhookController::class, 'verify']);
    Route::post('whatsapp', [WhatsAppWebhookController::class, 'webhook'])
        ->middleware('throttle:60,1'); // 60 requests per minute
});
