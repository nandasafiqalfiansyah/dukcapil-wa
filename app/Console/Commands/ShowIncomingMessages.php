<?php

namespace App\Console\Commands;

use App\Models\ConversationLog;
use Illuminate\Console\Command;

class ShowIncomingMessages extends Command
{
    protected $signature = 'messages:incoming {--limit=10 : Number of messages to show}';
    protected $description = 'Display incoming WhatsApp messages';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $messages = ConversationLog::with('whatsappUser', 'botInstance')
            ->where('direction', 'incoming')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        if ($messages->isEmpty()) {
            $this->error('❌ No incoming messages found');
            $this->newLine();
            $this->warn('Possible reasons:');
            $this->line('1. Webhook belum dikonfigurasi di Fonnte dashboard');
            $this->line('2. Belum ada pesan masuk dari WhatsApp');
            $this->line('3. Check storage/logs/laravel.log untuk error');
            return 0;
        }

        $this->info("📥 INCOMING MESSAGES ({$messages->count()} recent)");
        $this->line('═══════════════════════════════════════════════════════════════════════');
        $this->newLine();

        $data = [];
        foreach ($messages as $msg) {
            $data[] = [
                'ID' => $msg->id,
                'Device' => $msg->botInstance?->bot_id ?? $msg->bot_instance_id ?? '-',
                'Sender' => $msg->whatsappUser->phone_number,
                'Name' => $msg->whatsappUser->name ?? '-',
                'Message' => \Str::limit($msg->message_content, 40),
                'Type' => $msg->message_type,
                'Timestamp' => $msg->created_at->format('d M Y H:i'),
                'Status' => $msg->status,
            ];
        }

        $this->table(
            ['ID', 'Device', 'Sender', 'Name', 'Message', 'Type', 'Timestamp', 'Status'],
            $data
        );

        $this->newLine();
        $this->info('✅ Total incoming messages: ' . ConversationLog::where('direction', 'incoming')->count());
        $this->info('📤 Total outgoing messages: ' . ConversationLog::where('direction', 'outgoing')->count());

        return 0;
    }
}
