# DUKCAPIL Ponorogo WhatsApp Chatbot - Admin Dashboard

Sistem administrasi berbasis web untuk DUKCAPIL Ponorogo WhatsApp Chatbot menggunakan Laravel sebagai framework backend utama.

## Fitur Utama

### 1. Autentikasi & Otorisasi
- Login aman dengan Laravel Breeze
- Role-based access control (Admin, Officer, Viewer)
- Manajemen sesi dan token
- Validasi email dan reset password

### 2. Manajemen Pengguna WhatsApp
- Daftar pengguna WhatsApp yang terverifikasi
- Verifikasi pengguna berdasarkan NIK
- Update status pengguna (active, blocked, pending)
- Riwayat percakapan per pengguna

### 3. Log Percakapan
- Menyimpan semua percakapan masuk dan keluar
- Filter berdasarkan tanggal, arah pesan, dan intent
- Dukungan berbagai tipe pesan (text, image, document, audio, video)
- Status pengiriman (sent, delivered, read, failed)

### 4. Manajemen Permintaan Layanan
- Tracking permintaan layanan (KTP, KK, Akta, dll)
- Status permintaan (pending, in_review, processing, approved, rejected, completed)
- Sistem prioritas (low, normal, high, urgent)
- Assignment petugas
- **Eskalasi ke petugas** untuk penanganan cepat
- Catatan internal per permintaan

### 5. Validasi Dokumen
- Pre-validasi dokumen yang diupload
- Status validasi (pending, valid, invalid, needs_review)
- Download dokumen
- Catatan validasi

### 6. Notifikasi WhatsApp
- Pengiriman notifikasi otomatis ke pengguna
- Status notifikasi dan retry mechanism
- Tracking delivery dan read status

### 7. Audit Logging
- Pencatatan semua aktivitas admin
- Tracking perubahan data
- IP address dan user agent logging

### 8. Dashboard & Reporting
- Statistik real-time
- Grafik permintaan berdasarkan status dan jenis layanan
- Daftar permintaan terbaru
- Indikator permintaan yang dieskalasi

## Teknologi

- **Backend**: Laravel 12
- **Database**: SQLite (default, dapat diganti ke MySQL/PostgreSQL)
- **Frontend**: Blade Templates + Tailwind CSS
- **Authentication**: Laravel Breeze
- **Testing**: Pest
- **WhatsApp API**: Facebook Graph API

## Instalasi

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM

### Langkah Instalasi

1. Clone repository
```bash
git clone <repository-url>
cd dukcapil-wa
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di `.env`
```env
DB_CONNECTION=sqlite
```

5. Konfigurasi WhatsApp Business API di `.env`
```env
WHATSAPP_API_URL=https://graph.facebook.com/v18.0
WHATSAPP_ACCESS_TOKEN=your_access_token
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id
WHATSAPP_VERIFY_TOKEN=your_verify_token
```

6. Jalankan migrasi dan seeder
```bash
php artisan migrate --seed
```

7. Build assets
```bash
npm run build
```

8. Jalankan server
```bash
php artisan serve
```

## Akun Default

Setelah seeding, gunakan akun berikut untuk login:

**Admin:**
- Email: admin@dukcapil.ponorogo.go.id
- Password: password

**Officer:**
- Email: officer@dukcapil.ponorogo.go.id
- Password: password

**⚠️ PENTING: Ganti password default setelah login pertama kali!**

## Webhook WhatsApp

Untuk menerima pesan dari WhatsApp Business API, konfigurasikan webhook di Meta Developer Console:

- **Webhook URL**: `https://your-domain.com/api/webhook/whatsapp`
- **Verify Token**: Sesuaikan dengan `WHATSAPP_VERIFY_TOKEN` di `.env`
- **Subscribe**: messages, message_status

## Struktur Database

### Tabel Utama:
- `users` - Pengguna admin/officer
- `whatsapp_users` - Pengguna WhatsApp
- `conversation_logs` - Log percakapan
- `service_requests` - Permintaan layanan
- `document_validations` - Validasi dokumen
- `notifications` - Notifikasi
- `audit_logs` - Log audit aktivitas

## Keamanan

### Implementasi Keamanan:
- ✅ CSRF protection pada semua form
- ✅ Password hashing dengan bcrypt
- ✅ Input validation dan sanitization
- ✅ Role-based access control
- ✅ Audit logging untuk tracking aktivitas
- ✅ Session management
- ✅ Active user checking middleware
- ✅ Secure file upload (untuk dokumen)

### Best Practices:
- Gunakan HTTPS di production
- Set rate limiting pada API endpoints
- Enkripsi data sensitif
- Regular backup database
- Update dependencies secara berkala

## Testing

Jalankan test:
```bash
php artisan test
```

## Development

Untuk development dengan hot reload:
```bash
composer run dev
```

Atau jalankan service secara terpisah:
```bash
# Terminal 1 - Server
php artisan serve

# Terminal 2 - Queue Worker
php artisan queue:work

# Terminal 3 - Vite Dev Server
npm run dev
```

## Linting & Code Style

```bash
# Format code dengan Laravel Pint
./vendor/bin/pint
```

## Deployment

### Checklist Production:
1. ✅ Set `APP_ENV=production` di `.env`
2. ✅ Set `APP_DEBUG=false` di `.env`
3. ✅ Generate production key: `php artisan key:generate`
4. ✅ Optimize autoloader: `composer install --optimize-autoloader --no-dev`
5. ✅ Cache config: `php artisan config:cache`
6. ✅ Cache routes: `php artisan route:cache`
7. ✅ Cache views: `php artisan view:cache`
8. ✅ Build production assets: `npm run build`
9. ✅ Setup queue worker (supervisor/systemd)
10. ✅ Setup scheduled tasks (cron)
11. ✅ Configure web server (Nginx/Apache)
12. ✅ Setup SSL certificate
13. ✅ Configure firewall rules

## Dokumentasi API

### Webhook Endpoints

#### Verify Webhook
```
GET /api/webhook/whatsapp
Query Parameters:
- hub.mode=subscribe
- hub.verify_token=YOUR_VERIFY_TOKEN
- hub.challenge=CHALLENGE_STRING
```

#### Receive Messages
```
POST /api/webhook/whatsapp
Body: WhatsApp webhook payload
```

## Troubleshooting

### Database Issues
```bash
php artisan migrate:fresh --seed
```

### Cache Issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Permission Issues (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

MIT License

## Support

Untuk bantuan dan pertanyaan, hubungi tim development DUKCAPIL Ponorogo.

---

**Dikembangkan dengan ❤️ untuk DUKCAPIL Ponorogo**
