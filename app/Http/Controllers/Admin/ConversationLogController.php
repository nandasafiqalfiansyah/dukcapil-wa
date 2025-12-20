<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversationLog;
use App\Models\WhatsAppUser;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConversationLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ConversationLog::with('whatsappUser')
            ->latest();

        if ($request->filled('user_id')) {
            $query->where('whatsapp_user_id', $request->user_id);
        }

        if ($request->filled('direction')) {
            $query->where('direction', $request->direction);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('intent')) {
            $query->where('intent', $request->intent);
        }

        $conversations = $query->paginate(20);
        $whatsappUsers = WhatsAppUser::orderBy('name')->get();

        return view('admin.conversations.index', compact('conversations', 'whatsappUsers'));
    }

    public function show(ConversationLog $conversationLog): View
    {
        $conversationLog->load('whatsappUser');

        $relatedConversations = ConversationLog::where('whatsapp_user_id', $conversationLog->whatsapp_user_id)
            ->where('id', '!=', $conversationLog->id)
            ->latest()
            ->take(10)
            ->get();

        return view('admin.conversations.show', compact('conversationLog', 'relatedConversations'));
    }
}
