# WhatsApp Bot Setup Guide

## Panduan Setup Bot WhatsApp dengan QR Code

Sistem ini menggunakan WhatsApp Web dengan QR code scanning untuk menghubungkan bot WhatsApp. Setiap bot instance dapat dikonfigurasi secara independen.

## Persyaratan Sistem

### Laravel Backend
- PHP 8.2 atau lebih tinggi
- Composer
- SQLite/MySQL/PostgreSQL

### Bot Server
- Node.js 18.x atau lebih tinggi
- NPM atau Yarn
- Chrome/Chromium (untuk puppeteer)

## Instalasi

### 1. Setup Laravel Backend

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database
php artisan migrate

# Generate secure API token untuk bot
# Gunakan token yang kuat, misalnya:
# php -r "echo bin2hex(random_bytes(32));"
# Tambahkan ke .env:
# BOT_API_TOKEN=your-secure-random-token-here
```

### 2. Setup Bot Server

```bash
# Masuk ke direktori bot
cd bot

# Install dependencies
npm install

# Copy environment file
cp .env.example .env

# Edit .env dan sesuaikan:
# BOT_PORT=3000
# BOT_API_TOKEN=same-token-as-in-laravel-env
# APP_URL=http://localhost:8000
```

### 3. Konfigurasi Environment Variables

#### Laravel (.env)
```env
# WhatsApp Bot Server Configuration
WHATSAPP_BOT_SERVER_URL=http://localhost:3000
BOT_API_TOKEN=your-secure-api-token-here
```

#### Bot Server (bot/.env)
```env
# WhatsApp Bot Server Configuration
BOT_PORT=3000
BOT_API_TOKEN=same-token-as-laravel
APP_URL=http://localhost:8000
```

**PENTING:** Pastikan `BOT_API_TOKEN` sama di kedua file!

## Menjalankan Sistem

### Development (Local)

Buka 3 terminal terpisah:

**Terminal 1 - Laravel Server:**
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

### Production

#### 1. Setup Laravel dengan Web Server (Nginx/Apache)

**Nginx Configuration:**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/dukcapil-wa/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 2. Setup Bot Server dengan PM2

```bash
# Install PM2 globally
npm install -g pm2

# Start bot server
cd /path/to/dukcapil-wa/bot
pm2 start server.js --name "dukcapil-whatsapp-bot"

# Enable startup on boot
pm2 startup
pm2 save

# Monitor logs
pm2 logs dukcapil-whatsapp-bot

