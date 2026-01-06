# Bot Token Per Instance - Perbaikan Konfigurasi

## Perubahan

Sistem bot telah diperbarui untuk **mengharuskan token Fonnte per bot instance** tanpa fallback ke .env. Ini memberikan fleksibilitas lebih besar untuk mengelola multiple WhatsApp devices dengan token yang berbeda-beda.

## Apa yang Berubah?

### ✅ Sebelumnya
- Field `fonnte_token` di form bersifat **Optional**
- Jika tidak diisi, sistem fallback ke `FONNTE_TOKEN` di file `.env`
- Dapat membuat bot tanpa token jika ada di .env

### ✅ Sekarang
- Field `fonnte_token` di form **WAJIB (Required)**
- Setiap bot instance harus memiliki token Fonnte yang valid
- Token disimpan secara lengkap di `metadata` bot untuk penggunaan later
- Tidak ada fallback ke .env - semua token dikelola per bot

## File yang Dimodifikasi

### 1. **Controller** - `app/Http/Controllers/Admin/BotInstanceController.php`
```php
// Sebelumnya
'fonnte_token' => 'nullable|string',

// Sekarang
'fonnte_token' => 'required|string|min:10',
```

**Validasi message yang ditambahkan:**
- `'fonnte_token.required'` => Fonnte token is required to create a bot instance
- `'fonnte_token.min'` => Token appears to be invalid (too short)

### 2. **Service** - `app/Services/WhatsAppService.php`

#### Method `initializeBot()`
- Menghilangkan conditional check `if ($this->isFonnte() || $customToken)`
- Sekarang **selalu memerlukan token** (dari parameter atau .env, tapi jika .env kosong akan error)
- Token disimpan **full length** di metadata: `'token' => $token`
- Tambahan field: `'token_validated_at' => now()->toIso8601String()`

#### Method `getTokenForBot()` (BARU)
```php
protected function getTokenForBot(?BotInstance $bot = null): ?string
{
    // Ambil dari metadata bot terlebih dahulu
    if ($bot && !empty($bot->metadata['token'])) {
        return $bot->metadata['token'];
    }
    
    // Fallback ke .env jika bot tidak punya token
    return $this->token;
}
```

#### Method `sendMessageViaFonnte()`
- Menggunakan `getTokenForBot()` untuk mendapatkan token yang tepat
- Error message yang lebih jelas jika token tidak tersedia

### 3. **View** - `resources/views/admin/bots/create.blade.php`

#### Field Update
```blade
<label for="fonnte_token">
    Fonnte Token <span class="text-emerald-600">*</span>
</label>
```

#### Help Text Update
- Sebelumnya: "If empty, the system will use the token from .env file."
- Sekarang: "Enter your valid Fonnte API token. The token will be validated and stored securely for this bot instance."

#### New Info Box
Menambahkan info tentang "Per-Bot Configuration" yang menjelaskan bahwa:
- Setiap bot memerlukan token sendiri
- Memungkinkan management multiple WhatsApp numbers independently
- Tidak bergantung pada .env configuration

## Keuntungan Perubahan Ini

✅ **Multi-Device Management**: Kelola multiple WhatsApp numbers dengan token berbeda  
✅ **Token Isolation**: Setiap bot punya token tersendiri, lebih aman  
✅ **Flexible Scaling**: Mudah tambah bot baru tanpa edit .env  
✅ **No Hidden Dependencies**: Semua token terlihat di dalam aplikasi  
✅ **Better Security**: Token disimpan terenkripsi di database, bukan di .env file  

## Cara Upgrade Existing Bot

Jika Anda sudah punya bot yang menggunakan token dari .env:

### Method 1: Recreate Bot
1. Delete bot instance yang lama
2. Create bot baru dengan memasukkan token di form
3. Done!

### Method 2: Manual Update (Advanced)
```php
// Update bot metadata dengan token dari .env
$bot = BotInstance::find($botId);
$bot->metadata = array_merge($bot->metadata ?? [], [
    'token' => env('FONNTE_TOKEN'),
    'token_validated_at' => now()->toIso8601String(),
]);
$bot->save();
```

## Troubleshooting

### Q: Saya punya bot lama yang tidak ada tokennya di metadata
**A:** Update manually seperti di bagian "Manual Update" di atas, atau recreate bot dengan token baru.

### Q: Apakah masih bisa pakai token dari .env?
**A:** Ya! Jika bot tidak punya token di metadata, sistem akan fallback ke .env token. Tapi untuk best practice, masukkan token per bot.

### Q: Token saya sudah ada di form tapi form masih error?
**A:** Pastikan:
- Token cukup panjang (minimum 10 karakter)
- Token valid (sudah test di fonnte.com)
- WhatsApp device sudah connect di Fonnte

## Testing

Jalankan test yang ada untuk memastikan tidak ada regression:

```bash
# Run all bot tests
php artisan test tests/Feature/BotInstanceCreateTest.php
php artisan test tests/Feature/CreateBotInstanceIntegrationTest.php

# Or run specific test
php artisan test tests/Feature/CreateBotInstanceIntegrationTest.php --filter="store bot instance"
```

## Migration Checklist

- ✅ Update form validation (required)
- ✅ Update controller rules
- ✅ Update service `initializeBot()` logic
- ✅ Add `getTokenForBot()` helper method
- ✅ Update `sendMessageViaFonnte()` to use bot token
- ✅ Update form labels dan help text
- ✅ Add per-bot configuration info box
- ⏳ (Optional) Update existing bots dengan token di metadata

---

**Date**: January 6, 2026  
**Status**: ✅ Complete
