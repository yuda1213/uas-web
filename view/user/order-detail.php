<?php
$title = 'Detail Pesanan';
include __DIR__ . '/../layout_header.php';

$order_model = new Order($conn);

// Get order
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = $order_model->getOrderById($id);

if (!$order || $order['user_id'] != getCurrentUser()['id']) {
    setAlert('danger', 'Pesanan tidak ditemukan');
    redirect(BASE_URL . 'index.php?page=user/orders');
}

// Get order items
$items = $order_model->getOrderItems($id);
?>

<style>
    :root {
        --coffee-dark: #2D1B00;
        --coffee: #6F4E37;
        --coffee-light: #8B6F47;
        --gold: #D4A574;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 10px 0 24px;
        padding: 32px 28px;
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #8B6F47 100%);
        color: #fff;
        border-radius: 20px;
        box-shadow: 0 24px 64px rgba(45, 27, 0, 0.3),
                    inset 0 1px 0 rgba(255,255,255,0.15);
        position: relative;
        overflow: hidden;
        animation: slideDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        backdrop-filter: blur(12px);
        border: 1.5px solid rgba(255,255,255,0.12);
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -20%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.12), transparent 70%);
    }

    .page-header h2 { 
        margin: 0; 
        font-size: 28px; 
        font-weight: 900; 
        position: relative; 
        z-index: 1; 
        text-shadow: 0 4px 12px rgba(0,0,0,0.25), 0 2px 4px rgba(0,0,0,0.1);
        letter-spacing: -0.6px;
        line-height: 1.2;
    }
    .page-header a { position: relative; z-index: 1; }

    .order-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 24px;
        margin-top: 24px;
    }

    .card {
        border-radius: 20px;
        overflow: hidden;
        border: 1.5px solid rgba(212, 165, 116, 0.2);
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.08),
                    0 0 1px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        animation: slideInLeft 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(12px);
    }

    .card:last-child {
        animation: fadeUp 0.6s ease-out 0.2s backwards;
    }

    .card-header { 
        background: linear-gradient(135deg, rgba(255,248,241,0.9) 0%, rgba(255,243,232,0.9) 100%);
        border-bottom: 1.5px solid rgba(212, 165, 116, 0.2);
        padding: 18px;
        backdrop-filter: blur(8px);
    }

    .card-header h3 {
        margin: 0;
        font-size: 16px;
        color: #6F4E37;
        font-weight: 900;
        letter-spacing: -0.4px;
        line-height: 1.2;
    }

    .card-body {
        padding: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th { 
        background: linear-gradient(135deg, rgba(255,248,241,0.9) 0%, rgba(255,243,232,0.9) 100%); 
        color: #6F4E37; 
        border-bottom: 1.5px solid rgba(212, 165, 116, 0.2);
        font-weight: 800;
        padding: 14px;
        text-align: left;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .table tbody tr {
        border-bottom: 1px solid rgba(239, 230, 221, 0.4);
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(255, 248, 241, 0.9);
        box-shadow: inset 0 0 16px rgba(212, 165, 116, 0.08);
    }

    .table tbody td {
        padding: 12px;
        color: #2D1B00;
        font-size: 14px;
        line-height: 1.5;
        letter-spacing: 0.2px;
    }

    .badge { 
        border-radius: 999px; 
        padding: 8px 16px; 
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        box-shadow: 0 4px 12px rgba(45, 27, 0, 0.08);
        transition: all 0.3s ease;
        letter-spacing: 0.4px;
    }

    .badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45, 27, 0, 0.15);
    }

    .badge-warning {
        background: linear-gradient(135deg, rgba(255,243,232,0.95) 0%, rgba(255,229,180,0.8) 100%);
        color: #B26A00;
        border: 1px solid rgba(255, 179, 77, 0.2);
    }

    .badge-info {
        background: linear-gradient(135deg, rgba(227,242,253,0.95) 0%, rgba(129,212,250,0.8) 100%);
        color: #0277BD;
        border: 1px solid rgba(129, 212, 250, 0.2);
    }

    .badge-success {
        background: linear-gradient(135deg, rgba(232,245,233,0.95) 0%, rgba(165,214,167,0.8) 100%);
        color: #1B5E20;
        border: 1px solid rgba(76, 175, 80, 0.2);
    }

    .badge-danger {
        background: linear-gradient(135deg, rgba(253,236,236,0.95) 0%, rgba(229,57,57,0.15) 100%);
        color: #B91C1C;
        border: 1px solid rgba(229, 57, 57, 0.2);
    }

    .summary-card {
        background: rgba(255, 255, 255, 0.96);
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.08),
                    0 0 1px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        border: 1.5px solid rgba(212, 165, 116, 0.2);
        position: sticky;
        top: 120px;
        animation: fadeUp 0.6s ease-out 0.3s backwards;
        backdrop-filter: blur(12px);
    }

    .summary-card h3 {
        margin: 0 0 18px 0;
        font-size: 16px;
        font-weight: 800;
        color: #2D1B00;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid rgba(239, 230, 221, 0.5);
        font-size: 14px;
        color: #666;
    }

    .summary-row.total {
        border-bottom: none;
        font-weight: 900;
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #D4A574 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 22px;
        padding-top: 16px;
        margin-top: 14px;
        border-top: 1.5px solid rgba(212, 165, 116, 0.2);
    }

    .order-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .order-info-item strong {
        display: block;
        color: #2D1B00;
        font-weight: 700;
        margin-bottom: 4px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #999;
    }

    .order-info-item {
        font-size: 14px;
        color: #2D1B00;
        line-height: 1.6;
    }

    .btn {
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45, 27, 0, 0.15);
    }

    .btn-secondary {
        background: #FFF;
        color: var(--coffee);
        border: 1.5px solid #E3D5C8;
    }

    .btn-secondary:hover {
        background: #FFF6ED;
        border-color: #D4A574;
    }

    .divider {
        margin: 20px 0;
        border-bottom: 2px solid #EFE6DD;
    }

    @media (max-width: 900px) {
        .order-grid { 
            grid-template-columns: 1fr;
        }

        .order-info-grid {
            grid-template-columns: 1fr;
        }

        .summary-card {
            position: static;
        }
    }
