# Quick Commands untuk Monitor Bot WhatsApp

## 📥 Lihat Incoming Messages

### Menggunakan Artisan Command:
```bash
# Lihat 10 incoming messages terbaru
php artisan messages:incoming

# Lihat 20 incoming messages terbaru
php artisan messages:incoming --limit=20
```

### Menggunakan PHP Script:
```bash
# Lihat incoming messages dengan statistik
php show-messages.php

# Custom limit
php show-messages.php 30
```

## 💬 Lihat Semua Messages (Incoming & Outgoing)

```bash
# Lihat 20 messages terbaru
php artisan messages:all

# Custom limit
php artisan messages:all --limit=50

# Filter by phone number
php artisan messages:all --phone=628979213614
```

## 📊 Database Query Langsung

```bash
# Connect ke database
php artisan tinker

# Lihat incoming messages
App\Models\ConversationLog::where('direction', 'incoming')->latest()->limit(5)->get();

# Lihat by phone number
App\Models\ConversationLog::whereHas('whatsappUser', fn($q) => $q->where('phone_number', '628979213614'))->get();

# Count messages
App\Models\ConversationLog::where('direction', 'incoming')->count();
```

## 🔍 Cek Log Real-time

```bash
# Windows
Get-Content storage/logs/laravel.log -Wait -Tail 50

# Linux/Mac
tail -f storage/logs/laravel.log

# Filter webhook only
tail -f storage/logs/laravel.log | grep "Webhook\|Fonnte"
```

## 📱 Test Webhook

```bash
# Test lokal
php test-webhook.php

# Test dengan curl
curl -X POST http://localhost:8000/api/webhook/whatsapp \
  -H "Content-Type: application/json" \
  -d '{"device":"62851775578","from":"628123456789@c.us","message":"test","type":"text"}'
```

## ✅ Verifikasi Token

```bash
php test-fonnte-token.php
```

## 🚀 Start Development Server

```bash
php artisan serve
```

## 🌐 Setup Webhook untuk Development (dengan ngrok)

### Windows:
```bash
setup-ngrok.bat
```

### Linux/Mac:
```bash
./setup-ngrok.sh
```

Manual:
```bash
ngrok http 8000
# Copy URL HTTPS dan set di Fonnte dashboard
# Contoh: https://abc123.ngrok-free.app/api/webhook/whatsapp
```

## 📋 Status Check

```bash
# Cek routes
php artisan route:list --path=webhook

# Cek auto-reply configs
php artisan tinker --execute="App\Models\AutoReplyConfig::where('is_active', 1)->get(['trigger', 'response']);"

# Clear cache
php artisan config:cache
php artisan cache:clear
```
