<?php
$title = 'Manajemen Kategori';
include __DIR__ . '/../../layout_header.php';

$category_model = new Category($conn);

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    if ($category_model->deleteCategory($id)) {
        setAlert('success', 'Kategori berhasil dihapus');
    } else {
        setAlert('danger', 'Gagal menghapus kategori');
    }
    
    redirect(BASE_URL . 'index.php?page=admin/categories');
}

// Get data
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page_data = getPaginationData($category_model->countCategories($search));

$categories = $category_model->getCategoriesWithPagination(
    $page_data['items_per_page'],
    $page_data['offset'],
    $search
);
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Manajemen Kategori</h2>
        <a href="<?php echo BASE_URL . 'index.php?page=admin/categories/create'; ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah Kategori
        </a>
    </div>
    
    <!-- Search -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="">
                <input type="hidden" name="page" value="admin/categories">
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="search" placeholder="Cari kategori..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; padding: 10px;">
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
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $page_data['offset'] + 1;
                    if ($categories && $categories->num_rows > 0) {
                        while ($category = $categories->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $no++ . '</td>';
                            echo '<td><strong>' . htmlspecialchars($category['nama_kategori']) . '</strong></td>';
                            echo '<td>' . htmlspecialchars(substr($category['deskripsi'], 0, 50)) . (strlen($category['deskripsi']) > 50 ? '...' : '') . '</td>';
                            echo '<td class="table-actions">';
                            echo '<a href="' . BASE_URL . 'index.php?page=admin/categories/edit&id=' . $category['id'] . '" class="btn btn-sm">Edit</a>';
                            echo '<a href="' . BASE_URL . 'index.php?page=admin/categories&action=delete&id=' . $category['id'] . '" class="btn btn-sm btn-danger" onclick="return confirmDelete();">Hapus</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" style="text-align: center;">Tidak ada kategori ditemukan</td></tr>';
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
            if ($page_data['current_page'] > 1) {
                echo '<a href="' . BASE_URL . 'index.php?page=admin/categories&search=' . urlencode($search) . '&p=' . ($page_data['current_page'] - 1) . '">← Sebelumnya</a>';
            }
            
            for ($i = 1; $i <= $page_data['total_pages']; $i++) {
                if ($i === $page_data['current_page']) {
                    echo '<span class="active">' . $i . '</span>';
                } else {
                    echo '<a href="' . BASE_URL . 'index.php?page=admin/categories&search=' . urlencode($search) . '&p=' . $i . '">' . $i . '</a>';
                }
            }
            
            if ($page_data['current_page'] < $page_data['total_pages']) {
                echo '<a href="' . BASE_URL . 'index.php?page=admin/categories&search=' . urlencode($search) . '&p=' . ($page_data['current_page'] + 1) . '">Selanjutnya →</a>';
            }
            ?>
        </div>
    <?php } ?>
</div>

<?php include __DIR__ . '/../../layout_footer.php'; ?>
