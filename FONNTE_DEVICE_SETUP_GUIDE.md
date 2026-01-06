# Panduan Setup Device Fonnte untuk DUKCAPIL WhatsApp Bot

## Informasi Device Anda
- **Number**: 62881011983167
- **Name**: ets
- **Token**: Y8wkdkWFauGwmeCJGk9o ✅ (Sudah di .env)

---

## Cara Mengisi Form "Edit Device" di Fonnte

### 1. **Number** (Sudah Terisi)
```
62881011983167
```
✅ **Biarkan seperti ini** - ini nomor WhatsApp yang sudah connected

---

### 2. **Name** (Sudah Terisi)
```
ets
```
✅ **Biarkan atau ganti dengan nama yang lebih deskriptif**, contoh:
- `DUKCAPIL Bot`
- `Bot Layanan DUKCAPIL`
- `WhatsApp Bot Production`

---

### 3. **Token** (Sudah Terisi)
```
Y8wkdkWFauGwmeCJGk9o
```
✅ **Jangan diubah!** Token ini yang digunakan aplikasi untuk connect ke Fonnte

---

### 4. **Whitelist IP** (Optional)
```
KOSONGKAN atau hapus: 123.123.123.122,123.123.123.123
```

❌ **Recommendation**: **KOSONGKAN field ini**

**Kenapa?**
- IP server Anda bisa berubah
- Jika diisi dengan IP yang salah, aplikasi tidak bisa connect ke Fonnte
- Whitelist IP hanya perlu diisi jika Anda punya static IP dan ingin extra security

**Kapan harus diisi?**
- Hanya jika Anda deploy di server production dengan static IP
- Untuk development (localhost), **JANGAN** diisi

---

### 5. **Webhook** - URL untuk menerima pesan masuk
```
Isi dengan: https://your-domain.com/api/webhook/fonnte
```

#### Untuk Development (Localhost):
Anda perlu menggunakan tunneling service karena Fonnte tidak bisa akses localhost.

**Option A: Ngrok (Recommended)**
```bash
# Install ngrok: https://ngrok.com/download
# Jalankan Laravel
php artisan serve

# Di terminal lain, jalankan ngrok
ngrok http 8000

# Ngrok akan memberikan URL seperti:
# https://abc123.ngrok.io

# Isi webhook dengan:
https://abc123.ngrok.io/api/webhook/fonnte
```

**Option B: LocalTunnel**
```bash
npm install -g localtunnel
lt --port 8000

# Akan dapat URL seperti:
# https://xyz456.loca.lt

# Isi webhook dengan:
https://xyz456.loca.lt/api/webhook/fonnte
```

**Option C: Biarkan Kosong Dulu (Untuk Testing)**
```
https://fonnte.com/webhook.php
```
Biarkan default dulu sampai aplikasi Anda online/production

#### Untuk Production:
```
https://your-actual-domain.com/api/webhook/fonnte
```

---

### 6. **Webhook Connect** - Notifikasi saat device connect/disconnect
```
Isi dengan: https://your-domain.com/api/webhook/fonnte/connect
```

ATAU **biarkan kosong** jika tidak perlu notifikasi connect/disconnect

---

### 7. **Webhook Message Status** - Status pesan (terkirim/dibaca/gagal)
```
Isi dengan: https://your-domain.com/api/webhook/fonnte/status
```

ATAU **biarkan kosong** jika tidak perlu tracking status pesan

---

### 8. **Webhook Chaining** - Untuk multi-device/load balancing
```
KOSONGKAN - tidak perlu untuk single device
```

---

### 9. **autoread** - Tandai pesan otomatis sebagai dibaca
```
✅ Pilih: ON
```

**Kenapa ON?**
- Untuk chatbot, pesan harus di-mark as read secara otomatis
- User tidak akan melihat "unread message" di WhatsApp mereka
- Lebih professional untuk bot

---

### 10. **Autodelete Message** - Hapus history pesan otomatis
```
⚠️ Pilih: OFF (Recommended)
```

**Kenapa OFF?**
- Anda perlu history untuk debugging
- Aplikasi menyimpan conversation di database sendiri
- Jika ON, pesan akan terhapus dan tidak bisa direview

**Boleh ON jika:**
- Privacy concern
- Storage space terbatas di Fonnte
- Yakin tidak perlu history di Fonnte (karena sudah ada di database app)

---

## Summary - Recommended Settings

```
Number: 62881011983167 (sudah terisi)
Name: DUKCAPIL Bot (atau nama lain yang deskriptif)
Token: Y8wkdkWFauGwmeCJGk9o (jangan diubah!)
Whitelist IP: [KOSONG]
Webhook: https://fonnte.com/webhook.php (sementara)
Webhook Connect: [KOSONG]
Webhook Message Status: [KOSONG]
Webhook Chaining: [KOSONG]
autoread: ON ✅
Autodelete Message: OFF ⚠️
```

---

## Setup Webhook di Aplikasi Laravel

Webhook endpoint sudah perlu dibuat di aplikasi. Buat route di `routes/api.php`:

```php
// Webhook untuk terima pesan dari Fonnte
Route::post('/webhook/fonnte', [WebhookController::class, 'handle'])
    ->name('webhook.fonnte');

// Webhook untuk status connect/disconnect (optional)
Route::post('/webhook/fonnte/connect', [WebhookController::class, 'handleConnect'])
    ->name('webhook.fonnte.connect');

// Webhook untuk status pesan (optional)
Route::post('/webhook/fonnte/status', [WebhookController::class, 'handleStatus'])
    ->name('webhook.fonnte.status');
```

---

## Testing

### 1. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 2. Test Token
```bash
php test-fonnte-token.php
```

Expected output:
```
✅ SUCCESS! Token is valid
Device Number: 62881011983167
Status: connected
```

### 3. Try Create Bot
```
http://localhost/admin/bots/create

Bot Name: DUKCAPIL Bot
Bot ID: 62881011983167
Fonnte Token: Y8wkdkWFauGwmeCJGk9o
```

---

## Quick Start (Minimal Setup)

Untuk mulai cepat tanpa webhook dulu:

1. **Edit Device di Fonnte:**
   - Name: `DUKCAPIL Bot`
   - autoread: `ON`
   - Autodelete: `OFF`
   - **Sisanya biarkan default**

2. **Clear cache:**
   ```bash
   php artisan config:clear
   ```

3. **Create bot:**
   - Go to: http://localhost/admin/bots/create
   - Gunakan token yang sudah ada di .env

4. **Test send message dari aplikasi**

5. **Setup webhook nanti** setelah aplikasi jalan dengan baik

---

## Troubleshooting

### Token sudah valid tapi masih error?
```bash
# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Restart server
# Ctrl+C, then:
php artisan serve
```

### Webhook tidak terima pesan?
1. Check webhook URL bisa diakses dari internet
2. Pastikan method POST allowed
3. Check logs: `storage/logs/laravel.log`
4. Test webhook dengan Postman/cURL

---

**Note**: Untuk development (localhost), webhook tidak akan bekerja sampai Anda menggunakan ngrok/localtunnel atau deploy ke server yang bisa diakses dari internet.

Untuk sekarang, **fokus pada send message dulu** (aplikasi ke user), webhook bisa di-setup nanti untuk receive message (user ke aplikasi).
