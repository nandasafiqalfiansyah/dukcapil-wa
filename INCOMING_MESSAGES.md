# 📬 Incoming Messages Dashboard

## Overview

Semua data chat WhatsApp yang diterima oleh bot sudah terintegrasi dan bisa dilihat melalui beberapa cara:

## 1️⃣ Dashboard Website (UI)

### Akses Dashboard
1. Buka browser: `http://localhost:8000/admin/conversations`
2. Login dengan credentials Anda
3. Anda akan melihat tabel lengkap incoming messages

### Fitur Dashboard

#### Statistik Real-time
- 📊 **Total Messages**: Jumlah semua pesan
- 📥 **Incoming**: Pesan yang diterima dari user
- 📤 **Outgoing**: Pesan yang dikirim bot (auto-reply)
- 👥 **Total Users**: Jumlah unique users
- 📅 **Today**: Pesan hari ini

#### Filter & Search
- **Direction**: Filter masuk/keluar
- **User**: Pilih user tertentu
- **Phone Number**: Cari berdasarkan nomor
- **Message Type**: Tipe pesan (text, image, document, audio)
- **Date Range**: Filter tanggal
- **Status**: Delivered, Sent, Pending
- **Search**: Cari dalam isi pesan

#### Tabel Messages
| Kolom | Keterangan |
|-------|-----------|
| ID | ID unik pesan |
| Direction | 📥 Incoming atau 📤 Outgoing |
| Sender | Nomor WhatsApp pengirim |
| Name | Nama pengguna |
| Message | Isi pesan (truncated) |
| Type | Tipe pesan |
| Timestamp | Tanggal & jam pesan |
| Status | Status pengiriman |
| Action | Link untuk detail |

#### Detail View
Klik tombol "View →" untuk melihat:
- Pesan lengkap
- Info pengirim detail
- Seluruh conversation thread
- Metadata pesan

---

## 2️⃣ Command Line (CLI)

### Lihat Incoming Messages
```bash
# Lihat 10 incoming messages terbaru (default)
php artisan messages:incoming

# Lihat 20 incoming messages
php artisan messages:incoming --limit=20

# Lihat 50 incoming messages
php artisan messages:incoming --limit=50
```

Output:
```
📥 INCOMING MESSAGES (10 recent)
═══════════════════════════════════════════════════════════════════════

| ID | Device      | Sender       | Name        | Message     | Type | Timestamp         | Status    |
+----+-------------+--------------+-------------+-------------+------+-------------------+-----------+
| 41 | 62851775578 | 628979213614 | Nanda Safiq | ping        | text | 30 Jan 2026 03:14 | delivered |
| 40 | 62851775578 | 628979213614 | Nanda Safiq | test        | text | 30 Jan 2026 02:48 | delivered |
...

✅ Total incoming messages: 30
📤 Total outgoing messages: 12
```

### Lihat Semua Messages (Incoming + Outgoing)
```bash
# Lihat 20 messages terbaru
php artisan messages:all

# Custom limit
php artisan messages:all --limit=50

# Filter by phone
php artisan messages:all --phone=628979213614
```

### Script PHP
```bash
# Lihat dengan statistik & user aktif
php show-messages.php

# Custom limit
php show-messages.php 30
```

Output:
```
📥 INCOMING MESSAGES (Latest 15)
═══════════════════════════════════════════════════════════════════════

ID    | Device       | Sender          | Name         | Message               | Type     | Timestamp
──────────────────────────────────────────────────────────────────────
41    | 62851775578  | 628979213614    | Nanda Safiq  | ping                  | text     | 30 Jan 2026 03:14
40    | 62851775578  | 628979213614    | Nanda Safiq  | ping                  | text     | 30 Jan 2026 03:10

📊 Statistics:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
📥 Total Incoming Messages: 30
📤 Total Outgoing Messages: 12
👥 Total WhatsApp Users: 5
💬 Total Conversations: 42

👥 Recent Active Users:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
• 628979213614 (Nanda Safiq) - 24 messages - Last: 2 minutes ago
• 628123456789 (Test User) - 2 messages - Last: 12 minutes ago
```

---

## 3️⃣ Database Query (Tinker)

### Interactive Mode
```bash
# Buka tinker interactive
php artisan tinker
```

