# Debugging - Bot Tidak Membalas Pesan WhatsApp

## Diagnosis Cepat

Bot Anda **BELUM MEMBALAS** karena **webhook URL belum dikonfigurasi di Fonnte**. Berikut langkah-langkahnya:

---

## ✅ Solusi Lengkap

### LANGKAH 1: Pastikan Aplikasi Running

```bash
# Jalankan Laravel development server
php artisan serve

# Output harus seperti:
# >>>  Laravel development server started: http://127.0.0.1:8000
```

### LANGKAH 2: Expose Local Server ke Internet (Gunakan ngrok)

Karena aplikasi di local, Fonnte tidak bisa mengakses `http://localhost:8000`. Gunakan ngrok:

```bash
# Download ngrok dari: https://ngrok.com/download
# atau jika sudah terinstall:

ngrok http 8000
```

**Output ngrok akan terlihat seperti:**
```
Forwarding    https://abc123def456.ngrok.io -> http://127.0.0.1:8000
```

Catat URL `https://abc123def456.ngrok.io` (ini akan berubah tiap kali ngrok restart)

### LANGKAH 3: Setup Webhook di Fonnte Dashboard

1. **Login ke https://fonnte.com**
2. **Pergi ke Settings atau Webhook**
3. **Masukkan URL webhook:**
   ```
   https://abc123def456.ngrok.io/api/webhook/whatsapp
   ```
   *(Ganti `abc123def456` dengan URL dari ngrok Anda)*

4. **Klik Save/Submit**

### LANGKAH 4: Verify Token dan Device Connection

```bash
php test-fonnte-token.php
```

Output harus:
```
✅ SUCCESS! Token is valid
Device Number: 62851775578
Status: connect
```

### LANGKAH 5: Setup Auto-Reply Config

Pastikan ada auto-reply yang dikonfigurasi. Jalankan:

```bash
php artisan tinker
```

Kemudian di tinker shell:
```php
App\Models\AutoReplyConfig::create([
    'trigger' => 'halo',
    'response' => 'Halo! Selamat datang di DUKCAPIL Ponorogo. Ada yang bisa kami bantu?',
    'is_active' => true,
    'case_sensitive' => false,
    'priority' => 1
]);
```

Atau langsung query database:
```sql
INSERT INTO auto_reply_configs (trigger, response, is_active, case_sensitive, priority, created_at, updated_at) 
VALUES ('halo', 'Halo! Selamat datang di DUKCAPIL Ponorogo.', 1, 0, 1, NOW(), NOW());
```

### LANGKAH 6: Test Manually

#### Method A: Kirim pesan dari WhatsApp

1. Simpan nomor bot di phone (dari output `test-fonnte-token.php`)
2. Kirim pesan "halo"
3. Tunggu 2-5 detik

#### Method B: Test Webhook Secara Manual

```bash
curl -X POST https://abc123def456.ngrok.io/api/webhook/whatsapp \
  -H "Content-Type: application/json" \
  -d '{
    "device": "62851775578",
    "from": "628123456789@c.us",
    "message": "halo",
    "name": "John Doe",
    "type": "text",
    "id": "TEST123"
  }'
```

### LANGKAH 7: Cek Log

Monitor log real-time:
```bash
tail -f storage/logs/laravel.log | grep -i "webhook\|fonnte\|error"
```

**Yang harus Anda lihat di log:**
```
[2026-01-31 12:34:56] local.INFO: Webhook received...
[2026-01-31 12:34:56] local.INFO: Processing Fonnte webhook...
[2026-01-31 12:34:56] local.INFO: Handling Fonnte message...
[2026-01-31 12:34:56] local.INFO: Extracted phone number...
[2026-01-31 12:34:56] local.INFO: Fonnte auto-reply sent successfully...
```

Jika ada ERROR, catat error messagenya.

---

## 🔍 Checklist Debugging

- [ ] Aplikasi Laravel sedang running (`php artisan serve`)
- [ ] ngrok sedang running dan expose port 8000
- [ ] Webhook URL sudah masuk di Fonnte dashboard
- [ ] Token Fonnte valid (sudah ditest dengan `test-fonnte-token.php`)
- [ ] Ada minimal 1 auto-reply config di database
- [ ] Auto-reply config status = `is_active = 1`
- [ ] Nomor pengirim sudah save di phone (untuk test)
- [ ] Pesan yang dikirim match dengan trigger (case-sensitive atau tidak?)
- [ ] Cek log untuk error messages

---

## 🛠️ Common Issues

### "Webhook received" tidak muncul di log

**Masalah:** Webhook URL tidak dikonfigurasi di Fonnte atau URL salah.

**Solusi:**
1. Verifikasi webhook URL di Fonnte dashboard
2. Pastikan ngrok sedang jalan
3. Restart ngrok dan update URL di Fonnte (ngrok URL berubah tiap restart)

### "Fonnte auto-reply sent successfully" ada tapi tidak dapat balas

**Masalah:** Response mungkin terblokir atau ada masalah dengan nomor penerima.

**Solusi:**
1. Cek apakah nomor WhatsApp format valid (start with country code)
2. Cek quota Fonnte (dari `test-fonnte-token.php`)
3. Cek apakah ada rate limiting

### "Extracted phone number" tidak muncul

**Masalah:** Format webhook tidak sesuai atau field `from` kosong.

**Solusi:**
1. Cek format webhook dari Fonnte (pastikan sesuai dokumentasi)
2. Log akan menunjukkan: "missing phone number"

### Auto-reply tidak dipicu

**Masalah:** Trigger tidak match dengan pesan yang dikirim.

**Solusi:**
1. Pastikan `case_sensitive` setting sudah benar
2. Cek spasi di awal/akhir (whitespace)
3. Pesan harus exact match dengan trigger (tapi bisa case-insensitive jika `case_sensitive = 0`)

---

## 📞 Kontak Support

- **Fonnte:** https://fonnte.com/support
- **ngrok:** https://ngrok.com/docs
- **Laravel:** https://laravel.com/docs

---

## Next Steps

Setelah webhook sudah berfungsi:
1. Tambah lebih banyak auto-reply configs
2. Implementasi NLP untuk response lebih intelligent
3. Setup Chat Session untuk conversation tracking
4. Implementasi integration dengan sistem DUKCAPIL
