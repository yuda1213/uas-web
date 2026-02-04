<?php
/**
 * Model Category
 * Mengelola operasi database untuk tabel categories
 */

if (!class_exists('Category')) {
    class Category {
        private $conn;
        private $table = 'categories';
        
        public function __construct($database) {
            $this->conn = $database;
        }
    
    /**
     * Get all categories
     */
    public function getAllCategories() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nama_kategori ASC";
        return $this->conn->query($query);
    }
    
    /**
     * Get category by ID
     */
    public function getCategoryById($id) {
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
     * Get categories dengan pagination
     */
    public function getCategoriesWithPagination($limit = 10, $offset = 0, $search = '') {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE nama_kategori LIKE ? 
                  ORDER BY nama_kategori ASC 
                  LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $search_param = '%' . $search . '%';
        $stmt->bind_param("sii", $search_param, $limit, $offset);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * Count categories
     */
    public function countCategories($search = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE nama_kategori LIKE ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return 0;
        }
        
        $search_param = '%' . $search . '%';
        $stmt->bind_param("s", $search_param);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    
    /**
     * Create category
     */
    public function createCategory($nama_kategori, $deskripsi, $gambar = null) {
        $query = "INSERT INTO " . $this->table . " (nama_kategori, deskripsi, gambar) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("sss", $nama_kategori, $deskripsi, $gambar);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Update category
     */
    public function updateCategory($id, $nama_kategori, $deskripsi, $gambar = null) {
        if ($gambar) {
            $query = "UPDATE " . $this->table . " SET nama_kategori = ?, deskripsi = ?, gambar = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssi", $nama_kategori, $deskripsi, $gambar, $id);
        } else {
            $query = "UPDATE " . $this->table . " SET nama_kategori = ?, deskripsi = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssi", $nama_kategori, $deskripsi, $id);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Delete category
     */
    public function deleteCategory($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Check if category name exists (untuk validasi unique)
     */
    public function categoryNameExists($nama_kategori, $exclude_id = null) {
        if ($exclude_id) {
            $query = "SELECT id FROM " . $this->table . " WHERE nama_kategori = ? AND id != ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("si", $nama_kategori, $exclude_id);
        } else {
            $query = "SELECT id FROM " . $this->table . " WHERE nama_kategori = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $nama_kategori);
        }
        
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
    }
}
