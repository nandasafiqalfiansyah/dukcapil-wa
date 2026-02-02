<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotInstance;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageTestController extends Controller
{
    public function __construct(protected WhatsAppService $whatsappService) {}

    public function index(): View
    {
        $bots = BotInstance::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.message-test.index', compact('bots'));
    }

    public function send(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string|max:5000',
            'bot_id' => 'nullable|exists:bot_instances,id',
        ]);

        // Clean phone number
        $phoneNumber = preg_replace('/[^0-9]/', '', $validated['phone_number']);
        
        // Ensure Indonesian format
        if (!str_starts_with($phoneNumber, '62')) {
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            } else {
                $phoneNumber = '62' . $phoneNumber;
            }
        }

        // Get bot instance
        $bot = null;
        if (!empty($validated['bot_id'])) {
            $bot = BotInstance::find($validated['bot_id']);
        }

        // Send message
        $result = $this->whatsappService->sendMessage(
            $phoneNumber,
            $validated['message'],
            $bot
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully!',
                'data' => $result['data'] ?? null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send message',
            'error' => $result['error'] ?? 'Unknown error',
        ], 400);
    }
}
