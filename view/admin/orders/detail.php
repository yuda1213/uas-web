<?php
/**
 * Admin Order Detail
 */

$title = 'Detail Pesanan';
include __DIR__ . '/../../layout_header.php';

$order_model = new Order($conn);

// Get order
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = $order_model->getOrderById($id);

if (!$order) {
    setAlert('danger', 'Pesanan tidak ditemukan');
    redirect(BASE_URL . 'index.php?page=admin/orders');
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = sanitize($_POST['status'] ?? '');
    
    if ($order_model->updateOrderStatus($id, $status)) {
        setAlert('success', 'Status pesanan berhasil diperbarui');
        redirect(BASE_URL . 'index.php?page=admin/orders/detail&id=' . $id);
    } else {
        setAlert('danger', 'Gagal memperbarui status pesanan');
    }
}

// Get order items
$items = $order_model->getOrderItems($id);

// Refresh order data
$order = $order_model->getOrderById($id);
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Detail Pesanan</h2>
        <a href="<?php echo BASE_URL . 'index.php?page=admin/orders'; ?>" class="btn btn-secondary">Kembali</a>
    </div>
    
    <?php 
    $alert = getAlert();
    if ($alert) {
        echo '<div class="alert alert-' . htmlspecialchars($alert['type']) . '">';
        echo htmlspecialchars($alert['message']);
        echo '</div>';
    }
    ?>
    
    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 20px;">
        <div>
            <!-- Order Info -->
            <div class="card">
                <div class="card-header">
                    <h3>Informasi Pesanan</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                            <strong>No Pesanan:</strong><br>
                            <?php echo htmlspecialchars($order['no_pesanan']); ?><br><br>
                            
                            <strong>Tanggal Pesanan:</strong><br>
                            <?php echo formatTanggalId($order['tanggal_pesanan']); ?><br><br>
                        </div>
                        <div>
                            <strong>Nama Pelanggan:</strong><br>
                            <?php echo htmlspecialchars($order['nama']); ?><br><br>
                            
                            <strong>Email:</strong><br>
                            <?php echo htmlspecialchars($order['email']); ?>
                        </div>
                    </div>
                    
                    <hr style="margin: 20px 0;">
                    
                    <strong>No Telepon:</strong><br>
                    <?php echo htmlspecialchars($order['no_telepon']); ?><br><br>
                    
                    <?php if (!empty($order['tipe_pesanan'])) { ?>
                        <strong>Tipe Pesanan:</strong><br>
                        <?php echo $order['tipe_pesanan'] === 'delivery' ? '<span style="color: #2196F3;"><i class="fas fa-motorcycle"></i> Delivery</span>' : '<span style="color: #4CAF50;"><i class="fas fa-utensils"></i> Makan di Tempat</span>'; ?><br><br>
                    <?php } ?>
                    
                    <strong>Alamat Pengiriman:</strong><br>
                    <?php 
                    // Prioritas: alamat_pengiriman dari order, lalu alamat dari user
                    $alamat_display = !empty($order['alamat_pengiriman']) ? $order['alamat_pengiriman'] : $order['alamat'];
                    echo htmlspecialchars($alamat_display); 
                    ?><br><br>
                    
                    <?php if ($order['catatan']) { ?>
                        <strong>Catatan:</strong><br>
                        <?php echo htmlspecialchars($order['catatan']); ?><br><br>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3>Item Pesanan</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($items && $items->num_rows > 0) {
                                while ($item = $items->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td><strong>' . htmlspecialchars($item['nama_produk']) . '</strong></td>';
                                    echo '<td style="text-align: center;">' . $item['jumlah'] . '</td>';
                                    echo '<td>' . formatRupiah($item['harga_satuan']) . '</td>';
                                    echo '<td>' . formatRupiah($item['subtotal']) . '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Summary & Status -->
        <div>
            <div class="card">
                <div class="card-header">
                    <h3>Ringkasan</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span>Subtotal:</span>
                        <strong><?php echo formatRupiah($order['total_harga'] / 1.1); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        <span>Pajak (10%):</span>
                        <strong><?php echo formatRupiah($order['total_harga'] / 1.1 * 0.1); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold; color: #667eea;">
                        <span>Total:</span>
                        <span><?php echo formatRupiah($order['total_harga']); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Status Update Form -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3>Update Status</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="status">Status Pesanan</label>
                            <select id="status" name="status" required>
                                <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="diproses" <?php echo $order['status'] === 'diproses' ? 'selected' : ''; ?>>Diproses</option>
                                <option value="selesai" <?php echo $order['status'] === 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                                <option value="dibatalkan" <?php echo $order['status'] === 'dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success" style="width: 100%;">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layout_footer.php'; ?>
