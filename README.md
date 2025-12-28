# MISTER KOMPUTER - Sistem Manajemen Kursus Online

Sebuah sistem manajemen kursus komputer yang komprehensif dengan fitur authentication, payment gateway, dan admin dashboard.

## 📋 Fitur Utama

### 1. Landing Page (Index)
- Hero section yang menarik
- Showcase program kursus dengan harga
- Statistik dan keunggulan
- Call-to-action sections
- Footer dengan identitas developer

### 2. Authentication System
- **Register** - Pendaftaran user baru
- **Login** - Login dengan role-based access
- Password hashing dengan bcrypt
- Session management yang aman

### 3. User Dashboard
- Profil pengguna
- Daftar kursus yang diikuti
- Status pembayaran
- Statistik pengeluaran

### 4. Course Management
- Daftar semua kursus
- Detail kursus lengkap
- Enrollment system
- Capacity tracking

### 5. Payment System
- Multiple payment methods (Bank Transfer, E-Wallet, Credit Card)
- Payment status tracking
- Automated enrollment status update

### 6. Admin Dashboard
- Dashboard dengan statistik lengkap
- Manage courses (CRUD)
- Manage users
- Verify payments
- View enrollments

## 🗂️ Struktur Folder

```
mister-komputer/
├── index.php                 (Landing page utama)
├── config/
│   └── koneksi.php          (Database connection)
├── auth/
│   ├── login.php            (Login page)
│   ├── register.php         (Register page)
│   └── logout.php           (Logout handler)
├── pages/
│   ├── courses.php          (List semua kursus)
│   ├── course-detail.php    (Detail & enroll kursus)
│   ├── dashboard-user.php   (User dashboard)
│   └── payment.php          (Payment page)
├── admin/
│   ├── dashboard.php        (Admin dashboard)
│   ├── manage-courses.php   (Kelola kursus)
│   ├── manage-users.php     (Kelola user)
│   └── manage-payments.php  (Verifikasi pembayaran)
├── assets/
│   └── css/
│       ├── style.css        (Main CSS)
│       └── landing.css      (Landing page CSS)
└── database.sql             (Database setup)
```

## 🛠️ Setup & Instalasi

### 1. Buat Database
- Buka phpMyAdmin atau MySQL console
- Jalankan file `database.sql`
- Pastikan database `mkom` sudah dibuat

### 2. Configure Connection
- Buka file `config/koneksi.php`
- Sesuaikan dengan konfigurasi MySQL Anda
- Username: root (default)
- Password: (kosong - default)
- Database: mkom

### 3. Run Project
- Copy semua file ke folder htdocs (XAMPP) atau public_html
- Akses via browser: `http://localhost/mister-komputer/`

## 👤 Demo Akun

### Admin
- Email: admin@misterkomputer.com
- Password: admin123
- Role: Admin

### User
- Email: budi@email.com
- Password: user123
- Role: User

## 💾 Database Schema

### Users Table
- id_user (Primary Key)
- nama_lengkap
- email (Unique)
- password (Hashed)
- no_telepon
- alamat
- role (admin/user)
- status (aktif/nonaktif)
- timestamps

### Courses Table
- id_course (Primary Key)
- nama_course
- deskripsi
- harga
- durasi_jam
- instruktur
- kapasitas
- timestamps

### Enrollments Table
- id_enrollment (Primary Key)
- id_user (Foreign Key)
- id_course (Foreign Key)
- tanggal_daftar
- status_pembayaran (belum_bayar/verifikasi/sudah_bayar)
- sertifikat_url
- timestamps

### Payments Table
- id_payment (Primary Key)
- id_enrollment (Foreign Key)
- id_user (Foreign Key)
- id_course (Foreign Key)
- jumlah_pembayaran
- metode_pembayaran
- bukti_pembayaran
- status_pembayaran (pending/verified/failed)
- timestamps

## 🔐 Security Features

1. **Password Hashing** - Menggunakan bcrypt
2. **SQL Injection Prevention** - mysqli_real_escape_string
3. **Session Management** - Server-side session handling
4. **Role-Based Access Control** - Admin vs User
5. **Input Validation** - Form validation di server & client

## 🎨 Design & Styling

- **Color Scheme**: Purple (#4f46e5), Yellow (#fbbf24), White
- **Framework**: Pure CSS (No Bootstrap needed)
- **Responsive**: Mobile-friendly (480px, 768px breakpoints)
- **Animations**: Smooth transitions dan fade-in effects

## 📱 Halaman & Routes

### Public Pages
- `/index.php` - Landing page
- `/auth/login.php` - Login
- `/auth/register.php` - Register
- `/pages/courses.php` - Daftar kursus
- `/pages/course-detail.php?id=X` - Detail kursus

### User Pages (Protected)
- `/pages/dashboard-user.php` - User dashboard
- `/pages/payment.php?id_enrollment=X` - Payment

### Admin Pages (Protected)
- `/admin/dashboard.php` - Admin dashboard
- `/admin/manage-courses.php` - Kelola kursus
- `/admin/manage-users.php` - Kelola user
- `/admin/manage-payments.php` - Kelola pembayaran

## 🚀 Fitur Tambahan yang Bisa Ditambahkan

1. Email verification untuk register
2. Forgot password functionality
3. Student certificates generation
4. Real payment gateway integration (Stripe, Midtrans)
5. Student progress tracking
6. Course materials upload
7. Discussion forum
8. Student ratings & reviews

## 👨‍💻 Developer

**Nama**: Tri Puji Antoro
**NIM**: 221011402646
**Program**: Teknik Informatika
**Universitas**: Universitas Pamulang

## 📄 License

Educational Project - 2025

## 📞 Support

Email: info@misterkomputer.com
Phone: (021) 1234-5678
