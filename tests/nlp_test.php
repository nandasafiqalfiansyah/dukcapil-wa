#!/usr/bin/env php
<?php

/**
 * Test script for NLP functionality
 * This script demonstrates the enhanced NLP features
 */

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CsTrainingData;
use App\Models\NlpConfig;
use App\Services\ChatBotService;
use App\Models\ChatSession;

echo "\n";
echo "==========================================\n";
echo "  NLP Testing Script\n";
echo "==========================================\n\n";

// Test 1: Display NLP Configuration
echo "1. NLP Configuration:\n";
echo "   ------------------\n";
$configs = NlpConfig::where('group', 'algorithm')->get();
foreach ($configs as $config) {
    echo "   {$config->key}: {$config->value}\n";
}
echo "\n";

// Test 2: Display Training Data Statistics
echo "2. Training Data Statistics:\n";
echo "   -------------------------\n";
$totalTraining = CsTrainingData::count();
$activeTraining = CsTrainingData::active()->count();
$intents = CsTrainingData::select('intent')->distinct()->count();

echo "   Total Training Data: {$totalTraining}\n";
echo "   Active Training Data: {$activeTraining}\n";
echo "   Unique Intents: {$intents}\n";
echo "\n";

// Test 3: Display Sample Training Data by Intent
echo "3. Sample Training Data by Intent:\n";
echo "   -------------------------------\n";
$intentGroups = CsTrainingData::select('intent')
    ->distinct()
    ->orderBy('intent')
    ->get()
    ->groupBy('intent');

foreach (CsTrainingData::select('intent')->distinct()->orderBy('intent')->get() as $intentRow) {
    $count = CsTrainingData::where('intent', $intentRow->intent)->count();
    echo "   - {$intentRow->intent}: {$count} patterns\n";
}
echo "\n";

// Test 4: Test NLP Intent Detection
echo "4. Testing Intent Detection:\n";
echo "   -------------------------\n";

$testMessages = [
    'halo',
    'cara buat ktp',
    'ktp hilang',
    'jam buka',
    'berapa biaya',
    'akta kelahiran',
    'terima kasih',
];

$chatBotService = app(ChatBotService::class);
$testSession = new ChatSession(['id' => 1, 'user_id' => null, 'title' => 'Test Session']);

foreach ($testMessages as $message) {
    echo "\n   Message: \"{$message}\"\n";
    
    // Use reflection to call protected method
    $reflection = new ReflectionClass($chatBotService);
    $method = $reflection->getMethod('detectIntent');
    $method->setAccessible(true);
    
    $result = $method->invoke($chatBotService, $message);
    
    echo "   Intent: {$result['intent']}\n";
    echo "   Confidence: " . round($result['confidence'] * 100, 2) . "%\n";
    
    if (isset($result['matched_pattern'])) {
        echo "   Matched Pattern: {$result['matched_pattern']}\n";
    }
    
    if (isset($result['processing_time_ms'])) {
        echo "   Processing Time: {$result['processing_time_ms']}ms\n";
    }
}

echo "\n";
echo "==========================================\n";
echo "  Test Complete\n";
echo "==========================================\n\n";

echo "Next Steps:\n";
echo "- Run seeders: php artisan db:seed --class=ExtendedNlpTrainingDataSeeder\n";
echo "- Access NLP Config: http://localhost/admin/nlp-config\n";
echo "- View NLP Logs: http://localhost/admin/nlp-logs\n";
echo "- View Diagnostics: http://localhost/admin/nlp-config/diagnostics\n";
echo "\n";
