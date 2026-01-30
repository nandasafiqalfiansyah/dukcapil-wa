# Cara Setup Bot WhatsApp dengan Fonnte

## Masalah yang Ditemukan ✅

1. ❌ **Token Fonnte tidak valid/expired** - Token yang ada di `.env` menunjukkan error "unknown user"
2. ✅ **Typo di .env** - `NLP_CONFIDENCE_THRESHOLD=0.3S` sudah diperbaiki menjadi `0.3`
3. ✅ **Logging ditambahkan** - Sekarang lebih mudah debug masalah webhook

## Langkah-Langkah Perbaikan

### 1. Dapatkan Token Fonnte Baru

1. Buka https://fonnte.com
2. Login ke akun Anda
3. Hubungkan WhatsApp device Anda
4. Copy token dari dashboard Fonnte

### 2. Update Token di `.env`

```bash
FONNTE_TOKEN=YOUR_NEW_TOKEN_HERE
```

### 3. Setup Webhook di Fonnte

1. Login ke dashboard Fonnte
2. Pergi ke menu **Settings** atau **Webhook**
3. Masukkan URL webhook Anda:
   ```
   https://yourdomain.com/api/webhook/whatsapp
   ```
   
   **Untuk development lokal**, gunakan ngrok:
   ```bash
   ngrok http 8000
   ```
   Kemudian gunakan URL dari ngrok, contoh:
   ```
   https://abc123.ngrok.io/api/webhook/whatsapp
   ```

### 4. Test Token

Jalankan script test:
```bash
php test-fonnte-token.php
```

Output yang benar:
```
✅ SUCCESS! Token is valid
Device Number: 6281234567890
Status: connected
```

### 5. Clear Cache dan Restart

```bash
php artisan config:cache
php artisan cache:clear
```

### 6. Cek Auto Reply Configuration

Pastikan ada auto-reply yang aktif di database:

```sql
SELECT * FROM auto_reply_configs WHERE is_active = 1;
```

Jika belum ada, buat lewat interface atau:

```sql
INSERT INTO auto_reply_configs (trigger, response, is_active, case_sensitive, priority) 
VALUES ('halo', 'Halo! Selamat datang di DUKCAPIL Ponorogo. Ada yang bisa kami bantu?', 1, 0, 1);
```

## Testing

### Test 1: Kirim Pesan ke WhatsApp Bot
Kirim pesan "halo" dari WhatsApp Anda ke nomor bot.

### Test 2: Cek Log
```bash
tail -f storage/logs/laravel.log
```

Anda harus melihat:
- `Processing Fonnte webhook`
- `Handling Fonnte message`
- `Extracted phone number`
- `Fonnte auto-reply sent successfully`

### Test 3: Cek Database
```sql
SELECT * FROM conversation_logs ORDER BY created_at DESC LIMIT 5;
```

## Troubleshooting

### Bot tidak membalas sama sekali

1. **Cek token valid:**
   ```bash
   php test-fonnte-token.php
   ```

2. **Cek webhook terpanggil:**
   ```bash
   tail -f storage/logs/laravel.log | grep "Webhook received"
   ```

3. **Cek device terkoneksi:**
   - Login ke https://fonnte.com
   - Pastikan device status = "Connected"

### Bot menerima tapi tidak balas

1. **Cek auto-reply config:**
   ```sql
   SELECT * FROM auto_reply_configs WHERE is_active = 1;
   ```

2. **Cek log error:**
   ```bash
   tail -f storage/logs/laravel.log | grep "ERROR"
   ```

### Webhook tidak dipanggil

1. **Pastikan URL webhook benar di Fonnte dashboard**
2. **Test webhook manual:**
   ```bash
   curl -X POST https://yourdomain.com/api/webhook/whatsapp \
     -H "Content-Type: application/json" \
     -d '{"message":"test","from":"6281234567890"}'
   ```

## Format Webhook dari Fonnte

Fonnte mengirim webhook dengan format:

```json
{
  "device": "6281234567890",
  "from": "628123456789@c.us",
  "message": "halo",
  "name": "John Doe",
  "type": "text",
  "id": "BAE5F..."
}
```

atau multiple messages:

```json
{
  "device": "6281234567890",
  "messages": [
    {
      "from": "628123456789@c.us",
      "message": "halo",
      "type": "text"
    }
  ]
}
```

## Kontak Support

- Fonnte Support: https://fonnte.com/support
- Dokumentasi API: https://fonnte.com/api
