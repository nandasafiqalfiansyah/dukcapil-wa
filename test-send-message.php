<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\BotInstance;

$apiUrl = env('FONNTE_API_URL', 'https://api.fonnte.com');

// Get bot with token
$bot = BotInstance::where('status', 'connected')->first();

if (!$bot) {
    echo "❌ No connected bot found. Run sync-fonnte-devices.php first\n";
    exit(1);
}

$token = $bot->metadata['token'] ?? null;

if (!$token) {
    echo "❌ Bot has no token in metadata\n";
    exit(1);
}

// Test number
$testNumber = $argv[1] ?? '628979213614';

echo "🧪 Testing Send Message via Bot Instance...\n";
echo "Bot: {$bot->name} ({$bot->phone_number})\n";
echo "Token: " . substr($token, 0, 10) . "...\n";
echo "Target: $testNumber\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$apiUrl/send");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: $token"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'target' => $testNumber,
    'message' => 'Test dari Laravel - ' . date('Y-m-d H:i:s')
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
if ($error) {
    echo "cURL Error: $error\n";
}
echo "Response:\n";
echo $response . "\n\n";

$data = json_decode($response, true);
echo "Parsed:\n";
print_r($data);
