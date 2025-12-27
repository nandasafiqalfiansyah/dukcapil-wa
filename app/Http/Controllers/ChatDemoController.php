<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\ChatBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatDemoController extends Controller
{
    protected ChatBotService $chatBotService;

    public function __construct(ChatBotService $chatBotService)
    {
        $this->chatBotService = $chatBotService;
    }

    /**
     * Display the public chat demo interface.
     */
    public function index(Request $request)
    {
        $sessionId = $request->session()->get('demo_chat_session_id');
        $currentSession = null;
        $messages = [];

        if ($sessionId) {
            $currentSession = ChatSession::with('messages')->find($sessionId);
            if ($currentSession) {
                $messages = $currentSession->messages()->orderBy('created_at', 'asc')->get();
            }
        }

        return view('chat-demo.index', compact('currentSession', 'messages'));
    }

    /**
     * Create a new guest chat session.
     */
    public function createSession(Request $request)
    {
        try {
            $session = $this->chatBotService->getOrCreateSession(null);
            
            // Store session ID in guest session
            $request->session()->put('demo_chat_session_id', $session->id);

            return response()->json([
                'success' => true,
                'session' => $session,
            ]);
        } catch (\Exception $e) {
            Log::error('Chat demo create session error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Gagal membuat sesi chat. Silakan refresh halaman.',
            ], 500);
        }
    }

    /**
     * Send a message in demo chat.
     */
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|exists:chat_sessions,id',
                'message' => 'required|string|max:5000',
            ]);

            $session = ChatSession::findOrFail($request->session_id);
            
            // Verify this session belongs to current guest session
            $guestSessionId = $request->session()->get('demo_chat_session_id');
            if ($session->id !== $guestSessionId && $session->user_id !== null) {
                return response()->json([
                    'success' => false,
                    'error' => 'Sesi tidak valid. Silakan refresh halaman.',
                ], 403);
            }
            
            $result = $this->chatBotService->processMessage($session, $request->message);

            return response()->json([
                'success' => true,
                'user_message' => $result['user_message'],
                'bot_message' => $result['bot_message'],
                'intent' => $result['intent'],
                'confidence' => $result['confidence'],
                'nlp_details' => [
                    'matched_pattern' => $result['bot_message']->metadata['matched_pattern'] ?? null,
                    'processing_time' => isset($result['bot_message'], $result['user_message']) 
                        ? $result['bot_message']->created_at->diffInMilliseconds($result['user_message']->created_at) 
                        : null,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Data tidak valid. Silakan coba lagi.',
            ], 422);
        } catch (\Exception $e) {
            Log::error('Chat demo send message error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan. Silakan coba lagi atau refresh halaman.',
            ], 500);
        }
    }

    /**
     * Get messages for a demo session.
     */
    public function getMessages(Request $request, $sessionId)
    {
        $guestSessionId = $request->session()->get('demo_chat_session_id');
        
        $session = ChatSession::with('messages')->findOrFail($sessionId);
        
        // Verify access
        if ($session->id !== $guestSessionId && $session->user_id !== null) {
            return response()->json([
                'success' => false,
                'error' => 'Sesi tidak valid. Silakan refresh halaman.',
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'messages' => $session->messages()->orderBy('created_at', 'asc')->get(),
        ]);
    }

    /**
     * Reset demo session.
     */
    public function resetSession(Request $request)
    {
        $sessionId = $request->session()->get('demo_chat_session_id');
        
        if ($sessionId) {
            $session = ChatSession::find($sessionId);
            if ($session && $session->user_id === null) {
                $session->delete();
            }
            $request->session()->forget('demo_chat_session_id');
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
