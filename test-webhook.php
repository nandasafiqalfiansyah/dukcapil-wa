#!/usr/bin/env php
<?php

/**
 * Script untuk test webhook handler secara lokal
 * Usage: php test-webhook.php
 */

require __DIR__.'/vendor/autoload.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$appUrl = $_ENV['APP_URL'] ?? 'http://localhost:8000';
$webhookUrl = "$appUrl/api/webhook/whatsapp";

echo "🧪 Testing Webhook Handler...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Webhook URL: $webhookUrl\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Test data - simulating Fonnte webhook
$testData = [
    'device' => '62851775578',
    'from' => '628123456789@c.us',
    'message' => 'ping',
    'name' => 'Test User',
    'type' => 'text',
    'id' => 'TEST_' . uniqid(),
];

echo "📤 Sending test webhook data:\n";
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";

try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ Connection Error: $error\n";
        echo "\nMake sure Laravel server is running:\n";
        echo "  php artisan serve\n";
        exit(1);
    }
    
    echo "📥 Response:\n";
    echo "HTTP Status: $httpCode\n";
    echo "Body: $response\n\n";
    
    if ($httpCode === 200) {
        echo "✅ SUCCESS! Webhook handler is working\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "\n📋 Next steps:\n";
        echo "1. Check storage/logs/laravel.log for processing details\n";
        echo "2. Check database conversation_logs table for saved message\n";
        echo "3. Setup webhook URL in Fonnte dashboard:\n";
        echo "   → $webhookUrl\n";
        echo "\n💡 For production, use your actual domain\n";
        echo "💡 For development, use ngrok to expose local server\n";
        exit(0);
    } else {
        echo "❌ FAILED: Unexpected HTTP status\n";
        echo "Check if Laravel server is running and routes are correct\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    exit(1);
}
