#!/usr/bin/env php
<?php

/**
 * Script untuk melihat incoming messages dari WhatsApp
 * Usage: php show-messages.php [limit]
 */

require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ConversationLog;
use App\Models\WhatsAppUser;

$limit = $argv[1] ?? 20;

echo "📥 INCOMING MESSAGES (Latest {$limit})\n";
echo "═══════════════════════════════════════════════════════════════════════\n\n";

$messages = ConversationLog::with('whatsappUser', 'botInstance')
    ->where('direction', 'incoming')
    ->orderBy('created_at', 'desc')
    ->limit($limit)
    ->get();

if ($messages->isEmpty()) {
    echo "❌ No incoming messages found\n\n";
    echo "Possible reasons:\n";
    echo "1. Webhook belum dikonfigurasi di Fonnte dashboard\n";
    echo "2. Belum ada pesan masuk dari WhatsApp\n";
    echo "3. Check storage/logs/laravel.log untuk error\n";
    exit(0);
}

// Header
printf("%-5s | %-12s | %-15s | %-12s | %-40s | %-8s | %-16s\n",
    "ID", "Device", "Sender", "Name", "Message", "Type", "Timestamp");
echo str_repeat("─", 120) . "\n";

foreach ($messages as $msg) {
    printf("%-5s | %-12s | %-15s | %-12s | %-40s | %-8s | %-16s\n",
        $msg->id,
        $msg->botInstance?->bot_id ?? substr($msg->bot_instance_id ?? '-', 0, 12),
        $msg->whatsappUser->phone_number,
        substr($msg->whatsappUser->name ?? '-', 0, 12),
        substr($msg->message_content, 0, 40),
        $msg->message_type,
        $msg->created_at->format('d M Y H:i')
    );
}

echo str_repeat("─", 120) . "\n";
echo "\n";

// Statistics
$incomingTotal = ConversationLog::where('direction', 'incoming')->count();
$outgoingTotal = ConversationLog::where('direction', 'outgoing')->count();
$totalUsers = WhatsAppUser::count();

echo "📊 Statistics:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📥 Total Incoming Messages: {$incomingTotal}\n";
echo "📤 Total Outgoing Messages: {$outgoingTotal}\n";
echo "👥 Total WhatsApp Users: {$totalUsers}\n";
echo "💬 Total Conversations: " . ($incomingTotal + $outgoingTotal) . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

// Recent users
echo "\n👥 Recent Active Users:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
$recentUsers = ConversationLog::with('whatsappUser')
    ->select('whatsapp_user_id')
    ->where('direction', 'incoming')
    ->groupBy('whatsapp_user_id')
    ->orderByRaw('MAX(created_at) DESC')
    ->limit(5)
    ->get();

foreach ($recentUsers as $log) {
    $user = $log->whatsappUser;
    $messageCount = ConversationLog::where('whatsapp_user_id', $user->id)
        ->where('direction', 'incoming')
        ->count();
    $lastMessage = ConversationLog::where('whatsapp_user_id', $user->id)
        ->where('direction', 'incoming')
        ->orderBy('created_at', 'desc')
        ->first();
    
    echo sprintf("• %s (%s) - %d messages - Last: %s\n",
        $user->phone_number,
        $user->name ?? 'Unknown',
        $messageCount,
        $lastMessage->created_at->diffForHumans()
    );
}
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
