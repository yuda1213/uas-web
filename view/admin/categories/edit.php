<?php
$title = 'Edit Kategori';
include __DIR__ . '/../../layout_header.php';

$category_model = new Category($conn);

// Get category
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$category = $category_model->getCategoryById($id);

if (!$category) {
    setAlert('danger', 'Kategori tidak ditemukan');
    redirect(BASE_URL . 'index.php?page=admin/categories');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = sanitize($_POST['nama_kategori'] ?? '');
    $deskripsi = sanitize($_POST['deskripsi'] ?? '');
    
    // Validasi
    $errors = [];
    
    if (empty($nama_kategori)) {
        $errors[] = 'Nama kategori harus diisi';
    }
    
    if ($category_model->categoryNameExists($nama_kategori, $id)) {
        $errors[] = 'Nama kategori sudah ada';
    }
    
    if (!empty($errors)) {
        foreach ($errors as $error) {
            setAlert('danger', $error);
        }
    } else {
        // Update database
        if ($category_model->updateCategory($id, $nama_kategori, $deskripsi)) {
            setAlert('success', 'Kategori berhasil diperbarui');
            redirect(BASE_URL . 'index.php?page=admin/categories');
        } else {
            setAlert('danger', 'Gagal memperbarui kategori');
        }
    }
}
?>

<div class="container">
    <h2>Edit Kategori</h2>
    
    <?php 
    $alert = getAlert();
    if ($alert) {
        echo '<div class="alert alert-' . $alert['type'] . '">';
        echo htmlspecialchars($alert['message']);
        echo '</div>';
    }
    ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori *</label>
                    <input type="text" id="nama_kategori" name="nama_kategori" value="<?php echo htmlspecialchars($category['nama_kategori']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"><?php echo htmlspecialchars($category['deskripsi']); ?></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?php echo BASE_URL . 'index.php?page=admin/categories'; ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layout_footer.php'; ?>
