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
            'configs.*.key' => 'required|string',
            'configs.*.value' => 'required',
        ]);

        foreach ($request->configs as $config) {
            $nlpConfig = NlpConfig::where('key', $config['key'])->first();
            
            if ($nlpConfig) {
                // Convert value based on type
                $value = $config['value'];
                
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
        // Run the seeder again to reset to defaults
        \Artisan::call('db:seed', ['--class' => 'NlpConfigSeeder', '--force' => true]);

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
