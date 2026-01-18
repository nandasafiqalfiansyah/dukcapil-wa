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

        // WhatsApp chat statistics - count all if no WhatsApp-specific flag
        $waMessagesReceived = ChatMessage::where('role', 'user')->count();

        $waMessagesSent = ChatMessage::where('role', 'bot')->count();

        $waTotalConversations = ChatSession::count();

        // Merge into stats array for the view
        $stats = array_merge($stats, [
            'wa_messages_received' => $waMessagesReceived,
            'wa_messages_sent' => $waMessagesSent,
            'wa_total_conversations' => $waTotalConversations,
        ]);

        return view('admin.dashboard', compact(
            'stats',
            'recentRequests',
            'requestsByStatus',
            'requestsByType',
            'botInstances',
            'recentNlpLogs',
            'recentChatLogs',
            'whatsappLinks'
        ));
    }
}
