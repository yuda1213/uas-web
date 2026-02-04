<?php
/**
 * Model User
 * Mengelola operasi database untuk tabel users
 */

if (!class_exists('User')) {
    class User {
        private $conn;
        private $table = 'users';
        
        public function __construct($database) {
            $this->conn = $database;
        }
        
        /**
         * Login user
         */
        public function login($email, $password) {
            $query = "SELECT * FROM " . $this->table . " WHERE email = ? AND password = ? AND status = 'aktif'";
            $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $hashed_password = md5($password);
        $stmt->bind_param("ss", $email, $hashed_password);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Registrasi user baru
     */
    public function register($nama, $email, $password, $no_telepon, $alamat) {
        $query = "INSERT INTO " . $this->table . " (nama, email, password, no_telepon, alamat, role, status) 
                  VALUES (?, ?, ?, ?, ?, 'user', 'aktif')";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $hashed_password = md5($password);
        $stmt->bind_param("sssss", $nama, $email, $hashed_password, $no_telepon, $alamat);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Cek email sudah terdaftar
     */
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        return $stmt->get_result()->num_rows > 0;
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Get all users dengan pagination
     */
    public function getAllUsers($limit = 10, $offset = 0, $search = '') {
        $query = "SELECT * FROM " . $this->table . " WHERE (nama LIKE ? OR email LIKE ?) ORDER BY tanggal_daftar DESC LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $search_param = '%' . $search . '%';
        $stmt->bind_param("ssii", $search_param, $search_param, $limit, $offset);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * Count users dengan search
     */
    public function countUsers($search = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE (nama LIKE ? OR email LIKE ?)";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return 0;
        }
        
        $search_param = '%' . $search . '%';
        $stmt->bind_param("ss", $search_param, $search_param);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    
    /**
     * Update user
     */
    public function updateUser($id, $nama, $email, $no_telepon, $alamat, $role, $status) {
        $query = "UPDATE " . $this->table . " SET nama = ?, email = ?, no_telepon = ?, alamat = ?, role = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("ssssssi", $nama, $email, $no_telepon, $alamat, $role, $status, $id);
        return $stmt->execute();
    }
    
    /**
     * Update profile user
     */
    public function updateProfile($id, $nama, $no_telepon, $alamat, $foto = null) {
        if ($foto) {
            $query = "UPDATE " . $this->table . " SET nama = ?, no_telepon = ?, alamat = ?, foto_profil = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssi", $nama, $no_telepon, $alamat, $foto, $id);
        } else {
            $query = "UPDATE " . $this->table . " SET nama = ?, no_telepon = ?, alamat = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssi", $nama, $no_telepon, $alamat, $id);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Change password
     */
    public function changePassword($id, $new_password) {
        $query = "UPDATE " . $this->table . " SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $hashed_password = md5($new_password);
        $stmt->bind_param("si", $hashed_password, $id);
        return $stmt->execute();
    }
    
    /**
     * Delete user
     */
    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Get dashboard statistics
     */
    public function getUserStats() {
        $query = "SELECT 
                    COUNT(CASE WHEN role = 'user' THEN 1 END) as total_user,
                    COUNT(CASE WHEN role = 'admin' THEN 1 END) as total_admin,
                    COUNT(CASE WHEN status = 'aktif' THEN 1 END) as total_aktif,
                    COUNT(*) as total_all
                  FROM " . $this->table;
        
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    }
}
