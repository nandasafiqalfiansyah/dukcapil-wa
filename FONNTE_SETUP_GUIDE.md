# Panduan Setup Koneksi WhatsApp via Fonnte API

## 📱 Tentang Fonnte

Fonnte adalah layanan WhatsApp API Gateway yang memungkinkan Anda mengirim dan menerima pesan WhatsApp melalui API. Dengan Fonnte, Anda tidak memerlukan Facebook Business account dan setup lebih mudah dibandingkan WhatsApp Business API official.

### Keunggulan Fonnte:
- ✅ Tidak perlu Facebook Business Account
- ✅ Setup mudah dengan scan QR code
- ✅ Harga terjangkau untuk bisnis kecil
- ✅ Support webhook untuk menerima pesan
- ✅ Multi-device support
- ✅ Dashboard yang user-friendly
- ✅ Support untuk mengirim gambar, dokumen, dan media lainnya

## 🚀 Langkah-langkah Setup

### 1. Registrasi Akun Fonnte

1. Buka website [fonnte.com](https://fonnte.com)
2. Klik tombol **"Daftar"** atau **"Register"**
3. Isi form registrasi dengan data Anda:
   - Nama lengkap
   - Email aktif
   - Password
   - Nomor WhatsApp
4. Verifikasi email Anda dengan mengklik link yang dikirim ke email
5. Login ke dashboard Fonnte

### 2. Hubungkan Nomor WhatsApp Anda

1. Setelah login, Anda akan melihat dashboard Fonnte
2. Pilih menu **"Device"** atau **"Perangkat"**
3. Klik tombol **"Add Device"** atau **"Tambah Perangkat"**
4. Akan muncul QR Code di layar
5. Buka aplikasi WhatsApp di smartphone Anda
6. Pilih menu **"Linked Devices"** atau **"Perangkat Tertaut"**
7. Tap **"Link a Device"** atau **"Tautkan Perangkat"**
8. Scan QR Code yang muncul di dashboard Fonnte
9. Tunggu hingga proses koneksi selesai (status akan berubah menjadi "Connected")

### 3. Dapatkan API Token

1. Setelah WhatsApp berhasil terhubung, buka menu **"Settings"** atau **"Pengaturan"**
2. Pilih **"API"** atau **"API Configuration"**
3. Anda akan melihat **API Token** Anda
4. Klik tombol **"Copy"** untuk menyalin token
5. Simpan token ini dengan aman (jangan dibagikan ke orang lain)

**Contoh Token:**
```
abc123def456ghi789jkl012mno345pqr678stu901vwx
```

### 4. Konfigurasi di Aplikasi DUKCAPIL WhatsApp Bot

Ada dua cara untuk menggunakan token Fonnte:

#### Cara 1: Melalui File .env (Untuk semua bot)

1. Buka file `.env` di root project Anda
2. Tambahkan atau update baris berikut:
   ```env
   FONNTE_API_URL=https://md.fonnte.com
   FONNTE_TOKEN=token_anda_disini
   ```
3. Ganti `token_anda_disini` dengan token yang Anda copy dari dashboard Fonnte
4. Simpan file `.env`
5. Restart aplikasi Laravel:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

#### Cara 2: Input Token Saat Membuat Bot (Per bot)

1. Login ke admin dashboard aplikasi
2. Pilih menu **"Bots"** atau **"Perangkat"**
3. Klik tombol **"Add New Device"** atau **"Tambah Perangkat Baru"**
4. Isi form:
   - **Bot Name**: Nama untuk bot Anda (contoh: "DUKCAPIL Bot 1")
   - **Bot ID**: ID unik untuk bot (contoh: "bot-1")
   - **Fonnte Token**: Paste token Anda di sini
5. Klik **"Create Bot"** atau **"Buat Bot"**
6. Sistem akan otomatis validasi token Anda
7. Jika berhasil, bot akan langsung terhubung dan siap digunakan!

## 📊 Fitur-fitur Fonnte yang Didukung

### 1. Mengirim Pesan Text
```php
// Mengirim pesan text sederhana
$whatsappService->sendMessage('6281234567890', 'Halo, ini pesan dari bot!');
```

### 2. Mengirim Pesan dengan Media
```php
// Mengirim gambar
$whatsappService->sendMessage('6281234567890', 'Lihat gambar ini!', null, [
    'url' => 'https://example.com/image.jpg',
    'filename' => 'gambar.jpg'
]);

// Mengirim dokumen
$whatsappService->sendMessage('6281234567890', 'Ini dokumennya', null, [
    'url' => 'https://example.com/document.pdf',
    'filename' => 'dokumen.pdf'
]);
```

### 3. Menerima Pesan (Webhook)
Aplikasi ini otomatis menerima pesan masuk melalui webhook. Untuk mengaktifkan webhook:

1. Login ke dashboard Fonnte
2. Pilih menu **"Webhook"** atau **"Webhook Configuration"**
3. Masukkan URL webhook Anda:
   ```
   https://yourdomain.com/api/webhook/whatsapp
   ```
4. Pilih event yang ingin diterima:
   - ✅ Message received
   - ✅ Message sent (optional)
   - ✅ Message status (optional)
5. Simpan konfigurasi

**Catatan Penting:**
- URL webhook harus menggunakan HTTPS (SSL)
- Untuk development lokal, gunakan ngrok atau serveo untuk expose local server
- Fonnte akan mengirim POST request ke URL webhook saat ada pesan masuk
- Format webhook Fonnte otomatis terdeteksi oleh aplikasi

**Contoh menggunakan ngrok:**
```bash
# Install ngrok dari https://ngrok.com
# Jalankan Laravel
php artisan serve

# Di terminal lain, expose dengan ngrok
ngrok http 8000

# Copy URL https dari ngrok (contoh: https://abc123.ngrok.io)
# Masukkan ke Fonnte webhook: https://abc123.ngrok.io/api/webhook/whatsapp
```

## 💰 Harga dan Paket

Fonnte menawarkan beberapa paket berlangganan:

### Paket Free Trial
- 100 pesan gratis untuk testing
- Semua fitur tersedia
- Device: 1 device

### Paket Regular (Mulai dari Rp 50.000/bulan)
- 1000+ pesan per bulan
- Multi-device support
- Unlimited incoming messages
- Priority support

### Paket Business (Custom)
- Unlimited messages
- Dedicated server
- 24/7 support
- Custom features

Kunjungi [fonnte.com/pricing](https://fonnte.com/pricing) untuk detail lengkap.

## 🔧 Troubleshooting

### Masalah 1: Token Invalid atau Expired

**Gejala:** Error "Invalid token" atau "Token expired"

**Solusi:**
1. Login ke dashboard Fonnte
2. Generate token baru di menu Settings > API
3. Update token di .env atau di pengaturan bot
4. Restart aplikasi

### Masalah 2: Pesan Tidak Terkirim

**Gejala:** Pesan gagal dikirim, ada error di log

**Solusi:**
1. Pastikan WhatsApp masih terhubung di dashboard Fonnte
2. Cek status device di dashboard Fonnte
3. Pastikan nomor tujuan benar (format: 6281234567890)
4. Cek kuota pesan Anda di dashboard Fonnte
5. Pastikan nomor tujuan tidak memblokir nomor Anda

### Masalah 3: Webhook Tidak Menerima Pesan

**Gejala:** Pesan masuk tidak tercatat di aplikasi

**Solusi:**
1. Pastikan webhook URL sudah dikonfigurasi dengan benar di dashboard Fonnte
2. Pastikan URL menggunakan HTTPS (bukan HTTP)
3. Test webhook URL Anda menggunakan tools seperti webhook.site
4. Cek log Laravel untuk melihat error:
   ```bash
   tail -f storage/logs/laravel.log
   ```
5. Pastikan firewall tidak memblokir request dari server Fonnte

### Masalah 4: WhatsApp Disconnect

**Gejala:** WhatsApp tiba-tiba terputus dari Fonnte

**Solusi:**
1. Buka dashboard Fonnte
2. Scan ulang QR code untuk reconnect
3. Pastikan WhatsApp di smartphone tidak logout
4. Pastikan smartphone terhubung dengan internet

### Masalah 5: Rate Limit Exceeded

**Gejala:** Error "Too many requests" atau "Rate limit exceeded"

**Solusi:**
1. Tunggu beberapa menit sebelum mengirim pesan lagi
2. Kurangi frekuensi pengiriman pesan
3. Upgrade paket Anda untuk limit yang lebih tinggi
4. Implementasi queue untuk mengirim pesan secara bertahap

## 📱 Best Practices

### 1. Keamanan Token
- ❌ Jangan commit token ke Git repository
- ✅ Simpan token di .env file
- ✅ Gunakan .gitignore untuk mengabaikan .env
- ✅ Rotate token secara berkala

### 2. Pengiriman Pesan
- ✅ Validasi nomor tujuan sebelum mengirim
- ✅ Gunakan queue untuk mengirim banyak pesan
- ✅ Tambahkan delay antar pesan (avoid spam)
- ✅ Handle error dengan graceful
- ❌ Jangan spam pengguna

### 3. Format Nomor Telepon
```php
// Format yang benar:
6281234567890   // ✅ Dengan kode negara, tanpa + dan spasi
+6281234567890  // ✅ Dengan + (akan di-clean otomatis)

// Format yang salah:
081234567890    // ❌ Tanpa kode negara
62-812-3456-7890 // ❌ Dengan tanda hubung
62 812 3456 7890 // ❌ Dengan spasi
```

### 4. Monitoring
- ✅ Monitor status device di dashboard Fonnte
- ✅ Cek log aplikasi secara berkala
- ✅ Set up alert untuk error critical
- ✅ Track usage untuk menghindari over-limit

## 🆚 Perbandingan: Fonnte vs WhatsApp Business API (Meta)

| Fitur | Fonnte | WhatsApp Business API |
|-------|--------|----------------------|
| Setup | Mudah (scan QR) | Kompleks (perlu FB Business) |
| Biaya | Mulai Rp 50rb/bulan | $0.05 per conversation |
| Approval | Instant | Perlu approval (1-3 hari) |
| Multi-device | Ya | Ya |
| Webhook | Ya | Ya |
| Template approval | Tidak perlu | Wajib untuk business-initiated |
| Number type | Personal/Business | Business only |
| Best for | Bisnis kecil-menengah | Enterprise |

## 📚 Dokumentasi API Lengkap

Untuk informasi lebih detail tentang Fonnte API, kunjungi:
- [Dokumentasi Fonnte](https://fonnte.com/api)
- [API Reference](https://fonnte.com/api/docs)
- [Tutorial Video](https://youtube.com/@fonnte)
- [FAQ](https://fonnte.com/faq)

## 🆘 Support

Jika mengalami kesulitan:

1. **Dokumentasi Fonnte:** [fonnte.com/docs](https://fonnte.com/docs)
2. **Support Fonnte:** 
   - Email: support@fonnte.com
   - WhatsApp: 0811-222-4444
   - Telegram: @fonnte_support
3. **Community Forum:** [forum.fonnte.com](https://forum.fonnte.com)
4. **GitHub Issues:** Untuk masalah terkait aplikasi ini

## 🎓 Tutorial Video

Tonton tutorial video lengkap di:
- [Cara Setup Fonnte API](https://youtube.com)
- [Integrasi dengan Laravel](https://youtube.com)
- [Best Practices WhatsApp Bot](https://youtube.com)

---

## ✅ Checklist Setup

Gunakan checklist ini untuk memastikan setup Anda lengkap:

- [ ] Registrasi akun Fonnte
- [ ] Verifikasi email
- [ ] Scan QR code dan hubungkan WhatsApp
- [ ] Copy API token dari dashboard
- [ ] Tambahkan token ke .env atau saat create bot
- [ ] Test kirim pesan
- [ ] Konfigurasi webhook (optional)
- [ ] Test terima pesan
- [ ] Monitor status di dashboard

**Selamat! Bot WhatsApp Anda siap digunakan! 🎉**

---

**Dibuat dengan ❤️ untuk DUKCAPIL Ponorogo**
