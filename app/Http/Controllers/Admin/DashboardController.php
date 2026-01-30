<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotInstance;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\ConversationLog;
use App\Models\DocumentValidation;
use App\Models\ServiceRequest;
use App\Models\WhatsAppUser;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users' => WhatsAppUser::count(),
            'verified_users' => WhatsAppUser::verified()->count(),
            'pending_requests' => ServiceRequest::pending()->count(),
            'total_requests' => ServiceRequest::count(),
            'pending_validations' => DocumentValidation::pending()->count(),
            'conversations_today' => ConversationLog::whereDate('created_at', today())->count(),
            'escalated_requests' => ServiceRequest::escalated()->count(),
            'total_bots' => BotInstance::count(),
            'connected_bots' => BotInstance::connected()->count(),
        ];

        $recentRequests = ServiceRequest::with(['whatsappUser', 'assignedOfficer'])
            ->latest()
            ->take(10)
            ->get();

        $requestsByStatus = ServiceRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $requestsByType = ServiceRequest::selectRaw('service_type, count(*) as count')
            ->groupBy('service_type')
            ->pluck('count', 'service_type');

        // Get bot instances for tracking - show all if any exist
        $botInstances = BotInstance::orderBy('last_connected_at', 'desc')->get();

        // Get available WhatsApp links from connected bots
        $whatsappLinks = BotInstance::where('status', 'connected')
            ->where('is_active', true)
            ->whereNotNull('phone_number')
            ->get()
            ->map(function ($bot) {
                return [
                    'id' => $bot->id,
                    'name' => $bot->name,
                    'phone_number' => $bot->phone_number,
                    'link' => $bot->metadata['wa_link'] ?? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $bot->phone_number),
                    'message' => $bot->metadata['wa_message'] ?? null,
                ];
            })
            ->values();

        // All chat logs (user and bot, all sessions) for the bottom table
        $recentNlpLogs = ChatMessage::with('chatSession')
            ->latest()
            ->take(50)
            ->get();

        // Get recent chat logs - use all if WhatsApp-filtered is empty
        $recentChatLogs = ChatMessage::with('chatSession')
            ->latest()
            ->take(20)
            ->get();

        // Chat statistics (all devices)
        $messagesReceived = ChatMessage::where('role', 'user')->count();
        $messagesSent = ChatMessage::where('role', 'bot')->count();
        $totalConversations = ChatSession::count();

        // WhatsApp specific statistics (separate)
        $waMessagesReceived = ChatMessage::where('role', 'user')
            ->whereHas('chatSession', function($q) {
                $q->where('is_connected_to_whatsapp', true);
            })->count();
        $waMessagesSent = ChatMessage::where('role', 'bot')
            ->whereHas('chatSession', function($q) {
                $q->where('is_connected_to_whatsapp', true);
            })->count();
        $waConversations = ChatSession::where('is_connected_to_whatsapp', true)->count();

        // Merge into stats array for the view
        $stats = array_merge($stats, [
            'messages_received' => $messagesReceived,
            'messages_sent' => $messagesSent,
            'total_conversations' => $totalConversations,
            'wa_messages_received' => $waMessagesReceived,
            'wa_messages_sent' => $waMessagesSent,
            'wa_conversations' => $waConversations,
        ]);

        // WhatsApp devices detail stats (per device)
        $waDeviceStats = $this->buildWaDeviceStats();

        return view('admin.dashboard', compact(
            'stats',
            'recentRequests',
            'requestsByStatus',
            'requestsByType',
            'botInstances',
            'recentNlpLogs',
            'recentChatLogs',
            'whatsappLinks',
            'waDeviceStats'
        ));
    }

    public function waStats()
    {
        return response()->json([
            'summary' => $this->waSummary(),
            'devices' => $this->buildWaDeviceStats(),
        ]);
    }

    private function waSummary(): array
    {
        $messagesReceived = ChatMessage::where('role', 'user')
            ->whereHas('chatSession', function($q) {
                $q->where('is_connected_to_whatsapp', true);
            })
            ->count();

        $messagesSent = ChatMessage::where('role', 'bot')
            ->whereHas('chatSession', function($q) {
                $q->where('is_connected_to_whatsapp', true);
            })
            ->count();

        $conversations = ChatSession::where('is_connected_to_whatsapp', true)->count();

        return [
            'messages_received' => $messagesReceived,
            'messages_sent' => $messagesSent,
            'conversations' => $conversations,
        ];
    }

    private function buildWaDeviceStats()
    {
        return BotInstance::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function ($bot) {
                $sessionFilter = function ($query) use ($bot) {
                    $query->where('is_connected_to_whatsapp', true)
                        ->where('bot_instance_id', $bot->id);
                };

                $conversations = ChatSession::where($sessionFilter)->count();

                $messagesReceived = ChatMessage::where('role', 'user')
                    ->whereHas('chatSession', $sessionFilter)
                    ->count();

                $messagesSent = ChatMessage::where('role', 'bot')
                    ->whereHas('chatSession', $sessionFilter)
                    ->count();

                $lastMessage = ChatMessage::whereHas('chatSession', $sessionFilter)
                    ->latest()
                    ->first();

                return [
                    'id' => $bot->id,
                    'name' => $bot->name,
                    'phone_number' => $bot->phone_number,
                    'status' => $bot->status,
                    'received' => $messagesReceived,
                    'sent' => $messagesSent,
                    'conversations' => $conversations,
                    'last_message' => $lastMessage?->message,
                    'last_message_at' => optional($lastMessage?->created_at)->toDateTimeString(),
                ];
            })
            ->values();
    }
}
