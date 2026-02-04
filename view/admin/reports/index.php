<?php
$title = 'Laporan Penjualan';
include __DIR__ . '/../../layout_header.php';

$order_model = new Order($conn);

// Get date range
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-01');
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');

// Get sales data
$orders = $order_model->getOrdersWithPagination(1000, 0, '', 'selesai');
$order_list = [];
if ($orders && $orders->num_rows > 0) {
    while ($order = $orders->fetch_assoc()) {
        $order_list[] = $order;
    }
}

// Get product sales
$product_sales = $order_model->getProductSalesReport($date_from, $date_to);
$product_list = [];
if ($product_sales && $product_sales->num_rows > 0) {
    while ($product = $product_sales->fetch_assoc()) {
        $product_list[] = $product;
    }
}
?>

<div class="container">
    <h2>Laporan Penjualan</h2>
    
    <!-- Filter -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="">
                <input type="hidden" name="page" value="admin/reports">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label for="date_from">Tanggal Mulai</label>
                        <input type="date" id="date_from" name="date_from" value="<?php echo $date_from; ?>">
                    </div>
                    <div class="form-group">
                        <label for="date_to">Tanggal Akhir</label>
                        <input type="date" id="date_to" name="date_to" value="<?php echo $date_to; ?>">
                    </div>
                    <div class="form-group" style="display: flex; align-items: flex-end; gap: 10px;">
                        <button type="submit" class="btn" style="flex: 1;">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Export Options -->
    <div style="margin: 20px 0; display: flex; gap: 10px;">
        <a href="<?php echo BASE_URL . 'index.php?page=admin/reports/orders&export=pdf'; ?>" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export PDF Pesanan
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=admin/reports/orders&export=excel'; ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel Pesanan
        </a>
        <a href="<?php echo BASE_URL . 'index.php?page=admin/reports/products&export=excel'; ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel Produk
        </a>
    </div>
    
    <!-- Orders Report -->
    <div class="card">
        <div class="card-header">
            <h3>Laporan Pesanan (Selesai)</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>No Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_penjualan = 0;
                    if (!empty($order_list)) {
                        foreach ($order_list as $order) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($order['no_pesanan']) . '</td>';
                            echo '<td>' . htmlspecialchars($order['nama']) . '</td>';
                            echo '<td>' . date('d-m-Y', strtotime($order['tanggal_pesanan'])) . '</td>';
                            echo '<td>' . formatRupiah($order['total_harga']) . '</td>';
                            echo '</tr>';
                            $total_penjualan += $order['total_harga'];
                        }
                    } else {
                        echo '<tr><td colspan="4" style="text-align: center;">Tidak ada data</td></tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td colspan="3">TOTAL PENJUALAN</td>
                        <td><?php echo formatRupiah($total_penjualan); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Product Sales Report -->
    <div class="card" style="margin-top: 20px;">
        <div class="card-header">
            <h3>Laporan Penjualan Produk</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Jumlah Terjual</th>
                        <th>Total Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_produk = 0;
                    if (!empty($product_list)) {
                        foreach ($product_list as $product) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($product['nama_produk']) . '</td>';
                            echo '<td>' . htmlspecialchars($product['nama_kategori']) . '</td>';
                            echo '<td style="text-align: center;">' . $product['total_jumlah'] . '</td>';
                            echo '<td>' . formatRupiah($product['total_penjualan']) . '</td>';
                            echo '</tr>';
                            $total_produk += $product['total_penjualan'];
                        }
                    } else {
                        echo '<tr><td colspan="4" style="text-align: center;">Tidak ada data</td></tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td colspan="3">TOTAL PENJUALAN</td>
                        <td><?php echo formatRupiah($total_produk); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layout_footer.php'; ?>
