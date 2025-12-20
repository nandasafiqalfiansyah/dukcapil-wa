<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WhatsAppWebhookController extends Controller
{
    public function __construct(protected WhatsAppService $whatsappService) {}

    public function verify(Request $request): JsonResponse|string
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($challenge = $this->whatsappService->verifyWebhook($mode, $token, $challenge)) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response()->json(['error' => 'Verification failed'], 403);
    }

    public function webhook(Request $request): JsonResponse
    {
        $data = $request->all();

        $this->whatsappService->processIncomingMessage($data);

        return response()->json(['status' => 'success'], 200);
    }
}
