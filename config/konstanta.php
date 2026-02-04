<?php
/**
 * File Konstanta Aplikasi
 */

// URL Aplikasi
if (!defined('BASE_URL')) define('BASE_URL', 'http://localhost/uas_kopi/');
if (!defined('ASSETS_URL')) define('ASSETS_URL', BASE_URL . 'assets/');
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', 'uploads/');
if (!defined('UPLOAD_URL')) define('UPLOAD_URL', BASE_URL . 'uploads/');

// Konfigurasi Session
if (!defined('SESSION_TIMEOUT')) define('SESSION_TIMEOUT', 3600); // 1 jam dalam detik
if (!defined('SESSION_NAME')) define('SESSION_NAME', 'coffee_shop_session');

// Direktori Upload
if (!defined('UPLOAD_DIR')) define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// Role Constants
if (!defined('ROLE_ADMIN')) define('ROLE_ADMIN', 'admin');
if (!defined('ROLE_USER')) define('ROLE_USER', 'user');

// Status Constants
if (!defined('STATUS_AKTIF')) define('STATUS_AKTIF', 'aktif');
if (!defined('STATUS_NONAKTIF')) define('STATUS_NONAKTIF', 'nonaktif');
if (!defined('STATUS_TERSEDIA')) define('STATUS_TERSEDIA', 'tersedia');
if (!defined('STATUS_TIDAK_TERSEDIA')) define('STATUS_TIDAK_TERSEDIA', 'tidak_tersedia');

// Order Status
if (!defined('ORDER_STATUS_PENDING')) define('ORDER_STATUS_PENDING', 'pending');
if (!defined('ORDER_STATUS_DIPROSES')) define('ORDER_STATUS_DIPROSES', 'diproses');
if (!defined('ORDER_STATUS_SELESAI')) define('ORDER_STATUS_SELESAI', 'selesai');
if (!defined('ORDER_STATUS_DIBATALKAN')) define('ORDER_STATUS_DIBATALKAN', 'dibatalkan');

// Payment Status
if (!defined('PAYMENT_BELUM_BAYAR')) define('PAYMENT_BELUM_BAYAR', 'belum_bayar');
if (!defined('PAYMENT_LUNAS')) define('PAYMENT_LUNAS', 'lunas');
if (!defined('PAYMENT_PENDING')) define('PAYMENT_PENDING', 'pending');

// Konfigurasi Pagination
if (!defined('ITEMS_PER_PAGE')) define('ITEMS_PER_PAGE', 10);

// Format Tanggal
if (!defined('DATE_FORMAT')) define('DATE_FORMAT', 'd-m-Y');
if (!defined('DATETIME_FORMAT')) define('DATETIME_FORMAT', 'd-m-Y H:i:s');
