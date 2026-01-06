# Fix: "Failed to validate Fonnte token" Error

## ✅ Masalah yang Sudah Diperbaiki

### 1. API URL Configuration
**Sebelumnya:** `https://md.fonnte.com` (❌ Wrong)  
**Sekarang:** `https://api.fonnte.com` (✅ Correct)

**File yang diupdate:**
- `config/services.php` - Default API URL
- `test-fonnte-token.php` - Test script

### 2. API Endpoint
**Sebelumnya:** `GET /device` (❌ Wrong)  
**Sekarang:** `POST /get-devices` (✅ Correct)

**File yang diupdate:**
- `test-fonnte-token.php` - Menggunakan POST method

---

## ❌ Token Tidak Valid

Setelah perbaikan API endpoint, kami mendapatkan response dari Fonnte:

```json
{
    "reason": "unknown user",
    "status": false
}
```

**Artinya:** Token `Y8wkdkWFauGwmeCJGk9o` sudah **tidak valid** atau **expired**.

---

## 🔧 Cara Mendapatkan Token Baru

### Langkah-langkah:

1. **Buka Fonnte Dashboard**
   - Kunjungi: https://fonnte.com
   - Login dengan akun Anda

2. **Connect WhatsApp Device**
   - Scan QR code di dashboard
   - Tunggu sampai status "Connected" ✅
   - Pastikan WhatsApp Anda tetap online

3. **Copy Token Baru**
   - Pergi ke **Settings** atau **Dashboard**
   - Lihat bagian **API Configuration**
   - Copy **API Token** yang baru

4. **Gunakan Token Baru**
   - Paste token di form saat membuat bot
   - Token akan otomatis divalidasi

---

## 🧪 Test Token Baru

Sebelum membuat bot, test dulu token Anda:

```bash
php test-fonnte-token.php YOUR_NEW_TOKEN_HERE
```

**Expected Success Response:**
```
✅ SUCCESS! Fonnte connection working
Device: 628xxxxxxxxxx
Status: connected
```

**Jika masih error:**
- Pastikan WhatsApp sudah scan QR dan status "Connected"
- Pastikan token yang dicopy lengkap (tidak terpotong)
- Coba logout dan login kembali di fonnte.com

---

## 📝 Files Modified

### 1. config/services.php
```php
'fonnte' => [
    'api_url' => env('FONNTE_API_URL', 'https://api.fonnte.com'), // Fixed
    'token' => env('FONNTE_TOKEN'),
],
```

### 2. test-fonnte-token.php
```php
// Fixed default URL
$apiUrl = $_ENV['FONNTE_API_URL'] ?? 'https://api.fonnte.com';

// Fixed endpoint and method
curl_setopt($ch, CURLOPT_URL, "$apiUrl/get-devices");
curl_setopt($ch, CURLOPT_POST, true); // POST method
```

---

## ✅ Checklist

- [x] Fix API URL configuration
- [x] Fix API endpoint dan method
- [x] Update test script
- [ ] **TODO:** Get new valid token from fonnte.com
- [ ] **TODO:** Test dengan token baru
- [ ] **TODO:** Create bot dengan token yang valid

---

## 🚨 Important Notes

1. **Token bukan dari .env lagi**  
   Sejak update terakhir, token HARUS diinput di form saat membuat bot. Token disimpan per-bot instance.

2. **Token Security**  
   Token disimpan terenkripsi di database. Jangan share token dengan orang lain.

3. **Multiple Bots**  
   Setiap bot bisa punya token berbeda, memungkinkan management multiple WhatsApp numbers.

---

**Status:** API Configuration ✅ Fixed | Token ❌ Need Renewal  
**Date:** January 6, 2026
