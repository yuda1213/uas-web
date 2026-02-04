<?php
/**
 * Helper Functions
 * File ini berisi fungsi-fungsi utility yang sering digunakan
 */

/**
 * Redirect ke halaman lain
 */
if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}

/**
 * Fungsi untuk encode password
 */
if (!function_exists('hashPassword')) {
    function hashPassword($password) {
        return md5($password);
    }
}

/**
 * Fungsi untuk verifikasi password
 */
if (!function_exists('verifyPassword')) {
    function verifyPassword($password, $hash) {
        return md5($password) === $hash;
    }
}

/**
 * Fungsi untuk membuat nomor pesanan unik
 */
if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber() {
        return 'ORDER-' . date('YmdHis') . '-' . rand(1000, 9999);
    }
}

/**
 * Fungsi untuk format currency (Rupiah)
 */
if (!function_exists('formatRupiah')) {
    function formatRupiah($number) {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }
}

/**
 * Fungsi untuk format tanggal Indonesia
 */
if (!function_exists('formatTanggalId')) {
    function formatTanggalId($date) {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        
        $date_obj = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if ($date_obj) {
            $day = $date_obj->format('d');
            $month = $months[$date_obj->format('F')];
            $year = $date_obj->format('Y');
            $time = $date_obj->format('H:i');
            return $day . ' ' . $month . ' ' . $year . ' ' . $time;
        }
        return $date;
    }
}

/**
 * Fungsi untuk sanitasi input
 */
if (!function_exists('sanitize')) {
    function sanitize($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }
}

/**
 * Fungsi untuk validasi email
 */
if (!function_exists('validateEmail')) {
    function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

/**
 * Fungsi untuk mendapatkan URL halaman sebelumnya
 */
if (!function_exists('getReferer')) {
    function getReferer() {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : BASE_URL;
    }
}

/**
 * Fungsi untuk membuat alert/notifikasi
 */
if (!function_exists('setAlert')) {
    function setAlert($type, $message) {
        $_SESSION['alert'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}

/**
 * Fungsi untuk mendapatkan alert
 */
if (!function_exists('getAlert')) {
    function getAlert() {
        if (isset($_SESSION['alert'])) {
            $alert = $_SESSION['alert'];
            unset($_SESSION['alert']);
            return $alert;
        }
        return null;
    }
}

/**
 * Fungsi untuk validasi hak akses berdasarkan role
 */
if (!function_exists('checkRole')) {
    function checkRole($required_role) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $required_role) {
            setAlert('danger', 'Anda tidak memiliki akses ke halaman ini');
            redirect(BASE_URL . 'index.php');
        }
    }
}

/**
 * Fungsi untuk check apakah user sudah login
 */
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user']);
    }
}

/**
 * Fungsi untuk mendapatkan user dari session
 */
if (!function_exists('getCurrentUser')) {
    function getCurrentUser() {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }
}

/**
 * Fungsi untuk upload file
 */
if (!function_exists('uploadFile')) {
    function uploadFile($file, $destination) {
        if (!isset($_FILES[$file])) {
            return ['status' => false, 'message' => 'File tidak ditemukan'];
        }

        if (!empty($_FILES[$file]['error'])) {
            $errorMap = [
                UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi batas server',
                UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas form',
                UPLOAD_ERR_PARTIAL => 'File terupload sebagian',
                UPLOAD_ERR_NO_FILE => 'Tidak ada file yang dipilih',
                UPLOAD_ERR_NO_TMP_DIR => 'Folder sementara tidak tersedia',
                UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
                UPLOAD_ERR_EXTENSION => 'Upload dibatalkan oleh ekstensi PHP'
            ];
            $message = $errorMap[$_FILES[$file]['error']] ?? 'Terjadi kesalahan saat upload';
            return ['status' => false, 'message' => $message];
        }

        if ($_FILES[$file]['size'] > 2097152) { // 2MB
            return ['status' => false, 'message' => 'File terlalu besar (maksimal 2MB)'];
        }
        
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES[$file]['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            return ['status' => false, 'message' => 'Format file tidak didukung'];
        }
        
        $new_filename = time() . '_' . rand(1000, 9999) . '.' . $ext;
        $upload_path = UPLOAD_DIR . $destination . '/';
        
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        if (!is_writable($upload_path)) {
            return ['status' => false, 'message' => 'Folder upload tidak bisa ditulis'];
        }

        if (empty($_FILES[$file]['tmp_name']) || !is_uploaded_file($_FILES[$file]['tmp_name'])) {
            return ['status' => false, 'message' => 'File sementara tidak valid'];
        }
        
        if (move_uploaded_file($_FILES[$file]['tmp_name'], $upload_path . $new_filename)) {
            return ['status' => true, 'filename' => $new_filename];
        }
        
        return ['status' => false, 'message' => 'Gagal mengupload file'];
    }
}

/**
 * Fungsi untuk hapus file
 */
if (!function_exists('deleteFile')) {
    function deleteFile($file_path) {
        if (file_exists($file_path)) {
            unlink($file_path);
            return true;
        }
        return false;
    }
}

/**
 * Fungsi untuk pagination
 */
if (!function_exists('getPaginationData')) {
    function getPaginationData($total_items, $items_per_page = ITEMS_PER_PAGE) {
        $current_page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        if ($current_page < 1) $current_page = 1;
        
        $total_pages = ceil($total_items / $items_per_page);
        $offset = ($current_page - 1) * $items_per_page;
        
        return [
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'offset' => $offset,
            'items_per_page' => $items_per_page,
            'total_items' => $total_items
        ];
    }
}

/**
 * Generate CSRF Token
 */
if (!function_exists('generateCSRFToken')) {
    function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

/**
 * Verify CSRF Token
 */
if (!function_exists('verifyCSRFToken')) {
    function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