### Contoh Queries
```php
# Lihat 5 incoming messages terbaru
App\Models\ConversationLog::where('direction', 'incoming')->latest()->limit(5)->get();

# Lihat pesan dari user tertentu
App\Models\ConversationLog::whereHas('whatsappUser', fn($q) => 
    $q->where('phone_number', '628979213614')
)->get();

# Count pesan per user
App\Models\ConversationLog::selectRaw('whatsapp_user_id, count(*) as count')
    ->groupBy('whatsapp_user_id')
    ->with('whatsappUser')
    ->get();

# Pesan hari ini
App\Models\ConversationLog::whereDate('created_at', today())->get();

# Statistik
[
    'incoming' => App\Models\ConversationLog::where('direction', 'incoming')->count(),
    'outgoing' => App\Models\ConversationLog::where('direction', 'outgoing')->count(),
    'users' => App\Models\WhatsAppUser::count(),
]
```

---

## 4️⃣ Real-time Log Monitoring

```bash
# Monitor webhook in real-time
tail -f storage/logs/laravel.log | grep "Webhook\|Fonnte"

# Monitor all activities
tail -f storage/logs/laravel.log

# Filter by specific user
tail -f storage/logs/laravel.log | grep "628979213614"
```

---

## Data Struktur

### Table: conversation_logs

```sql
SELECT 
    id,
    bot_instance_id,
    whatsapp_user_id,
    message_id,
    direction,           -- 'incoming' atau 'outgoing'
    message_content,
    message_type,        -- 'text', 'image', 'document', 'audio'
    status,              -- 'delivered', 'sent', 'pending'
    intent,
    confidence,
    metadata,
    created_at,
    updated_at
FROM conversation_logs
ORDER BY created_at DESC;
```

### Table: whatsapp_users

```sql
SELECT 
    id,
    phone_number,        -- Nomor WhatsApp (628123456789)
    name,                -- Nama user
    status,              -- 'active', 'inactive'
    metadata,
    created_at,
    updated_at
FROM whatsapp_users;
```

---

## Example Data Format

### Incoming Message Record
```json
{
  "id": 41,
  "direction": "incoming",
  "whatsapp_user_id": 1,
  "whatsappUser": {
    "phone_number": "628979213614",
    "name": "Nanda Safiq"
  },
  "message_content": "ping",
  "message_type": "text",
  "status": "delivered",
  "created_at": "2026-01-30T03:14:00"
}
```

### Outgoing Message Record (Auto-reply)
```json
{
  "id": 42,
  "direction": "outgoing",
  "whatsapp_user_id": 1,
  "whatsappUser": {
    "phone_number": "628979213614",
    "name": "Nanda Safiq"
  },
  "message_content": "🤖 *Pong!*\n\nBot DUKCAPIL Ponorogo aktif dan berfungsi dengan baik.\n\nWaktu: 30/01/2026 03:14:27",
  "message_type": "text",
  "status": "sent",
  "created_at": "2026-01-30T03:14:27"
}
```

---

## Troubleshooting

### Messages Tidak Muncul di Dashboard

1. **Cek webhook sudah dikonfigurasi di Fonnte**
   ```bash
   php test-fonnte-token.php
   ```

2. **Cek log untuk error**
   ```bash
   tail -f storage/logs/laravel.log | grep "ERROR"
   ```

3. **Cek database connection**
   ```bash
   php artisan db
   ```

4. **Clear cache**
   ```bash
   php artisan cache:clear
   php artisan config:cache
   ```

### Dashboard Lambat

1. **Pagination sudah unlimited (25 per page), bisa disesuaikan di controller**
2. **Tambah index di database untuk phone_number:**
   ```sql
   CREATE INDEX idx_whatsapp_phone ON whatsapp_users(phone_number);
   CREATE INDEX idx_conv_direction ON conversation_logs(direction);
   CREATE INDEX idx_conv_user ON conversation_logs(whatsapp_user_id);
   ```

---

## API Endpoint (Future)

Jika ingin membuat REST API:

```php
// GET /api/conversations
// GET /api/conversations?direction=incoming&limit=20
// GET /api/conversations/{id}
// GET /api/conversations/user/{phoneNumber}
```

---

## Summary

✅ **3 Cara Akses Incoming Messages:**
1. **Dashboard Web** - UI yang user-friendly, filter & search lengkap
2. **CLI Commands** - Cepat untuk monitoring, bisa di-pipe ke tools lain
3. **Database Queries** - Full control, bisa custom logic

Semua data tercatat lengkap dengan timestamp, status, dan metadata lengkap!
