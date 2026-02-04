<?php
/**
 * Model Product
 * Mengelola operasi database untuk tabel products
 */

if (!class_exists('Product')) {
    class Product {
        private $conn;
        private $table = 'products';
        
        public function __construct($database) {
            $this->conn = $database;
        }
    
    /**
     * Get all products
     */
    public function getAllProducts() {
        $query = "SELECT p.*, c.nama_kategori 
                  FROM " . $this->table . " p 
                  LEFT JOIN categories c ON p.kategori_id = c.id 
                  WHERE p.status = 'tersedia'
                  ORDER BY p.nama_produk ASC";
        return $this->conn->query($query);
    }
    
    /**
     * Get products dengan pagination dan search
     */
    public function getProductsWithPagination($limit = 10, $offset = 0, $search = '', $kategori_id = null) {
        $query = "SELECT p.*, c.nama_kategori 
                  FROM " . $this->table . " p 
                  LEFT JOIN categories c ON p.kategori_id = c.id 
                  WHERE (p.nama_produk LIKE ? OR c.nama_kategori LIKE ?)";
        
        if ($kategori_id) {
            $query .= " AND p.kategori_id = " . (int)$kategori_id;
        }
        
        $query .= " ORDER BY p.nama_produk ASC LIMIT ? OFFSET ?";
        
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
     * Count products
     */
    public function countProducts($search = '', $kategori_id = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " p 
                  LEFT JOIN categories c ON p.kategori_id = c.id 
                  WHERE (p.nama_produk LIKE ? OR c.nama_kategori LIKE ?)";
        
        if ($kategori_id) {
            $query .= " AND p.kategori_id = " . (int)$kategori_id;
        }
        
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
     * Get product by ID
     */
    public function getProductById($id) {
        $query = "SELECT p.*, c.nama_kategori 
                  FROM " . $this->table . " p 
                  LEFT JOIN categories c ON p.kategori_id = c.id 
                  WHERE p.id = ?";
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
     * Get products by category
     */
    public function getProductsByCategory($kategori_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE kategori_id = ? AND status = 'tersedia' ORDER BY nama_produk ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $kategori_id);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * Create product
     */
    public function createProduct($nama_produk, $kategori_id, $harga, $deskripsi, $stok, $gambar = null) {
        $query = "INSERT INTO " . $this->table . " (nama_produk, kategori_id, harga, deskripsi, stok, gambar, status) 
                  VALUES (?, ?, ?, ?, ?, ?, 'tersedia')";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            error_log('Product createProduct prepare error: ' . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param("sidsis", $nama_produk, $kategori_id, $harga, $deskripsi, $stok, $gambar);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Update product
     */
    public function updateProduct($id, $nama_produk, $kategori_id, $harga, $deskripsi, $stok, $status, $gambar = null) {
        if ($gambar) {
            $query = "UPDATE " . $this->table . " 
                      SET nama_produk = ?, kategori_id = ?, harga = ?, deskripsi = ?, stok = ?, status = ?, gambar = ?, updated_at = CURRENT_TIMESTAMP 
                      WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log('Product updateProduct prepare error: ' . $this->conn->error);
                return false;
            }
            $stmt->bind_param("sidsissi", $nama_produk, $kategori_id, $harga, $deskripsi, $stok, $status, $gambar, $id);
        } else {
            $query = "UPDATE " . $this->table . " 
                      SET nama_produk = ?, kategori_id = ?, harga = ?, deskripsi = ?, stok = ?, status = ?, updated_at = CURRENT_TIMESTAMP 
                      WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                error_log('Product updateProduct prepare error: ' . $this->conn->error);
                return false;
            }
            $stmt->bind_param("sidsisi", $nama_produk, $kategori_id, $harga, $deskripsi, $stok, $status, $id);
        }
        
        return $stmt->execute();
    }
    
    /**
     * Delete product
     */
    public function deleteProduct($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Get product statistics
     */
    public function getProductStats() {
        $query = "SELECT 
                    COUNT(*) as total_produk,
                    COUNT(CASE WHEN status = 'tersedia' THEN 1 END) as tersedia,
                    COUNT(CASE WHEN status = 'tidak_tersedia' THEN 1 END) as tidak_tersedia,
                    SUM(stok) as total_stok,
                    AVG(harga) as harga_rata_rata
                  FROM " . $this->table;
        
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    }
}
