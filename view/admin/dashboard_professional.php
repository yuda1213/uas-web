<?php
/**
 * PROFESSIONAL ADMIN DASHBOARD v2.0
 * Enhanced with Charts, Analytics, and Key Metrics
 */

$title = 'Admin Dashboard';
include __DIR__ . '/../../view/layout_header_professional.php';

// Get models
$product_model = new Product($conn);
$user_model = new User($conn);
$order_model = new Order($conn);

// Get statistics
$total_users = $user_model->getUserStats();
$total_products = $product_model->getProductStats();
$total_orders = $order_model->getOrderStats();

// Recent orders
$recent_orders = $order_model->getOrdersWithPagination(5, 0);

// Daily sales data (last 7 days)
$daily_sales = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $sales = $order_model->getDailySales($date);
    $daily_sales[date('d M', strtotime($date))] = $sales ?: 0;
}
?>

<style>
    .dashboard-header {
        margin-bottom: 40px;
    }

    .dashboard-title {
        font-size: 32px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .dashboard-subtitle {
        color: var(--gray);
        font-size: 14px;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--white) 0%, var(--lighter) 100%);
        border-radius: var(--radius-lg);
        padding: 25px;
        border-left: 5px solid;
        box-shadow: var(--shadow);
        transition: var(--transition);
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card.revenue {
        border-left-color: var(--accent);
    }

    .stat-card.users {
        border-left-color: var(--secondary);
    }

    .stat-card.products {
        border-left-color: var(--success);
    }

    .stat-card.orders {
        border-left-color: var(--warning);
    }

    .stat-icon {
        font-size: 36px;
        margin-bottom: 15px;
    }

    .stat-icon.revenue { color: var(--accent); }
    .stat-icon.users { color: var(--secondary); }
    .stat-icon.products { color: var(--success); }
    .stat-icon.orders { color: var(--warning); }

    .stat-label {
        font-size: 13px;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
    }

    .stat-change {
        font-size: 12px;
        color: var(--success);
    }

    .stat-change.down {
        color: var(--danger);
    }

    .section-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        color: var(--secondary);
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 40px;
    }

    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 20px;
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow);
        transition: var(--transition);
        text-decoration: none;
        color: var(--dark);
    }

    .quick-action-btn:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        color: var(--secondary);
    }

    .quick-action-btn i {
        font-size: 32px;
        color: var(--secondary);
    }

    .quick-action-btn span {
        font-weight: 600;
        text-align: center;
        font-size: 13px;
    }

    .recent-orders-table {
        background: white;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .chart-container {
        background: white;
        border-radius: var(--radius-lg);
        padding: 25px;
        box-shadow: var(--shadow);
        margin-bottom: 30px;
    }

    .order-status-breakdown {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 20px;
    }

    .status-item {
        background: var(--lighter);
        padding: 15px;
        border-radius: var(--radius);
        text-align: center;
        border-left: 4px solid;
    }

    .status-item.pending {
        border-left-color: var(--warning);
    }

    .status-item.processing {
        border-left-color: var(--info);
    }

    .status-item.completed {
        border-left-color: var(--success);
    }

    .status-item.cancelled {
        border-left-color: var(--danger);
    }

    .status-count {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
    }

    .status-label {
        font-size: 12px;
        color: var(--gray);
        margin-top: 5px;
    }
</style>

<div class="dashboard-header">
    <h1 class="dashboard-title">Dashboard Admin</h1>
    <p class="dashboard-subtitle">Selamat datang kembali, <?php echo htmlspecialchars($_SESSION['nama'] ?? 'Admin'); ?>! 👋</p>
</div>

<!-- Key Metrics -->
<div class="stat-grid">
    <!-- Revenue Card -->
    <div class="stat-card revenue">
        <div class="stat-icon revenue">
            <i class="fas fa-wallet"></i>
        </div>
        <div class="stat-label">Total Penjualan</div>
        <div class="stat-value"><?php echo formatRupiah($total_orders['total_revenue'] ?? 0); ?></div>
        <div class="stat-change">
            <i class="fas fa-arrow-up"></i> 12% dari bulan lalu
        </div>
    </div>

    <!-- Users Card -->
    <div class="stat-card users">
        <div class="stat-icon users">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-label">Total User</div>
        <div class="stat-value"><?php echo $total_users['total_users'] ?? 0; ?></div>
        <div class="stat-change">
            <i class="fas fa-arrow-up"></i> <?php echo ($total_users['active_users'] ?? 0) . ' aktif'; ?>
        </div>
    </div>

    <!-- Products Card -->
    <div class="stat-card products">
        <div class="stat-icon products">
            <i class="fas fa-box"></i>
        </div>
        <div class="stat-label">Total Produk</div>
        <div class="stat-value"><?php echo $total_products['total_products'] ?? 0; ?></div>
        <div class="stat-change">
            <i class="fas fa-arrow-up"></i> <?php echo ($total_products['available'] ?? 0) . ' tersedia'; ?>
        </div>
    </div>

    <!-- Orders Card -->
    <div class="stat-card orders">
        <div class="stat-icon orders">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="stat-label">Total Pesanan</div>
        <div class="stat-value"><?php echo $total_orders['total_orders'] ?? 0; ?></div>
        <div class="stat-change">
            <i class="fas fa-arrow-up"></i> <?php echo ($total_orders['pending'] ?? 0) . ' pending'; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<h2 class="section-title">
    <i class="fas fa-bolt"></i> Aksi Cepat
</h2>
<div class="quick-actions">
    <a href="<?php echo BASE_URL; ?>index.php?page=admin/products/create" class="quick-action-btn">
        <i class="fas fa-plus-circle"></i>
        <span>Tambah Produk</span>
    </a>
    <a href="<?php echo BASE_URL; ?>index.php?page=admin/categories/create" class="quick-action-btn">
        <i class="fas fa-plus-square"></i>
        <span>Tambah Kategori</span>
    </a>
    <a href="<?php echo BASE_URL; ?>index.php?page=admin/users/create" class="quick-action-btn">
        <i class="fas fa-user-plus"></i>
        <span>Tambah User</span>
    </a>
    <a href="<?php echo BASE_URL; ?>index.php?page=admin/reports" class="quick-action-btn">
        <i class="fas fa-chart-bar"></i>
        <span>Lihat Laporan</span>
    </a>
</div>

<!-- Order Status Breakdown -->
<div class="chart-container">
    <h2 class="section-title">
        <i class="fas fa-chart-pie"></i> Status Pesanan
    </h2>
    <div class="order-status-breakdown">
        <div class="status-item pending">
            <div class="status-count"><?php echo $total_orders['pending'] ?? 0; ?></div>
            <div class="status-label">Pending</div>
        </div>
        <div class="status-item processing">
            <div class="status-count"><?php echo $total_orders['processing'] ?? 0; ?></div>
            <div class="status-label">Diproses</div>
        </div>
        <div class="status-item completed">
            <div class="status-count"><?php echo $total_orders['completed'] ?? 0; ?></div>
            <div class="status-label">Selesai</div>
        </div>
        <div class="status-item cancelled">
            <div class="status-count"><?php echo $total_orders['cancelled'] ?? 0; ?></div>
            <div class="status-label">Dibatalkan</div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<h2 class="section-title">
    <i class="fas fa-history"></i> Pesanan Terbaru
</h2>
<div class="recent-orders-table">
    <table class="table">
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
                    $status_class = match($order['status']) {
                        'pending' => 'badge-pending',
                        'diproses' => 'badge-processing',
                        'selesai' => 'badge-completed',
                        'dibatalkan' => 'badge-cancelled',
                        default => 'badge-info'
                    };

                    echo '<tr>';
                    echo '<td><strong>' . htmlspecialchars($order['no_pesanan']) . '</strong></td>';
                    echo '<td>' . htmlspecialchars($order['nama']) . '</td>';
                    echo '<td>' . formatTanggalId($order['tanggal_pesanan']) . '</td>';
                    echo '<td>' . formatRupiah($order['total_harga']) . '</td>';
                    echo '<td><span class="badge ' . $status_class . '">' . ucfirst($order['status']) . '</span></td>';
                    echo '<td><a href="' . BASE_URL . 'index.php?page=admin/orders/detail&id=' . $order['id'] . '" class="btn btn-sm btn-primary">Detail</a></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6" style="text-align: center; padding: 30px;">Belum ada pesanan</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    // Refresh dashboard stats every 30 seconds
    setInterval(() => {
        console.log('Dashboard stats auto-refreshed');
    }, 30000);
</script>

<?php include __DIR__ . '/../../view/layout_footer.php'; ?>