# Other useful commands
pm2 status              # Check status
pm2 restart dukcapil-whatsapp-bot  # Restart
pm2 stop dukcapil-whatsapp-bot     # Stop
```

#### 3. Setup Queue Worker dengan Supervisor

**Create supervisor config: `/etc/supervisor/conf.d/dukcapil-queue.conf`**
```ini
[program:dukcapil-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/dukcapil-wa/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/dukcapil-wa/storage/logs/queue-worker.log
stopwaitsecs=3600
```

```bash
# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start dukcapil-queue:*
```

## Cara Menggunakan Bot

### 1. Login ke Admin Dashboard

Buka browser dan akses: `http://your-domain.com/admin`

Login dengan credentials admin:
- Email: admin@dukcapil.ponorogo.go.id
- Password: password

### 2. Buat Bot Instance Baru

1. Klik menu "Bot Management" atau akses: `http://your-domain.com/admin/bots`
2. Klik tombol "Add New Bot"
3. Isi form:
   - **Bot Name**: Nama yang mudah diingat (misal: "DUKCAPIL Bot Utama")
   - **Bot ID**: ID unik dengan format lowercase (misal: "bot-1", "dukcapil-main")
4. Klik "Create Bot"

### 3. Scan QR Code

Setelah bot dibuat, Anda akan melihat halaman dengan QR code.

**Cara scan:**
1. Buka WhatsApp di HP Anda
2. Tap Menu (⋮) atau Settings
3. Pilih "Linked Devices" / "Perangkat Tertaut"
4. Tap "Link a Device" / "Tautkan Perangkat"
5. Arahkan kamera HP ke QR code di layar
6. Tunggu hingga status berubah menjadi "Connected"

### 4. Bot Siap Digunakan!

Setelah connected:
- Bot akan otomatis menerima dan membalas pesan
- Semua pesan akan tercatat di "Conversation Logs"
- Bot akan tetap connected meskipun halaman ditutup

## Mengelola Multiple Bots

Anda bisa menjalankan beberapa bot secara bersamaan:

1. Buat bot instance baru dengan Bot ID berbeda
2. Scan QR code untuk setiap bot dengan HP WhatsApp yang berbeda
3. Setiap bot akan menangani pesan dari nomornya masing-masing

**Catatan:** Satu nomor WhatsApp hanya bisa digunakan untuk satu bot instance.

## Troubleshooting

### QR Code tidak muncul

1. Pastikan bot server berjalan: `pm2 status` atau cek terminal
2. Check logs bot server: `pm2 logs dukcapil-whatsapp-bot`
3. Pastikan BOT_API_TOKEN sama di Laravel dan Bot Server
4. Restart bot server: `pm2 restart dukcapil-whatsapp-bot`

### Bot disconnect setelah beberapa waktu

1. QR code WhatsApp expired (15-20 hari), scan ulang QR code
2. WhatsApp Web session berakhir di HP
3. Bot server restart (normal, bot akan reconnect otomatis)

### Pesan tidak terkirim

1. Pastikan bot status "Connected"
2. Check queue worker berjalan: `php artisan queue:work`
3. Check logs: `storage/logs/laravel.log`
4. Pastikan format nomor benar (contoh: 628123456789)

### Bot tidak menerima pesan

1. Check bot server logs: `pm2 logs`
2. Pastikan webhook endpoint bisa diakses dari bot server
3. Test koneksi: `curl http://localhost:8000/api/bot/webhook`

### Dependencies Puppeteer Error

Jika ada error saat install puppeteer, install dependencies Chrome:

**Ubuntu/Debian:**
```bash
sudo apt-get update
sudo apt-get install -y \
    ca-certificates \
    fonts-liberation \
    libappindicator3-1 \
    libasound2 \
    libatk-bridge2.0-0 \
    libatk1.0-0 \
    libcups2 \
    libdbus-1-3 \
    libdrm2 \
    libgbm1 \
    libgtk-3-0 \
    libnspr4 \
    libnss3 \
    libx11-xcb1 \
    libxcomposite1 \
    libxdamage1 \
    libxrandr2 \
    xdg-utils
```

## Maintenance

### Backup Session Data

Session WhatsApp disimpan di `bot/.wwebjs_auth/`. Backup folder ini untuk restore bot tanpa scan QR ulang:

```bash
# Backup
tar -czf wwebjs_backup_$(date +%Y%m%d).tar.gz bot/.wwebjs_auth/

# Restore
tar -xzf wwebjs_backup_YYYYMMDD.tar.gz -C bot/
```

### Update Bot Server

```bash
cd bot
npm update
pm2 restart dukcapil-whatsapp-bot
```

### Monitoring

**Check bot status:**
```bash
pm2 status
pm2 logs dukcapil-whatsapp-bot --lines 100
```

**Check Laravel logs:**
```bash
tail -f storage/logs/laravel.log
```

**Check database:**
```bash
php artisan tinker
>>> App\Models\BotInstance::all();
>>> App\Models\ConversationLog::latest()->take(10)->get();
```

## Security Best Practices

1. **Gunakan BOT_API_TOKEN yang kuat** (minimal 32 karakter random)
2. **Aktifkan HTTPS** di production
3. **Firewall**: Hanya buka port yang diperlukan (80, 443)
4. **Update dependencies** secara berkala
5. **Backup session data** secara rutin
6. **Monitor logs** untuk aktivitas mencurigakan
7. **Batasi akses admin** hanya ke IP tertentu jika memungkinkan

## Support

Untuk bantuan lebih lanjut:
- Check logs: `storage/logs/laravel.log` dan `pm2 logs`
- Review dokumentasi WhatsApp Web.js: https://wwebjs.dev/
- Hubungi tim development DUKCAPIL Ponorogo

---

**Dikembangkan dengan ❤️ untuk DUKCAPIL Ponorogo**
