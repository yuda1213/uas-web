<?php
/**
 * Model Order
 * Mengelola operasi database untuk tabel orders dan order_items
 */

if (!class_exists('Order')) {
    class Order {
        private $conn;
        private $table = 'orders';
        private $items_table = 'order_items';
        
        public function __construct($database) {
            $this->conn = $database;
        }
    
    /**
     * Create order baru
     */
    public function createOrder($user_id, $total_harga, $catatan = '', $tipe_pesanan = 'dine_in', $alamat_pengiriman = '') {
        $no_pesanan = generateOrderNumber();
        $query = "INSERT INTO " . $this->table . " (user_id, no_pesanan, total_harga, catatan, tipe_pesanan, alamat_pengiriman, status) 
                  VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("isdsss", $user_id, $no_pesanan, $total_harga, $catatan, $tipe_pesanan, $alamat_pengiriman);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Add item ke order
     */
    public function addOrderItem($order_id, $product_id, $jumlah, $harga_satuan, $subtotal) {
        $query = "INSERT INTO " . $this->items_table . " (order_id, product_id, jumlah, harga_satuan, subtotal) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("iiidd", $order_id, $product_id, $jumlah, $harga_satuan, $subtotal);
        return $stmt->execute();
    }
    
    /**
     * Get order by ID
     */
    public function getOrderById($id) {
        $query = "SELECT o.*, u.nama, u.email, u.no_telepon, u.alamat
                  FROM " . $this->table . " o 
                  LEFT JOIN users u ON o.user_id = u.id 
                  WHERE o.id = ?";
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
     * Get order items
     */
    public function getOrderItems($order_id) {
        $query = "SELECT oi.*, p.nama_produk 
                  FROM " . $this->items_table . " oi 
                  LEFT JOIN products p ON oi.product_id = p.id 
                  WHERE oi.order_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * Get orders dengan pagination
     */
    public function getOrdersWithPagination($limit = 10, $offset = 0, $search = '', $status = null, $user_id = null) {
        $query = "SELECT o.*, u.nama 
                  FROM " . $this->table . " o 
                  LEFT JOIN users u ON o.user_id = u.id 
                  WHERE (o.no_pesanan LIKE ? OR u.nama LIKE ? OR u.email LIKE ?)";
        
        if ($status) {
            $query .= " AND o.status = '" . $this->conn->real_escape_string($status) . "'";
        }
        
        if ($user_id) {
            $query .= " AND o.user_id = " . (int)$user_id;
        }
        
        $query .= " ORDER BY o.tanggal_pesanan DESC LIMIT ? OFFSET ?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $search_param = '%' . $search . '%';
        $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $limit, $offset);
        $stmt->execute();
        
        return $stmt->get_result();
    }
    
    /**
     * Count orders
     */
    public function countOrders($search = '', $status = null, $user_id = null) {
        $query = "SELECT COUNT(*) as total 
                  FROM " . $this->table . " o 
                  LEFT JOIN users u ON o.user_id = u.id 
                  WHERE (o.no_pesanan LIKE ? OR u.nama LIKE ? OR u.email LIKE ?)";
        
        if ($status) {
            $query .= " AND o.status = '" . $this->conn->real_escape_string($status) . "'";
        }
        
        if ($user_id) {
            $query .= " AND o.user_id = " . (int)$user_id;
        }
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return 0;
        }
        
        $search_param = '%' . $search . '%';
        $stmt->bind_param("sss", $search_param, $search_param, $search_param);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'];
    }
    
    /**
     * Update order status
     */
    public function updateOrderStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }
        
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
    
    /**
     * Get order statistics
     */
    public function getOrderStats($user_id = null) {
        $query = "SELECT 
                    COUNT(*) as total_pesanan,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending,
                    COUNT(CASE WHEN status = 'diproses' THEN 1 END) as diproses,
                    COUNT(CASE WHEN status = 'selesai' THEN 1 END) as selesai,
                    COUNT(CASE WHEN status = 'dibatalkan' THEN 1 END) as dibatalkan,
                    COALESCE(SUM(total_harga), 0) as total_penjualan
                  FROM " . $this->table;
        
        if ($user_id) {
            $query .= " WHERE user_id = " . (int)$user_id;
        }
        
        $result = $this->conn->query($query);
        
        if ($result) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Get daily sales
     */
    public function getDailySales($date_from = null, $date_to = null) {
        $query = "SELECT DATE(tanggal_pesanan) as tanggal, COUNT(*) as jumlah_pesanan, COALESCE(SUM(total_harga), 0) as total_penjualan
                  FROM " . $this->table . " 
                  WHERE status IN ('selesai')";
        
        if ($date_from && $date_to) {
            $query .= " AND DATE(tanggal_pesanan) BETWEEN '" . $this->conn->real_escape_string($date_from) . "' AND '" . $this->conn->real_escape_string($date_to) . "'";
        }
        
        $query .= " GROUP BY DATE(tanggal_pesanan) ORDER BY tanggal DESC";
        
        return $this->conn->query($query);
    }
    
    /**
     * Get product sales report
     */
    public function getProductSalesReport($date_from = null, $date_to = null) {
        $query = "SELECT p.id, p.nama_produk, c.nama_kategori, SUM(oi.jumlah) as total_jumlah, COALESCE(SUM(oi.subtotal), 0) as total_penjualan
                  FROM " . $this->items_table . " oi 
                  LEFT JOIN products p ON oi.product_id = p.id 
                  LEFT JOIN categories c ON p.kategori_id = c.id 
                  LEFT JOIN " . $this->table . " o ON oi.order_id = o.id 
                  WHERE o.status = 'selesai'";
        
        if ($date_from && $date_to) {
            $query .= " AND DATE(o.tanggal_pesanan) BETWEEN '" . $this->conn->real_escape_string($date_from) . "' AND '" . $this->conn->real_escape_string($date_to) . "'";
        }
        
        $query .= " GROUP BY p.id, p.nama_produk, c.nama_kategori ORDER BY total_penjualan DESC";
        
        return $this->conn->query($query);
    }
    }
}
