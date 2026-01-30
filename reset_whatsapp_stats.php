<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\WhatsAppUser;
use App\Models\BotInstance;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

// Disable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Clear existing data
ChatMessage::truncate();
ChatSession::truncate();

// Re-enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "Creating test data...\n";

// Get or create a bot instance
$bot = BotInstance::first() ?? BotInstance::create([
    'name' => 'Test Bot',
    'phone_number' => '6281234567890',
    'status' => 'connected',
    'is_active' => true,
]);

// Create 31 chat sessions (mix of WhatsApp and Web)
$sessions = [];

// Create 20 WhatsApp sessions
for ($i = 1; $i <= 20; $i++) {
    $session = ChatSession::create([
        'whatsapp_user_id' => WhatsAppUser::inRandomOrder()->first()->id ?? null,
        'bot_instance_id' => $bot->id,
        'session_token' => Str::uuid(),
        'last_message_at' => now()->subHours(rand(0, 720)),
        'message_count' => rand(1, 5),
        'is_connected_to_whatsapp' => true,
    ]);
    $sessions[] = $session;
}

// Create 11 Web chat sessions
for ($i = 1; $i <= 11; $i++) {
    $session = ChatSession::create([
        'user_id' => 1, // Assuming user ID 1 exists
        'bot_instance_id' => $bot->id,
        'session_token' => Str::uuid(),
        'last_message_at' => now()->subHours(rand(0, 720)),
        'message_count' => rand(1, 5),
        'is_connected_to_whatsapp' => false,
    ]);
    $sessions[] = $session;
}

echo "Created 31 chat sessions (20 WA + 11 Web)\n";

// Filter sessions by type
$waSessions = array_filter($sessions, fn($s) => $s->is_connected_to_whatsapp);
$webSessions = array_filter($sessions, fn($s) => !$s->is_connected_to_whatsapp);

// Create 15 WA received messages + 7 Web received messages = 22 total
for ($i = 1; $i <= 15; $i++) {
    ChatMessage::create([
        'chat_session_id' => $waSessions[array_rand($waSessions)]->id,
        'message_type' => 'text',
        'role' => 'user',
        'message' => "WA User message #$i",
        'created_at' => now()->subHours(rand(0, 720)),
    ]);
}

for ($i = 1; $i <= 7; $i++) {
    ChatMessage::create([
        'chat_session_id' => $webSessions[array_rand($webSessions)]->id,
        'message_type' => 'text',
        'role' => 'user',
        'message' => "Web User message #$i",
        'created_at' => now()->subHours(rand(0, 720)),
    ]);
}

echo "Created 22 messages received (15 WA + 7 Web)\n";

// Create 6 WA sent messages + 3 Web sent messages = 9 total
for ($i = 1; $i <= 6; $i++) {
    ChatMessage::create([
        'chat_session_id' => $waSessions[array_rand($waSessions)]->id,
        'message_type' => 'text',
        'role' => 'bot',
        'message' => "WA Bot reply #$i",
        'created_at' => now()->subHours(rand(0, 720)),
    ]);
}

for ($i = 1; $i <= 3; $i++) {
    ChatMessage::create([
        'chat_session_id' => $webSessions[array_rand($webSessions)]->id,
        'message_type' => 'text',
        'role' => 'bot',
        'message' => "Web Bot reply #$i",
        'created_at' => now()->subHours(rand(0, 720)),
    ]);
}

echo "Created 9 messages sent (6 WA + 3 Web)\n";

// Verify
$totalReceived = ChatMessage::where('role', 'user')->count();
$totalSent = ChatMessage::where('role', 'bot')->count();
$totalConversations = ChatSession::count();

$waReceived = ChatMessage::where('role', 'user')->whereHas('chatSession', fn($q) => $q->where('is_connected_to_whatsapp', true))->count();
$waSent = ChatMessage::where('role', 'bot')->whereHas('chatSession', fn($q) => $q->where('is_connected_to_whatsapp', true))->count();
$waConversations = ChatSession::where('is_connected_to_whatsapp', true)->count();

$webReceived = ChatMessage::where('role', 'user')->whereHas('chatSession', fn($q) => $q->where('is_connected_to_whatsapp', false))->count();
$webSent = ChatMessage::where('role', 'bot')->whereHas('chatSession', fn($q) => $q->where('is_connected_to_whatsapp', false))->count();
$webConversations = ChatSession::where('is_connected_to_whatsapp', false)->count();

echo "\n✅ Statistics Reset Complete:\n";
echo "═══════════════════════════════════════\n";
echo "TOTAL (WA + Web):\n";
echo "  Messages Received: $totalReceived\n";
echo "  Messages Sent: $totalSent\n";
echo "  Total Conversations: $totalConversations\n";
echo "\n";
echo "WhatsApp Only:\n";
echo "  Messages Received: $waReceived\n";
echo "  Messages Sent: $waSent\n";
echo "  Conversations: $waConversations\n";
echo "\n";
echo "Web Chat Only:\n";
echo "  Messages Received: $webReceived\n";
echo "  Messages Sent: $webSent\n";
echo "  Conversations: $webConversations\n";
