<?php

namespace App\Console\Commands;

use App\Models\BotInstance;
use App\Services\WhatsAppBotManager;
use Illuminate\Console\Command;

class WhatsAppBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'whatsapp:bot {action} {--bot-id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage WhatsApp bot instances';

    protected WhatsAppBotManager $botManager;

    public function __construct(WhatsAppBotManager $botManager)
    {
        parent::__construct();
        $this->botManager = $botManager;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action');

        return match ($action) {
            'start' => $this->startBot(),
            'start-all' => $this->startAllBots(),
            'stop' => $this->stopBot(),
            'stop-all' => $this->stopAllBots(),
            'status' => $this->showStatus(),
            'list' => $this->listBots(),
            default => $this->error("Unknown action: {$action}. Available: start, start-all, stop, stop-all, status, list"),
        };
    }

    protected function startBot(): int
    {
        $botId = $this->option('bot-id');

        if (!$botId) {
            $this->error('Please provide --bot-id option');
            return 1;
        }

        $bot = BotInstance::where('bot_id', $botId)->first();

        if (!$bot) {
            $this->error("Bot not found: {$botId}");
            return 1;
        }

        $this->info("Starting bot: {$bot->name} ({$botId})");

        $result = $this->botManager->initializeBot($botId, $bot->name);

        if ($result['success']) {
            $this->info("Bot started successfully!");
            $this->info("Check status with: php artisan whatsapp:bot status --bot-id={$botId}");
            return 0;
        }

        $this->error("Failed to start bot: " . ($result['error'] ?? 'Unknown error'));
        return 1;
    }

    protected function startAllBots(): int
    {
        $bots = BotInstance::where('is_active', true)->get();

        if ($bots->isEmpty()) {
            $this->warn('No active bots found');
            return 0;
        }

        $this->info("Starting {$bots->count()} bot(s)...");

        foreach ($bots as $bot) {
            $this->info("  - Starting {$bot->name} ({$bot->bot_id})");
            $result = $this->botManager->initializeBot($bot->bot_id, $bot->name);

            if ($result['success']) {
                $this->line("    ✓ Started");
            } else {
                $this->line("    ✗ Failed: " . ($result['error'] ?? 'Unknown'));
            }
        }

        $this->info("All bots started!");
        return 0;
    }

    protected function stopBot(): int
    {
        $botId = $this->option('bot-id');

        if (!$botId) {
            $this->error('Please provide --bot-id option');
            return 1;
        }

        $this->info("Stopping bot: {$botId}");

        $result = $this->botManager->disconnectBot($botId);

        if ($result['success']) {
            $this->info("Bot stopped successfully!");
            return 0;
        }

        $this->error("Failed to stop bot: " . ($result['error'] ?? 'Unknown error'));
        return 1;
    }

    protected function stopAllBots(): int
    {
        $bots = BotInstance::all();

        if ($bots->isEmpty()) {
            $this->warn('No bots found');
            return 0;
        }

        $this->info("Stopping {$bots->count()} bot(s)...");

        foreach ($bots as $bot) {
            $this->info("  - Stopping {$bot->name} ({$bot->bot_id})");
            $result = $this->botManager->disconnectBot($bot->bot_id);

            if ($result['success']) {
                $this->line("    ✓ Stopped");
            } else {
                $this->line("    ✗ Failed: " . ($result['error'] ?? 'Unknown'));
            }
        }

        $this->info("All bots stopped!");
        return 0;
    }

    protected function showStatus(): int
    {
        $botId = $this->option('bot-id');

        if (!$botId) {
            $this->error('Please provide --bot-id option');
            return 1;
        }

        $bot = BotInstance::where('bot_id', $botId)->first();

        if (!$bot) {
            $this->error("Bot not found: {$botId}");
            return 1;
        }

        $result = $this->botManager->getBotStatus($botId);

        $this->table(
            ['Property', 'Value'],
            [
                ['Bot ID', $bot->bot_id],
                ['Name', $bot->name],
                ['Status', $bot->status],
                ['Phone Number', $bot->phone_number ?? 'N/A'],
                ['Platform', $bot->platform ?? 'N/A'],
                ['Active', $bot->is_active ? 'Yes' : 'No'],
                ['Last Connected', $bot->last_connected_at ? $bot->last_connected_at->diffForHumans() : 'Never'],
                ['Runtime Status', $result['data']['status'] ?? 'Unknown'],
            ]
        );

        return 0;
    }

    protected function listBots(): int
    {
        $bots = BotInstance::all();

        if ($bots->isEmpty()) {
            $this->warn('No bots found');
            $this->info('Create a bot from the web interface: ' . url('/admin/bots/create'));
            return 0;
        }

        $rows = $bots->map(function ($bot) {
            return [
                $bot->bot_id,
                $bot->name,
                $bot->status,
                $bot->phone_number ?? 'N/A',
                $bot->is_active ? '✓' : '✗',
                $bot->isConnected() ? '✓' : '✗',
            ];
        });

        $this->table(
            ['Bot ID', 'Name', 'Status', 'Phone', 'Active', 'Connected'],
            $rows
        );

        $this->newLine();
        $this->info("Commands:");
        $this->line("  Start bot:  php artisan whatsapp:bot start --bot-id=<bot-id>");
        $this->line("  Stop bot:   php artisan whatsapp:bot stop --bot-id=<bot-id>");
        $this->line("  Status:     php artisan whatsapp:bot status --bot-id=<bot-id>");

        return 0;
    }
}
