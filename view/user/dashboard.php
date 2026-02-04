<?php
$title = 'Dashboard Pelanggan';
include __DIR__ . '/../layout_header.php';

$order_model = new Order($conn);
$user_id = getCurrentUser()['id'];

// Get user orders
$user_orders = $order_model->getOrdersWithPagination(5, 0, '', null, $user_id);
$order_stats = $order_model->getOrderStats($user_id);
?>

<style>
    :root {
        --coffee-dark: #2D1B00;
        --coffee: #6F4E37;
        --coffee-light: #8B6F47;
        --gold: #D4A574;
        --cream: #FAF7F4;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes glow {
        0%, 100% { box-shadow: 0 0 20px rgba(212, 165, 116, 0.3); }
        50% { box-shadow: 0 0 40px rgba(212, 165, 116, 0.6); }
    }

    @keyframes ripple {
        0% { 
            transform: scale(0);
            opacity: 1;
        }
        100% {
            transform: scale(4);
            opacity: 0;
        }
    }

    @keyframes slideUp {
        from { 
            opacity: 0; 
            transform: translateY(30px);
        }
        to { 
            opacity: 1; 
            transform: translateY(0);
        }
    }

    @keyframes textShine {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }

    @keyframes borderGlow {
        0%, 100% { border-color: rgba(212, 165, 116, 0.3); }
        50% { border-color: rgba(212, 165, 116, 0.8); }
    }

    @keyframes subtleShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(1px); }
        75% { transform: translateX(-1px); }
    }

    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .page-header {
        margin-bottom: 28px;
        padding: 32px 28px;
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #8B6F47 100%);
        color: #fff;
        border-radius: 20px;
        box-shadow: 0 24px 64px rgba(45, 27, 0, 0.3),
                    inset 0 1px 0 rgba(255,255,255,0.15);
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        backdrop-filter: blur(12px);
        border: 1.5px solid rgba(255,255,255,0.12);
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.12), transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.08), transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    .page-header h2 { margin: 0 0 8px; font-size: 28px; font-weight: 800; letter-spacing: -0.8px; position: relative; z-index: 1; text-shadow: 0 4px 12px rgba(0,0,0,0.3), 0 2px 4px rgba(0,0,0,0.2); line-height: 1.2; }
    .page-header p { margin: 0; opacity: 0.92; font-size: 14px; position: relative; z-index: 1; font-weight: 500; letter-spacing: 0.4px; line-height: 1.6; }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
        animation: slideUp 0.8s ease-out;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.96);
        border: 1.5px solid rgba(212, 165, 116, 0.3);
        border-radius: 20px;
        padding: 24px;
        display: flex;
        gap: 16px;
        align-items: center;
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.08),
                    0 0 1px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        animation: fadeUp 0.6s ease-out backwards;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(12px);
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }

    .stat-card:hover::before {
        left: 100%;
    }

    .stat-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 28px 64px rgba(45, 27, 0, 0.16),
                    0 8px 24px rgba(212, 165, 116, 0.2),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        border-color: rgba(212, 165, 116, 0.6);
        background: rgba(255, 255, 255, 0.98);
    }

    .stat-card .icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, #FFF8F3 0%, #FFF5EB 50%, #FFE8D0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: var(--coffee);
        flex-shrink: 0;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(111, 78, 55, 0.15);
        border: 1px solid rgba(212, 165, 116, 0.2);
    }

    .stat-card:hover .icon {
        transform: scale(1.15) rotate(8deg);
        box-shadow: 0 8px 24px rgba(111, 78, 55, 0.25);
    }

    .stat-card:nth-child(2) .icon { 
        color: #fff; 
        background: linear-gradient(135deg, #E8F5E9 0%, #A5D6A7 50%, #7CB342 100%);
        box-shadow: 0 12px 32px rgba(46, 125, 50, 0.25);
    }
    .stat-card:nth-child(3) .icon { 
        color: #fff; 
        background: linear-gradient(135deg, #FFF3E0 0%, #FFB74D 50%, #E65100 100%);
        box-shadow: 0 12px 32px rgba(230, 81, 0, 0.25);
    }
    .stat-card:nth-child(4) .icon { 
        color: #fff; 
        background: linear-gradient(135deg, #E3F2FD 0%, #81D4FA 50%, #01579B 100%);
        box-shadow: 0 12px 32px rgba(25, 118, 210, 0.25);
    }

    .stat-card .content h4 { margin: 0 0 8px; font-size: 11px; color: #999; font-weight: 800; text-transform: uppercase; letter-spacing: 1.4px; }
    .stat-card .content p { margin: 0; font-size: 28px; font-weight: 900; background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #D4A574 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; line-height: 1.1; }

    .quick-actions {
        margin: 28px 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
    }

    .quick-action {
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 72px;
        border-radius: 16px;
        font-weight: 700;
        text-decoration: none;
        background: linear-gradient(135deg, #FFF8F3 0%, #FFF5EB 100%);
        border: 1.5px solid rgba(212, 165, 116, 0.4);
        color: #6F4E37;
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.1),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        font-size: 15px;
        backdrop-filter: blur(12px);
        letter-spacing: 0.3px;
    }

    .quick-action::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(212, 165, 116, 0.15) 0%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .quick-action:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 20px 48px rgba(45, 27, 0, 0.15),
                    0 8px 24px rgba(212, 165, 116, 0.25),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        border-color: rgba(212, 165, 116, 0.8);
        background: linear-gradient(135deg, rgba(255,243,232,0.96) 0%, rgba(255,253,251,0.96) 100%);
    }

    .quick-action:hover::before {
        opacity: 1;
    }

    .card {
        border-radius: 20px;
        overflow: hidden;
        border: 1.5px solid rgba(212, 165, 116, 0.2);
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.08),
                    0 0 1px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        animation: fadeUp 0.6s ease-out 0.5s backwards;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(12px);
    }

    .card-header {
        background: linear-gradient(135deg, rgba(255,248,241,0.85) 0%, rgba(255,243,232,0.85) 100%);
        border-bottom: 1px solid rgba(212, 165, 116, 0.2);
        padding: 18px;
        backdrop-filter: blur(10px);
    }

    .card-header h3 {
        margin: 0;
        font-size: 16px;
        color: #6F4E37;
    }

    .table thead th {
        background: #FFF8F1;
        color: #6F4E37;
        border-bottom: 2px solid #EFE6DD;
        font-weight: 700;
    }

    .table tbody tr {
        transition: background 0.2s ease;
    }

    .table tbody tr:hover {
        background: #FFFDFB;
    }

    .badge {
        border-radius: 999px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }
</style>

<div class="container">
    <div class="page-header">
        <h2>Dashboard Pelanggan</h2>
        <p>Selamat datang, <?php echo htmlspecialchars(getCurrentUser()['nama']); ?>!</p>
    </div>
    
    <!-- Statistics -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="icon">
                <i class="fas fa-shopping-bag" style="color: #667eea;"></i>
            </div>
            <div class="content">
                <h4>Total Pesanan</h4>
                <p><?php echo $order_stats['total_pesanan']; ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="icon">
                <i class="fas fa-check-circle" style="color: #4caf50;"></i>
            </div>
            <div class="content">
                <h4>Pesanan Selesai</h4>
                <p><?php echo $order_stats['selesai']; ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="icon">
                <i class="fas fa-spinner" style="color: #ff9800;"></i>
            </div>
            <div class="content">
                <h4>Sedang Diproses</h4>
                <p><?php echo $order_stats['diproses']; ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="icon">
                <i class="fas fa-money-bill" style="color: #f44336;"></i>
            </div>
            <div class="content">
                <h4>Total Belanja</h4>
                <p><?php echo formatRupiah($order_stats['total_penjualan']); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="<?php echo BASE_URL . 'index.php?page=user/menu'; ?>" class="quick-action">
            <i class="fas fa-coffee" style="margin-right: 10px;"></i> Pesan Kopi
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=user/cart'; ?>" class="quick-action">
            <i class="fas fa-shopping-cart" style="margin-right: 10px;"></i> Keranjang
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=user/orders'; ?>" class="quick-action">
            <i class="fas fa-list" style="margin-right: 10px;"></i> Pesanan Saya
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=user/profile'; ?>" class="quick-action">
            <i class="fas fa-user" style="margin-right: 10px;"></i> Profile
        </a>
    </div>
    
    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list"></i> Pesanan Terbaru</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>No Pesanan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($user_orders && $user_orders->num_rows > 0) {
                        while ($order = $user_orders->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><strong>' . htmlspecialchars($order['no_pesanan']) . '</strong></td>';
                            echo '<td>' . formatTanggalId($order['tanggal_pesanan']) . '</td>';
                            echo '<td>' . formatRupiah($order['total_harga']) . '</td>';
                            echo '<td>';
                            
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
                            echo '</td>';
                            echo '<td><a href="' . BASE_URL . 'index.php?page=user/orders/detail&id=' . $order['id'] . '" class="btn btn-sm">Detail</a></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5" style="text-align: center;">Belum ada pesanan</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="<?php echo BASE_URL . 'index.php?page=user/orders'; ?>" class="btn">Lihat Semua Pesanan</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
