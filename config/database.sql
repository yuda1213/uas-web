-- ============================================
-- DATABASE COFFEE SHOP
-- ============================================

-- Buat database
CREATE DATABASE IF NOT EXISTS db_coffee_shop;
USE db_coffee_shop;

-- ============================================
-- TABEL USERS (Pengguna)
-- ============================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_telepon VARCHAR(15),
    alamat TEXT,
    role ENUM('admin', 'user') DEFAULT 'user',
    tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    foto_profil VARCHAR(255),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL CATEGORIES (Kategori Menu)
-- ============================================
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL UNIQUE,
    deskripsi TEXT,
    gambar VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL PRODUCTS (Produk Kopi)
-- ============================================
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_produk VARCHAR(100) NOT NULL,
    kategori_id INT NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    stok INT DEFAULT 0,
    status ENUM('tersedia', 'tidak_tersedia') DEFAULT 'tersedia',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL ORDERS (Pesanan/Transaksi)
-- ============================================
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    no_pesanan VARCHAR(50) UNIQUE,
    tanggal_pesanan DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_harga DECIMAL(10, 2),
    status ENUM('pending', 'diproses', 'selesai', 'dibatalkan') DEFAULT 'pending',
    catatan TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL ORDER_ITEMS (Item dalam Pesanan)
-- ============================================
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    jumlah INT NOT NULL,
    harga_satuan DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL PAYMENTS (Pembayaran)
-- ============================================
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL UNIQUE,
    metode_pembayaran ENUM('cash', 'transfer', 'kartu_kredit') DEFAULT 'cash',
    jumlah_pembayaran DECIMAL(10, 2) NOT NULL,
    tanggal_pembayaran DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('belum_bayar', 'lunas', 'pending') DEFAULT 'belum_bayar',
    bukti_pembayaran VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL FEEDBACK (Ulasan Pelanggan)
-- ============================================
CREATE TABLE feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    komentar TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- DATA AWAL (SEED DATA)
-- ============================================

-- Admin User
INSERT INTO users (nama, email, password, no_telepon, alamat, role, status) 
VALUES ('Admin Coffee Shop', 'admin@coffeeshop.com', MD5('admin123'), '081234567890', 'Jalan Kopi No 1, Jakarta', 'admin', 'aktif');

-- Sample Categories
INSERT INTO categories (nama_kategori, deskripsi) 
VALUES 
('Espresso', 'Minuman kopi dengan metode espresso'),
('Americano', 'Kopi dengan air panas'),
('Latte', 'Kopi dengan susu dan busa'),
('Cappuccino', 'Kopi dengan susu dan espresso shots'),
('Cold Brew', 'Kopi dingin yang menyegarkan');

-- Sample Products
INSERT INTO products (nama_produk, kategori_id, harga, deskripsi, stok, status)
VALUES 
('Single Espresso', 1, 25000, 'Espresso murni dari biji kopi pilihan', 50, 'tersedia'),
('Double Espresso', 1, 35000, 'Dua shot espresso yang kuat', 45, 'tersedia'),
('Americano Classic', 2, 28000, 'Espresso dengan air panas', 60, 'tersedia'),
('Americano Long', 2, 32000, 'Americano dengan porsi lebih besar', 55, 'tersedia'),
('Caffe Latte', 3, 38000, 'Espresso dengan steamed milk', 70, 'tersedia'),
('Iced Latte', 3, 42000, 'Latte dengan es batu', 65, 'tersedia'),
('Cappuccino Ristretto', 4, 40000, 'Cappuccino dengan porsi kecil', 50, 'tersedia'),
('Cappuccino Regolare', 4, 45000, 'Cappuccino standar', 55, 'tersedia'),
('Cold Brew Classic', 5, 35000, 'Cold brew tradisional', 40, 'tersedia'),
('Cold Brew Vanilla', 5, 42000, 'Cold brew dengan vanilla', 35, 'tersedia');

-- ============================================
-- INDEXES TAMBAHAN UNTUK PERFORMA
-- ============================================
CREATE INDEX idx_product_kategori ON products(kategori_id);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_order_items_order ON order_items(order_id);
CREATE INDEX idx_order_items_product ON order_items(product_id);
CREATE INDEX idx_feedback_product ON feedback(product_id);
CREATE INDEX idx_feedback_user ON feedback(user_id);
