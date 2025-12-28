# QUICK START GUIDE - Mister Komputer

## ⚡ Langkah-Langkah Cepat

### 1. Setup Database (2 Menit)

```bash
1. Buka XAMPP Control Panel → Start Apache & MySQL
2. Buka browser → localhost/phpmyadmin
3. Login dengan username: root
4. Buat database baru dengan nama: mkom
5. Pilih database mkom
6. Pilih tab SQL
7. Copy-paste isi file database.sql
8. Klik Go/Execute
```

### 2. Upload Files

```bash
1. Download semua file project
2. Extract di C:\xampp\htdocs\mister-komputer
3. Atau di /var/www/html/mister-komputer (Linux)
```

### 3. Akses Project

```bash
Buka di browser: http://localhost/mister-komputer/
```

## 🔑 Akun Testing

**Admin Account:**
```
Email: admin@misterkomputer.com
Password: admin123
```

**User Account:**
```
Email: budi@email.com
Password: user123
```

## 🧪 Testing Checklist

### Landing Page
- [ ] Load halaman utama
- [ ] Navbar links berfungsi (smooth scroll)
- [ ] Hero section menampilkan program dari database
- [ ] Footer menampilkan informasi dengan benar

### Authentication
- [ ] Register akun baru berhasil
- [ ] Login dengan akun yang sudah dibuat
- [ ] Logout berfungsi
- [ ] Password validation bekerja

### User Features
- [ ] Lihat semua kursus di /pages/courses.php
- [ ] Klik detail kursus
- [ ] Daftar kursus (enroll)
- [ ] Akses dashboard user
- [ ] Lakukan pembayaran
- [ ] Verifikasi status pembayaran

### Admin Features
- [ ] Login sebagai admin
- [ ] Lihat dashboard dengan statistik
- [ ] Tambah kursus baru
- [ ] Edit kursus
- [ ] Hapus kursus
- [ ] Lihat daftar user
- [ ] Ubah status user
- [ ] Verifikasi pembayaran user

## 📊 Database Reset

Jika ingin reset database ke kondisi awal:

```sql
1. Di phpMyAdmin, pilih database mkom
2. Klik tab SQL
3. Jalankan: DROP DATABASE mkom;
4. Buat database baru: CREATE DATABASE mkom;
5. Jalankan ulang file database.sql
```

## 🛠️ Troubleshooting

**Error: Koneksi database gagal**
- [ ] Pastikan MySQL sudah running
- [ ] Check username & password di config/koneksi.php
- [ ] Check nama database adalah "mkom"

**Error: Table doesn't exist**
- [ ] Run database.sql lagi
- [ ] Check file database.sql sudah dijalankan

**Login tidak bisa**
- [ ] Clear browser cache/cookies
- [ ] Coba dengan akun demo yang disediakan
- [ ] Check apakah user sudah registered di database

**Styling tidak muncul**
- [ ] Refresh browser (Ctrl+F5)
- [ ] Check path CSS file di assets/css/
- [ ] Pastikan file style.css dan landing.css ada

## 📝 Notes

- Default password hashing menggunakan PASSWORD_BCRYPT
- Session timeout bisa dikonfigurasi di PHP.ini
- Untuk production, gunakan HTTPS
- Untuk real payment, integrate dengan payment gateway
- Backup database secara berkala

## 🎓 Learning Resources

- Pelajari struktur kode di setiap file
- Pahami flow authentication di auth/ folder
- Explore admin functions di admin/ folder
- Modifikasi styling sesuai kebutuhan

Selamat mencoba! 🚀
