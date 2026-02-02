<?php

namespace App\Console\Commands;

use App\Models\BotInstance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncFonnteDevices extends Command
{
    protected $signature = 'fonnte:sync-devices';
    protected $description = 'Sync bot instances with Fonnte devices and update their tokens';

    public function handle()
    {
        $mainToken = config('services.fonnte.token');
        $apiUrl = config('services.fonnte.api_url', 'https://api.fonnte.com');

        $this->info('🔄 Syncing Bot Instances with Fonnte Devices...');
        $this->newLine();

        try {
            $response = Http::withHeaders([
                'Authorization' => $mainToken,
            ])->post("$apiUrl/get-devices");

            if (!$response->successful()) {
                $this->error('❌ Failed to connect to Fonnte API');
                return 1;
            }

            $data = $response->json();

            if (!isset($data['status']) || $data['status'] !== true) {
                $this->error('❌ Failed to get devices from Fonnte');
                $this->line(json_encode($data, JSON_PRETTY_PRINT));
                return 1;
            }

            $devices = $data['data'] ?? [];

            if (empty($devices)) {
                $this->warn('⚠️  No devices found in Fonnte');
                return 1;
            }

            $this->info('Found ' . count($devices) . ' device(s) in Fonnte:');
            $this->newLine();

            foreach ($devices as $device) {
                $phoneNumber = $device['device'];
                $deviceToken = $device['token'];
                $status = $device['status'];
                $name = $device['name'];

                $this->line("📱 Device: <fg=cyan>$name</> (<fg=yellow>$phoneNumber</>)");
                $this->line("   Status: <fg=" . ($status === 'connect' ? 'green' : 'red') . ">$status</>");
                $this->line("   Token: " . substr($deviceToken, 0, 10) . "...");

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
                    $this->line("   <fg=green>✅ Created new bot instance</>");
                    Log::info('Created new Fonnte bot instance', ['phone' => $phoneNumber]);
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
                    $this->line("   <fg=green>✅ Updated bot instance</>");
                    Log::info('Updated Fonnte bot instance', ['phone' => $phoneNumber]);
                }

                $this->newLine();
            }

            $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            $this->info('✅ Sync completed successfully!');
            $this->newLine();
            $this->line('Each bot instance now has its own device token.');
            $this->line('Messages will be sent using the correct token.');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            Log::error('Fonnte device sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }
}
