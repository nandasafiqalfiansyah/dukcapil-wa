# Auto-Reply Feature Documentation

## Overview
Sistem auto-reply memungkinkan bot WhatsApp untuk secara otomatis membalas pesan dengan kata kunci tertentu. Fitur ini sangat berguna untuk memberikan respons cepat terhadap pertanyaan umum seperti "ping", "test", "halo", dll.

## Fitur Utama

### 1. Dashboard Tracking Bot
Dashboard kini menampilkan status bot WhatsApp yang terhubung:
- **Total bot** yang terdaftar
- **Bot aktif** yang sedang terhubung
- **Status real-time** setiap bot (Connected, Disconnected, QR Generated, dll)
- **Informasi koneksi** terakhir
- **Nomor telepon** yang terhubung

Akses: Menu **Dashboard** di admin panel

### 2. Manajemen Auto-Reply
Admin dapat mengkonfigurasi balasan otomatis melalui antarmuka web:
- **Trigger**: Kata kunci yang memicu balasan (contoh: ping, test, halo)
- **Response**: Pesan balasan yang akan dikirim
- **Priority**: Urutan pencocokan (lebih tinggi = dicek lebih dulu)
- **Status**: Aktif/Nonaktif
- **Case Sensitive**: Apakah trigger memperhatikan huruf besar/kecil

Akses: Menu **Auto-Reply** di admin panel

### 3. Placeholder Dinamis
Response dapat menggunakan placeholder yang akan diganti secara dinamis:
- `{{timestamp}}` - Tanggal dan waktu lengkap (contoh: 20/12/2025 15:30:45)
- `{{date}}` - Tanggal saja (contoh: 20/12/2025)
- `{{time}}` - Waktu saja (contoh: 15:30:45)

Contoh penggunaan:
```
🤖 *Pong!*

Bot DUKCAPIL Ponorogo aktif dan berfungsi dengan baik.

Waktu: {{timestamp}}
```

## Cara Menggunakan

### Menambah Auto-Reply Baru

1. Login ke admin panel sebagai **Admin**
2. Klik menu **Auto-Reply** di navigasi
3. Klik tombol **Add Auto-Reply**
4. Isi form:
   - **Trigger**: Masukkan kata kunci (contoh: "info")
   - **Response**: Masukkan pesan balasan
   - **Priority**: Tentukan prioritas (0-1000, default: 50)
   - **Active**: Centang untuk mengaktifkan
   - **Case Sensitive**: Centang jika ingin trigger case-sensitive
5. Klik **Create Auto-Reply**

### Mengedit Auto-Reply

1. Buka menu **Auto-Reply**
2. Klik **Edit** pada auto-reply yang ingin diubah
3. Update informasi yang diperlukan
4. Klik **Update Auto-Reply**

### Mengaktifkan/Menonaktifkan Auto-Reply

Klik badge status (Active/Inactive) pada daftar auto-reply untuk toggle status.

### Menghapus Auto-Reply

1. Buka menu **Auto-Reply**
2. Klik **Delete** pada auto-reply yang ingin dihapus
3. Konfirmasi penghapusan

## Default Auto-Replies

Sistem dilengkapi dengan auto-reply default:

| Trigger | Response | Priority |
|---------|----------|----------|
| ping | Pong! Bot aktif | 100 |
| test | Bot aktif dan siap melayani | 90 |
| halo | Selamat datang di DUKCAPIL | 80 |
| hai | Selamat datang di DUKCAPIL | 80 |
| help | Daftar layanan yang tersedia | 70 |
| info | Informasi kontak DUKCAPIL | 60 |

## Technical Details

### Database Schema

**Table: `auto_reply_configs`**
```sql
- id (bigint, primary key)
- trigger (string, unique) - Kata kunci trigger
- response (text) - Pesan balasan
- is_active (boolean) - Status aktif
- priority (integer) - Prioritas pencocokan
- case_sensitive (boolean) - Case sensitive matching
- created_at (timestamp)
- updated_at (timestamp)
```

### Cara Kerja

