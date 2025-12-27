<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NlpConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NlpConfigController extends Controller
{
    /**
     * Display NLP configuration interface.
     */
    public function index()
    {
        $configs = NlpConfig::orderBy('group')->orderBy('key')->get()->groupBy('group');
        
        return view('admin.nlp-config.index', compact('configs'));
    }

    /**
     * Update NLP configuration.
     */
    public function update(Request $request)
    {
        $request->validate([
            'configs' => 'required|array',
        ]);

        foreach ($request->configs as $configId => $configData) {
            $nlpConfig = NlpConfig::find($configId);
            
            if ($nlpConfig && isset($configData['key']) && isset($configData['value'])) {
                // Convert value based on type
                $value = $configData['value'];
                
                if ($nlpConfig->type === 'boolean') {
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                } elseif ($nlpConfig->type === 'integer') {
                    $value = (int) $value;
                } elseif ($nlpConfig->type === 'float') {
                    $value = (float) $value;
                }
                
                $nlpConfig->update(['value' => $value]);
            }
        }

        // Clear training data cache if caching is enabled
        Cache::forget('nlp_training_data');

        return redirect()->route('admin.nlp-config.index')
            ->with('success', 'Konfigurasi NLP berhasil diperbarui!');
    }

    /**
     * Reset NLP configuration to defaults.
     */
    public function reset()
    {
        // Define default configurations
        $defaultConfigs = [
            // Algorithm Configuration
            ['key' => 'nlp_confidence_threshold', 'value' => 0.3, 'type' => 'float', 'group' => 'algorithm', 'description' => 'Minimum confidence score (0-1) required to accept an intent match. Lower values are more permissive.'],
            ['key' => 'nlp_exact_match_weight', 'value' => 1.0, 'type' => 'float', 'group' => 'algorithm', 'description' => 'Weight for exact pattern matching in confidence calculation (0-1).'],
            ['key' => 'nlp_partial_match_weight', 'value' => 0.6, 'type' => 'float', 'group' => 'algorithm', 'description' => 'Weight for partial pattern matching in confidence calculation (0-1).'],
            ['key' => 'nlp_keyword_match_weight', 'value' => 0.4, 'type' => 'float', 'group' => 'algorithm', 'description' => 'Weight for keyword matching in confidence calculation (0-1).'],
            ['key' => 'nlp_word_similarity_weight', 'value' => 0.3, 'type' => 'float', 'group' => 'algorithm', 'description' => 'Weight for word similarity matching in confidence calculation (0-1).'],
            
            // Logging Configuration
            ['key' => 'nlp_enable_detailed_logging', 'value' => true, 'type' => 'boolean', 'group' => 'logging', 'description' => 'Enable detailed logging of NLP processing steps for debugging and transparency.'],
            ['key' => 'nlp_log_level', 'value' => 'debug', 'type' => 'string', 'group' => 'logging', 'description' => 'Log level for NLP operations (debug, info, warning, error).'],
            ['key' => 'nlp_log_intent_detection', 'value' => true, 'type' => 'boolean', 'group' => 'logging', 'description' => 'Log intent detection details including all matches and confidence scores.'],
            ['key' => 'nlp_log_confidence_scores', 'value' => true, 'type' => 'boolean', 'group' => 'logging', 'description' => 'Log detailed confidence score calculations for each training data match.'],
            
            // Algorithm Toggle
            ['key' => 'nlp_enable_exact_match', 'value' => true, 'type' => 'boolean', 'group' => 'algorithm', 'description' => 'Enable exact pattern matching algorithm.'],
            ['key' => 'nlp_enable_partial_match', 'value' => true, 'type' => 'boolean', 'group' => 'algorithm', 'description' => 'Enable partial pattern matching algorithm.'],
            ['key' => 'nlp_enable_keyword_match', 'value' => true, 'type' => 'boolean', 'group' => 'algorithm', 'description' => 'Enable keyword matching algorithm.'],
            ['key' => 'nlp_enable_word_similarity', 'value' => true, 'type' => 'boolean', 'group' => 'algorithm', 'description' => 'Enable word similarity matching algorithm.'],
            
            // Performance Configuration
            ['key' => 'nlp_cache_training_data', 'value' => false, 'type' => 'boolean', 'group' => 'performance', 'description' => 'Cache training data to improve performance (disable for real-time updates).'],
            ['key' => 'nlp_cache_ttl', 'value' => 3600, 'type' => 'integer', 'group' => 'performance', 'description' => 'Training data cache time-to-live in seconds (default: 1 hour).'],
            
            // Preprocessing Configuration
            ['key' => 'nlp_remove_punctuation', 'value' => true, 'type' => 'boolean', 'group' => 'preprocessing', 'description' => 'Remove punctuation from messages before processing.'],
            ['key' => 'nlp_normalize_whitespace', 'value' => true, 'type' => 'boolean', 'group' => 'preprocessing', 'description' => 'Normalize whitespace (collapse multiple spaces to single space).'],
            ['key' => 'nlp_convert_lowercase', 'value' => true, 'type' => 'boolean', 'group' => 'preprocessing', 'description' => 'Convert all text to lowercase for case-insensitive matching.'],
        ];

        // Reset configurations
        foreach ($defaultConfigs as $config) {
            NlpConfig::updateOrCreate(
                ['key' => $config['key']],
                $config
            );
        }

        // Clear cache
        Cache::forget('nlp_training_data');

        return redirect()->route('admin.nlp-config.index')
            ->with('success', 'Konfigurasi NLP berhasil direset ke default!');
    }

    /**
     * Get NLP statistics and diagnostics.
     */
    public function diagnostics()
    {
        $trainingDataCount = \App\Models\CsTrainingData::count();
        $activeTrainingData = \App\Models\CsTrainingData::active()->count();
        $totalProcessed = \App\Models\ChatMessage::where('role', 'bot')
            ->whereNotNull('intent')
            ->count();
        $avgConfidence = \App\Models\ChatMessage::where('role', 'bot')
            ->whereNotNull('confidence')
            ->avg('confidence');
        
        $intentDistribution = \App\Models\ChatMessage::where('role', 'bot')
            ->whereNotNull('intent')
            ->selectRaw('intent, COUNT(*) as count, AVG(confidence) as avg_confidence')
            ->groupBy('intent')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.nlp-config.diagnostics', compact(
            'trainingDataCount',
            'activeTrainingData',
            'totalProcessed',
            'avgConfidence',
            'intentDistribution'
        ));
    }

    /**
     * Clear NLP cache.
     */
    public function clearCache()
    {
        Cache::forget('nlp_training_data');
        
        return redirect()->route('admin.nlp-config.index')
            ->with('success', 'Cache NLP berhasil dibersihkan!');
    }
}
