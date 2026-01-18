<?php

namespace App\Services;

use App\Models\AutoReplyConfig;
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
        try {
            // Save user message
            $userChatMessage = ChatMessage::create([
                'chat_session_id' => $session->id,
                'role' => 'user',
                'message' => $userMessage,
            ]);

            // Check for auto-reply match first (takes priority over NLP)
            $autoReplyResult = $this->checkAutoReply($userMessage);
            
            if ($autoReplyResult) {
                // Use auto-reply response
                $response = [
                    'message' => $autoReplyResult['response'],
                    'intent' => 'auto_reply',
                ];
                $intentResult = [
                    'intent' => 'auto_reply',
                    'confidence' => 1.0,
                    'matched_pattern' => $autoReplyResult['trigger'],
                    'source' => 'auto_reply_config',
                ];
            } else {
                // Fallback to NLP detection
                $intentResult = $this->detectIntent($userMessage);
                $response = $this->generateResponse($intentResult, $userMessage);
            }

            // Save bot message
            $botChatMessage = ChatMessage::create([
                'chat_session_id' => $session->id,
                'role' => 'bot',
                'message' => $response['message'],
                'intent' => $intentResult['intent'],
                'confidence' => $intentResult['confidence'],
                'metadata' => [
                    'matched_pattern' => $intentResult['matched_pattern'] ?? null,
                    'source' => $intentResult['source'] ?? 'nlp',
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
        } catch (\Exception $e) {
            Log::error('[ChatBot] Error processing message', [
                'session_id' => $session->id,
                'message' => $userMessage,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Create fallback error response
            $errorResponse = "Maaf, terjadi kesalahan dalam memproses pesan Anda. Silakan coba lagi atau hubungi petugas kami untuk bantuan.";
            
            $botChatMessage = ChatMessage::create([
                'chat_session_id' => $session->id,
                'role' => 'bot',
                'message' => $errorResponse,
                'intent' => 'error',
                'confidence' => 0,
                'metadata' => [
                    'error' => true,
                    'error_message' => $e->getMessage(),
                    'source' => 'error_handler',
                ],
            ]);

            return [
                'user_message' => $userChatMessage ?? null,
                'bot_message' => $botChatMessage,
                'intent' => 'error',
                'confidence' => 0,
            ];
        }
    }

    /**
     * Check if message matches any auto-reply configuration.
     * Returns the matched auto-reply or null if no match.
     */
    protected function checkAutoReply(string $message): ?array
    {
        $messageBody = trim($message);

        // Get active auto-reply configurations ordered by priority (with caching)
        $autoReplies = Cache::remember('auto_reply_configs_active', 300, function () {
            return AutoReplyConfig::active()
                ->byPriority()
                ->get();
        });

        // Check if message matches any auto-reply trigger
        foreach ($autoReplies as $autoReply) {
            $trigger = $autoReply->trigger;
            $matches = false;

            if ($autoReply->case_sensitive) {
                $matches = $messageBody === $trigger;
            } else {
                $matches = strtolower($messageBody) === strtolower($trigger);
            }

            if ($matches) {
                // Replace dynamic placeholders in response
                $response = str_replace(
                    ['{{timestamp}}', '{{date}}', '{{time}}', '{{day}}', '{{user_message}}'],
                    [
                        now()->format('d/m/Y H:i:s'),
                        now()->format('d/m/Y'),
                        now()->format('H:i:s'),
                        now()->isoFormat('dddd'),
                        $messageBody,
                    ],
                    $autoReply->response
                );

                Log::info('[ChatBot] Auto-reply matched', [
                    'trigger' => $trigger,
                    'priority' => $autoReply->priority,
                    'case_sensitive' => $autoReply->case_sensitive,
                ]);

                return [
                    'trigger' => $trigger,
                    'response' => $response,
                    'config_id' => $autoReply->id,
                    'priority' => $autoReply->priority,
                ];
            }
        }

        return null;
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
        
        // Early exit for very short or likely random messages
        $wordCount = str_word_count($message);
        if ($wordCount === 1 && strlen($message) <= 8 && !preg_match('/[aeiou]{2,}/i', $message)) {
            // Looks like random string (e.g., "dsada", "sdfsdf")
            return [
                'intent' => 'unknown',
                'confidence' => 0,
                'matched_pattern' => null,
                'training_data' => null,
                'processing_time_ms' => round((microtime(true) - $startTime) * 1000, 2),
                'all_matches' => [],
                'early_exit' => 'random_pattern',
            ];
        }
        
        $this->logNlp('info', 'NLP Intent Detection Started', [
            'original_message' => $originalMessage,
            'preprocessed_message' => $message,
        ]);
        
        // Get active training data ordered by priority (with caching)
        $trainingData = Cache::remember('cs_training_data_active', 300, function () {
            return $this->getTrainingData();
        });

        $bestMatch = null;
        $maxConfidence = 0;
        $allMatches = [];
        $confidenceThreshold = $this->getNlpConfig('nlp_confidence_threshold', 0.3);
        $maxIterations = 50; // Limit iterations untuk prevent timeout
        $iteration = 0;

        foreach ($trainingData as $data) {
            $iteration++;
            
            // Break early if we found a very good match or exceeded max iterations
            if ($maxConfidence >= 0.95 || $iteration > $maxIterations) {
                break;
            }
            
            $confidence = $this->calculateConfidence($message, $data);
            
            // Only log matches above minimal threshold
            if ($confidence > 0.1) {
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
                'iterations' => $iteration ?? 0,
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
        $this->logNlp('info', 'No Intent Matched', [
            'message' => substr($message, 0, 50),
            'max_confidence' => round($maxConfidence, 4),
            'threshold' => $confidenceThreshold,
            'processing_time_ms' => $processingTime,
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
        
        // Exact pattern match - fastest check
        $pattern = strtolower($data->pattern);
        if ($message === $pattern) {
            return 1.0;
        }

        // Quick length check - skip if too different
        $lengthDiff = abs(strlen($message) - strlen($pattern));
        if ($lengthDiff > max(strlen($message), strlen($pattern)) * 0.7) {
            return 0; // Too different in length
        }

        // Partial pattern match
        if (str_contains($message, $pattern) || str_contains($pattern, $message)) {
            $confidence += 0.6;
        }

        // Early exit if no partial match and no keywords
        if ($confidence === 0 && (!$data->keywords || !is_array($data->keywords) || count($data->keywords) === 0)) {
            return 0;
        }

        // Keyword matching (simplified)
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

        // Word similarity (only if we have some confidence already)
        if ($confidence > 0) {
            $messageWords = explode(' ', $message);
            $patternWords = explode(' ', $pattern);
            $commonWords = array_intersect($messageWords, $patternWords);
            
            if (count($patternWords) > 0) {
                $wordSimilarity = (count($commonWords) / count($patternWords)) * 0.3;
                $confidence += $wordSimilarity;
            }
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
        $defaultResponses = [
            "Maaf, saya belum memahami pertanyaan Anda. Silakan ulangi dengan kata kunci yang berbeda atau hubungi petugas kami untuk bantuan lebih lanjut.",
            "Maaf, saya belum dapat memproses pertanyaan tersebut. Mohon coba dengan pertanyaan lain atau hubungi kantor DUKCAPIL Ponorogo untuk bantuan langsung.",
            "Mohon maaf, pertanyaan Anda belum dapat saya pahami. Silakan kirim pertanyaan dengan kata kunci seperti 'KTP', 'KK', atau 'Akta' untuk informasi layanan kami.",
        ];
        
        // Randomly select a default response for variety
        $randomResponse = $defaultResponses[array_rand($defaultResponses)];
        
        return [
            'message' => $randomResponse,
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