1. Bot menerima pesan masuk dari WhatsApp
2. Sistem memeriksa apakah pesan adalah dari bot sendiri (skip jika ya)
3. Sistem mengambil semua auto-reply yang aktif, diurutkan berdasarkan priority
4. Untuk setiap auto-reply:
   - Cocokkan trigger dengan isi pesan (case-sensitive atau tidak)
   - Jika cocok, ganti placeholder dan kirim balasan
   - Hentikan pencocokan (hanya satu balasan per pesan)

### API Endpoints

Auto-reply dikelola melalui route admin:
- `GET /admin/auto-replies` - List semua auto-reply
- `GET /admin/auto-replies/create` - Form tambah auto-reply
- `POST /admin/auto-replies` - Simpan auto-reply baru
- `GET /admin/auto-replies/{id}/edit` - Form edit auto-reply
- `PUT /admin/auto-replies/{id}` - Update auto-reply
- `DELETE /admin/auto-replies/{id}` - Hapus auto-reply
- `POST /admin/auto-replies/{id}/toggle-active` - Toggle status aktif

## Best Practices

1. **Gunakan Priority dengan Bijak**
   - Priority lebih tinggi untuk keyword spesifik
   - Priority lebih rendah untuk keyword umum

2. **Hindari Trigger yang Ambigu**
   - Jangan gunakan trigger yang terlalu umum (contoh: "a", "ok")
   - Gunakan trigger yang jelas dan spesifik

3. **Test Response Anda**
   - Kirim pesan test ke bot untuk memastikan response sesuai
   - Periksa apakah placeholder berfungsi dengan benar

4. **Dokumentasikan Response Anda**
   - Gunakan response yang informatif dan jelas
   - Sertakan emoji untuk membuat response lebih menarik

5. **Monitor dan Update**
   - Review log percakapan untuk melihat response yang sering digunakan
   - Update response berdasarkan feedback pengguna

## Troubleshooting

### Auto-Reply Tidak Bekerja

1. **Cek Status Auto-Reply**
   - Pastikan auto-reply dalam status "Active"
   - Periksa apakah trigger sudah benar

2. **Cek Status Bot**
   - Pastikan bot dalam status "Connected"
   - Periksa log bot server untuk error

3. **Cek Trigger Matching**
   - Jika case-sensitive, pastikan penulisan tepat
   - Cek apakah ada spasi di awal/akhir trigger

4. **Cek Priority**
   - Auto-reply lain mungkin cocok terlebih dahulu
   - Sesuaikan priority jika perlu

### Response Tidak Sesuai Ekspektasi

1. **Cek Placeholder**
   - Pastikan placeholder ditulis dengan benar: `{{timestamp}}`
   - Tidak ada spasi ekstra dalam placeholder

2. **Cek Format Pesan**
   - WhatsApp mendukung format Markdown sederhana
   - Gunakan `*bold*` untuk tebal, `_italic_` untuk miring

## Security Considerations

1. **Akses Terbatas**
   - Hanya admin yang dapat mengelola auto-reply
   - Gunakan role-based access control

2. **Validasi Input**
   - Trigger harus unique
   - Response wajib diisi
   - Priority harus dalam range 0-1000

3. **Rate Limiting**
   - Bot webhook sudah dilindungi rate limiting
   - Maksimum 120 request per menit

## Future Enhancements

Fitur yang dapat dikembangkan:
- [ ] Pattern matching dengan regex
- [ ] Multi-step conversation flow
- [ ] Conditional responses berdasarkan context
- [ ] A/B testing untuk responses
- [ ] Analytics untuk auto-reply usage
- [ ] Export/import auto-reply configuration
- [ ] Template library untuk responses umum
- [ ] Integration dengan knowledge base

## Support

Untuk bantuan lebih lanjut:
- Check log: `storage/logs/laravel.log`
- Check bot logs: `pm2 logs dukcapil-whatsapp-bot`
- Review conversation logs di admin panel
- Hubungi tim development DUKCAPIL Ponorogo

---

**Last Updated**: December 20, 2025
**Version**: 1.0.0
