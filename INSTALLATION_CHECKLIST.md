# INSTALLATION & DEPLOYMENT CHECKLIST

## ✅ Pre-Installation

- [ ] XAMPP/LAMP/LEMP server sudah installed
- [ ] Apache & MySQL services bisa dijalankan
- [ ] PHP version 7.0+ atau lebih
- [ ] Browser modern (Chrome, Firefox, Edge)
- [ ] Text editor untuk edit config (VS Code, Sublime, dll)

## ✅ Database Setup

- [ ] MySQL/MariaDB running
- [ ] phpMyAdmin accessible di localhost/phpmyadmin
- [ ] Create database named 'mkom'
- [ ] Run database.sql dengan sukses
- [ ] Verify semua 4 tables tercipta:
  - [ ] users table (dengan 2 sample records)
  - [ ] courses table (dengan 6 kursus)
  - [ ] enrollments table (empty)
  - [ ] payments table (empty)

## ✅ File Placement

- [ ] Folder structure sudah benar:
  - [ ] /index.php
  - [ ] /config/koneksi.php
  - [ ] /auth/*.php (3 files)
  - [ ] /pages/*.php (4 files)
  - [ ] /admin/*.php (4 files)
  - [ ] /assets/css/*.css (2 files)
  - [ ] Database documentation files

- [ ] File permissions:
  - [ ] PHP files readable
  - [ ] No execute permission needed
  - [ ] Folder permissions 755

## ✅ Configuration Check

- [ ] config/koneksi.php:
  - [ ] Hostname: localhost
  - [ ] Username: root
  - [ ] Password: (empty atau sesuai)
  - [ ] Database: mkom

- [ ] Bisa akses URL:
  - [ ] http://localhost/mister-komputer/
  - [ ] Database connection successful

## ✅ Landing Page Test

- [ ] index.php loads without error
- [ ] Navbar displays correctly
- [ ] All navigation links work (smooth scroll)
- [ ] Hero section visible
- [ ] Program cards display dari database
- [ ] Harga menampilkan dengan format Rp
- [ ] Statistics section visible
- [ ] Footer menampilkan identitas Tri Puji Antoro
- [ ] Responsive di mobile (test dengan F12)

## ✅ Authentication Test

**Register:**
- [ ] Klik "Register" di navbar
- [ ] Form validation bekerja (required fields)
- [ ] Password & confirm password match check
- [ ] Password minimum 6 chars
- [ ] Email unique validation (coba daftar 2x)
- [ ] Register berhasil → redirect ke login
- [ ] Data user terlihat di database

**Login:**
- [ ] Klik "Login" di navbar
- [ ] Login dengan akun demo (budi@email.com / user123)
- [ ] Login berhasil → redirect ke dashboard
- [ ] Session variables terupdate
- [ ] Navbar menampilkan nama user & logout button

**Logout:**
- [ ] Klik logout button
- [ ] Session destroyed
- [ ] Redirect ke index.php
- [ ] Navbar kembali ke state sebelum login

## ✅ User Features Test

**Courses Page:**
- [ ] pages/courses.php loads
- [ ] Semua 6 kursus ditampilkan
- [ ] Course cards menampilkan:
  - [ ] Nama kursus
  - [ ] Deskripsi
  - [ ] Harga (format Rp)
  - [ ] Durasi
  - [ ] Jumlah peserta
  - [ ] Instruktur

**Course Detail:**
- [ ] Click "Lihat Detail & Daftar"
- [ ] Detail page muncul dengan:
  - [ ] Full deskripsi
  - [ ] Learning list
  - [ ] Harga besar
  - [ ] Capacity bar
  - [ ] Enroll button
- [ ] Klik "Daftar Sekarang"
- [ ] Enrollment berhasil (check database)
- [ ] Status berubah ke "Sudah Terdaftar"
- [ ] Button berubah ke "Lanjut ke Pembayaran"

**Dashboard User:**
- [ ] pages/dashboard-user.php loads
- [ ] Profil section menampilkan nama, email, role
- [ ] Statistics:
  - [ ] Jumlah kursus terdaftar
  - [ ] Jumlah sudah dibayar
  - [ ] Total pengeluaran
- [ ] Kursus yang diikuti ditampilkan di table
- [ ] Status pembayaran menampilkan badge

**Payment:**
- [ ] Klik "Bayar" di dashboard atau course detail
- [ ] Payment page menampilkan:
  - [ ] Order summary
  - [ ] Payment method options
  - [ ] Total harga
- [ ] Pilih payment method
- [ ] Submit form
- [ ] Payment success message muncul
- [ ] Status pembayaran berubah menjadi "Sudah Bayar"
- [ ] Enrollment status updated di database

## ✅ Admin Features Test

**Admin Login:**
- [ ] Clear cookies/logout user akun terlebih dahulu
- [ ] Login dengan:
  - [ ] Email: admin@misterkomputer.com
  - [ ] Password: admin123
- [ ] Redirect ke admin/dashboard.php
- [ ] Navbar menampilkan "Admin Panel"

**Admin Dashboard:**
- [ ] Dashboard menampilkan statistics:
  - [ ] Total users (minimal 1)
  - [ ] Total courses (6)
  - [ ] Total enrollments
  - [ ] Total income
- [ ] Recent courses list visible
- [ ] Recent enrollments list visible
- [ ] Navigation buttons ke manage pages

**Manage Courses:**
- [ ] admin/manage-courses.php loads
- [ ] Semua 6 kursus ditampilkan di table
- [ ] "Tambah Kursus Baru" form visible
- [ ] Fill form & submit → Course added
- [ ] Course list updated
- [ ] Edit course → Form populated
- [ ] Update course & check database
- [ ] Delete course → Confirm & deleted
- [ ] Course list updated

**Manage Users:**
- [ ] admin/manage-users.php loads
- [ ] User list menampilkan:
  - [ ] Nama, email, phone
  - [ ] Kursus terdaftar
  - [ ] Status
- [ ] Deactivate user → Status changed
- [ ] Activate user → Status changed
- [ ] Delete user → Confirm & deleted
- [ ] Check database updated

**Manage Payments:**
- [ ] admin/manage-payments.php loads
- [ ] Payment list menampilkan semua transaksi
- [ ] Status pembayaran visible (pending/verified/failed)
- [ ] Untuk pending payments:
  - [ ] Verify button muncul
  - [ ] Reject button muncul
  - [ ] Klik verify → Status changed ke verified
  - [ ] Enrollment status auto-updated
  - [ ] Klik reject → Status changed ke failed

## ✅ Styling & Responsive Test

**Desktop (1200px+):**
- [ ] Layout proper
- [ ] All elements visible
- [ ] Navbar horizontal
- [ ] Grid columns optimal
- [ ] Tables readable

**Tablet (768px):**
- [ ] Responsive grid works (2 cols → 1 col)
- [ ] Navbar items wrap
- [ ] Button sizes appropriate
- [ ] Font sizes readable

**Mobile (480px):**
- [ ] All elements accessible
- [ ] Navigation works
- [ ] Forms fillable
- [ ] Tables scrollable
- [ ] Images scale properly

**Dark Mode Test (optional):**
- [ ] Check dengan device dengan dark mode
- [ ] Styling tetap readable

## ✅ Database Integrity

- [ ] No orphaned records
- [ ] Foreign keys working
- [ ] Unique constraints enforced
- [ ] Timestamps updating
- [ ] Cascade deletes working (delete enrollment when delete course)

## ✅ Error Handling

- [ ] Try invalid login → error message
- [ ] Try SQL injection → prevented
- [ ] Try access admin page as user → redirect
- [ ] Try access protected page without login → redirect to login
- [ ] Try duplicate email register → error message
- [ ] Broken links → redirect atau error page

## ✅ Performance Check

- [ ] index.php loads dalam < 1 second
- [ ] CSS files loading without 404
- [ ] No console errors (F12)
- [ ] Form submission smooth
- [ ] Page transitions smooth
- [ ] No memory leaks

## ✅ Browser Compatibility

Test di browsers:
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge

## ✅ Production Checklist

- [ ] Remove demo data (optional)
- [ ] Test dengan real admin account
- [ ] Update footer copyright year
- [ ] Set realistic course prices
- [ ] Update contact information
- [ ] Backup database
- [ ] Set proper file permissions
- [ ] Setup https (if possible)
- [ ] Configure error logging

## ✅ Documentation Check

- [ ] README.md reviewed
- [ ] QUICK_START.md tested
- [ ] PROJECT_SUMMARY.md accurate
- [ ] INSTALLATION_CHECKLIST.md completed
- [ ] Code comments present
- [ ] Database documented

## 🎉 Final Sign-Off

- [ ] All tests passed
- [ ] All features working
- [ ] No critical bugs
- [ ] Project ready for deployment
- [ ] Credentials documented securely
- [ ] Backup taken

---

**Tanda Tangan**: ________________
**Tanggal**: ________________
**Tester**: Tri Puji Antoro (221011402646)

---

## 📞 Troubleshooting Quick Reference

| Issue | Solution |
|-------|----------|
| Blank page | Check PHP errors in console |
| CSS not loading | Check file path in link tag |
| Database error | Verify koneksi.php settings |
| Login fails | Clear cookies, check database |
| 404 on pages | Check folder structure |
| Query error | Run database.sql again |
| Session lost | Check browser cookies |

Jika sudah lengkap semua checklist, sistem sudah siap untuk digunakan! 🚀
