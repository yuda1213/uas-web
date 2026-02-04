<?php
/**
 * Session Management
 * File ini mengelola session dan cookie pengguna
 */

// Set session settings SEBELUM session_start()
if (session_status() === PHP_SESSION_NONE) {
    // Pastikan SESSION_TIMEOUT dan SESSION_NAME sudah terdefinisi
    $timeout = defined('SESSION_TIMEOUT') ? SESSION_TIMEOUT : 3600;
    $name = defined('SESSION_NAME') ? SESSION_NAME : 'coffee_shop_session';
    
    session_name($name);
    ini_set('session.gc_maxlifetime', $timeout);
    session_set_cookie_params([
        'lifetime' => $timeout,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

/**
 * Cek apakah session user masih valid (tidak expired)
 */
if (!function_exists('checkSessionTimeout')) {
    function checkSessionTimeout() {
        if (isset($_SESSION['user'])) {
            $last_activity = isset($_SESSION['last_activity']) ? $_SESSION['last_activity'] : time();
            $current_time = time();
            
            if (($current_time - $last_activity) > SESSION_TIMEOUT) {
                // Session expired
                destroySession();
                return false;
            }
            
            // Update last activity time
            $_SESSION['last_activity'] = $current_time;
            return true;
        }
        return false;
    }
}

/**
 * Destroy session ketika logout
 */
if (!function_exists('destroySession')) {
    function destroySession() {
        $_SESSION = [];
        
        // Hapus session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        session_destroy();
    }
}

/**
 * Set user session setelah login berhasil
 */
if (!function_exists('setUserSession')) {
    function setUserSession($user) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'role' => $user['role'],
            'foto_profil' => $user['foto_profil']
        ];
        $_SESSION['last_activity'] = time();
    }
}

/**
 * Fungsi untuk memeriksa apakah halaman memerlukan login
 */
if (!function_exists('requireLogin')) {
    function requireLogin() {
        if (!isLoggedIn() || !checkSessionTimeout()) {
            setAlert('warning', 'Silakan login terlebih dahulu');
            redirect(BASE_URL . 'index.php?page=login');
        }
    }
}

/**
 * Fungsi untuk memeriksa role pengguna
 */
if (!function_exists('requireRole')) {
    function requireRole($role) {
        if (!isLoggedIn() || !checkSessionTimeout()) {
            setAlert('warning', 'Silakan login terlebih dahulu');
            redirect(BASE_URL . 'index.php?page=login');
        }
        
        $user = getCurrentUser();
        if ($user['role'] !== $role) {
            setAlert('danger', 'Anda tidak memiliki akses ke halaman ini');
            redirect(BASE_URL . 'index.php');
        }
    }
}

// Jalankan pengecekan session di setiap request
if (isLoggedIn() && !checkSessionTimeout()) {
    destroySession();
    setAlert('warning', 'Session Anda telah berakhir. Silakan login kembali');
    redirect(BASE_URL . 'index.php?page=login');
}
