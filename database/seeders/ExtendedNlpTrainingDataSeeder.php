<?php

namespace Database\Seeders;

use App\Models\CsTrainingData;
use Illuminate\Database\Seeder;

class ExtendedNlpTrainingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trainingData = [
            // ===== GREETINGS & FAREWELLS =====
            [
                'intent' => 'greeting',
                'pattern' => 'halo',
                'response' => 'Halo! Selamat datang di layanan DUKCAPIL Ponorogo. Ada yang bisa saya bantu?',
                'keywords' => ['halo', 'hai', 'hello', 'hei', 'hi'],
                'priority' => 10,
            ],
            [
                'intent' => 'greeting',
                'pattern' => 'selamat pagi',
                'response' => 'Selamat pagi! Bagaimana saya bisa membantu Anda hari ini?',
                'keywords' => ['selamat pagi', 'pagi'],
                'priority' => 10,
            ],
            [
                'intent' => 'greeting',
                'pattern' => 'selamat siang',
                'response' => 'Selamat siang! Ada yang bisa saya bantu?',
                'keywords' => ['selamat siang', 'siang'],
                'priority' => 10,
            ],
            [
                'intent' => 'greeting',
                'pattern' => 'selamat sore',
                'response' => 'Selamat sore! Bagaimana saya bisa membantu Anda?',
                'keywords' => ['selamat sore', 'sore'],
                'priority' => 10,
            ],
            [
                'intent' => 'greeting',
                'pattern' => 'selamat malam',
                'response' => 'Selamat malam! Ada yang bisa saya bantu malam ini?',
                'keywords' => ['selamat malam', 'malam'],
                'priority' => 10,
            ],
            [
                'intent' => 'greeting',
                'pattern' => 'assalamualaikum',
                'response' => 'Waalaikumsalam! Selamat datang di layanan DUKCAPIL Ponorogo. Bagaimana saya bisa membantu Anda?',
                'keywords' => ['assalamualaikum', 'assalamu alaikum', 'assalammualaikum'],
                'priority' => 10,
            ],
            [
                'intent' => 'goodbye',
                'pattern' => 'sampai jumpa',
                'response' => 'Sampai jumpa! Semoga hari Anda menyenangkan. Kami siap membantu kapan saja.',
                'keywords' => ['bye', 'sampai jumpa', 'dadah', 'selamat tinggal', 'good bye'],
                'priority' => 10,
            ],
            [
                'intent' => 'thanks',
                'pattern' => 'terima kasih',
                'response' => 'Sama-sama! Senang bisa membantu Anda. Jika ada pertanyaan lain, jangan ragu untuk bertanya.',
                'keywords' => ['terima kasih', 'makasih', 'thanks', 'thank you', 'terimakasih', 'thx'],
                'priority' => 10,
            ],

            // ===== KTP (E-KTP) - COMPREHENSIVE =====
            [
                'intent' => 'ktp_info',
                'pattern' => 'cara buat ktp',
                'response' => "Untuk membuat KTP baru, Anda perlu:\n1. Kartu Keluarga asli\n2. Kutipan akta kelahiran\n3. Surat keterangan dari RT/RW\n4. Foto 4x6 (2 lembar)\n\nDatang ke kantor DUKCAPIL dengan membawa persyaratan di atas. Proses pembuatan KTP gratis!",
                'keywords' => ['ktp', 'e-ktp', 'buat ktp', 'cara buat', 'bikin ktp', 'pembuatan ktp'],
                'priority' => 20,
            ],
            [
                'intent' => 'ktp_hilang',
                'pattern' => 'ktp hilang',
                'response' => "Untuk KTP yang hilang:\n1. Buat surat kehilangan di kantor polisi\n2. Bawa surat kehilangan dan Kartu Keluarga ke DUKCAPIL\n3. Mengisi formulir permohonan\n\nKTP pengganti akan diterbitkan dalam 14 hari kerja.",
                'keywords' => ['ktp hilang', 'kehilangan ktp', 'ktp ilang', 'ktp raib', 'ktp ketinggalan'],
                'priority' => 20,
            ],
            [
                'intent' => 'ktp_rusak',
                'pattern' => 'ktp rusak',
                'response' => "Untuk penggantian KTP rusak:\n1. Bawa KTP lama yang rusak\n2. Bawa Kartu Keluarga\n3. Datang ke kantor DUKCAPIL\n\nProses penggantian gratis dan selesai dalam 14 hari kerja.",
                'keywords' => ['ktp rusak', 'ktp sobek', 'ktp pecah', 'ktp hancur'],
                'priority' => 18,
            ],
            [
                'intent' => 'ktp_elektronik',
                'pattern' => 'apa itu e-ktp',
                'response' => "E-KTP atau KTP Elektronik adalah kartu identitas yang dilengkapi dengan chip untuk menyimpan biodata dan sidik jari. E-KTP:\n- Berlaku seumur hidup\n- Lebih aman dari pemalsuan\n- Terintegrasi secara nasional\n- Gratis pengurusannya",
                'keywords' => ['e-ktp', 'ktp elektronik', 'apa itu ktp', 'pengertian ktp'],
                'priority' => 15,
            ],
            [
                'intent' => 'ktp_pertama_kali',
                'pattern' => 'ktp pertama kali',
                'response' => "Untuk membuat KTP pertama kali (usia 17 tahun):\n1. Kartu Keluarga asli\n2. Akta kelahiran\n3. Surat pengantar RT/RW\n4. Foto 4x6 (2 lembar)\n5. Formulir biodata\n\nPendaftaran gratis. Datang ke kantor DUKCAPIL untuk perekaman data dan sidik jari.",
                'keywords' => ['ktp pertama', 'ktp pertama kali', 'buat ktp pertama', 'umur 17 tahun', '17 tahun'],
                'priority' => 19,
            ],
            [
                'intent' => 'ktp_pindah',
                'pattern' => 'ktp pindah alamat',
                'response' => "Untuk perubahan alamat KTP:\n1. Urus surat pindah dari kelurahan lama\n2. Lapor ke kelurahan baru\n3. Bawa KTP lama dan KK\n4. Datang ke DUKCAPIL untuk cetak KTP baru\n\nProses gratis, selesai 14 hari kerja.",
                'keywords' => ['ktp pindah', 'pindah alamat', 'ganti alamat ktp', 'ubah alamat'],
                'priority' => 18,
            ],
            [
                'intent' => 'ktp_update_data',
                'pattern' => 'update data ktp',
                'response' => "Untuk pembaruan data KTP:\n1. Bawa KTP lama\n2. Dokumen pendukung perubahan (ijazah untuk pendidikan, surat nikah untuk status, dll)\n3. Kartu Keluarga\n4. Formulir perubahan data\n\nDatang ke DUKCAPIL untuk proses pembaruan. Gratis!",
                'keywords' => ['update ktp', 'ubah data ktp', 'ganti data', 'perbarui ktp'],
                'priority' => 17,
            ],
            [
                'intent' => 'ktp_masa_berlaku',
                'pattern' => 'masa berlaku ktp',
                'response' => "E-KTP berlaku SEUMUR HIDUP!\n\nTidak perlu diperpanjang kecuali:\n- KTP rusak atau hilang\n- Ada perubahan data\n- Pindah alamat\n\nKTP lama (non-elektronik) harus diganti dengan E-KTP.",
                'keywords' => ['masa berlaku', 'berlaku ktp', 'expired ktp', 'habis masa'],
                'priority' => 16,
            ],

            // ===== KARTU KELUARGA (KK) - COMPREHENSIVE =====
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
                'keywords' => ['tambah anggota', 'tambah kk', 'masukkan anggota keluarga', 'tambah nama'],
                'priority' => 18,
            ],
            [
                'intent' => 'kk_kurang_anggota',
                'pattern' => 'kurangi anggota kk',
                'response' => "Untuk mengurangi anggota dari KK (karena meninggal/pindah):\n1. KK asli\n2. Akta kematian (jika meninggal) atau surat pindah (jika pindah)\n3. Surat pengantar RT/RW\n4. KTP pemohon\n\nProses di DUKCAPIL, gratis.",
                'keywords' => ['kurang anggota', 'hapus anggota', 'keluarkan anggota', 'anggota meninggal'],
                'priority' => 17,
            ],
            [
                'intent' => 'kk_hilang',
                'pattern' => 'kk hilang',
                'response' => "Untuk KK yang hilang:\n1. Buat surat kehilangan di polisi\n2. Bawa surat kehilangan\n3. KTP kepala keluarga\n4. Surat pengantar RT/RW\n\nDatang ke DUKCAPIL untuk cetak ulang KK. Gratis!",
                'keywords' => ['kk hilang', 'kartu keluarga hilang', 'kk ilang', 'kehilangan kk'],
                'priority' => 19,
            ],
            [
                'intent' => 'kk_rusak',
                'pattern' => 'kk rusak',
                'response' => "Untuk KK yang rusak:\n1. Bawa KK lama yang rusak\n2. KTP kepala keluarga\n3. Surat pengantar RT/RW\n\nProses cetak ulang di DUKCAPIL. Gratis dan selesai 3-5 hari.",
                'keywords' => ['kk rusak', 'kartu keluarga rusak', 'kk sobek', 'kk hancur'],
                'priority' => 17,
            ],
            [
                'intent' => 'kk_pisah',
                'pattern' => 'pisah kk',
                'response' => "Untuk memisahkan KK:\n1. KK lama\n2. KTP pemohon\n3. Surat nikah (jika sudah menikah)\n4. Surat pengantar RT/RW\n5. Surat keterangan pisah KK dari kelurahan\n\nProses di DUKCAPIL untuk KK baru.",
                'keywords' => ['pisah kk', 'memisahkan kk', 'kk sendiri', 'buat kk baru'],
                'priority' => 18,
            ],

            // ===== AKTA KELAHIRAN - COMPREHENSIVE =====
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
                'keywords' => ['akta terlambat', 'telat buat akta', 'akta lewat waktu', 'akta terlambat daftar'],
                'priority' => 18,
            ],
            [
                'intent' => 'akta_hilang',
                'pattern' => 'akta kelahiran hilang',
                'response' => "Untuk akta kelahiran yang hilang:\n1. Surat keterangan kehilangan dari polisi\n2. KTP dan KK orang tua\n3. Akta nikah orang tua\n4. Surat pengantar RT/RW\n\nDatang ke DUKCAPIL untuk cetak ulang. Ada biaya administrasi untuk cetak ulang.",
                'keywords' => ['akta hilang', 'akta kelahiran hilang', 'kehilangan akta', 'akta ilang'],
                'priority' => 17,
            ],
            [
                'intent' => 'akta_bayi_baru_lahir',
                'pattern' => 'akta bayi baru lahir',
                'response' => "Untuk bayi baru lahir (< 60 hari):\n1. Surat keterangan lahir dari rumah sakit/bidan\n2. Fotocopy KTP & KK orang tua\n3. Fotocopy akta nikah/buku nikah\n4. Saksi 2 orang (fotocopy KTP)\n\nGRATIS! Selesai dalam 3-7 hari. Segera urus agar tidak terlambat.",
                'keywords' => ['bayi baru lahir', 'akta bayi', 'bayi lahir', 'newborn'],
                'priority' => 20,
            ],

            // ===== AKTA KEMATIAN =====
            [
                'intent' => 'akta_kematian',
                'pattern' => 'cara buat akta kematian',
                'response' => "Persyaratan akta kematian:\n1. Surat keterangan kematian dari kelurahan/rumah sakit\n2. KTP dan KK almarhum\n3. KTP pelapor (keluarga)\n4. Surat pengantar RT/RW\n\nProses di DUKCAPIL. GRATIS! Penting untuk administrasi warisan dan asuransi.",
                'keywords' => ['akta kematian', 'akte meninggal', 'surat kematian', 'buat akta kematian'],
                'priority' => 19,
            ],

            // ===== AKTA PERKAWINAN =====
            [
                'intent' => 'akta_nikah',
                'pattern' => 'akta perkawinan',
                'response' => "Untuk akta perkawinan:\n1. Buku nikah asli dari KUA/Gereja\n2. KTP & KK suami istri\n3. Pas foto suami istri (4x6, 2 lembar)\n4. Surat pengantar RT/RW\n\nProses di DUKCAPIL. Selesai 7-14 hari kerja.",
                'keywords' => ['akta nikah', 'akta perkawinan', 'buat akta nikah', 'surat nikah'],
                'priority' => 18,
            ],

            // ===== AKTA PERCERAIAN =====
            [
                'intent' => 'akta_cerai',
                'pattern' => 'akta perceraian',
                'response' => "Untuk akta perceraian:\n1. Putusan pengadilan yang sudah berkekuatan hukum tetap\n2. KTP & KK\n3. Akta nikah/buku nikah\n4. Surat pengantar RT/RW\n\nProses di DUKCAPIL setelah putusan cerai final.",
                'keywords' => ['akta cerai', 'akta perceraian', 'surat cerai', 'bercerai'],
                'priority' => 17,
            ],

            // ===== JAM OPERASIONAL & LOKASI =====
            [
                'intent' => 'jam_operasional',
                'pattern' => 'jam buka',
                'response' => "Jam operasional DUKCAPIL Ponorogo:\nSenin - Kamis: 08.00 - 15.00 WIB\nJumat: 08.00 - 15.30 WIB\nSabtu - Minggu: TUTUP\n\nIstirahat: 12.00 - 13.00 WIB",
                'keywords' => ['jam buka', 'jam kerja', 'buka jam berapa', 'jam operasional', 'kapan buka', 'jam kantor'],
                'priority' => 15,
            ],
            [
                'intent' => 'lokasi',
                'pattern' => 'alamat dukcapil',
                'response' => "Alamat DUKCAPIL Ponorogo:\nJl. Batoro Katong No. 1, Ponorogo, Jawa Timur\nTelp: (0352) 461485\n\nUntuk informasi lebih lanjut, Anda bisa menghubungi kami di jam operasional.",
                'keywords' => ['alamat', 'lokasi', 'dimana', 'di mana', 'kantor dukcapil', 'tempat'],
                'priority' => 15,
            ],
            [
                'intent' => 'kontak',
                'pattern' => 'nomor telepon',
                'response' => "Kontak DUKCAPIL Ponorogo:\nTelepon: (0352) 461485\nEmail: dukcapil@ponorogokab.go.id\n\nAtau kunjungi kami di:\nJl. Batoro Katong No. 1, Ponorogo, Jawa Timur",
                'keywords' => ['kontak', 'telepon', 'nomor', 'hubungi', 'email', 'no telp'],
                'priority' => 15,
            ],

            // ===== BIAYA & LAYANAN =====
            [
                'intent' => 'biaya',
                'pattern' => 'berapa biaya',
                'response' => "Semua layanan DUKCAPIL GRATIS!\nTidak ada biaya untuk:\n- Pembuatan KTP\n- Kartu Keluarga\n- Akta kelahiran (< 1 tahun)\n- Akta kematian\n- Dan layanan lainnya\n\nJika ada yang meminta biaya, silakan laporkan ke kami!",
                'keywords' => ['biaya', 'berapa', 'harga', 'bayar', 'gratis', 'kena charge', 'tarif'],
                'priority' => 18,
            ],
            [
                'intent' => 'persyaratan_umum',
                'pattern' => 'syarat umum',
                'response' => "Persyaratan umum untuk layanan DUKCAPIL:\n1. KTP pemohon\n2. Kartu Keluarga\n3. Surat pengantar RT/RW\n4. Dokumen pendukung sesuai jenis layanan\n\nSemua layanan GRATIS! Silakan tanyakan jenis layanan spesifik untuk info lebih detail.",
                'keywords' => ['syarat', 'persyaratan', 'butuh apa', 'perlu apa', 'dokumen'],
                'priority' => 16,
            ],

            // ===== STATUS & TRACKING =====
            [
                'intent' => 'cek_status',
                'pattern' => 'cek status',
                'response' => "Untuk mengecek status permohonan Anda:\n1. Hubungi kami di (0352) 461485\n2. Sebutkan nomor permohonan Anda\n3. Atau datang langsung ke kantor membawa bukti permohonan\n\nBiasanya dokumen selesai dalam 3-14 hari kerja tergantung jenis layanan.",
                'keywords' => ['cek status', 'status permohonan', 'sudah jadi', 'kapan jadi', 'sudah selesai', 'tracking'],
                'priority' => 16,
            ],

            // ===== LAYANAN ONLINE =====
            [
                'intent' => 'layanan_online',
                'pattern' => 'layanan online',
                'response' => "Layanan online DUKCAPIL:\n1. Cek status dokumen via website\n2. Download formulir persyaratan\n3. Konsultasi via WhatsApp\n4. Booking antrian online\n\nKunjungi website resmi kami atau hubungi nomor layanan untuk info lebih lanjut.",
                'keywords' => ['online', 'daring', 'internet', 'website', 'aplikasi', 'booking'],
                'priority' => 16,
            ],

            // ===== PERTANYAAN UMUM =====
            [
                'intent' => 'info_umum',
                'pattern' => 'apa saja layanan',
                'response' => "Layanan DUKCAPIL Ponorogo:\n1. KTP & E-KTP\n2. Kartu Keluarga (KK)\n3. Akta Kelahiran\n4. Akta Kematian\n5. Akta Perkawinan\n6. Akta Perceraian\n7. Surat Pindah\n8. Dan layanan kependudukan lainnya\n\nSemua GRATIS! Tanyakan untuk info detail layanan tertentu.",
                'keywords' => ['layanan', 'apa saja', 'jenis layanan', 'fasilitas', 'pelayanan'],
                'priority' => 17,
            ],
            [
                'intent' => 'waktu_proses',
                'pattern' => 'berapa lama',
                'response' => "Estimasi waktu proses:\n- KTP: 14 hari kerja\n- KK: 3-5 hari kerja\n- Akta Kelahiran: 3-7 hari kerja (< 1 tahun)\n- Akta Kematian: 3-5 hari kerja\n- Akta Perkawinan: 7-14 hari kerja\n\nWaktu bisa lebih cepat tergantung kelengkapan dokumen.",
                'keywords' => ['berapa lama', 'waktu proses', 'lama proses', 'estimasi waktu', 'kapan selesai'],
                'priority' => 16,
            ],

            // ===== BANTUAN & KELUHAN =====
            [
                'intent' => 'bantuan',
                'pattern' => 'butuh bantuan',
                'response' => "Kami siap membantu Anda!\n\nUntuk bantuan lebih lanjut:\n1. Datang langsung ke kantor DUKCAPIL\n2. Hubungi: (0352) 461485\n3. Email: dukcapil@ponorogokab.go.id\n4. Chat via WhatsApp ini\n\nSilakan jelaskan permasalahan Anda dengan detail.",
                'keywords' => ['bantuan', 'tolong', 'help', 'bantu', 'perlu bantuan'],
                'priority' => 14,
            ],
            [
                'intent' => 'keluhan',
                'pattern' => 'komplain',
                'response' => "Untuk keluhan atau saran:\n1. Datang ke kantor DUKCAPIL\n2. Hubungi: (0352) 461485\n3. Email: dukcapil@ponorogokab.go.id\n4. Kotak saran di kantor\n\nKami akan segera menindaklanjuti keluhan Anda. Terima kasih atas masukan Anda!",
                'keywords' => ['keluhan', 'komplain', 'lapor', 'aduan', 'pengaduan', 'saran'],
                'priority' => 16,
            ],

            // ===== IDENTITAS BOT =====
            [
                'intent' => 'bot_identity',
                'pattern' => 'siapa kamu',
                'response' => 'Saya adalah asisten virtual DUKCAPIL Ponorogo. Saya di sini untuk membantu menjawab pertanyaan Anda tentang layanan kependudukan dan administrasi.',
                'keywords' => ['siapa kamu', 'siapa anda', 'kamu siapa', 'bot', 'robot'],
                'priority' => 12,
            ],
        ];

        foreach ($trainingData as $data) {
            CsTrainingData::updateOrCreate(
                [
                    'intent' => $data['intent'],
                    'pattern' => $data['pattern'],
                ],
                $data
            );
        }
    }
}
