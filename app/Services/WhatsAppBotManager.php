<?php

namespace App\Services;

use App\Models\BotInstance;
use App\Models\ConversationLog;
use App\Models\WhatsAppUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

/**
 * WhatsApp Bot Manager - Manages WhatsApp Web bot instances directly in Laravel
 * Uses Node.js whatsapp-web.js library via process execution
 */
class WhatsAppBotManager
{
    protected string $sessionsPath;
    protected string $nodePath;
    protected array $runningBots = [];

    public function __construct()
    {
        $this->sessionsPath = storage_path('app/whatsapp-sessions');
        $this->nodePath = base_path('bot-runtime');
        
        // Ensure directories exist
        if (!file_exists($this->sessionsPath)) {
            mkdir($this->sessionsPath, 0755, true);
        }
        if (!file_exists($this->nodePath)) {
            mkdir($this->nodePath, 0755, true);
        }
    }

    /**
     * Initialize a new bot instance
     */
    public function initializeBot(string $botId, string $botName): array
    {
        try {
            // Create or update bot instance
            $bot = BotInstance::updateOrCreate(
                ['bot_id' => $botId],
                [
                    'name' => $botName,
                    'status' => 'initializing',
                    'is_active' => true,
                ]
            );

            // Start bot process in background
            $this->startBotProcess($botId, $botName);

            return [
                'success' => true,
                'message' => 'Bot initialization started',
                'bot' => $bot,
            ];
        } catch (\Exception $e) {
            Log::error('Error initializing bot', [
                'bot_id' => $botId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Start bot process in background
     */
    protected function startBotProcess(string $botId, string $botName): void
    {
        $scriptPath = $this->createBotScript($botId, $botName);
        
        // Start Node.js process in background
        $command = sprintf(
            'node %s > %s 2>&1 &',
            escapeshellarg($scriptPath),
            escapeshellarg(storage_path("logs/bot-{$botId}.log"))
        );

        if (PHP_OS_FAMILY === 'Windows') {
            $command = sprintf(
                'start /B node %s > %s 2>&1',
                escapeshellarg($scriptPath),
                escapeshellarg(storage_path("logs/bot-{$botId}.log"))
            );
        }

        exec($command);

        Log::info("Started bot process", ['bot_id' => $botId, 'command' => $command]);
    }

    /**
     * Create bot script file
     */
    protected function createBotScript(string $botId, string $botName): string
    {
        $scriptPath = $this->nodePath . "/bot-{$botId}.js";
        $sessionsPath = $this->sessionsPath;
        $appUrl = config('app.url');

        $script = <<<JS
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode');
const axios = require('axios');
const fs = require('fs');

const BOT_ID = '{$botId}';
const BOT_NAME = '{$botName}';
const SESSIONS_PATH = '{$sessionsPath}';
const APP_URL = '{$appUrl}';

let currentQR = null;
let isReady = false;

const client = new Client({
    authStrategy: new LocalAuth({
        clientId: BOT_ID,
        dataPath: SESSIONS_PATH
    }),
    puppeteer: {
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-accelerated-2d-canvas',
            '--no-first-run',
            '--no-zygote',
            '--disable-gpu'
        ]
    }
});

// QR Code event
client.on('qr', async (qr) => {
    console.log('QR Code generated for bot', BOT_ID);
    try {
        const qrDataUrl = await qrcode.toDataURL(qr);
        currentQR = qrDataUrl;
        
        // Save QR to file
        const qrPath = SESSIONS_PATH + '/qr-' + BOT_ID + '.txt';
        fs.writeFileSync(qrPath, qrDataUrl);
        
        // Notify Laravel
        await axios.post(APP_URL + '/api/bot/event', {
            event: 'qr_generated',
            data: {
                bot_id: BOT_ID,
                bot_name: BOT_NAME,
                qr_code: qrDataUrl
            }
        }).catch(err => console.error('Error notifying Laravel:', err.message));
    } catch (err) {
        console.error('Error generating QR code:', err);
    }
});

// Ready event
client.on('ready', async () => {
    console.log('Bot', BOT_ID, 'is ready!');
    isReady = true;
    currentQR = null;
    
    const info = client.info;
    await axios.post(APP_URL + '/api/bot/event', {
        event: 'connected',
        data: {
            bot_id: BOT_ID,
            bot_name: BOT_NAME,
            phone_number: info.wid.user,
            platform: info.platform
        }
    }).catch(err => console.error('Error notifying Laravel:', err.message));
});

// Authentication events
client.on('authenticated', () => {
    console.log('Bot', BOT_ID, 'authenticated');
});

client.on('auth_failure', async (msg) => {
    console.error('Bot', BOT_ID, 'authentication failure:', msg);
    await axios.post(APP_URL + '/api/bot/event', {
        event: 'auth_failed',
        data: {
            bot_id: BOT_ID,
            error: msg
        }
    }).catch(err => console.error('Error notifying Laravel:', err.message));
});

// Disconnection event
client.on('disconnected', async (reason) => {
    console.log('Bot', BOT_ID, 'disconnected:', reason);
    await axios.post(APP_URL + '/api/bot/event', {
        event: 'disconnected',
        data: {
            bot_id: BOT_ID,
            reason: reason
        }
    }).catch(err => console.error('Error notifying Laravel:', err.message));
    
    process.exit(0);
});

// Message event
client.on('message', async (message) => {
    console.log('Message received on bot', BOT_ID, 'from:', message.from);
    
    try {
        await axios.post(APP_URL + '/api/bot/webhook', {
            bot_id: BOT_ID,
            message: {
                id: message.id._serialized,
                from: message.from,
                to: message.to,
                body: message.body,
                type: message.type,
                timestamp: message.timestamp,
                hasMedia: message.hasMedia,
                isForwarded: message.isForwarded,
                isStatus: message.isStatus,
                isStarred: message.isStarred,
                broadcast: message.broadcast,
                fromMe: message.fromMe,
                hasQuotedMsg: message.hasQuotedMsg,
                author: message.author,
                mentionedIds: message.mentionedIds
            }
        }).catch(err => console.error('Error forwarding message:', err.message));
    } catch (error) {
        console.error('Error processing message:', error.message);
    }
});

// Initialize
console.log('Initializing bot', BOT_ID);
client.initialize();

// Keep alive and status monitoring
setInterval(() => {
    const statusPath = SESSIONS_PATH + '/status-' + BOT_ID + '.json';
    fs.writeFileSync(statusPath, JSON.stringify({
        bot_id: BOT_ID,
        status: isReady ? 'connected' : (currentQR ? 'qr_generated' : 'initializing'),
        qr_code: currentQR,
        timestamp: new Date().toISOString()
    }));
}, 5000);

// Handle process termination
process.on('SIGINT', async () => {
    console.log('Shutting down bot', BOT_ID);
    await client.destroy();
    process.exit(0);
});

process.on('SIGTERM', async () => {
    console.log('Terminating bot', BOT_ID);
    await client.destroy();
    process.exit(0);
});
JS;

        file_put_contents($scriptPath, $script);
        return $scriptPath;
    }

    /**
     * Get bot status from file
     */
    public function getBotStatus(string $botId): array
    {
        try {
            $statusPath = $this->sessionsPath . "/status-{$botId}.json";
            
            if (!file_exists($statusPath)) {
                return [
                    'success' => true,
                    'data' => [
                        'bot_id' => $botId,
                        'status' => 'not_initialized',
                        'qr_code' => null,
                        'connected' => false,
                    ],
                ];
            }

            $status = json_decode(file_get_contents($statusPath), true);

            return [
                'success' => true,
                'data' => [
                    'bot_id' => $botId,
                    'status' => $status['status'] ?? 'unknown',
                    'qr_code' => $status['qr_code'] ?? null,
                    'connected' => ($status['status'] ?? '') === 'connected',
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Disconnect bot
     */
    public function disconnectBot(string $botId): array
    {
        try {
            $this->killBotProcess($botId);

            $bot = BotInstance::where('bot_id', $botId)->first();
            if ($bot) {
                $bot->update([
                    'status' => 'disconnected',
                    'last_disconnected_at' => now(),
                ]);
            }

            return [
                'success' => true,
                'message' => 'Bot disconnected',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Logout bot (remove session)
     */
    public function logoutBot(string $botId): array
    {
        try {
            $this->killBotProcess($botId);

            // Remove session files
            $sessionPath = $this->sessionsPath . "/session-{$botId}";
            if (file_exists($sessionPath)) {
                $this->deleteDirectory($sessionPath);
            }

            // Remove status files
            @unlink($this->sessionsPath . "/status-{$botId}.json");
            @unlink($this->sessionsPath . "/qr-{$botId}.txt");

            $bot = BotInstance::where('bot_id', $botId)->first();
            if ($bot) {
                $bot->update([
                    'status' => 'not_initialized',
                    'phone_number' => null,
                    'platform' => null,
                    'qr_code' => null,
                ]);
            }

            return [
                'success' => true,
                'message' => 'Bot logged out',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Kill bot process
     */
    protected function killBotProcess(string $botId): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // Windows: find and kill node process
            exec("taskkill /F /FI \"WINDOWTITLE eq bot-{$botId}*\" 2>&1");
        } else {
            // Unix: find and kill node process
            exec("pkill -f \"node.*bot-{$botId}\" 2>&1");
        }
    }

    /**
     * Delete directory recursively
     */
    protected function deleteDirectory(string $dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    /**
     * Send message via bot
     */
    public function sendMessage(string $botId, string $to, string $message): array
    {
        // This would require implementing IPC or API endpoint in the bot script
        // For now, return not implemented
        return [
            'success' => false,
            'error' => 'Direct message sending requires bot API endpoint',
        ];
    }
}
