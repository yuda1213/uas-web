<?php
/**
 * Admin Orders Management
 */

$title = 'Manajemen Pesanan';
include __DIR__ . '/../../layout_header.php';

$order_model = new Order($conn);

// Get status filter
$status_filter = isset($_GET['status']) ? sanitize($_GET['status']) : null;

// Get data
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page_data = getPaginationData($order_model->countOrders($search, $status_filter));

$orders = $order_model->getOrdersWithPagination(
    $page_data['items_per_page'],
    $page_data['offset'],
    $search,
    $status_filter
);
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Manajemen Pesanan</h2>
    </div>
    
    <!-- Filter -->
    <div style="margin: 20px 0; display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="<?php echo BASE_URL . 'index.php?page=admin/orders'; ?>" class="btn <?php echo !$status_filter ? 'btn-primary' : 'btn-secondary'; ?>">
            Semua
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=admin/orders&status=pending'; ?>" class="btn <?php echo $status_filter === 'pending' ? 'btn-primary' : 'btn-secondary'; ?>">
            Pending
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=admin/orders&status=diproses'; ?>" class="btn <?php echo $status_filter === 'diproses' ? 'btn-primary' : 'btn-secondary'; ?>">
            Diproses
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=admin/orders&status=selesai'; ?>" class="btn <?php echo $status_filter === 'selesai' ? 'btn-primary' : 'btn-secondary'; ?>">
            Selesai
        </a>
    </div>
    
    <!-- Search -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="">
                <input type="hidden" name="page" value="admin/orders">
                <?php if ($status_filter) { echo '<input type="hidden" name="status" value="' . htmlspecialchars($status_filter) . '">'; } ?>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="search" placeholder="Cari No Pesanan atau Nama..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; padding: 10px;">
                    <button type="submit" class="btn">Cari</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Table -->
    <div class="card">
        <div class="card-body">
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
                    if ($orders && $orders->num_rows > 0) {
                        while ($order = $orders->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><strong>' . htmlspecialchars($order['no_pesanan']) . '</strong></td>';
                            echo '<td>' . htmlspecialchars($order['nama']) . '</td>';
                            echo '<td>' . formatTanggalId($order['tanggal_pesanan']) . '</td>';
                            echo '<td>' . formatRupiah($order['total_harga']) . '</td>';
                            echo '<td>';
                            
                            $status_class = '';
                            $status_text = '';
                            
                            switch ($order['status']) {
                                case 'pending':
                                    $status_class = 'badge-warning';
                                    $status_text = 'Pending';
                                    break;
                                case 'diproses':
                                    $status_class = 'badge-info';
                                    $status_text = 'Diproses';
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
                            echo '<td class="table-actions">';
                            echo '<a href="' . BASE_URL . 'index.php?page=admin/orders/detail&id=' . $order['id'] . '" class="btn btn-sm">Detail</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" style="text-align: center;">Tidak ada pesanan ditemukan</td></tr>';
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
                echo '<a href="' . BASE_URL . 'index.php?page=admin/orders' . $status_param . '&search=' . urlencode($search) . '&p=' . ($page_data['current_page'] - 1) . '">← Sebelumnya</a>';
            }
            
            for ($i = 1; $i <= $page_data['total_pages']; $i++) {
                if ($i === $page_data['current_page']) {
                    echo '<span class="active">' . $i . '</span>';
                } else {
                    echo '<a href="' . BASE_URL . 'index.php?page=admin/orders' . $status_param . '&search=' . urlencode($search) . '&p=' . $i . '">' . $i . '</a>';
                }
            }
            
            if ($page_data['current_page'] < $page_data['total_pages']) {
                echo '<a href="' . BASE_URL . 'index.php?page=admin/orders' . $status_param . '&search=' . urlencode($search) . '&p=' . ($page_data['current_page'] + 1) . '">Selanjutnya →</a>';
            }
            ?>
        </div>
    <?php } ?>
</div>

<?php include __DIR__ . '/../../layout_footer.php'; ?>
