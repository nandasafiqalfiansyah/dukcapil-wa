# Troubleshooting: "Failed to get device info"

## Error Deskripsi
Error ini muncul saat mencoba membuat bot instance baru dan sistem tidak dapat memvalidasi token Fonnte Anda.

## Penyebab Umum

### 1. **Token Tidak Valid atau Expired**
Token Fonnte Anda mungkin:
- Tidak valid
- Sudah expired
- Salah dimasukkan

**Solusi:**
```bash
# Test token Anda dengan script
php test-fonnte-token.php

# Atau test dengan token tertentu
php test-fonnte-token.php YOUR_TOKEN_HERE
```

### 2. **Token Tidak Ada di .env**
Jika Anda tidak memasukkan token saat membuat bot, sistem akan menggunakan token dari `.env`.

**Solusi:**
```bash
# Buka file .env dan pastikan ada baris ini:
FONNTE_TOKEN=your_valid_token_here
```

### 3. **Akun Fonnte Tidak Aktif**
Akun Fonnte Anda mungkin:
- Tidak aktif
- Belum connect WhatsApp
- Habis masa trial/quota

**Solusi:**
1. Login ke https://fonnte.com
2. Cek status akun Anda
3. Pastikan WhatsApp sudah terconnect
4. Cek quota/saldo Anda

### 4. **Network/Firewall Issue**
Koneksi ke Fonnte API mungkin terblokir.

**Solusi:**
```bash
# Test koneksi manual
curl -H "Authorization: YOUR_TOKEN" https://md.fonnte.com/device
```

## Cara Mendapatkan Token Valid

1. **Kunjungi Fonnte**
   - Go to: https://fonnte.com

2. **Login/Register**
   - Buat akun baru atau login

3. **Connect WhatsApp**
   - Scan QR code dengan WhatsApp Anda
   - Tunggu sampai status "Connected"

4. **Copy Token**
   - Di dashboard, copy API token Anda
   - Paste ke form saat create bot ATAU ke .env file

## Testing Token

### Method 1: Script PHP
```bash
php test-fonnte-token.php
```

Output yang diharapkan:
```
✅ SUCCESS! Token is valid
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Device Number: 628123456789
Status: connected
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

### Method 2: cURL
```bash
curl -H "Authorization: ZxQwavLt9yqxyGBVpYBy" https://md.fonnte.com/device
```

Response sukses:
```json
{
  "device": "628123456789",
  "status": "connected"
}
```

Response error:
```json
{
  "reason": "Invalid token"
}
```

### Method 3: Artisan Tinker
```bash
php artisan tinker
```

```php
$service = new App\Services\WhatsAppService();
$result = $service->initializeBot('test-bot', 'Test Bot', 'YOUR_TOKEN');
dd($result);
```

## Error Messages dan Solusinya

### "Fonnte token not provided"
❌ **Problem**: Token kosong atau null

✅ **Solusi**: 
1. Isi field "Fonnte Token" di form, ATAU
2. Set `FONNTE_TOKEN` di .env file

### "Invalid Fonnte token"
❌ **Problem**: Token salah atau expired

✅ **Solusi**:
1. Login ke fonnte.com
2. Copy token yang benar
3. Update token Anda

### "Invalid response from Fonnte API"
❌ **Problem**: Response tidak sesuai format

✅ **Solusi**:
1. Pastikan Anda menggunakan Fonnte MD API (bukan Legacy API)
2. Check API URL: harus `https://md.fonnte.com`

### "Cannot connect to Fonnte API"
❌ **Problem**: Network issue

✅ **Solusi**:
1. Check internet connection
2. Test: `ping md.fonnte.com`
3. Check firewall/proxy settings

### "Fonnte API server error"
❌ **Problem**: Server Fonnte sedang down

✅ **Solusi**:
1. Tunggu beberapa menit
2. Check status Fonnte di Twitter/sosmed mereka
3. Coba lagi nanti

## Checklist Before Creating Bot

- [ ] Token Fonnte valid dan tidak expired
- [ ] Akun Fonnte aktif
- [ ] WhatsApp sudah connected di Fonnte
- [ ] Internet connection stable
- [ ] Bot ID unique (belum dipakai)

## Testing Flow

```
1. Test token dulu
   → php test-fonnte-token.php

2. Kalau token valid, coba create bot via UI
   → http://localhost/admin/bots/create

3. Kalau masih error, check logs
   → storage/logs/laravel.log
```

## Log Files

Error details bisa dilihat di:
```bash
tail -f storage/logs/laravel.log
```

Look for entries:
- "Fonnte device info request failed"
- "Fonnte device info connection failed"
- "Fonnte device info request exception"

## Quick Fix Guide

**Jika error "Failed to get device info":**

1. **Cepat Fix** (Recommended):
   ```bash
   # 1. Test token
   php test-fonnte-token.php
   
   # 2. Jika invalid, update .env
   nano .env  # atau editor lain
   # Update: FONNTE_TOKEN=new_valid_token
   
   # 3. Clear cache
   php artisan config:clear
   
   # 4. Try create bot lagi
   ```

2. **Jika masih error**:
   - Check fonnte.com dashboard
   - Pastikan WhatsApp connected
   - Generate new token kalau perlu
   - Contact Fonnte support jika masalah persist

## Contact Support

- **Fonnte**: https://fonnte.com (Help section)
- **Application**: Check logs at `storage/logs/laravel.log`

---

**Last Updated**: December 29, 2025
