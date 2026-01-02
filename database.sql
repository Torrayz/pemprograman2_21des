CREATE DATABASE IF NOT EXISTS tripujiantoro;
USE tripujiantoro;

-- Users table untuk login/register
CREATE TABLE IF NOT EXISTS users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_telepon VARCHAR(15) NOT NULL,
    alamat TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    id_course INT AUTO_INCREMENT PRIMARY KEY,
    nama_course VARCHAR(100) NOT NULL,
    deskripsi TEXT NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,
    durasi_jam INT NOT NULL,
    instruktur VARCHAR(100) NOT NULL,
    kapasitas INT DEFAULT 30,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Enrollments table untuk mendaftar kursus
CREATE TABLE IF NOT EXISTS enrollments (
    id_enrollment INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_course INT NOT NULL,
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_pembayaran ENUM('belum_bayar', 'sudah_bayar', 'verifikasi') DEFAULT 'belum_bayar',
    sertifikat_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_course) REFERENCES courses(id_course) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (id_user, id_course)
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id_payment INT AUTO_INCREMENT PRIMARY KEY,
    id_enrollment INT NOT NULL,
    id_user INT NOT NULL,
    id_course INT NOT NULL,
    jumlah_pembayaran DECIMAL(10, 2) NOT NULL,
    metode_pembayaran VARCHAR(50) NOT NULL,
    bukti_pembayaran VARCHAR(255),
    status_pembayaran ENUM('pending', 'verified', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_enrollment) REFERENCES enrollments(id_enrollment) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_course) REFERENCES courses(id_course) ON DELETE CASCADE
);

-- Password disimpan sebagai plain text (tidak di-hash) untuk mempermudah testing
-- Admin user dengan password: admin123
INSERT INTO users (nama_lengkap, email, password, no_telepon, role) VALUES
('Admin Mister Komputer', 'admin@misterkomputer.com', 'admin123', '081234567890', 'admin');

-- Sample courses
INSERT INTO courses (nama_course, deskripsi, harga, durasi_jam, instruktur, kapasitas) VALUES
('Microsoft Office', 'Kuasai Word, Excel, dan PowerPoint untuk meningkatkan produktivitas kerja Anda', 500000, 10, 'Tri Puji Antoro', 30),
('Web Design', 'Belajar membuat website profesional dengan HTML, CSS, dan JavaScript dari nol', 750000, 14, 'Tri Puji Antoro', 25),
('Database & SQL', 'Mengelola database dengan SQL dan membuat aplikasi bisnis yang handal', 800000, 12, 'Tri Puji Antoro', 20),
('Graphic Design', 'Desain grafis profesional menggunakan Adobe Creative Suite dan tools modern', 900000, 12, 'Tri Puji Antoro', 25),
('Data Analytics', 'Analisis data dan business intelligence untuk pengambilan keputusan yang tepat', 700000, 10, 'Tri Puji Antoro', 20),
('Sistem & Networking', 'Kelola jaringan komputer dan sistem operasi secara profesional', 850000, 15, 'Tri Puji Antoro', 18);

-- User demo dengan password: user123 (plain text)
INSERT INTO users (nama_lengkap, email, password, no_telepon, alamat, role) VALUES
('Budi Santoso', 'budi@email.com', 'user123', '081234567890', 'Jl. Merdeka No. 123, Jakarta', 'user');
