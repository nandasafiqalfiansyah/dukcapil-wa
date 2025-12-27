<?php

namespace Database\Seeders;

use App\Models\NlpConfig;
use Illuminate\Database\Seeder;

class NlpConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = [
            // Algorithm Configuration
            [
                'key' => 'nlp_confidence_threshold',
                'value' => 0.3,
                'type' => 'float',
                'group' => 'algorithm',
                'description' => 'Minimum confidence score (0-1) required to accept an intent match. Lower values are more permissive.',
            ],
            [
                'key' => 'nlp_exact_match_weight',
                'value' => 1.0,
                'type' => 'float',
                'group' => 'algorithm',
                'description' => 'Weight for exact pattern matching in confidence calculation (0-1).',
            ],
            [
                'key' => 'nlp_partial_match_weight',
                'value' => 0.6,
                'type' => 'float',
                'group' => 'algorithm',
                'description' => 'Weight for partial pattern matching in confidence calculation (0-1).',
            ],
            [
                'key' => 'nlp_keyword_match_weight',
                'value' => 0.4,
                'type' => 'float',
                'group' => 'algorithm',
                'description' => 'Weight for keyword matching in confidence calculation (0-1).',
            ],
            [
                'key' => 'nlp_word_similarity_weight',
                'value' => 0.3,
                'type' => 'float',
                'group' => 'algorithm',
                'description' => 'Weight for word similarity matching in confidence calculation (0-1).',
            ],

            // Logging Configuration
            [
                'key' => 'nlp_enable_detailed_logging',
                'value' => true,
                'type' => 'boolean',
                'group' => 'logging',
                'description' => 'Enable detailed logging of NLP processing steps for debugging and transparency.',
            ],
            [
                'key' => 'nlp_log_level',
                'value' => 'debug',
                'type' => 'string',
                'group' => 'logging',
                'description' => 'Log level for NLP operations (debug, info, warning, error).',
            ],
            [
                'key' => 'nlp_log_intent_detection',
                'value' => true,
                'type' => 'boolean',
                'group' => 'logging',
                'description' => 'Log intent detection details including all matches and confidence scores.',
            ],
            [
                'key' => 'nlp_log_confidence_scores',
                'value' => true,
                'type' => 'boolean',
                'group' => 'logging',
                'description' => 'Log detailed confidence score calculations for each training data match.',
            ],

            // Algorithm Toggle
            [
                'key' => 'nlp_enable_exact_match',
                'value' => true,
                'type' => 'boolean',
                'group' => 'algorithm',
                'description' => 'Enable exact pattern matching algorithm.',
            ],
            [
                'key' => 'nlp_enable_partial_match',
                'value' => true,
                'type' => 'boolean',
                'group' => 'algorithm',
                'description' => 'Enable partial pattern matching algorithm.',
            ],
            [
                'key' => 'nlp_enable_keyword_match',
                'value' => true,
                'type' => 'boolean',
                'group' => 'algorithm',
                'description' => 'Enable keyword matching algorithm.',
            ],
            [
                'key' => 'nlp_enable_word_similarity',
                'value' => true,
                'type' => 'boolean',
                'group' => 'algorithm',
                'description' => 'Enable word similarity matching algorithm.',
            ],

            // Performance Configuration
            [
                'key' => 'nlp_cache_training_data',
                'value' => false,
                'type' => 'boolean',
                'group' => 'performance',
                'description' => 'Cache training data to improve performance (disable for real-time updates).',
            ],
            [
                'key' => 'nlp_cache_ttl',
                'value' => 3600,
                'type' => 'integer',
                'group' => 'performance',
                'description' => 'Training data cache time-to-live in seconds (default: 1 hour).',
            ],

            // Preprocessing Configuration
            [
                'key' => 'nlp_remove_punctuation',
                'value' => true,
                'type' => 'boolean',
                'group' => 'preprocessing',
                'description' => 'Remove punctuation from messages before processing.',
            ],
            [
                'key' => 'nlp_normalize_whitespace',
                'value' => true,
                'type' => 'boolean',
                'group' => 'preprocessing',
                'description' => 'Normalize whitespace (collapse multiple spaces to single space).',
            ],
            [
                'key' => 'nlp_convert_lowercase',
                'value' => true,
                'type' => 'boolean',
                'group' => 'preprocessing',
                'description' => 'Convert all text to lowercase for case-insensitive matching.',
            ],
        ];

        foreach ($configs as $config) {
            NlpConfig::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }
    }
}
