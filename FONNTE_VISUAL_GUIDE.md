# Diagram Alur Koneksi WhatsApp via Fonnte

## Alur Lengkap Setup WhatsApp Bot

```
┌─────────────────────────────────────────────────────────────┐
│                    PANDUAN VISUAL SETUP                     │
└─────────────────────────────────────────────────────────────┘

LANGKAH 1: REGISTRASI FONNTE
┌──────────────────────────────────────┐
│  🌐 Buka fonnte.com                 │
│  📝 Daftar akun baru                │
│  ✉️  Verifikasi email               │
│  🔐 Login ke dashboard              │
└──────────────────────────────────────┘
         │
         ▼
LANGKAH 2: HUBUNGKAN WHATSAPP
┌──────────────────────────────────────┐
│  📱 Pilih "Add Device"              │
│  📷 Scan QR Code dengan WhatsApp    │
│  ✅ Tunggu status "Connected"       │
└──────────────────────────────────────┘
         │
         ▼
LANGKAH 3: DAPATKAN TOKEN
┌──────────────────────────────────────┐
│  ⚙️  Buka Settings > API            │
│  📋 Copy API Token                  │
│  💾 Simpan token dengan aman        │
└──────────────────────────────────────┘
         │
         ▼
LANGKAH 4: KONFIGURASI APLIKASI
┌──────────────────────────────────────┐
│  PILIHAN A: Via .env                │
│  └─ Edit .env                       │
│     FONNTE_TOKEN=token_anda         │
│                                      │
│  PILIHAN B: Via Dashboard           │
│  └─ Bots > Add Device              │
│     Input token di form             │
└──────────────────────────────────────┘
         │
         ▼
LANGKAH 5: BUAT BOT
┌──────────────────────────────────────┐
│  🤖 Login admin dashboard           │
│  ➕ Klik "Add New Device"          │
│  📝 Isi:                            │
│     - Bot Name: DUKCAPIL Bot        │
│     - Bot ID: bot-1                 │
│     - Fonnte Token: [paste token]  │
│  💾 Simpan                          │
└──────────────────────────────────────┘
         │
         ▼
LANGKAH 6: SELESAI! 🎉
┌──────────────────────────────────────┐
│  ✅ Bot terhubung                   │
│  📤 Bisa kirim pesan                │
│  📥 Bisa terima pesan (via webhook) │
│  🤖 Auto-reply aktif                │
└──────────────────────────────────────┘
```

## Alur Pengiriman Pesan

```
┌────────────┐
│   Admin    │ (Kirim pesan via dashboard)
└─────┬──────┘
      │
      ▼
┌────────────────────────┐
│  Laravel Application   │
│  WhatsAppService       │
└─────┬──────────────────┘
      │
      ▼
┌────────────────────────┐
│  Fonnte API Server     │
│  md.fonnte.com/send    │
└─────┬──────────────────┘
      │
      ▼
┌────────────────────────┐
│  WhatsApp Server       │
└─────┬──────────────────┘
      │
      ▼
┌────────────┐
│  Customer  │ (Terima pesan)
└────────────┘
```

## Alur Penerimaan Pesan

```
┌────────────┐
│  Customer  │ (Kirim pesan ke nomor bot)
└─────┬──────┘
      │
      ▼
┌────────────────────────┐
│  WhatsApp Server       │
└─────┬──────────────────┘
      │
      ▼
┌────────────────────────┐
│  Fonnte API Server     │
└─────┬──────────────────┘
      │ (POST webhook)
      ▼
┌────────────────────────┐
│  Laravel Application   │
│  /api/webhook/whatsapp │
│  WhatsAppService       │
│  - Log conversation    │
│  - Check auto-reply    │
│  - Send response       │
└────────────────────────┘
```

## Struktur Token

```
┌──────────────────────────────────────────┐
│  Token Format:                           │
│  abc123def456ghi789jkl012mno345pqr678   │
│                                          │
│  Karakteristik:                          │
│  - 35-40 karakter                        │
│  - Random alphanumeric                   │
│  - Case-sensitive                        │
│  - Unik per device                       │
└──────────────────────────────────────────┘
```

## Konfigurasi Webhook (Opsional)

```
UNTUK MENERIMA PESAN MASUK:

1. Setup URL Webhook
   ┌─────────────────────────────────────┐
   │ Fonnte Dashboard > Webhook          │
   │ URL: https://domain.com/api/        │
   │      webhook/whatsapp               │
   │ Event: ✅ Message received          │
   └─────────────────────────────────────┘

2. Untuk Local Development
   ┌─────────────────────────────────────┐
   │ Terminal 1:                         │
   │ $ php artisan serve                 │
   │                                     │
   │ Terminal 2:                         │
   │ $ ngrok http 8000                   │
   │                                     │
   │ Copy URL ngrok:                     │
   │ https://abc123.ngrok.io             │
   │                                     │
   │ Masukkan ke Fonnte:                 │
   │ https://abc123.ngrok.io/api/        │
   │ webhook/whatsapp                    │
   └─────────────────────────────────────┘
```

