<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\BotInstance;

$mainToken = env('FONNTE_TOKEN');
$apiUrl = env('FONNTE_API_URL', 'https://api.fonnte.com');

echo "🔄 Syncing Bot Instances with Fonnte Devices...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Get devices from Fonnte
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$apiUrl/get-devices");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: $mainToken"
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!isset($data['status']) || $data['status'] !== true) {
    echo "❌ Failed to get devices from Fonnte\n";
    print_r($data);
    exit(1);
}

$devices = $data['data'] ?? [];

if (empty($devices)) {
    echo "⚠️  No devices found in Fonnte\n";
    exit(1);
}

echo "Found " . count($devices) . " device(s) in Fonnte:\n\n";

foreach ($devices as $device) {
    $phoneNumber = $device['device'];
    $deviceToken = $device['token'];
    $status = $device['status'];
    $name = $device['name'];
    
    echo "📱 Device: $name ($phoneNumber)\n";
    echo "   Status: $status\n";
    echo "   Token: " . substr($deviceToken, 0, 10) . "...\n";
    
    // Find or create bot instance
    $bot = BotInstance::where('phone_number', $phoneNumber)->first();
    
    if (!$bot) {
        // Create new bot instance
        $bot = BotInstance::create([
            'bot_id' => 'fonnte_' . $phoneNumber,
            'name' => $name,
            'status' => $status === 'connect' ? 'connected' : 'disconnected',
            'phone_number' => $phoneNumber,
            'platform' => 'fonnte',
            'is_active' => $status === 'connect',
            'last_connected_at' => $status === 'connect' ? now() : null,
            'metadata' => [
                'token' => $deviceToken,
                'package' => $device['package'] ?? null,
                'quota' => $device['quota'] ?? null,
                'autoread' => $device['autoread'] ?? null,
                'api_url' => $apiUrl,
            ],
        ]);
        echo "   ✅ Created new bot instance\n";
    } else {
        // Update existing bot
        $metadata = $bot->metadata ?? [];
        $metadata['token'] = $deviceToken;
        $metadata['package'] = $device['package'] ?? null;
        $metadata['quota'] = $device['quota'] ?? null;
        $metadata['autoread'] = $device['autoread'] ?? null;
        $metadata['api_url'] = $apiUrl;
        
        $bot->update([
            'name' => $name,
            'status' => $status === 'connect' ? 'connected' : 'disconnected',
            'is_active' => $status === 'connect',
            'last_connected_at' => $status === 'connect' ? now() : $bot->last_connected_at,
            'metadata' => $metadata,
        ]);
        echo "   ✅ Updated bot instance\n";
    }
    
    echo "\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Sync completed!\n";
echo "\n";
echo "Now each bot instance has its own device token.\n";
echo "Messages will be sent using the correct token.\n";
