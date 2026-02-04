<?php
$title = 'Dashboard Admin';
include __DIR__ . '/../layout_header.php';

// Inisialisasi models
$user_model = new User($conn);
$product_model = new Product($conn);
$order_model = new Order($conn);
$category_model = new Category($conn);

// Get statistics
$user_stats = $user_model->getUserStats();
$product_stats = $product_model->getProductStats();
$order_stats = $order_model->getOrderStats();

// Get recent orders
$recent_orders = $order_model->getOrdersWithPagination(5, 0);

// Count categories
$categories = $category_model->getAllCategories();
$total_categories = $categories ? $categories->num_rows : 0;
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

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-8px); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .dashboard-hero {
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #8B6F47 100%);
        color: #fff;
        padding: 36px 32px;
        border-radius: 24px;
        margin: 10px 0 32px;
        box-shadow: 0 28px 72px rgba(45, 27, 0, 0.35),
                    inset 0 1px 0 rgba(255,255,255,0.15);
        position: relative;
        overflow: hidden;
        animation: slideDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 1.5px solid rgba(255,255,255,0.12);
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -15%;
        width: 350px;
        height: 350px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.12), transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    .dashboard-hero h2 {
        margin: 0 0 8px 0;
        font-size: 32px;
        font-weight: 900;
        position: relative;
        z-index: 1;
        text-shadow: 0 4px 16px rgba(0,0,0,0.3);
        letter-spacing: -0.8px;
    }

    .dashboard-hero p {
        margin: 0;
        opacity: 0.95;
        font-size: 15px;
        position: relative;
        z-index: 1;
        font-weight: 500;
    }

    .dashboard-hero .date-info {
        position: absolute;
        right: 32px;
        top: 50%;
        transform: translateY(-50%);
        text-align: right;
        z-index: 1;
    }

    .dashboard-hero .date-info .day {
        font-size: 48px;
        font-weight: 900;
        line-height: 1;
        text-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .dashboard-hero .date-info .month-year {
        font-size: 14px;
        opacity: 0.9;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.96);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 12px 32px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        border: 1.5px solid rgba(212, 165, 116, 0.2);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        animation: fadeUp 0.6s ease-out backwards;
        backdrop-filter: blur(12px);
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.15s; }
    .stat-card:nth-child(3) { animation-delay: 0.2s; }
    .stat-card:nth-child(4) { animation-delay: 0.25s; }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 24px 56px rgba(45, 27, 0, 0.15),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        border-color: rgba(212, 165, 116, 0.4);
    }

    .stat-card .icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: all 0.3s ease;
    }

    .stat-card:hover .icon {
        transform: scale(1.1);
    }

    .stat-card.users .icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .stat-card.products .icon {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: #fff;
        box-shadow: 0 8px 20px rgba(255, 152, 0, 0.3);
    }

    .stat-card.orders .icon {
        background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
        color: #fff;
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
    }

    .stat-card.sales .icon {
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 100%);
        color: #fff;
        box-shadow: 0 8px 20px rgba(45, 27, 0, 0.3);
    }

    .stat-card .content h4 {
        margin: 0 0 4px 0;
        font-size: 13px;
        color: #8B7355;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card .content .number {
        font-size: 28px;
        font-weight: 900;
        color: #2D1B00;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }

    .stat-card .content small {
        font-size: 12px;
        color: #A89080;
        font-weight: 600;
    }

    .dashboard-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 28px;
    }

    .dashboard-card {
        background: rgba(255, 255, 255, 0.96);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        border: 1.5px solid rgba(212, 165, 116, 0.2);
        animation: fadeUp 0.6s ease-out 0.3s backwards;
        backdrop-filter: blur(12px);
    }

    .dashboard-card-header {
        background: linear-gradient(135deg, rgba(255, 248, 241, 0.95) 0%, rgba(255, 246, 237, 0.95) 100%);
        padding: 18px 24px;
        border-bottom: 1px solid rgba(212, 165, 116, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dashboard-card-header h3 {
        margin: 0;
        font-size: 16px;
        color: #2D1B00;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.3px;
    }

    .dashboard-card-header h3 i {
        color: #6F4E37;
        font-size: 18px;
    }

    .dashboard-card-body {
        padding: 20px 24px;
    }

    .status-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .status-list li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid rgba(212, 165, 116, 0.1);
        transition: all 0.2s ease;
    }

    .status-list li:last-child {
        border-bottom: none;
    }

    .status-list li:hover {
        padding-left: 8px;
    }

    .status-list .label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #5A4A3A;
        font-weight: 600;
    }

    .status-list .label .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .status-list .label .dot.pending { background: #ff9800; }
    .status-list .label .dot.process { background: #2196f3; }
    .status-list .label .dot.done { background: #4caf50; }
    .status-list .label .dot.cancel { background: #f44336; }

    .status-list .value {
        font-size: 18px;
        font-weight: 900;
        color: #2D1B00;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .quick-action {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: linear-gradient(135deg, rgba(255, 248, 241, 0.8) 0%, rgba(255, 253, 251, 0.8) 100%);
        border-radius: 14px;
        text-decoration: none;
        color: #2D1B00;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 1px solid rgba(212, 165, 116, 0.15);
    }

    .quick-action:hover {
        transform: translateY(-4px);
        background: linear-gradient(135deg, rgba(255, 246, 237, 0.95) 0%, rgba(212, 165, 116, 0.15) 100%);
        box-shadow: 0 12px 28px rgba(45, 27, 0, 0.12);
        border-color: rgba(212, 165, 116, 0.4);
    }

    .quick-action i {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        color: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        box-shadow: 0 6px 16px rgba(45, 27, 0, 0.15);
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table th {
        text-align: left;
        padding: 14px 16px;
        font-size: 12px;
        color: #8B7355;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(212, 165, 116, 0.2);
    }

    .orders-table td {
        padding: 16px;
        border-bottom: 1px solid rgba(212, 165, 116, 0.1);
        font-size: 14px;
        color: #5A4A3A;
    }

    .orders-table tr:hover {
        background: rgba(255, 248, 241, 0.5);
    }

    .orders-table .order-id {
        font-weight: 800;
        color: #2D1B00;
    }

    .orders-table .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .orders-table .badge-pending {
        background: rgba(255, 152, 0, 0.15);
        color: #e65100;
    }

    .orders-table .badge-process {
        background: rgba(33, 150, 243, 0.15);
        color: #1565c0;
    }

    .orders-table .badge-done {
        background: rgba(76, 175, 80, 0.15);
        color: #2e7d32;
    }

    .orders-table .badge-cancel {
        background: rgba(244, 67, 54, 0.15);
        color: #c62828;
    }

    .btn-detail {
        padding: 8px 16px;
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45, 27, 0, 0.2);
    }

    .dashboard-card-footer {
        padding: 16px 24px;
        background: linear-gradient(135deg, rgba(255, 248, 241, 0.5) 0%, rgba(255, 246, 237, 0.5) 100%);
        border-top: 1px solid rgba(212, 165, 116, 0.1);
        text-align: center;
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 8px 24px rgba(45, 27, 0, 0.2);
    }

    .btn-view-all:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 40px rgba(45, 27, 0, 0.3);
    }

    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .dashboard-row {
            grid-template-columns: 1fr;
        }
        .dashboard-hero .date-info {
            display: none;
        }
    }

    @media (max-width: 600px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .quick-actions {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container">
    <div class="dashboard-hero">
        <h2>Selamat Datang, <?php echo htmlspecialchars(getCurrentUser()['nama']); ?>!</h2>
        <p>Kelola bisnis kopi Anda dari satu tempat. Pantau pesanan, produk, dan performa toko.</p>
        <div class="date-info">
            <div class="day"><?php echo date('d'); ?></div>
            <div class="month-year"><?php echo date('F Y'); ?></div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card users">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="content">
                <h4>Total User</h4>
                <div class="number"><?php echo $user_stats['total_all']; ?></div>
                <small><?php echo $user_stats['total_aktif']; ?> user aktif</small>
            </div>
        </div>
        
        <div class="stat-card products">
            <div class="icon">
                <i class="fas fa-coffee"></i>
            </div>
            <div class="content">
                <h4>Total Produk</h4>
                <div class="number"><?php echo $product_stats['total_produk']; ?></div>
                <small><?php echo $product_stats['tersedia']; ?> tersedia</small>
            </div>
        </div>
        
        <div class="stat-card orders">
            <div class="icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="content">
                <h4>Total Pesanan</h4>
                <div class="number"><?php echo $order_stats['total_pesanan']; ?></div>
                <small><?php echo $order_stats['selesai']; ?> selesai</small>
            </div>
        </div>
        
        <div class="stat-card sales">
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="content">
                <h4>Penjualan</h4>
                <div class="number" style="font-size: 20px;"><?php echo formatRupiah($order_stats['total_penjualan']); ?></div>
                <small>Total pendapatan</small>
            </div>
        </div>
    </div>
    
    <!-- Status & Quick Actions -->
    <div class="dashboard-row">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h3><i class="fas fa-chart-pie"></i> Status Pesanan</h3>
            </div>
            <div class="dashboard-card-body">
                <ul class="status-list">
                    <li>
                        <span class="label"><span class="dot pending"></span> Pending</span>
                        <span class="value"><?php echo $order_stats['pending']; ?></span>
                    </li>
                    <li>
                        <span class="label"><span class="dot process"></span> Diproses</span>
                        <span class="value"><?php echo $order_stats['diproses']; ?></span>
                    </li>
                    <li>
                        <span class="label"><span class="dot done"></span> Selesai</span>
                        <span class="value"><?php echo $order_stats['selesai']; ?></span>
                    </li>
                    <li>
                        <span class="label"><span class="dot cancel"></span> Dibatalkan</span>
                        <span class="value"><?php echo $order_stats['dibatalkan']; ?></span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <h3><i class="fas fa-bolt"></i> Aksi Cepat</h3>
            </div>
            <div class="dashboard-card-body">
                <div class="quick-actions">
                    <a href="<?php echo BASE_URL; ?>index.php?page=admin/products/create" class="quick-action">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Produk</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>index.php?page=admin/orders" class="quick-action">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Kelola Pesanan</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>index.php?page=admin/categories" class="quick-action">
                        <i class="fas fa-tags"></i>
                        <span>Kategori</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>index.php?page=admin/reports" class="quick-action">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h3><i class="fas fa-clock"></i> Pesanan Terbaru</h3>
        </div>
        <div class="dashboard-card-body" style="padding: 0;">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>No Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($recent_orders && $recent_orders->num_rows > 0) {
                        while ($order = $recent_orders->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td class="order-id">' . htmlspecialchars($order['no_pesanan']) . '</td>';
                            echo '<td>' . htmlspecialchars($order['nama']) . '</td>';
                            echo '<td>' . formatTanggalId($order['tanggal_pesanan']) . '</td>';
                            echo '<td style="font-weight: 700; color: #2D1B00;">' . formatRupiah($order['total_harga']) . '</td>';
                            echo '<td>';
                            
                            $badge_class = '';
                            $status_text = '';
                            
                            switch ($order['status']) {
                                case 'pending':
                                    $badge_class = 'badge-pending';
                                    $status_text = 'Pending';
                                    break;
                                case 'diproses':
                                    $badge_class = 'badge-process';
                                    $status_text = 'Diproses';
                                    break;
                                case 'selesai':
                                    $badge_class = 'badge-done';
                                    $status_text = 'Selesai';
                                    break;
                                case 'dibatalkan':
                                    $badge_class = 'badge-cancel';
                                    $status_text = 'Dibatalkan';
                                    break;
                            }
                            
                            echo '<span class="badge ' . $badge_class . '">' . $status_text . '</span>';
                            echo '</td>';
                            echo '<td>';
                            echo '<a href="' . BASE_URL . 'index.php?page=admin/orders/detail&id=' . $order['id'] . '" class="btn-detail">Detail</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" style="text-align: center; padding: 40px; color: #8B7355;">Belum ada pesanan</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="dashboard-card-footer">
            <a href="<?php echo BASE_URL . 'index.php?page=admin/orders'; ?>" class="btn-view-all">
                <i class="fas fa-list"></i> Lihat Semua Pesanan
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
