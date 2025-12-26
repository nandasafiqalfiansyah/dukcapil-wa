<?php

namespace Database\Seeders;

use App\Models\CsTrainingData;
use Illuminate\Database\Seeder;

class CsTrainingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainingData = [
            // Greetings
            [
                'intent' => 'greeting',
                'pattern' => 'halo',
                'response' => 'Halo! Selamat datang di layanan DUKCAPIL Ponorogo. Ada yang bisa saya bantu?',
                'keywords' => ['halo', 'hai', 'hello', 'hei', 'selamat pagi', 'selamat siang', 'selamat sore', 'selamat malam'],
                'priority' => 10,
            ],
            [
                'intent' => 'greeting',
                'pattern' => 'selamat pagi',
                'response' => 'Selamat pagi! Bagaimana saya bisa membantu Anda hari ini?',
                'keywords' => ['selamat pagi', 'pagi'],
                'priority' => 9,
            ],
            
            // KTP (e-KTP)
            [
                'intent' => 'ktp_info',
                'pattern' => 'cara buat ktp',
                'response' => "Untuk membuat KTP baru, Anda perlu:\n1. Kartu Keluarga asli\n2. Kutipan akta kelahiran\n3. Surat keterangan dari RT/RW\n4. Foto 4x6 (2 lembar)\n\nDatang ke kantor DUKCAPIL dengan membawa persyaratan di atas. Proses pembuatan KTP gratis!",
                'keywords' => ['ktp', 'e-ktp', 'buat ktp', 'cara buat', 'bikin ktp'],
                'priority' => 20,
            ],
            [
                'intent' => 'ktp_hilang',
                'pattern' => 'ktp hilang',
                'response' => "Untuk KTP yang hilang:\n1. Buat surat kehilangan di kantor polisi\n2. Bawa surat kehilangan dan Kartu Keluarga ke DUKCAPIL\n3. Mengisi formulir permohonan\n\nKTP pengganti akan diterbitkan dalam 14 hari kerja.",
                'keywords' => ['ktp hilang', 'kehilangan ktp', 'ktp ilang', 'ktp raib'],
                'priority' => 20,
            ],
            [
                'intent' => 'ktp_rusak',
                'pattern' => 'ktp rusak',
                'response' => "Untuk penggantian KTP rusak:\n1. Bawa KTP lama yang rusak\n2. Bawa Kartu Keluarga\n3. Datang ke kantor DUKCAPIL\n\nProses penggantian gratis dan selesai dalam 14 hari kerja.",
                'keywords' => ['ktp rusak', 'ktp sobek', 'ktp pecah'],
                'priority' => 18,
            ],
            
            // Kartu Keluarga (KK)
            [
                'intent' => 'kk_info',
                'pattern' => 'cara buat kartu keluarga',
                'response' => "Persyaratan membuat Kartu Keluarga baru:\n1. Surat Nikah/Akta Nikah (bagi yang sudah menikah)\n2. KTP suami dan istri\n3. Akta kelahiran anak (jika ada)\n4. Surat pengantar RT/RW\n\nSilakan datang ke DUKCAPIL untuk memproses.",
                'keywords' => ['kartu keluarga', 'kk', 'buat kk', 'bikin kk', 'cara buat kartu keluarga'],
                'priority' => 20,
            ],
            [
                'intent' => 'kk_tambah_anggota',
                'pattern' => 'tambah anggota keluarga',
                'response' => "Untuk menambah anggota keluarga di KK:\n1. Bawa KK asli\n2. Akta kelahiran/KTP anggota baru\n3. Surat nikah (jika pasangan baru)\n4. Surat pengantar RT/RW\n\nProses pembaruan KK sekitar 3-5 hari kerja.",
                'keywords' => ['tambah anggota', 'tambah kk', 'masukkan anggota keluarga'],
                'priority' => 18,
            ],
            
            // Akta Kelahiran
            [
                'intent' => 'akta_kelahiran',
                'pattern' => 'cara buat akta kelahiran',
                'response' => "Persyaratan membuat akta kelahiran:\n1. Surat keterangan lahir dari rumah sakit/bidan\n2. KTP dan KK orang tua\n3. Akta nikah orang tua\n4. Saksi 2 orang (KTP)\n\nPendaftaran gratis jika usia anak di bawah 1 tahun. Datang ke kantor DUKCAPIL untuk diproses.",
                'keywords' => ['akta kelahiran', 'akte lahir', 'buat akta', 'bikin akta kelahiran'],
                'priority' => 20,
            ],
            [
                'intent' => 'akta_terlambat',
                'pattern' => 'akta terlambat',
                'response' => "Untuk akta kelahiran terlambat (usia anak > 1 tahun):\n1. Membuat surat keterangan kelahiran dari desa/kelurahan\n2. Penetapan pengadilan (untuk usia > 1 tahun)\n3. KTP & KK orang tua\n4. Akta nikah orang tua\n\nSilakan konsultasi langsung ke kantor DUKCAPIL untuk prosesnya.",
                'keywords' => ['akta terlambat', 'telat buat akta', 'akta lewat waktu'],
                'priority' => 18,
            ],
            
            // Jam operasional
            [
                'intent' => 'jam_operasional',
                'pattern' => 'jam buka',
                'response' => "Jam operasional DUKCAPIL Ponorogo:\nSenin - Kamis: 08.00 - 15.00 WIB\nJumat: 08.00 - 15.30 WIB\nSabtu - Minggu: TUTUP\n\nIstirahat: 12.00 - 13.00 WIB",
                'keywords' => ['jam buka', 'jam kerja', 'buka jam berapa', 'jam operasional', 'kapan buka'],
                'priority' => 15,
            ],
            
            // Lokasi
            [
                'intent' => 'lokasi',
                'pattern' => 'alamat dukcapil',
                'response' => "Alamat DUKCAPIL Ponorogo:\nJl. Batoro Katong No. 1, Ponorogo, Jawa Timur\nTelp: (0352) 461485\n\nUntuk informasi lebih lanjut, Anda bisa menghubungi kami di jam operasional.",
                'keywords' => ['alamat', 'lokasi', 'dimana', 'di mana', 'kantor dukcapil'],
                'priority' => 15,
            ],
            
            // Biaya
            [
                'intent' => 'biaya',
                'pattern' => 'berapa biaya',
                'response' => "Semua layanan DUKCAPIL GRATIS!\nTidak ada biaya untuk:\n- Pembuatan KTP\n- Kartu Keluarga\n- Akta kelahiran (< 1 tahun)\n- Akta kematian\n- Dan layanan lainnya\n\nJika ada yang meminta biaya, silakan laporkan ke kami!",
                'keywords' => ['biaya', 'berapa', 'harga', 'bayar', 'gratis', 'kena charge'],
                'priority' => 18,
            ],
            
            // Status permohonan
            [
                'intent' => 'cek_status',
                'pattern' => 'cek status',
                'response' => "Untuk mengecek status permohonan Anda:\n1. Hubungi kami di (0352) 461485\n2. Sebutkan nomor permohonan Anda\n3. Atau datang langsung ke kantor membawa bukti permohonan\n\nBiasanya dokumen selesai dalam 3-14 hari kerja tergantung jenis layanan.",
                'keywords' => ['cek status', 'status permohonan', 'sudah jadi', 'kapan jadi', 'sudah selesai'],
                'priority' => 16,
            ],
            
            // Terima kasih
            [
                'intent' => 'thanks',
                'pattern' => 'terima kasih',
                'response' => 'Sama-sama! Senang bisa membantu Anda. Jika ada pertanyaan lain, jangan ragu untuk bertanya.',
                'keywords' => ['terima kasih', 'makasih', 'thanks', 'thank you', 'terimakasih'],
                'priority' => 10,
            ],
            
            // Selamat tinggal
            [
                'intent' => 'goodbye',
                'pattern' => 'sampai jumpa',
                'response' => 'Sampai jumpa! Semoga hari Anda menyenangkan. Kami siap membantu kapan saja.',
                'keywords' => ['bye', 'sampai jumpa', 'dadah', 'selamat tinggal'],
                'priority' => 10,
            ],
        ];

        foreach ($trainingData as $data) {
            CsTrainingData::create($data);
        }
    }
}

