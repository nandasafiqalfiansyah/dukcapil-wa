<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BotInstance;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\ChatBotService;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    protected ChatBotService $chatBotService;

    public function __construct(ChatBotService $chatBotService)
    {
        $this->chatBotService = $chatBotService;
    }

    /**
     * Display the chat bot testing interface.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();
        $sessions = $this->chatBotService->getUserSessions($userId);
        
        $currentSessionId = $request->query('session');
        $currentSession = null;
        $messages = [];

        if ($currentSessionId) {
            $currentSession = ChatSession::with('messages')->find($currentSessionId);
            if ($currentSession) {
                $messages = $currentSession->messages()->orderBy('created_at', 'asc')->get();
            }
        }

        $botInstances = BotInstance::active()->connected()->get();

        return view('admin.chatbot.index', compact('sessions', 'currentSession', 'messages', 'botInstances'));
    }

    /**
     * Create a new chat session.
     */
    public function createSession(Request $request)
    {
        $session = $this->chatBotService->getOrCreateSession(auth()->id());

        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }

    /**
     * Send a message and get bot response.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:chat_sessions,id',
            'message' => 'required|string|max:5000',
        ]);

        $session = ChatSession::findOrFail($request->session_id);
        
        $result = $this->chatBotService->processMessage($session, $request->message);

        return response()->json([
            'success' => true,
            'user_message' => $result['user_message'],
            'bot_message' => $result['bot_message'],
            'intent' => $result['intent'],
            'confidence' => $result['confidence'],
        ]);
    }

    /**
     * Get messages for a session.
     */
    public function getMessages(Request $request, $sessionId)
    {
        $session = ChatSession::with('messages')->findOrFail($sessionId);
        
        return response()->json([
            'success' => true,
            'messages' => $session->messages()->orderBy('created_at', 'asc')->get(),
        ]);
    }

    /**
     * Update session title.
     */
    public function updateSession(Request $request, $sessionId)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'is_connected_to_whatsapp' => 'sometimes|boolean',
            'whatsapp_number' => 'nullable|string|max:20',
            'bot_instance_id' => 'nullable|exists:bot_instances,id',
        ]);

        $session = ChatSession::findOrFail($sessionId);
        $session->update($request->only(['title', 'is_connected_to_whatsapp', 'whatsapp_number', 'bot_instance_id']));

        return response()->json([
            'success' => true,
            'session' => $session,
        ]);
    }

    /**
     * Delete a chat session.
     */
    public function deleteSession($sessionId)
    {
        $session = ChatSession::findOrFail($sessionId);
        $session->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Get all sessions for the user.
     */
    public function getSessions()
    {
        $sessions = $this->chatBotService->getUserSessions(auth()->id());

        return response()->json([
            'success' => true,
            'sessions' => $sessions,
        ]);
    }
}

