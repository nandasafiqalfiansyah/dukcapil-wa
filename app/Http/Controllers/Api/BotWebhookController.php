<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BotWebhookController extends Controller
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle incoming messages from bot server.
     */
    public function webhook(Request $request)
    {
        try {
            // Validate API token
            $token = $request->bearerToken();
            $expectedToken = config('services.whatsapp_bot.api_token');

            if ($token !== $expectedToken) {
                Log::warning('Unauthorized bot webhook request');

                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Process the incoming message
            $this->whatsappService->processIncomingMessage($request->all());

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error in bot webhook', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle bot events (connection, disconnection, etc.).
     */
    public function event(Request $request)
    {
        try {
            // Validate API token
            $token = $request->bearerToken();
            $expectedToken = config('services.whatsapp_bot.api_token');

            if ($token !== $expectedToken) {
                Log::warning('Unauthorized bot event request');

                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $event = $request->input('event');
            $data = $request->input('data', []);

            if (! $event) {
                return response()->json(['error' => 'Event type required'], 400);
            }

            // Handle the bot event
            $this->whatsappService->handleBotEvent($event, $data);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error in bot event handler', [
                'error' => $e->getMessage(),
                'event' => $request->input('event'),
                'data' => $request->input('data'),
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
