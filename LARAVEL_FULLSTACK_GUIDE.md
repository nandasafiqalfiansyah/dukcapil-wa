# WhatsApp Bot - Full Laravel Integration

## 🎯 Overview

Sistem WhatsApp Bot sekarang terintegrasi penuh dengan Laravel tanpa memerlukan server Node.js terpisah. Semua fungsi bot dijalankan langsung dari Laravel menggunakan PHP.

## ⚡ Quick Start

### 1. Install Dependencies

```bash
# Install Laravel dependencies
composer install

# Install Node.js dependencies (untuk WhatsApp Web.js)
cd bot
npm install
cd ..

# Install frontend dependencies
npm install
```

### 2. Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Setup database
php artisan migrate --seed
```

### 3. Run Application

```bash
# Start Laravel dan frontend
npm run start

# Atau manual:
# Terminal 1
php artisan serve

# Terminal 2  
npm run dev
```

## 📱 Menggunakan Bot

### Melalui Web Interface

1. Login ke admin dashboard
2. Buka menu "Bot Management"
3. Klik "Add New Device"
4. Isi form:
   - **Bot Name**: Nama bot (contoh: "DUKCAPIL Bot 1")
   - **Bot ID**: ID unik (contoh: "bot-1")
5. Klik "Create Bot"
6. Scan QR code yang muncul dengan WhatsApp di HP Anda
7. Bot akan otomatis connect!

### Melalui Command Line

```bash
# List semua bot
php artisan whatsapp:bot list

# Start bot tertentu
php artisan whatsapp:bot start --bot-id=bot-1

# Start semua bot aktif
php artisan whatsapp:bot start-all

# Cek status bot
php artisan whatsapp:bot status --bot-id=bot-1

# Stop bot
php artisan whatsapp:bot stop --bot-id=bot-1

# Stop semua bot
php artisan whatsapp:bot stop-all
```

## 🔧 Arsitektur

### Full Laravel Stack

```
Laravel Application
├── WhatsAppBotManager        # Service untuk manage bot instances
├── WhatsAppService            # Service untuk WhatsApp operations
├── BotInstanceController      # Controller untuk web interface
├── WhatsAppBotCommand         # Artisan command untuk CLI
└── Bot Runtime
    ├── bot-{id}.js            # Auto-generated bot script
    └── storage/
        ├── whatsapp-sessions/ # Session data untuk setiap bot
        └── logs/              # Log file untuk setiap bot
```

### Keuntungan Pendekatan Ini

✅ **Single Stack**: Semua dalam Laravel, tidak perlu manage server terpisah  
✅ **Auto Start**: Bot otomatis start saat create dari web  
✅ **CLI Management**: Control bot via artisan command  
✅ **Session Management**: Session tersimpan di `storage/app/whatsapp-sessions/`  
✅ **Logging**: Log terpisah per bot di `storage/logs/bot-{id}.log`  
✅ **Auto Reconnect**: Bot otomatis reconnect setelah server restart  

## 📂 File Structure

```
app/
├── Console/Commands/
│   └── WhatsAppBotCommand.php          # CLI management
├── Services/
│   ├── WhatsAppBotManager.php          # Bot lifecycle manager
│   └── WhatsAppService.php              # WhatsApp operations
├── Http/Controllers/Admin/
│   └── BotInstanceController.php        # Web UI controller
└── Models/
    └── BotInstance.php                  # Bot model

storage/
├── app/whatsapp-sessions/               # Bot sessions
│   ├── session-bot-1/                   # Session untuk bot-1
│   ├── qr-bot-1.txt                     # QR code untuk bot-1
│   └── status-bot-1.json                # Status bot-1
└── logs/
    └── bot-bot-1.log                    # Log bot-1

bot-runtime/                              # Runtime scripts (auto-generated)
└── bot-{id}.js                          # Bot script per instance
```

## 🚀 Production Deployment

### With Supervisor (Recommended)

```ini
[program:dukcapil-whatsapp-bots]
directory=/path/to/dukcapil-wa
command=php artisan whatsapp:bot start-all
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/dukcapil-wa/storage/logs/bots.log
```

### Manual Start on Boot

Add to `/etc/rc.local` or systemd:

```bash
cd /path/to/dukcapil-wa
php artisan whatsapp:bot start-all
```

## 🔍 Monitoring

### Check Bot Status

```bash
# Via CLI
php artisan whatsapp:bot list

# Via Log
tail -f storage/logs/bot-bot-1.log

# Via Web
# Login ke admin → Bot Management
```

### Status Files

Setiap bot menyimpan status real-time di:
```
storage/app/whatsapp-sessions/status-{bot-id}.json
```

Format:
```json
{
  "bot_id": "bot-1",
  "status": "connected",
  "qr_code": null,
  "timestamp": "2025-12-26T10:30:00.000Z"
}
```

## 🐛 Troubleshooting

### QR Code Tidak Muncul

1. Pastikan Node.js terinstall: `node --version`
2. Install dependencies: `cd bot && npm install`
3. Check log: `tail -f storage/logs/bot-{bot-id}.log`

### Bot Tidak Connect

1. Clear session: `php artisan whatsapp:bot stop --bot-id=bot-1`
2. Logout: Klik "Logout Device" di web interface
3. Reinitialize: Klik "Generate QR Code"
4. Scan ulang QR code

### Bot Disconnect Setelah Beberapa Hari

WhatsApp session expire setelah 15-20 hari. Solusi:
- Scan ulang QR code
- Atau set auto-reconnect di production

## 🔐 Security

- Bot API token tidak diperlukan lagi (no external API)
- Session files protected dengan file permissions
- QR codes auto-deleted setelah connection
- Process isolation per bot instance

## 📝 Notes

- Setiap bot berjalan sebagai Node.js process terpisah
- Bot otomatis restart jika crash (gunakan Supervisor di production)
- Session files harus di-backup untuk avoid rescan
- Maximum 4 linked devices per WhatsApp account (WhatsApp limitation)

---

**Developed with ❤️ for DUKCAPIL Ponorogo**
