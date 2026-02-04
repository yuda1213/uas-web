<?php
$title = 'Menu & Pesanan';
include __DIR__ . '/../layout_header.php';

$product_model = new Product($conn);
$category_model = new Category($conn);

// Get categories
$categories = $category_model->getAllCategories();

// Get category filter
$kategori_id = isset($_GET['kategori']) ? (int)$_GET['kategori'] : null;

// Get products
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page_data = getPaginationData($product_model->countProducts($search, $kategori_id));

$products = $product_model->getProductsWithPagination(
    $page_data['items_per_page'],
    $page_data['offset'],
    $search,
    $kategori_id
);
?>

<style>
    :root {
        --coffee-dark: #2D1B00;
        --coffee: #6F4E37;
        --coffee-light: #8B6F47;
        --gold: #D4A574;
        --cream: #FAF7F4;
        --cream-2: #F5F3F0;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes glowPulse {
        0%, 100% { text-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        50% { text-shadow: 0 4px 20px rgba(212, 165, 116, 0.4); }
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

    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .menu-hero {
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #8B6F47 100%);
        color: #fff;
        padding: 40px 32px;
        border-radius: 24px;
        margin: 10px 0 28px;
        box-shadow: 0 28px 72px rgba(45, 27, 0, 0.35),
                    inset 0 1px 0 rgba(255,255,255,0.15);
        position: relative;
        overflow: hidden;
        animation: slideDown 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        backdrop-filter: blur(12px);
        border: 1.5px solid rgba(255,255,255,0.15);
    }

    .menu-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -40px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.15), transparent 70%);
        animation: pulse 5s ease-in-out infinite;
    }

    .menu-hero::after {
        content: '';
        position: absolute;
        bottom: -40px;
        left: -20px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.08), transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    .menu-hero h2 {
        margin: 0 0 10px 0;
        font-size: 38px;
        font-weight: 900;
        letter-spacing: -1px;
        position: relative;
        z-index: 1;
        text-shadow: 0 6px 16px rgba(0,0,0,0.3), 0 2px 6px rgba(0,0,0,0.15);
        animation: slideDown 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        line-height: 1.1;
    }

    .menu-hero p {
        margin: 0;
        opacity: 0.95;
        font-size: 16px;
        position: relative;
        z-index: 1;
        font-weight: 500;
        letter-spacing: 0.5px;
        line-height: 1.6;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 24px;
        margin: 24px 0;
    }
    
    .product-card {
        background: rgba(255, 255, 255, 0.96);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.08),
                    0 0 1px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        flex-direction: column;
        border: 1.5px solid rgba(212, 165, 116, 0.3);
        animation: fadeUp 0.6s ease-out backwards;
        backdrop-filter: blur(10px);
    }

    .product-card:nth-child(1) { animation-delay: 0.05s; }
    .product-card:nth-child(2) { animation-delay: 0.1s; }
    .product-card:nth-child(3) { animation-delay: 0.15s; }
    .product-card:nth-child(4) { animation-delay: 0.2s; }
    .product-card:nth-child(n+5) { animation-delay: 0.25s; }
    
    .product-card:hover {
        transform: translateY(-16px) scale(1.03);
        box-shadow: 0 32px 72px rgba(45, 27, 0, 0.16),
                    0 12px 36px rgba(212, 165, 116, 0.2),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        border-color: rgba(212, 165, 116, 0.6);
        background: rgba(255, 255, 255, 0.98);
    }
    
    .product-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background: linear-gradient(135deg, #6F4E37 0%, #D4A574 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        color: #fff;
        position: relative;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .product-image img,
    img.product-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }

    .product-image::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent, rgba(0,0,0,0.12));
        transition: all 0.4s ease;
    }

    .product-card:hover .product-image,
    .product-card:hover img.product-image {
        transform: scale(1.1);
    }

    .product-card:hover .product-image::after {
        background: linear-gradient(135deg, rgba(0,0,0,0.08), rgba(0,0,0,0.2));
    }
    
    .product-body {
        padding: 18px 16px 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .product-body h3 {
        margin: 0 0 8px 0;
        font-size: 16px;
        color: var(--coffee-dark);
        font-weight: 800;
        letter-spacing: -0.4px;
        line-height: 1.3;
    }
    
    .product-body p {
        margin: 0 0 14px 0;
        color: #6B5B4A;
        font-size: 13px;
        flex-grow: 1;
        line-height: 1.6;
        letter-spacing: 0.2px;
    }
    
    .product-price {
        font-size: 26px;
        font-weight: 900;
        margin-bottom: 14px;
        letter-spacing: -0.3px;
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #D4A574 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .product-actions {
        display: flex;
        gap: 10px;
    }
    
    .product-actions button {
        flex: 1;
        padding: 12px;
        font-size: 13px;
        border-radius: 11px;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: none;
        cursor: pointer;
        box-shadow: 0 6px 16px rgba(45, 27, 0, 0.08);
    }

    .product-actions .btn {
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 60%, #D4A574 100%);
        color: #fff;
    }

    .product-actions .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(45, 27, 0, 0.18);
        background: linear-gradient(135deg, #5A3E2C 0%, #7A5E3B 60%, #C79966 100%);
    }
    
    .filters {
        margin-bottom: 20px;
        padding: 22px;
        background: rgba(255, 255, 255, 0.96);
        border-radius: 18px;
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        align-items: center;
        border: 1.5px solid rgba(212, 165, 116, 0.2);
        box-shadow: 0 12px 32px rgba(45, 27, 0, 0.06),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        animation: slideDown 0.6s ease-out;
        backdrop-filter: blur(12px);
    }
    
    .filters a {
        padding: 12px 20px;
        border: 1.5px solid rgba(212, 165, 116, 0.3);
        border-radius: 999px;
        text-decoration: none;
        color: #6F4E37;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        font-size: 13px;
        font-weight: 700;
        background: linear-gradient(135deg, rgba(255,248,241,0.8) 0%, rgba(255,253,251,0.8) 100%);
        cursor: pointer;
        display: inline-block;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 12px rgba(45, 27, 0, 0.04);
    }
    
    .filters a:hover,
    .filters a.active {
        border-color: rgba(212, 165, 116, 0.6);
        color: #2D1B00;
        background: linear-gradient(135deg, rgba(255,243,232,0.96) 0%, rgba(212, 165, 116, 0.15) 100%);
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 28px rgba(45, 27, 0, 0.12);
    }

    .filters input[type="text"] {
        border: 1.5px solid #E3D5C8;
        background: #FFFDFB;
        border-radius: 11px;
        padding: 11px 14px;
        font-size: 14px;
        transition: all 0.25s ease;
    }

    .filters input[type="text"]:focus {
        outline: none;
        border-color: #D4A574;
        box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
    }

    .filters .btn {
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        color: #fff;
        border-radius: 11px;
        font-weight: 700;
        border: none;
        padding: 10px 20px;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .filters .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45, 27, 0, 0.15);
    }

    .pagination a,
    .pagination span {
        border-radius: 999px;
        transition: all 0.25s ease;
    }

    .pagination a:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container">
    <div class="menu-hero">
        <h2>Menu Kopi</h2>
        <p>Temukan pilihan kopi terbaik untuk hari Anda.</p>
    </div>
    
    <!-- Search and Filter -->
    <div class="filters">
        <form method="GET" action="" style="flex: 1; display: flex; gap: 10px;">
            <input type="hidden" name="page" value="user/menu">
            <input type="text" name="search" placeholder="Cari produk..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            <button type="submit" class="btn" style="padding: 10px 20px;">Cari</button>
        </form>
    </div>
    
    <!-- Category Filter -->
    <div class="filters">
        <a href="<?php echo BASE_URL . 'index.php?page=user/menu'; ?>" class="<?php echo !$kategori_id ? 'active' : ''; ?>">Semua</a>
        <?php
        if ($categories && $categories->num_rows > 0) {
            while ($cat = $categories->fetch_assoc()) {
                $active = (int)$kategori_id === (int)$cat['id'] ? 'active' : '';
                echo '<a href="' . BASE_URL . 'index.php?page=user/menu&kategori=' . $cat['id'] . '" class="' . $active . '">' . htmlspecialchars($cat['nama_kategori']) . '</a>';
            }
        }
        ?>
    </div>
    
    <!-- Products Grid -->
    <div class="product-grid">
        <?php
        if ($products && $products->num_rows > 0) {
            while ($product = $products->fetch_assoc()) {
                echo '<div class="product-card">';
                
                // Tampilkan gambar jika ada, jika tidak gunakan placeholder
                if (!empty($product['gambar'])) {
                    echo '<img src="' . UPLOAD_URL . 'products/' . htmlspecialchars($product['gambar']) . '" alt="' . htmlspecialchars($product['nama_produk']) . '" class="product-image" style="object-fit: cover;">';
                } else {
                    echo '<div class="product-image">☕</div>';
                }
                
                echo '<div class="product-body">';
                echo '<h3>' . htmlspecialchars($product['nama_produk']) . '</h3>';
                echo '<p>' . htmlspecialchars(substr($product['deskripsi'] ?? '', 0, 50)) . '...</p>';
                echo '<div class="product-price">' . formatRupiah($product['harga']) . '</div>';
                
                if ($product['status'] === 'tersedia' && $product['stok'] > 0) {
                    echo '<div class="product-actions">';
                    echo '<button class="btn btn-sm" onclick="addToCart(' . $product['id'] . ', \'' . htmlspecialchars(addslashes($product['nama_produk'])) . '\', ' . $product['harga'] . ')">Keranjang</button>';
                    echo '</div>';
                } else {
                    echo '<div style="text-align: center; padding: 8px; background: #fee; color: #c00; border-radius: 3px; font-size: 12px;">Tidak Tersedia</div>';
                }
                
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div style="grid-column: 1/-1; text-align: center; padding: 40px;">Produk tidak ditemukan</div>';
        }
        ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($page_data['total_pages'] > 1) { ?>
        <div class="pagination">
            <?php
            if ($page_data['current_page'] > 1) {
                echo '<a href="' . BASE_URL . 'index.php?page=user/menu&search=' . urlencode($search) . '&kategori=' . $kategori_id . '&page=' . ($page_data['current_page'] - 1) . '">← Sebelumnya</a>';
            }
            
            for ($i = 1; $i <= $page_data['total_pages']; $i++) {
                if ($i === $page_data['current_page']) {
                    echo '<span class="active">' . $i . '</span>';
                } else {
                    echo '<a href="' . BASE_URL . 'index.php?page=user/menu&search=' . urlencode($search) . '&kategori=' . $kategori_id . '&page=' . $i . '">' . $i . '</a>';
                }
            }
            
            if ($page_data['current_page'] < $page_data['total_pages']) {
                echo '<a href="' . BASE_URL . 'index.php?page=user/menu&search=' . urlencode($search) . '&kategori=' . $kategori_id . '&page=' . ($page_data['current_page'] + 1) . '">Selanjutnya →</a>';
            }
            ?>
        </div>
    <?php } ?>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
