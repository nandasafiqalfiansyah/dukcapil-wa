<?php

namespace App\Console\Commands;

use App\Models\ConversationLog;
use Illuminate\Console\Command;

class ShowAllMessages extends Command
{
    protected $signature = 'messages:all {--limit=20 : Number of messages to show} {--phone= : Filter by phone number}';
    protected $description = 'Display all WhatsApp messages (incoming and outgoing)';

    public function handle()
    {
        $limit = $this->option('limit');
        $phone = $this->option('phone');
        
        $query = ConversationLog::with('whatsappUser', 'botInstance')
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($phone) {
            $query->whereHas('whatsappUser', function ($q) use ($phone) {
                $q->where('phone_number', 'like', '%' . $phone . '%');
            });
        }

        $messages = $query->get();

        if ($messages->isEmpty()) {
            $this->error('❌ No messages found');
            return 0;
        }

        $this->info("💬 ALL MESSAGES ({$messages->count()} recent)");
        $this->line('═══════════════════════════════════════════════════════════════════════');
        $this->newLine();

        $data = [];
        foreach ($messages as $msg) {
            $direction = $msg->direction === 'incoming' ? '📥' : '📤';
            
            $data[] = [
                'ID' => $msg->id,
                'Dir' => $direction,
                'Sender' => $msg->whatsappUser->phone_number,
                'Name' => $msg->whatsappUser->name ?? '-',
                'Message' => \Str::limit($msg->message_content, 50),
                'Type' => $msg->message_type,
                'Time' => $msg->created_at->format('d M H:i'),
                'Status' => $msg->status,
            ];
        }

        $this->table(
            ['ID', 'Dir', 'Sender', 'Name', 'Message', 'Type', 'Time', 'Status'],
            $data
        );

        $this->newLine();
        $incoming = ConversationLog::where('direction', 'incoming')->count();
        $outgoing = ConversationLog::where('direction', 'outgoing')->count();
        
        $this->info("📥 Incoming: {$incoming} | 📤 Outgoing: {$outgoing} | Total: " . ($incoming + $outgoing));

        return 0;
    }
}
