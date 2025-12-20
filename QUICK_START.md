# Quick Start Guide - WhatsApp Bot dengan QR Code

Panduan singkat untuk setup dan menjalankan WhatsApp bot dengan QR code scanning.

## 📋 Persiapan

Pastikan sudah terinstall:
- PHP 8.2+
- Composer
- Node.js 18+
- Database (SQLite/MySQL)

## ⚡ Setup Cepat (5 Menit)

### 1. Install Dependencies

```bash
# Clone repository
git clone <repository-url>
cd dukcapil-wa

# Install Laravel dependencies
composer install
npm install

# Install bot dependencies
cd bot
npm install
cd ..
```

### 2. Konfigurasi

```bash
# Setup Laravel
cp .env.example .env
php artisan key:generate

# Generate secure token
php -r "echo bin2hex(random_bytes(32)).PHP_EOL;" > token.txt
# Copy token dari token.txt ke .env

# Edit .env, tambahkan:
BOT_API_TOKEN=<token-dari-file-token.txt>
WHATSAPP_BOT_SERVER_URL=http://localhost:3000

# Setup bot server
cd bot
cp .env.example .env
# Edit bot/.env, gunakan token yang sama:
BOT_API_TOKEN=<token-yang-sama>
cd ..
```

### 3. Setup Database

```bash
# Buat database SQLite
touch database/database.sqlite

# Jalankan migrations
php artisan migrate --seed
```

### 4. Jalankan Sistem

Buka 3 terminal:

**Terminal 1 - Laravel:**
```bash
php artisan serve
```

**Terminal 2 - Queue Worker:**
```bash
php artisan queue:work
```

**Terminal 3 - Bot Server:**
```bash
cd bot
npm start
```

**Atau gunakan satu command (recommended):**
```bash
composer run dev
```

## 🎯 Setup Bot WhatsApp

### 1. Login ke Admin
- Buka: http://localhost:8000/admin
- Email: `admin@dukcapil.ponorogo.go.id`
- Password: `password`

### 2. Buat Bot Baru
1. Klik **"Bot Management"** di menu
2. Klik **"Add New Bot"**
3. Isi:
   - Bot Name: `Bot Utama DUKCAPIL`
   - Bot ID: `bot-dukcapil-1` (harus unique, lowercase)
4. Klik **"Create Bot"**

### 3. Scan QR Code
1. Tunggu QR code muncul (±10 detik)
2. Buka WhatsApp di HP
3. Buka Menu (⋮) → **Linked Devices**
4. Tap **"Link a Device"**
5. Scan QR code di layar
6. Tunggu status berubah **"Connected"** ✅

### 4. Test Bot
Kirim pesan ke nomor WhatsApp yang sudah di-scan. Bot akan otomatis menerima pesan dan tercatat di admin dashboard!

## 🔧 Troubleshooting Cepat

### QR Code tidak muncul?
```bash
# Check bot server status
cd bot
npm start

# Check logs
tail -f ../storage/logs/laravel.log
```

### Bot disconnect?
```bash
# Di admin dashboard:
# 1. Buka bot detail
# 2. Klik "Reinitialize Bot"
# 3. Scan QR code lagi
```

### Token mismatch error?
```bash
# Pastikan BOT_API_TOKEN sama di:
# - Laravel .env
# - bot/.env
```

## 📚 Next Steps

- Baca [BOT_SETUP_GUIDE.md](BOT_SETUP_GUIDE.md) untuk setup production
- Baca [DUKCAPIL_README.md](DUKCAPIL_README.md) untuk fitur lengkap
- Setup multiple bots dengan Bot ID berbeda

## 🆘 Bantuan

**Bot tidak menerima pesan?**
- Pastikan queue worker running
- Check bot status harus "Connected"

**Pesan tidak terkirim?**
- Pastikan bot status "Connected"
- Check format nomor: 628123456789

**Error lainnya?**
- Check logs: `storage/logs/laravel.log`
- Check bot logs di terminal bot server

## 🎉 Selamat!

Bot WhatsApp Anda sudah siap digunakan! 

Untuk production deployment, ikuti panduan lengkap di [BOT_SETUP_GUIDE.md](BOT_SETUP_GUIDE.md).

---
Made with ❤️ for DUKCAPIL Ponorogo
