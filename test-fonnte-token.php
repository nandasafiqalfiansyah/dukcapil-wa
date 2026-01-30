#!/usr/bin/env php
<?php

/**
 * Script untuk test koneksi ke Fonnte API
 * Usage: php test-fonnte-token.php [optional-token]
 */

require __DIR__.'/vendor/autoload.php';

// Load .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get token from command line argument or .env
$token = $argv[1] ?? $_ENV['FONNTE_TOKEN'] ?? null;
$apiUrl = $_ENV['FONNTE_API_URL'] ?? 'https://api.fonnte.com';

if (!$token) {
    echo "❌ Error: Fonnte token not provided\n";
    echo "Usage: php test-fonnte-token.php [token]\n";
    echo "Or set FONNTE_TOKEN in .env file\n";
    exit(1);
}

echo "🔍 Testing Fonnte API Connection...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "API URL: $apiUrl\n";
echo "Token: " . substr($token, 0, 10) . "...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Test connection
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$apiUrl/get-devices");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true); // Use POST method
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: $token"
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ Connection Error: $error\n";
        exit(1);
    }
    
    $data = json_decode($response, true);
    
    echo "HTTP Status: $httpCode\n";
    echo "Response:\n";
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    
    // Check if response is successful
    if ($httpCode === 200 && isset($data['status']) && $data['status'] === true) {
        // Check if there are connected devices
        if (isset($data['data']) && is_array($data['data']) && count($data['data']) > 0) {
            $device = $data['data'][0];
            
            echo "✅ SUCCESS! Token is valid\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "Device Number: {$device['device']}\n";
            echo "Status: {$device['status']}\n";
            echo "Name: {$device['name']}\n";
            echo "Package: {$device['package']}\n";
            echo "Quota: {$device['quota']} messages\n";
            echo "Auto Read: {$device['autoread']}\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "✅ Bot is ready to receive and reply messages!\n";
            exit(0);
        } else {
            echo "⚠️  WARNING: Token valid but no devices connected\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "Please connect a WhatsApp device at: https://fonnte.com\n";
            exit(1);
        }
    } elseif ($httpCode === 401) {
        echo "❌ FAILED: Invalid token\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Please check your token at: https://fonnte.com\n";
        echo "Make sure:\n";
        echo "1. Token is correct\n";
        echo "2. Token has not expired\n";
        echo "3. Your Fonnte account is active\n";
        exit(1);
    } elseif ($httpCode === 403) {
        echo "❌ FAILED: Access forbidden\n";
        echo "Your account may not have permission\n";
        exit(1);
    } elseif ($httpCode >= 500) {
        echo "❌ FAILED: Fonnte server error\n";
        echo "Please try again later\n";
        exit(1);
    } else {
        echo "❌ FAILED: Unexpected response\n";
        echo "Reason: " . ($data['reason'] ?? 'Unknown error') . "\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    exit(1);
}
