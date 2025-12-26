<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\CsTrainingData;
use Illuminate\Support\Facades\Log;

class ChatBotService
{
    protected WhatsAppService $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Process user message and generate bot response.
     */
    public function processMessage(ChatSession $session, string $userMessage): array
    {
        // Save user message
        $userChatMessage = ChatMessage::create([
            'chat_session_id' => $session->id,
            'role' => 'user',
            'message' => $userMessage,
        ]);

        // Detect intent using simple NLP
        $intentResult = $this->detectIntent($userMessage);

        // Generate response
        $response = $this->generateResponse($intentResult, $userMessage);

        // Save bot message
        $botChatMessage = ChatMessage::create([
            'chat_session_id' => $session->id,
            'role' => 'bot',
            'message' => $response['message'],
            'intent' => $intentResult['intent'],
            'confidence' => $intentResult['confidence'],
            'metadata' => [
                'matched_pattern' => $intentResult['matched_pattern'] ?? null,
            ],
        ]);

        // If connected to WhatsApp, send message there too
        if ($session->is_connected_to_whatsapp && $session->whatsapp_number) {
            $this->sendToWhatsApp($session, $response['message']);
        }

        return [
            'user_message' => $userChatMessage,
            'bot_message' => $botChatMessage,
            'intent' => $intentResult['intent'],
            'confidence' => $intentResult['confidence'],
        ];
    }

    /**
     * Simple NLP intent detection using keyword matching and pattern matching.
     */
    protected function detectIntent(string $message): array
    {
        $message = strtolower(trim($message));
        
        // Get active training data ordered by priority
        $trainingData = CsTrainingData::active()
            ->byPriority()
            ->get();

        $bestMatch = null;
        $maxConfidence = 0;

        foreach ($trainingData as $data) {
            $confidence = $this->calculateConfidence($message, $data);
            
            if ($confidence > $maxConfidence) {
                $maxConfidence = $confidence;
                $bestMatch = $data;
            }
        }

        if ($bestMatch && $maxConfidence > 0.3) {
            return [
                'intent' => $bestMatch->intent,
                'confidence' => $maxConfidence,
                'matched_pattern' => $bestMatch->pattern,
                'training_data' => $bestMatch,
            ];
        }

        // Default intent
        return [
            'intent' => 'unknown',
            'confidence' => 0,
            'matched_pattern' => null,
            'training_data' => null,
        ];
    }

    /**
     * Calculate confidence score for a training data match.
     * Uses simple keyword matching and pattern similarity.
     */
    protected function calculateConfidence(string $message, CsTrainingData $data): float
    {
        $confidence = 0;
        
        // Exact pattern match
        $pattern = strtolower($data->pattern);
        if ($message === $pattern) {
            return 1.0;
        }

        // Partial pattern match
        if (str_contains($message, $pattern) || str_contains($pattern, $message)) {
            $confidence += 0.6;
        }

        // Keyword matching
        if ($data->keywords && is_array($data->keywords)) {
            $matchedKeywords = 0;
            $totalKeywords = count($data->keywords);
            
            foreach ($data->keywords as $keyword) {
                if (str_contains($message, strtolower($keyword))) {
                    $matchedKeywords++;
                }
            }
            
            if ($totalKeywords > 0) {
                $keywordScore = ($matchedKeywords / $totalKeywords) * 0.4;
                $confidence += $keywordScore;
            }
        }

        // Word similarity (simple approach)
        $messageWords = explode(' ', $message);
        $patternWords = explode(' ', $pattern);
        $commonWords = array_intersect($messageWords, $patternWords);
        
        if (count($patternWords) > 0) {
            $wordSimilarity = (count($commonWords) / count($patternWords)) * 0.3;
            $confidence += $wordSimilarity;
        }

        return min($confidence, 1.0);
    }

    /**
     * Generate response based on intent.
     */
    protected function generateResponse(array $intentResult, string $originalMessage): array
    {
        if ($intentResult['training_data']) {
            $response = $intentResult['training_data']->response;
            
            // Replace placeholders
            $response = $this->replacePlaceholders($response, $originalMessage);
            
            return [
                'message' => $response,
                'intent' => $intentResult['intent'],
            ];
        }

        // Default response for unknown intent
        return [
            'message' => "Maaf, saya belum memahami pertanyaan Anda. Silakan hubungi petugas kami untuk bantuan lebih lanjut.",
            'intent' => 'unknown',
        ];
    }

    /**
     * Replace placeholders in response template.
     */
    protected function replacePlaceholders(string $response, string $originalMessage): string
    {
        $replacements = [
            '{{timestamp}}' => now()->format('d/m/Y H:i:s'),
            '{{date}}' => now()->format('d/m/Y'),
            '{{time}}' => now()->format('H:i:s'),
            '{{day}}' => now()->isoFormat('dddd'),
            '{{user_message}}' => $originalMessage,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $response);
    }

    /**
     * Send message to WhatsApp if session is connected.
     */
    protected function sendToWhatsApp(ChatSession $session, string $message): void
    {
        try {
            $result = $this->whatsappService->sendMessage(
                $session->whatsapp_number,
                $message,
                $session->botInstance
            );

            if (!$result['success']) {
                Log::error('Failed to send message to WhatsApp', [
                    'session_id' => $session->id,
                    'error' => $result['error'] ?? 'Unknown error',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception sending message to WhatsApp', [
                'session_id' => $session->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get or create a chat session for the user.
     */
    public function getOrCreateSession(?int $userId = null, ?int $sessionId = null): ChatSession
    {
        if ($sessionId) {
            $session = ChatSession::find($sessionId);
            if ($session) {
                return $session;
            }
        }

        return ChatSession::create([
            'user_id' => $userId,
            'title' => 'Chat Baru - ' . now()->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Get all sessions for a user.
     */
    public function getUserSessions(?int $userId = null): \Illuminate\Database\Eloquent\Collection
    {
        if ($userId) {
            return ChatSession::where('user_id', $userId)
                ->orderBy('updated_at', 'desc')
                ->get();
        }

        // For guest users, return empty collection for security
        return new \Illuminate\Database\Eloquent\Collection();
    }
}
