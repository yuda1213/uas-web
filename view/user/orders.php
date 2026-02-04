<?php
$title = 'Pesanan Saya';
include __DIR__ . '/../layout_header.php';

$order_model = new Order($conn);
$user_id = getCurrentUser()['id'];

// Get status filter
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : null;

// Get data
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page_data = getPaginationData($order_model->countOrders($search, $status_filter, $user_id));

$orders = $order_model->getOrdersWithPagination(
    $page_data['items_per_page'],
    $page_data['offset'],
    $search,
    $status_filter,
    $user_id
);
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

    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .page-hero {
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #8B6F47 100%);
        color: #fff;
        padding: 28px 24px;
        border-radius: 18px;
        margin: 10px 0 24px;
        box-shadow: 0 20px 50px rgba(45, 27, 0, 0.25),
                    inset 0 1px 0 rgba(255,255,255,0.15);
        position: relative;
        overflow: hidden;
        animation: slideDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .page-hero::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -20%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.12), transparent 70%);
    }

    .page-hero h2 { 
        margin: 0 0 6px 0; 
        font-size: 28px; 
        font-weight: 900; 
        position: relative; 
        z-index: 1; 
        text-shadow: 0 4px 12px rgba(0,0,0,0.25), 0 2px 4px rgba(0,0,0,0.1);
        letter-spacing: -0.6px;
        line-height: 1.2;
    }
    .page-hero p { 
        margin: 0; 
        opacity: 0.94; 
        font-size: 14px; 
        position: relative; 
        z-index: 1;
        font-weight: 500;
        letter-spacing: 0.3px;
        line-height: 1.6;
    }

    .filter-bar {
        margin: 20px 0 24px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        animation: slideDown 0.6s ease-out 0.15s backwards;
    }

    .filter-bar a {
        border-radius: 999px;
        padding: 12px 18px;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.4px;
        text-decoration: none;
        border: 1.5px solid rgba(212, 165, 116, 0.3);
        background: linear-gradient(135deg, rgba(255,248,241,0.85) 0%, rgba(255,253,251,0.85) 100%);
        color: #6F4E37;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 12px rgba(45, 27, 0, 0.04);
    }

    .filter-bar a:hover {
        border-color: rgba(212, 165, 116, 0.6);
        background: linear-gradient(135deg, rgba(255,243,232,0.96) 0%, rgba(212, 165, 116, 0.15) 100%);
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 28px rgba(45, 27, 0, 0.12);
    }

    .filter-bar a.btn-primary {
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        color: #fff;
        border-color: #6F4E37;
    }

    .card {
        border-radius: 20px;
        overflow: hidden;
        border: 1.5px solid rgba(212, 165, 116, 0.2);
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.08),
                    0 0 1px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        animation: fadeUp 0.6s ease-out 0.3s backwards;
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.96);
    }

    .card-body {
        padding: 0;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background: linear-gradient(135deg, rgba(255,248,241,0.9) 0%, rgba(255,243,232,0.9) 100%);
        color: #6F4E37;
        border-bottom: 1.5px solid rgba(212, 165, 116, 0.2);
        font-weight: 900;
        padding: 18px 16px;
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        line-height: 1.2;
    }

    .table tbody tr {
        border-bottom: 1px solid rgba(239, 230, 221, 0.4);
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: rgba(255, 248, 241, 0.9);
        box-shadow: inset 0 0 20px rgba(212, 165, 116, 0.08);
    }

    .table tbody td {
        padding: 16px;
        font-size: 14px;
        color: #2D1B00;
        line-height: 1.5;
        letter-spacing: 0.2px;
    }

    .table tbody tr td:first-child {
        font-weight: 800;
        letter-spacing: -0.3px;
    }

    .badge { 
        border-radius: 999px; 
        padding: 8px 16px; 
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(45, 27, 0, 0.08);
        transition: all 0.3s ease;
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
        background: #E8F5E9;
        color: #2E7D32;
    }

    .badge-danger {
        background: #FDECEC;
        color: #B91C1C;
    }

    .pagination {
        display: flex;
        gap: 8px;
        margin-top: 24px;
        justify-content: center;
        flex-wrap: wrap;
        animation: fadeUp 0.6s ease-out 0.4s backwards;
    }

    .pagination a,
    .pagination span {
        border-radius: 999px;
        padding: 8px 12px;
        text-decoration: none;
        border: 1px solid #E3D5C8;
        background: #FFF;
        color: #6F4E37;
        transition: all 0.2s ease;
        font-weight: 600;
        font-size: 13px;
    }

    .pagination a:hover {
        border-color: #D4A574;
        background: linear-gradient(135deg, #FFF3E8 0%, #FFFDFB 100%);
        transform: translateY(-2px);
    }

    .pagination span.active {
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        color: #fff;
        border-color: #6F4E37;
    }
</style>

<div class="container">
    <div class="page-hero">
        <h2>Pesanan Saya</h2>
        <p>Pantau status pesanan Anda secara real-time.</p>
    </div>
    
    <!-- Filter -->
    <div class="filter-bar">
        <a href="<?php echo BASE_URL . 'index.php?page=user/orders'; ?>" class="btn <?php echo !$status_filter ? 'btn-primary' : 'btn-secondary'; ?>">
            Semua Pesanan
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=user/orders&status=pending'; ?>" class="btn <?php echo $status_filter === 'pending' ? 'btn-primary' : 'btn-secondary'; ?>">
            Pending
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=user/orders&status=diproses'; ?>" class="btn <?php echo $status_filter === 'diproses' ? 'btn-primary' : 'btn-secondary'; ?>">
            Diproses
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=user/orders&status=selesai'; ?>" class="btn <?php echo $status_filter === 'selesai' ? 'btn-primary' : 'btn-secondary'; ?>">
            Selesai
        </a>
    </div>
    
    <!-- Orders List -->
    <div class="card">
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
                    if ($orders && $orders->num_rows > 0) {
                        while ($order = $orders->fetch_assoc()) {
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
                        echo '<tr><td colspan="5" style="text-align: center; padding: 40px;">Belum ada pesanan</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($page_data['total_pages'] > 1) { ?>
        <div class="pagination">
            <?php
            $status_param = $status_filter ? '&status=' . $status_filter : '';
            
            if ($page_data['current_page'] > 1) {
                echo '<a href="' . BASE_URL . 'index.php?page=user/orders' . $status_param . '&p=' . ($page_data['current_page'] - 1) . '">← Sebelumnya</a>';
            }
            
            for ($i = 1; $i <= $page_data['total_pages']; $i++) {
                if ($i === $page_data['current_page']) {
                    echo '<span class="active">' . $i . '</span>';
                } else {
                    echo '<a href="' . BASE_URL . 'index.php?page=user/orders' . $status_param . '&p=' . $i . '">' . $i . '</a>';
                }
            }
            
            if ($page_data['current_page'] < $page_data['total_pages']) {
                echo '<a href="' . BASE_URL . 'index.php?page=user/orders' . $status_param . '&p=' . ($page_data['current_page'] + 1) . '">Selanjutnya →</a>';
            }
            ?>
        </div>
        
        <!-- Reset Button -->
        <div style="text-align: center; margin-top: 16px;">
            <a href="<?php echo BASE_URL . 'index.php?page=user/orders'; ?>" class="btn btn-secondary" style="border-radius: 999px; padding: 10px 24px; font-size: 13px; text-decoration: none; background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%); color: #666; border: 1px solid #ddd;">
                <i class="fas fa-sync-alt"></i> Reset Filter & Halaman
            </a>
        </div>
    <?php } ?>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
