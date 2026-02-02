<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversationLog;
use App\Models\WhatsAppUser;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Pagination\Paginator;

class ConversationLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ConversationLog::with('whatsappUser', 'botInstance')
            ->latest();

        // Filter by direction
        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('whatsapp_user_id', $request->user_id);
        }

        // Filter by phone number
        if ($request->filled('phone')) {
            $query->whereHas('whatsappUser', function ($q) use ($request) {
                $q->where('phone_number', 'like', '%' . $request->phone . '%');
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by message type
        if ($request->filled('message_type')) {
            $query->where('message_type', $request->message_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search in message content
        if ($request->filled('search')) {
            $query->where('message_content', 'like', '%' . $request->search . '%');
        }

        $conversations = $query->paginate(25);
        $whatsappUsers = WhatsAppUser::orderBy('name')->get();

        // Statistics
        $stats = [
            'total_messages' => ConversationLog::count(),
            'incoming_messages' => ConversationLog::where('direction', 'incoming')->count(),
            'outgoing_messages' => ConversationLog::where('direction', 'outgoing')->count(),
            'total_users' => WhatsAppUser::count(),
            'today_messages' => ConversationLog::whereDate('created_at', today())->count(),
        ];

        return view('admin.conversations.index', compact('conversations', 'whatsappUsers', 'stats'));
    }

    public function show(ConversationLog $conversationLog): View
    {
        $conversationLog->load('whatsappUser', 'botInstance');

        // Get conversation thread for this user (only if whatsapp_user_id exists)
        $relatedConversations = [];
        if ($conversationLog->whatsapp_user_id) {
            $relatedConversations = ConversationLog::where('whatsapp_user_id', $conversationLog->whatsapp_user_id)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('admin.conversations.show', compact('conversationLog', 'relatedConversations'));
    }
}
