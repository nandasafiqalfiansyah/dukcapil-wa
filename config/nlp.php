<?php

return [
    /*
    |--------------------------------------------------------------------------
    | NLP Algorithm Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Natural Language Processing engine used for
    | intent detection and chatbot responses.
    |
    */

    // Minimum confidence threshold for accepting an intent match
    'confidence_threshold' => env('NLP_CONFIDENCE_THRESHOLD', 0.3),

    // Enable detailed logging for NLP processing (disable in production for performance)
    'enable_detailed_logging' => env('NLP_ENABLE_LOGGING', false),

    // Log level for NLP operations (debug, info, warning, error)
    'log_level' => env('NLP_LOG_LEVEL', 'info'),

    // Algorithm weights for confidence calculation
    'algorithm' => [
        // Weight for exact pattern matching (0-1)
        'exact_match_weight' => env('NLP_EXACT_MATCH_WEIGHT', 1.0),
        
        // Weight for partial pattern matching (0-1)
        'partial_match_weight' => env('NLP_PARTIAL_MATCH_WEIGHT', 0.6),
        
        // Weight for keyword matching (0-1)
        'keyword_match_weight' => env('NLP_KEYWORD_MATCH_WEIGHT', 0.4),
        
        // Weight for word similarity (0-1)
        'word_similarity_weight' => env('NLP_WORD_SIMILARITY_WEIGHT', 0.3),
    ],

    // Enable/disable specific matching algorithms
    'enabled_algorithms' => [
        'exact_match' => env('NLP_ENABLE_EXACT_MATCH', true),
        'partial_match' => env('NLP_ENABLE_PARTIAL_MATCH', true),
        'keyword_match' => env('NLP_ENABLE_KEYWORD_MATCH', true),
        'word_similarity' => env('NLP_ENABLE_WORD_SIMILARITY', true),
    ],

    // Performance settings
    'cache_training_data' => env('NLP_CACHE_TRAINING_DATA', false),
    'cache_ttl' => env('NLP_CACHE_TTL', 3600), // seconds

    // Text preprocessing
    'preprocessing' => [
        'remove_punctuation' => env('NLP_REMOVE_PUNCTUATION', true),
        'normalize_whitespace' => env('NLP_NORMALIZE_WHITESPACE', true),
        'convert_to_lowercase' => env('NLP_CONVERT_LOWERCASE', true),
    ],
];
