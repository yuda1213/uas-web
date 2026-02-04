# Coffee Shop - Aplikasi Web PHP Native

## 📋 Daftar Isi
1. [Ringkasan Proyek](#ringkasan-proyek)
2. [Fitur Aplikasi](#fitur-aplikasi)
3. [Instalasi & Setup](#instalasi--setup)
4. [Struktur Folder](#struktur-folder)
5. [Database Design](#database-design)
6. [Panduan Penggunaan](#panduan-penggunaan)
7. [Akun Demo](#akun-demo)

---

## 📱 Ringkasan Proyek

Aplikasi Coffee Shop adalah sistem manajemen penjualan kopi berbasis web yang dibangun dengan PHP native (tanpa framework), HTML, CSS, JavaScript, dan MySQL. Aplikasi ini dirancang untuk mengelola:

- **Produk/Menu Kopi**: Katalog lengkap dengan kategori
- **Pesanan Pelanggan**: Sistem ordering dan tracking
- **Manajemen User**: Admin dan customer
- **Laporan Penjualan**: Export PDF dan Excel
- **Session Management**: Login/logout dengan role-based access control

---

## ✨ Fitur Aplikasi

### 1. **Autentikasi & Otorisasi**
- ✅ Login dengan validasi email dan password
- ✅ Registrasi user baru
- ✅ Logout dengan destroy session
- ✅ Role-based access control (Admin & User)
- ✅ Session timeout management

### 2. **Dashboard Admin**
- ✅ Statistik penjualan, produk, user, pesanan
- ✅ Daftar pesanan terbaru
- ✅ Quick actions ke berbagai modul

### 3. **Manajemen Produk (CRUD)**
- ✅ Tambah, edit, hapus produk
- ✅ Upload gambar produk
- ✅ Kelola stok dan status
- ✅ Kategori produk
- ✅ Pagination dan search

### 4. **Manajemen Kategori (CRUD)**
- ✅ Tambah, edit, hapus kategori
- ✅ Validasi unique name
- ✅ Deskripsi kategori

### 5. **Manajemen User (CRUD)**
- ✅ Kelola semua user (Admin only)
- ✅ Edit role dan status user
- ✅ Hapus user kecuali admin
- ✅ Pagination dan search

### 6. **Sistem Pesanan (Order)**
- ✅ Customer dapat melihat daftar produk
- ✅ Tambah ke keranjang (localStorage)
- ✅ Checkout dengan metode pembayaran
- ✅ Riwayat pesanan
- ✅ Detail pesanan dengan item

### 7. **Dashboard Customer**
- ✅ Ringkasan statistik pesanan
- ✅ Lihat menu produk
- ✅ Keranjang belanja
- ✅ Checkout
- ✅ History pesanan
- ✅ Edit profile

### 8. **Laporan & Export**
- ✅ Laporan penjualan per periode
- ✅ Laporan penjualan per produk
- ✅ Export ke Excel (CSV format)
- ✅ Export ke PDF (dengan library FPDF)

---

## 🔧 Instalasi & Setup

### Persyaratan
- PHP 7.4+
- MySQL/MariaDB 5.7+
- Laragon/XAMPP/Wamp Server
- Browser modern

### Langkah-langkah

#### 1. **Clone/Extract Project**
```bash
cd c:\laragon\www
# Copy seluruh folder uas_kopi ke directory
```

#### 2. **Buat Database**
```sql
-- Buka phpMyAdmin (http://localhost/phpmyadmin)
-- Import file: config/database.sql
-- Atau copy-paste isi SQL ke dalam phpMyAdmin
```

#### 3. **Konfigurasi Database** (jika berbeda)
Edit file `config/database.php`:
```php
define('DB_HOST', 'localhost');    // Host database
define('DB_USER', 'root');         // Username
define('DB_PASS', '');             // Password
define('DB_NAME', 'db_coffee_shop'); // Nama database
```

#### 4. **Jalankan Aplikasi**
```
http://localhost/uas_kopi
```

#### 5. **Install Dependencies (Optional untuk PDF)**
Jika ingin menggunakan PDF export:
```bash
cd c:\laragon\www\uas_kopi
composer require setasign/fpdf
```

---

## 📁 Struktur Folder

```
uas_kopi/
├── config/
│   ├── database.php      # Konfigurasi database
│   ├── database.sql      # Schema SQL
│   └── konstanta.php     # Konstanta aplikasi
│
├── controller/
│   └── AuthController.php # Logika login/register
│
├── models/
│   ├── User.php          # Model user
│   ├── Category.php      # Model kategori
│   ├── Product.php       # Model produk
│   └── Order.php         # Model pesanan
│
├── view/
│   ├── auth/
│   │   ├── login.php     # Halaman login
│   │   └── register.php  # Halaman register
│   │
│   ├── admin/
│   │   ├── dashboard.php           # Dashboard admin
│   │   ├── products/               # CRUD produk
│   │   ├── categories/             # CRUD kategori
│   │   ├── users/                  # CRUD user
│   │   ├── orders/                 # Daftar pesanan
│   │   └── reports/                # Laporan
│   │
│   ├── user/
│   │   ├── dashboard.php           # Dashboard customer
│   │   ├── menu.php                # Daftar menu
│   │   ├── cart.php                # Keranjang
│   │   ├── checkout.php            # Checkout
│   │   ├── orders.php              # Riwayat pesanan
│   │   ├── order-detail.php        # Detail pesanan
│   │   └── profile.php             # Profile user
│   │
│   ├── home.php          # Halaman depan
│   ├── layout_header.php # Template header
│   └── layout_footer.php # Template footer
│
├── assets/
│   ├── css/
│   │   └── style.css     # Styling utama
│   └── js/
│       └── main.js       # JavaScript utama
│
├── helpers/
│   ├── functions.php     # Fungsi utility
│   └── session.php       # Manajemen session
│
├── export/
│   ├── PDFExport.php     # Export PDF
│   └── ExcelExport.php   # Export Excel/CSV
│
├── uploads/
│   └── products/         # Folder untuk upload gambar
│
└── index.php             # Entry point aplikasi
```

---

## 🗄️ Database Design

### Entity Relationship Diagram (Tabel-Tabel)

#### 1. **users** (Pengguna)
```
- id (PK, Auto Increment)
- nama (VARCHAR 100)
- email (VARCHAR 100, UNIQUE)
- password (VARCHAR 255, hashed with MD5)
- no_telepon (VARCHAR 15)
- alamat (TEXT)
- role (ENUM: 'admin', 'user')
- tanggal_daftar (DATETIME)
- status (ENUM: 'aktif', 'nonaktif')
- foto_profil (VARCHAR 255)
- updated_at (DATETIME)
```

#### 2. **categories** (Kategori Menu)
```
- id (PK, Auto Increment)
- nama_kategori (VARCHAR 100, UNIQUE)
- deskripsi (TEXT)
- gambar (VARCHAR 255)
- created_at (DATETIME)
- updated_at (DATETIME)
```

#### 3. **products** (Produk Kopi)
```
- id (PK, Auto Increment)
- nama_produk (VARCHAR 100)
- kategori_id (FK → categories.id)
- harga (DECIMAL 10,2)
- deskripsi (TEXT)
- gambar (VARCHAR 255)
- stok (INT)
- status (ENUM: 'tersedia', 'tidak_tersedia')
- created_at (DATETIME)
- updated_at (DATETIME)
```

#### 4. **orders** (Pesanan)
```
- id (PK, Auto Increment)
- user_id (FK → users.id)
- no_pesanan (VARCHAR 50, UNIQUE)
- tanggal_pesanan (DATETIME)
- total_harga (DECIMAL 10,2)
- status (ENUM: 'pending', 'diproses', 'selesai', 'dibatalkan')
- catatan (TEXT)
- created_at (DATETIME)
- updated_at (DATETIME)
```

#### 5. **order_items** (Item dalam Pesanan)
```
- id (PK, Auto Increment)
- order_id (FK → orders.id)
- product_id (FK → products.id)
- jumlah (INT)
- harga_satuan (DECIMAL 10,2)
- subtotal (DECIMAL 10,2)
- created_at (DATETIME)
```

#### 6. **payments** (Pembayaran)
```
- id (PK, Auto Increment)
- order_id (FK → orders.id, UNIQUE)
- metode_pembayaran (ENUM: 'cash', 'transfer', 'kartu_kredit')
- jumlah_pembayaran (DECIMAL 10,2)
- tanggal_pembayaran (DATETIME)
- status (ENUM: 'belum_bayar', 'lunas', 'pending')
- bukti_pembayaran (VARCHAR 255)
- created_at (DATETIME)
- updated_at (DATETIME)
```

#### 7. **feedback** (Ulasan)
```
- id (PK, Auto Increment)
- product_id (FK → products.id)
- user_id (FK → users.id)
- rating (INT, 1-5)
- komentar (TEXT)
- created_at (DATETIME)
```

---

## 👤 Panduan Penggunaan

### A. **Untuk Admin**

#### Login
1. Buka http://localhost/uas_kopi
2. Klik "Login"
3. Gunakan akun: `admin@coffeeshop.com` / `admin123`

#### Dashboard Admin
- Lihat statistik: Total user, produk, pesanan, penjualan
- Akses cepat ke semua modul management

#### Manajemen Produk
1. Klik **Produk** di navbar
2. **Tambah Produk**: Pilih kategori, isi harga, stok, upload gambar
3. **Edit Produk**: Ubah informasi produk
4. **Hapus Produk**: Konfirmasi penghapusan

#### Manajemen Kategori
1. Klik **Kategori** di navbar
2. **Tambah Kategori**: Isi nama dan deskripsi
3. **Edit/Hapus**: Sesuai kebutuhan

#### Manajemen User
1. Klik **User** di navbar
2. Lihat semua user terdaftar
3. Edit role (admin/user) dan status (aktif/nonaktif)
4. Hapus user (kecuali diri sendiri)

#### Manajemen Pesanan
1. Klik **Pesanan** di navbar
2. Lihat semua pesanan dengan filter status
3. Klik **Detail** untuk melihat item pesanan
4. Ubah status pesanan (pending → diproses → selesai)

#### Laporan & Export
1. Klik **Laporan** di navbar
2. Pilih date range
3. Export ke PDF atau Excel
   - **Export PDF Pesanan**: Daftar pesanan terbaru
   - **Export Excel Pesanan**: Data lengkap pesanan
   - **Export Excel Produk**: Laporan penjualan per produk

### B. **Untuk Customer/User**

#### Registrasi & Login
1. Klik **Daftar di sini** di halaman login
2. Isi form registrasi: nama, email, password, no telepon, alamat
3. Setelah registrasi, login dengan email dan password Anda

#### Dashboard Customer
- Lihat statistik: Total pesanan, pesanan selesai, total belanja
- Quick actions: Pesan Kopi, Keranjang, Pesanan Saya, Profile

#### Pesan Kopi
1. Klik **Menu** di navbar atau **Pesan Kopi** di dashboard
2. Lihat daftar kopi per kategori
3. Gunakan search untuk mencari produk
4. Klik **Keranjang** untuk menambah ke cart

#### Keranjang & Checkout
1. Klik **Keranjang** di navbar
2. Ubah jumlah atau hapus item
3. Klik **Lanjut ke Checkout**
4. Pilih metode pembayaran (Cash/Transfer/Kartu Kredit)
5. Tambah catatan jika perlu
6. Klik **Konfirmasi Pesanan**

#### Riwayat Pesanan
1. Klik **Pesanan Saya** di navbar
2. Filter berdasarkan status
3. Klik **Detail** untuk melihat informasi lengkap pesanan

#### Edit Profile
1. Klik **Profile** di navbar
2. Edit data pribadi (nama, no telepon, alamat)
3. Ubah password

---

## 🔑 Akun Demo

### Admin Account
- **Email**: `admin@coffeeshop.com`
- **Password**: `admin123`

### Customer Account (untuk testing)
- Buat akun baru melalui form registrasi
- Atau gunakan:
  - **Email**: `customer@test.com`
  - **Password**: `test123` (jika sudah dibuat)

---

## 🔒 Keamanan

### Implementasi:
1. **Password Hashing**: MD5 (Production: gunakan bcrypt)
2. **Session Management**: Session timeout 1 jam
3. **Input Sanitization**: sanitize() untuk semua input
4. **SQL Injection Prevention**: Prepared Statements (mysqli)
5. **CSRF Token**: generateCSRFToken() (siap implementasi)
6. **Role-Based Access**: checkRole() function

### Rekomendasi Production:
```php
// Gunakan password_hash() bukan MD5
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Gunakan HTTPS
// Enable HSTS headers
// Implementasi proper CSRF tokens
// Rate limiting pada login
```

---

## 📞 Dukungan & Troubleshooting

### Error: "Koneksi database gagal"
- Cek DB_HOST, DB_USER, DB_PASS, DB_NAME di config/database.php
- Pastikan MySQL/MariaDB running

### Error: "File tidak bisa diupload"
- Pastikan folder `uploads/products/` writable (chmod 755)
- Ukuran file tidak boleh lebih dari 2MB

### Session tidak bekerja
- Pastikan session.start() dipanggil di awal
- Cek session timeout di config/konstanta.php

### PDF Export tidak jalan
- Install FPDF: `composer require setasign/fpdf`
- Pastikan folder vendor ada di root project

---

## 📄 Lisensi
Gratis untuk penggunaan edukatif dan komersial

---

**Terakhir diupdate**: 18 Januari 2024
#   u a s - w e b  
 