<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\CsTrainingData;
use App\Models\NlpConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ChatBotService
{
    protected WhatsAppService $whatsappService;

    /**
     * Log level priorities for filtering
     */
    protected const LOG_LEVELS = ['debug' => 0, 'info' => 1, 'warning' => 2, 'error' => 3];

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
        $startTime = microtime(true);
        $originalMessage = $message;
        
        // Preprocess message
        $message = $this->preprocessMessage($message);
        
        $this->logNlp('info', 'NLP Intent Detection Started', [
            'original_message' => $originalMessage,
            'preprocessed_message' => $message,
        ]);
        
        // Get active training data ordered by priority
        $trainingData = $this->getTrainingData();
        
        $this->logNlp('debug', 'Training Data Loaded', [
            'total_records' => $trainingData->count(),
        ]);

        $bestMatch = null;
        $maxConfidence = 0;
        $allMatches = [];
        $confidenceThreshold = $this->getNlpConfig('nlp_confidence_threshold', 0.3);

        foreach ($trainingData as $data) {
            $confidence = $this->calculateConfidence($message, $data);
            
            // Log individual match
            if ($this->getNlpConfig('nlp_log_confidence_scores', true)) {
                $allMatches[] = [
                    'intent' => $data->intent,
                    'pattern' => $data->pattern,
                    'confidence' => round($confidence, 4),
                ];
            }
            
            if ($confidence > $maxConfidence) {
                $maxConfidence = $confidence;
                $bestMatch = $data;
            }
        }

        $processingTime = round((microtime(true) - $startTime) * 1000, 2);

        if ($bestMatch && $maxConfidence > $confidenceThreshold) {
            $this->logNlp('info', 'Intent Detected Successfully', [
                'intent' => $bestMatch->intent,
                'confidence' => round($maxConfidence, 4),
                'threshold' => $confidenceThreshold,
                'matched_pattern' => $bestMatch->pattern,
                'processing_time_ms' => $processingTime,
                'top_matches' => array_slice($allMatches, 0, 5),
            ]);
            
            return [
                'intent' => $bestMatch->intent,
                'confidence' => $maxConfidence,
                'matched_pattern' => $bestMatch->pattern,
                'training_data' => $bestMatch,
                'processing_time_ms' => $processingTime,
                'all_matches' => $allMatches,
            ];
        }

        // Default intent
        $this->logNlp('warning', 'No Intent Matched', [
            'message' => $message,
            'max_confidence' => round($maxConfidence, 4),
            'threshold' => $confidenceThreshold,
            'processing_time_ms' => $processingTime,
            'top_matches' => array_slice($allMatches, 0, 5),
        ]);
        
        return [
            'intent' => 'unknown',
            'confidence' => 0,
            'matched_pattern' => null,
            'training_data' => null,
            'processing_time_ms' => $processingTime,
            'all_matches' => $allMatches,
        ];
    }

    /**
     * Calculate confidence score for a training data match.
     * Uses simple keyword matching and pattern similarity.
     */
    protected function calculateConfidence(string $message, CsTrainingData $data): float
    {
        $confidence = 0;
        $details = [];
        
        // Exact pattern match
        $pattern = strtolower($data->pattern);
        if ($message === $pattern && $this->getNlpConfig('nlp_enable_exact_match', true)) {
            $this->logNlp('debug', 'Exact Match Found', [
                'pattern' => $pattern,
                'message' => $message,
            ]);
            return 1.0;
        }

        // Partial pattern match
        if ($this->getNlpConfig('nlp_enable_partial_match', true)) {
            if (str_contains($message, $pattern) || str_contains($pattern, $message)) {
                $partialWeight = $this->getNlpConfig('nlp_partial_match_weight', 0.6);
                $confidence += $partialWeight;
                $details['partial_match'] = $partialWeight;
            }
        }

        // Keyword matching
        if ($this->getNlpConfig('nlp_enable_keyword_match', true) && $data->keywords && is_array($data->keywords)) {
            $matchedKeywords = 0;
            $totalKeywords = count($data->keywords);
            
            foreach ($data->keywords as $keyword) {
                if (str_contains($message, strtolower($keyword))) {
                    $matchedKeywords++;
                }
            }
            
            if ($totalKeywords > 0) {
                $keywordWeight = $this->getNlpConfig('nlp_keyword_match_weight', 0.4);
                $keywordScore = ($matchedKeywords / $totalKeywords) * $keywordWeight;
                $confidence += $keywordScore;
                $details['keyword_match'] = [
                    'matched' => $matchedKeywords,
                    'total' => $totalKeywords,
                    'score' => $keywordScore,
                ];
            }
        }

        // Word similarity (simple approach)
        if ($this->getNlpConfig('nlp_enable_word_similarity', true)) {
            $messageWords = explode(' ', $message);
            $patternWords = explode(' ', $pattern);
            $commonWords = array_intersect($messageWords, $patternWords);
            
            if (count($patternWords) > 0) {
                $similarityWeight = $this->getNlpConfig('nlp_word_similarity_weight', 0.3);
                $wordSimilarity = (count($commonWords) / count($patternWords)) * $similarityWeight;
                $confidence += $wordSimilarity;
                $details['word_similarity'] = [
                    'common_words' => count($commonWords),
                    'total_words' => count($patternWords),
                    'score' => $wordSimilarity,
                ];
            }
        }

        $finalConfidence = min($confidence, 1.0);
        
        if ($this->getNlpConfig('nlp_log_confidence_scores', true) && $finalConfidence > 0.1) {
            $this->logNlp('debug', 'Confidence Calculated', [
                'intent' => $data->intent,
                'pattern' => $pattern,
                'confidence' => round($finalConfidence, 4),
                'details' => $details,
            ]);
        }

        return $finalConfidence;
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

    /**
     * Preprocess message before NLP processing.
     */
    protected function preprocessMessage(string $message): string
    {
        // Convert to lowercase
        if ($this->getNlpConfig('nlp_convert_lowercase', true)) {
            $message = strtolower($message);
        }

        // Remove punctuation - using simple approach for better performance
        if ($this->getNlpConfig('nlp_remove_punctuation', true)) {
            // Remove common punctuation while preserving Indonesian characters
            $message = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $message);
        }

        // Normalize whitespace
        if ($this->getNlpConfig('nlp_normalize_whitespace', true)) {
            $message = preg_replace('/\s+/', ' ', $message);
        }

        return trim($message);
    }

    /**
     * Get training data with optional caching.
     */
    protected function getTrainingData()
    {
        $cacheEnabled = $this->getNlpConfig('nlp_cache_training_data', false);
        $cacheTtl = $this->getNlpConfig('nlp_cache_ttl', 3600);

        if ($cacheEnabled) {
            return Cache::remember('nlp_training_data', $cacheTtl, function () {
                return CsTrainingData::active()->byPriority()->get();
            });
        }

        return CsTrainingData::active()->byPriority()->get();
    }

    /**
     * Get NLP configuration value.
     */
    protected function getNlpConfig(string $key, $default = null)
    {
        // Try to get from database first
        $dbValue = NlpConfig::getValue($key, null);
        
        if ($dbValue !== null) {
            return $dbValue;
        }

        // Fallback to config file
        $configKey = str_replace('nlp_', '', $key);
        
        // Handle nested config keys
        if (str_contains($configKey, '_')) {
            $parts = explode('_', $configKey);
            if (count($parts) >= 2) {
                $group = $parts[0];
                $subKey = implode('_', array_slice($parts, 1));
                return config("nlp.{$group}.{$subKey}", $default);
            }
        }

        return config("nlp.{$configKey}", $default);
    }

    /**
     * Log NLP processing information.
     */
    protected function logNlp(string $level, string $message, array $context = []): void
    {
        if (!$this->getNlpConfig('nlp_enable_detailed_logging', true)) {
            return;
        }

        $logLevel = $this->getNlpConfig('nlp_log_level', 'debug');
        
        // Only log if current level is appropriate
        if ((self::LOG_LEVELS[$level] ?? 0) >= (self::LOG_LEVELS[$logLevel] ?? 0)) {
            Log::channel('daily')->{$level}("[NLP] {$message}", $context);
        }
    }
}
