<?php
$title = 'Manajemen Produk';
include __DIR__ . '/../../layout_header.php';

$product_model = new Product($conn);
$category_model = new Category($conn);

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Cek apakah produk ada
    $product = $product_model->getProductById($id);
    if ($product && $product_model->deleteProduct($id)) {
        setAlert('success', 'Produk berhasil dihapus');
    } else {
        setAlert('danger', 'Gagal menghapus produk');
    }
    
    redirect(BASE_URL . 'index.php?page=admin/products');
}

// Get data
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page_data = getPaginationData($product_model->countProducts($search));

$products = $product_model->getProductsWithPagination(
    $page_data['items_per_page'],
    $page_data['offset'],
    $search
);
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Manajemen Produk</h2>
        <a href="<?php echo BASE_URL . 'index.php?page=admin/products/create'; ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>
    
    <!-- Search -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="">
                <input type="hidden" name="page" value="admin/products">
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="search" placeholder="Cari produk..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; padding: 10px;">
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
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $page_data['offset'] + 1;
                    if ($products && $products->num_rows > 0) {
                        while ($product = $products->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $no++ . '</td>';
                            echo '<td>';
                            if (!empty($product['gambar'])) {
                                echo '<img src="' . UPLOAD_URL . 'products/' . htmlspecialchars($product['gambar']) . '" alt="' . htmlspecialchars($product['nama_produk']) . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">';
                            } else {
                                echo '<div style="width: 50px; height: 50px; background: linear-gradient(135deg, #6F4E37, #D4A574); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #fff;">☕</div>';
                            }
                            echo '</td>';
                            echo '<td><strong>' . htmlspecialchars($product['nama_produk']) . '</strong></td>';
                            echo '<td>' . htmlspecialchars($product['nama_kategori']) . '</td>';
                            echo '<td>' . formatRupiah($product['harga']) . '</td>';
                            echo '<td>' . $product['stok'] . ' unit</td>';
                            echo '<td><span class="badge ' . ($product['status'] === 'tersedia' ? 'badge-success' : 'badge-danger') . '">' . ucfirst($product['status']) . '</span></td>';
                            echo '<td class="table-actions">';
                            echo '<a href="' . BASE_URL . 'index.php?page=admin/products/edit&id=' . $product['id'] . '" class="btn btn-sm">Edit</a>';
                            echo '<a href="' . BASE_URL . 'index.php?page=admin/products&action=delete&id=' . $product['id'] . '" class="btn btn-sm btn-danger" onclick="return confirmDelete();">Hapus</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="8" style="text-align: center;">Tidak ada produk ditemukan</td></tr>';
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
            // Previous button
            if ($page_data['current_page'] > 1) {
                echo '<a href="' . BASE_URL . 'index.php?page=admin/products&search=' . urlencode($search) . '&p=' . ($page_data['current_page'] - 1) . '">← Sebelumnya</a>';
            }
            
            // Page numbers
            for ($i = 1; $i <= $page_data['total_pages']; $i++) {
                if ($i === $page_data['current_page']) {
                    echo '<span class="active">' . $i . '</span>';
                } else {
                    echo '<a href="' . BASE_URL . 'index.php?page=admin/products&search=' . urlencode($search) . '&p=' . $i . '">' . $i . '</a>';
                }
            }
            
            // Next button
            if ($page_data['current_page'] < $page_data['total_pages']) {
                echo '<a href="' . BASE_URL . 'index.php?page=admin/products&search=' . urlencode($search) . '&p=' . ($page_data['current_page'] + 1) . '">Selanjutnya →</a>';
            }
            ?>
        </div>
    <?php } ?>
</div>

<?php include __DIR__ . '/../../layout_footer.php'; ?>
