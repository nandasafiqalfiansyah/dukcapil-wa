<?php

namespace Database\Seeders;

use App\Models\AutoReplyConfig;
use Illuminate\Database\Seeder;

class AutoReplyConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $autoReplies = [
            [
                'trigger' => 'ping',
                'response' => "🤖 *Pong!*\n\nBot DUKCAPIL Ponorogo aktif dan berfungsi dengan baik.\n\nWaktu: {{timestamp}}",
                'priority' => 100,
                'is_active' => true,
                'case_sensitive' => false,
            ],
            [
                'trigger' => 'test',
                'response' => "✅ *Bot Aktif*\n\nBot WhatsApp DUKCAPIL Ponorogo sedang online dan siap melayani Anda.\n\nUntuk bantuan, kirim pesan atau hubungi kantor kami.",
                'priority' => 90,
                'is_active' => true,
                'case_sensitive' => false,
            ],
            [
                'trigger' => 'halo',
                'response' => "👋 *Halo!*\n\nSelamat datang di layanan WhatsApp DUKCAPIL Ponorogo.\n\nKami siap membantu Anda dengan:\n- Informasi layanan kependudukan\n- Status dokumen\n- Pertanyaan umum\n\nSilakan sampaikan kebutuhan Anda.",
                'priority' => 80,
                'is_active' => true,
                'case_sensitive' => false,
            ],
            [
                'trigger' => 'hai',
                'response' => "👋 *Hai!*\n\nSelamat datang di layanan WhatsApp DUKCAPIL Ponorogo.\n\nKami siap membantu Anda dengan:\n- Informasi layanan kependudukan\n- Status dokumen\n- Pertanyaan umum\n\nSilakan sampaikan kebutuhan Anda.",
                'priority' => 80,
                'is_active' => true,
                'case_sensitive' => false,
            ],
            [
                'trigger' => 'help',
                'response' => "ℹ️ *Bantuan*\n\nLayanan yang tersedia:\n\n1. *KTP* - Informasi e-KTP\n2. *KK* - Informasi Kartu Keluarga\n3. *Akta* - Informasi Akta Kelahiran/Kematian\n4. *Status* - Cek status dokumen\n\nKirim kata kunci di atas untuk informasi lebih lanjut.",
                'priority' => 70,
                'is_active' => true,
                'case_sensitive' => false,
            ],
            [
                'trigger' => 'info',
                'response' => "ℹ️ *Informasi DUKCAPIL Ponorogo*\n\n📍 Alamat: [Alamat Kantor]\n📞 Telepon: [Nomor Telepon]\n🕐 Jam Layanan: Senin-Jumat, 08:00-15:00\n\nUntuk pertanyaan, silakan kirim pesan atau hubungi langsung.",
                'priority' => 60,
                'is_active' => true,
                'case_sensitive' => false,
            ],
        ];

        foreach ($autoReplies as $reply) {
            AutoReplyConfig::updateOrCreate(
                ['trigger' => $reply['trigger']],
                $reply
            );
        }
    }
}
