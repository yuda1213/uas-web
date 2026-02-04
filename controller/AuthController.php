<?php
/**
 * Auth Controller
 * Mengelola login, logout, dan registrasi pengguna
 */

class AuthController {
    private $conn;
    private $user_model;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
        include_once __DIR__ . '/../models/User.php';
        $this->user_model = new User($this->conn);
    }
    
    /**
     * Handle login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email'] ?? '');
            $password = sanitize($_POST['password'] ?? '');
            
            if (empty($email) || empty($password)) {
                setAlert('danger', 'Email dan password harus diisi');
                return false;
            }
            
            if (!validateEmail($email)) {
                setAlert('danger', 'Format email tidak valid');
                return false;
            }
            
            // Cek user di database
            $user = $this->user_model->login($email, $password);
            
            if ($user) {
                // Set session
                setUserSession($user);
                setAlert('success', 'Login berhasil');
                
                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    redirect(BASE_URL . 'index.php?page=admin/dashboard');
                } else {
                    redirect(BASE_URL . 'index.php?page=user/dashboard');
                }
            } else {
                setAlert('danger', 'Email atau password salah');
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Handle logout
     */
    public function logout() {
        destroySession();
        setAlert('success', 'Logout berhasil');
        redirect(BASE_URL . 'index.php?page=login');
    }
    
    /**
     * Handle registrasi
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = sanitize($_POST['nama'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $password = sanitize($_POST['password'] ?? '');
            $password_confirm = sanitize($_POST['password_confirm'] ?? '');
            $no_telepon = sanitize($_POST['no_telepon'] ?? '');
            $alamat = sanitize($_POST['alamat'] ?? '');
            
            // Validasi
            $errors = [];
            
            if (empty($nama)) {
                $errors[] = 'Nama harus diisi';
            }
            
            if (empty($email)) {
                $errors[] = 'Email harus diisi';
            }
            
            if (!validateEmail($email)) {
                $errors[] = 'Format email tidak valid';
            }
            
            if ($this->user_model->emailExists($email)) {
                $errors[] = 'Email sudah terdaftar';
            }
            
            if (empty($password)) {
                $errors[] = 'Password harus diisi';
            }
            
            if (strlen($password) < 6) {
                $errors[] = 'Password minimal 6 karakter';
            }
            
            if ($password !== $password_confirm) {
                $errors[] = 'Konfirmasi password tidak cocok';
            }
            
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    setAlert('danger', $error);
                }
                return false;
            }
            
            // Buat user baru
            $user_id = $this->user_model->register($nama, $email, $password, $no_telepon, $alamat);
            
            if ($user_id) {
                setAlert('success', 'Registrasi berhasil. Silakan login');
                redirect(BASE_URL . 'index.php?page=login');
            } else {
                setAlert('danger', 'Registrasi gagal. Silakan coba lagi');
                return false;
            }
        }
        
        return true;
    }
}
