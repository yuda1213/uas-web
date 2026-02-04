<?php
$title = 'Tambah Produk';
include __DIR__ . '/../../layout_header.php';

$product_model = new Product($conn);
$category_model = new Category($conn);

$categories = $category_model->getAllCategories();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = sanitize($_POST['nama_produk'] ?? '');
    $kategori_id = (int)($_POST['kategori_id'] ?? 0);
    $harga = (float)($_POST['harga'] ?? 0);
    $deskripsi = sanitize($_POST['deskripsi'] ?? '');
    $stok = (int)($_POST['stok'] ?? 0);
    
    // Validasi
    $errors = [];
    
    if (empty($nama_produk)) {
        $errors[] = 'Nama produk harus diisi';
    }
    
    if ($kategori_id <= 0) {
        $errors[] = 'Kategori harus dipilih';
    }
    
    if ($harga <= 0) {
        $errors[] = 'Harga harus lebih dari 0';
    }
    
    if ($stok < 0) {
        $errors[] = 'Stok tidak boleh negatif';
    }
    
    // Handle file upload
    $gambar = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['size'] > 0) {
        $upload_result = uploadFile('gambar', 'products');
        if ($upload_result['status']) {
            $gambar = $upload_result['filename'];
        } else {
            $errors[] = $upload_result['message'];
        }
    }
    
    if (!empty($errors)) {
        foreach ($errors as $error) {
            setAlert('danger', $error);
        }
    } else {
        // Debug: log values
        error_log("Creating product: $nama_produk, $kategori_id, $harga, $deskripsi, $stok, $gambar");
        
        // Save to database
        $product_id = $product_model->createProduct($nama_produk, $kategori_id, $harga, $deskripsi, $stok, $gambar);
        
        if ($product_id) {
            setAlert('success', 'Produk berhasil ditambahkan');
            redirect(BASE_URL . 'index.php?page=admin/products');
        } else {
            $db_error = $conn->error ?: 'Unknown error';
            error_log("Product create failed: $db_error");
            setAlert('danger', 'Gagal menambahkan produk: ' . $db_error);
        }
    }
}
?>

<div class="container">
    <h2>Tambah Produk Baru</h2>
    
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
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama_produk">Nama Produk *</label>
                    <input type="text" id="nama_produk" name="nama_produk" required>
                </div>
                
                <div class="form-group">
                    <label for="kategori_id">Kategori *</label>
                    <select id="kategori_id" name="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php
                        if ($categories && $categories->num_rows > 0) {
                            while ($cat = $categories->fetch_assoc()) {
                                echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['nama_kategori']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="harga">Harga *</label>
                    <input type="number" id="harga" name="harga" step="0.01" required>
                </div>
                
                <div class="form-group">
                    <label for="stok">Stok *</label>
                    <input type="number" id="stok" name="stok" required>
                </div>
                
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="gambar">Gambar Produk</label>
                    <input type="file" id="gambar" name="gambar" accept="image/*">
                    <small>Format: JPG, PNG, GIF (Max 2MB)</small>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?php echo BASE_URL . 'index.php?page=admin/products'; ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layout_footer.php'; ?>
