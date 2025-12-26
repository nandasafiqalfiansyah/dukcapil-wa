<?php

use App\Http\Controllers\Api\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

// WhatsApp Business API webhook endpoints
Route::prefix('webhook')->group(function () {
    Route::get('whatsapp', [WhatsAppWebhookController::class, 'verify']);
    Route::post('whatsapp', [WhatsAppWebhookController::class, 'webhook'])
        ->middleware('throttle:60,1'); // 60 requests per minute
});