</style>

<div class="container">
    <div class="page-header">
        <h2>Detail Pesanan</h2>
        <a href="<?php echo BASE_URL . 'index.php?page=user/orders'; ?>" class="btn btn-secondary">Kembali</a>
    </div>
    
    <div class="order-grid">
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
                            
                            <strong>Status:</strong><br>
                            <?php
                            $status_class = '';
                            $status_text = '';
                            
                            switch ($order['status']) {
                                case 'pending':
                                    $status_class = 'badge-warning';
                                    $status_text = 'Menunggu Konfirmasi';
                                    break;
                                case 'diproses':
                                    $status_class = 'badge-info';
                                    $status_text = 'Sedang Diproses';
                                    break;
                                case 'selesai':
                                    $status_class = 'badge-success';
                                    $status_text = 'Selesai';
                                    break;
                                case 'dibatalkan':
                                    $status_class = 'badge-danger';
                                    $status_text = 'Dibatalkan';
                                    break;
                            }
                            
                            echo '<span class="badge ' . $status_class . '">' . $status_text . '</span>';
                            ?>
                        </div>
                        <div>
                            <strong>Nama:</strong><br>
                            <?php echo htmlspecialchars($order['nama']); ?><br><br>
                            
                            <strong>Email:</strong><br>
                            <?php echo htmlspecialchars($order['email']); ?><br><br>
                            
                            <strong>No Telepon:</strong><br>
                            <?php echo htmlspecialchars($order['no_telepon']); ?>
                        </div>
                    </div>
                    
                    <hr style="margin: 20px 0; border-color: #EFE6DD;">
                    
                    <strong>Alamat Pengiriman:</strong><br>
                    <?php echo htmlspecialchars($order['alamat']); ?><br><br>
                    
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
        
        <!-- Summary -->
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
                    <div class="summary-total" style="display: flex; justify-content: space-between; font-size: 18px;">
                        <span>Total:</span>
                        <span><?php echo formatRupiah($order['total_harga']); ?></span>
                    </div>
                </div>
            </div>
            
            <?php if ($order['status'] === 'pending') { ?>
                <div class="alert alert-warning" style="margin-top: 20px;">
                    <i class="fas fa-info-circle"></i> Pesanan Anda sedang menunggu konfirmasi dari admin.
                </div>
            <?php } elseif ($order['status'] === 'selesai') { ?>
                <div class="alert alert-success" style="margin-top: 20px;">
                    <i class="fas fa-check-circle"></i> Pesanan Anda telah selesai.
                </div>
            <?php } elseif ($order['status'] === 'diproses') { ?>
                <div class="alert alert-info" style="margin-top: 20px;">
                    <i class="fas fa-spinner"></i> Pesanan Anda sedang diproses.
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