## Format Nomor Telepon

```
✅ FORMAT BENAR:
   6281234567890      (Kode negara + nomor)
   +6281234567890     (Dengan +, akan dibersihkan otomatis)
   62 812 3456 7890   (Dengan spasi, akan dibersihkan otomatis)

❌ FORMAT SALAH:
   081234567890       (Tanpa kode negara)
   62-812-3456-7890   (Dengan dash)
   (0812) 3456-7890   (Dengan kurung dan dash)
```

## Troubleshooting Visual

```
MASALAH 1: Token Invalid
┌─────────────────────────┐
│ Error: Invalid token    │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│ SOLUSI:                             │
│ 1. Login ke Fonnte dashboard        │
│ 2. Settings > API                   │
│ 3. Generate token baru              │
│ 4. Update di .env atau bot setting  │
│ 5. Restart: php artisan config:clear│
└─────────────────────────────────────┘

MASALAH 2: Pesan Tidak Terkirim
┌─────────────────────────┐
│ Pesan gagal dikirim     │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│ CEK:                                │
│ ✓ WhatsApp masih connected?         │
│   → Cek di Fonnte dashboard         │
│                                     │
│ ✓ Format nomor benar?               │
│   → 6281234567890                   │
│                                     │
│ ✓ Kuota masih ada?                  │
│   → Cek usage di dashboard          │
│                                     │
│ ✓ Nomor tidak diblock?              │
│   → Test kirim ke nomor lain        │
└─────────────────────────────────────┘

MASALAH 3: Webhook Tidak Jalan
┌─────────────────────────┐
│ Tidak terima pesan      │
└────────┬────────────────┘
         │
         ▼
┌─────────────────────────────────────┐
│ CEK:                                │
│ ✓ URL webhook benar?                │
│   → https://domain.com/api/...      │
│                                     │
│ ✓ Server online?                    │
│   → Test buka URL di browser        │
│                                     │
│ ✓ SSL aktif?                        │
│   → Harus HTTPS (bukan HTTP)        │
│                                     │
│ ✓ Firewall?                         │
│   → Allow dari IP Fonnte            │
└─────────────────────────────────────┘
```

## Perbandingan Setup Method

```
┌─────────────────────────────────────────────────────────┐
│                    META API vs FONNTE                   │
├─────────────┬──────────────────┬───────────────────────┤
│ Aspek       │ Meta API         │ Fonnte               │
├─────────────┼──────────────────┼───────────────────────┤
│ Setup       │ ⭐⭐            │ ⭐⭐⭐⭐⭐        │
│ Kompleksitas│ Tinggi          │ Rendah               │
│ Waktu Setup │ 2-3 hari        │ 5 menit              │
│ FB Business │ Wajib           │ Tidak perlu          │
│ Approval    │ Ya              │ Tidak                │
│ QR Code     │ Tidak           │ Ya                   │
│ Harga       │ $0.05/conv      │ ~Rp 50rb/bulan       │
│ Support     │ English         │ Indonesia/English    │
│ Best For    │ Enterprise      │ UMKM                 │
└─────────────┴──────────────────┴───────────────────────┘
```

## Checklist Setup Lengkap

```
PRE-SETUP:
□ Nomor WhatsApp siap
□ Email aktif untuk registrasi
□ Akses ke dashboard aplikasi
□ PHP 8.2+ terinstall

SETUP FONNTE:
□ Registrasi di fonnte.com
□ Verifikasi email
□ Login dashboard
□ Scan QR code dengan WhatsApp
□ Status "Connected"
□ Copy API token

KONFIGURASI APLIKASI:
□ Edit .env file ATAU
□ Siap input token di form

BUAT BOT:
□ Login admin dashboard
□ Add New Device
□ Isi form (nama, ID, token)
□ Submit

TESTING:
□ Kirim pesan test
□ Pesan terkirim ✅
□ Setup webhook (opsional)
□ Terima pesan test
□ Auto-reply berfungsi

PRODUCTION:
□ Domain dengan SSL
□ Webhook configured
□ Monitor dashboard
□ Backup token

SELESAI! 🎉
```

## Tips & Trik

```
💡 TIPS HEMAT BIAYA:
   - Gunakan paket trial dulu
   - Monitor usage rutin
   - Atur auto-reply efisien
   - Jangan spam

🔒 TIPS KEAMANAN:
   - Jangan share token
   - Gunakan .gitignore
   - Rotate token berkala
   - Monitor access log

⚡ TIPS PERFORMA:
   - Gunakan queue untuk broadcast
   - Cache auto-reply rules
   - Optimize database
   - Monitor response time

📊 TIPS MONITORING:
   - Check dashboard daily
   - Set usage alerts
   - Track success rate
   - Log semua activity
```

---

**Pertanyaan?** 
Buka [FONNTE_SETUP_GUIDE.md](FONNTE_SETUP_GUIDE.md) untuk panduan lengkap!
