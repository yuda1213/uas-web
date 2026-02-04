<?php
/**
 * Professional Product Menu Page
 * Customer-facing menu with professional grid layout
 */

$title = 'Menu Produk';
include __DIR__ . '/../../view/layout_header_professional.php';

$product_model = new Product($conn);
$category_model = new Category($conn);

// Get filter parameters
$category_filter = $_GET['category'] ?? '';
$search_query = $_GET['search'] ?? '';
$sort_by = $_GET['sort'] ?? 'terbaru';

// Get all categories
$categories = $category_model->getAllCategories();

// Get products with filters
if ($category_filter) {
    $products = $product_model->getProductsByCategory($category_filter);
} elseif ($search_query) {
    $products = $product_model->searchProducts($search_query);
} else {
    $products = $product_model->getAllProducts();
}
?>

<style>
    .menu-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 60px 20px;
        margin-bottom: 50px;
        border-radius: var(--radius-lg);
        text-align: center;
    }

    .menu-header h1 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .menu-header p {
        font-size: 16px;
        opacity: 0.9;
    }

    .menu-controls {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 40px;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 12px 40px 12px 16px;
        border: 2px solid var(--border);
        border-radius: var(--radius-lg);
        font-size: 14px;
        transition: var(--transition);
    }

    .search-box input:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        outline: none;
    }

    .search-box i {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
    }

    .filter-controls {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 10px 16px;
        border: 2px solid var(--border);
        background: white;
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: var(--transition);
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: var(--secondary);
        color: white;
        border-color: var(--secondary);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 50px;
    }

    .product-card {
        background: white;
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
    }

    .product-image {
        width: 100%;
        height: 240px;
        background: linear-gradient(135deg, var(--lighter) 0%, #ecf0f1 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    .product-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: var(--accent);
        color: white;
        padding: 6px 12px;
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 700;
    }

    .product-badge.new {
        background: var(--secondary);
    }

    .product-badge.sale {
        background: var(--danger);
    }

    .product-info {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-category {
        font-size: 11px;
        text-transform: uppercase;
        color: var(--secondary);
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .product-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
        line-height: 1.4;
        flex-grow: 1;
    }

    .product-description {
        font-size: 13px;
        color: var(--gray);
        margin-bottom: 15px;
        line-height: 1.5;
        flex-grow: 1;
    }

    .product-rating {
        display: flex;
        gap: 4px;
        margin-bottom: 12px;
        font-size: 12px;
    }

    .product-rating .star {
        color: #ffc107;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid var(--border);
    }

    .product-price {
        font-size: 18px;
        font-weight: 700;
        color: var(--accent);
    }

    .product-price .original {
        font-size: 12px;
        color: var(--gray);
        text-decoration: line-through;
        margin-right: 8px;
        font-weight: normal;
    }

    .btn-add-cart {
        background: var(--secondary);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: var(--radius);
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-add-cart:hover {
        background: #2980b9;
        transform: translateX(2px);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 64px;
        color: var(--border);
        margin-bottom: 20px;
    }

    .empty-state h2 {
        color: var(--dark);
        margin-bottom: 10px;
    }

    .empty-state p {
        color: var(--gray);
        margin-bottom: 30px;
    }

    .categories-section {
        margin-bottom: 40px;
    }

    .categories-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .categories-list {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .category-chip {
        padding: 8px 16px;
        background: white;
        border: 2px solid var(--border);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: var(--transition);
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .category-chip:hover,
    .category-chip.active {
        background: var(--secondary);
        color: white;
        border-color: var(--secondary);
    }

    .sort-select {
        padding: 10px 16px;
        border: 2px solid var(--border);
        border-radius: var(--radius-lg);
        background: white;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: var(--transition);
    }

    .sort-select:focus {
        border-color: var(--secondary);
        outline: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .menu-header {
            padding: 40px 20px;
        }

        .menu-header h1 {
            font-size: 28px;
        }

        .menu-controls {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .product-image {
            height: 180px;
        }
    }
</style>

<!-- Menu Header -->
<div class="menu-header">
    <h1>☕ Menu Produk Kami</h1>
    <p>Nikmati berbagai pilihan kopi berkualitas tinggi</p>
</div>

<!-- Categories Filter -->
<div class="categories-section">
    <div class="categories-title">
        <i class="fas fa-filter"></i> Kategori
    </div>
    <div class="categories-list">
        <a href="<?php echo BASE_URL; ?>index.php?page=user/menu" class="category-chip <?php echo !$category_filter ? 'active' : ''; ?>">
            <i class="fas fa-th"></i> Semua
        </a>
        <?php
        if ($categories && $categories->num_rows > 0) {
            while ($cat = $categories->fetch_assoc()) {
                $active = ($category_filter == $cat['id']) ? 'active' : '';
                echo '<a href="' . BASE_URL . 'index.php?page=user/menu&category=' . $cat['id'] . '" class="category-chip ' . $active . '">';
                echo '<i class="fas fa-tag"></i> ' . htmlspecialchars($cat['nama']);
                echo '</a>';
            }
        }
        ?>
    </div>
</div>

<!-- Search and Controls -->
<div class="menu-controls">
    <div class="search-box">
        <input type="text" placeholder="Cari produk..." id="searchInput" value="<?php echo htmlspecialchars($search_query); ?>">
        <i class="fas fa-search"></i>
    </div>
    <select class="sort-select" id="sortSelect">
        <option value="terbaru">Terbaru</option>
        <option value="termurah">Harga: Murah ke Mahal</option>
        <option value="termahal">Harga: Mahal ke Murah</option>
        <option value="populer">Paling Populer</option>
    </select>
</div>

<!-- Products Grid -->
<?php
if ($products && $products->num_rows > 0) {
    echo '<div class="products-grid">';

    while ($product = $products->fetch_assoc()) {
        // Determine badge
        $badge_html = '';
        $is_new = strtotime($product['created_at'] ?? '') > strtotime('-7 days') ? true : false;

        if ($is_new) {
            $badge_html = '<div class="product-badge new">BARU</div>';
        }

        echo '<div class="product-card">';
        echo '  <div class="product-image">';
        echo '    <img src="' . BASE_URL . 'assets/images/products/' . htmlspecialchars($product['gambar'] ?? 'default.jpg') . '" alt="' . htmlspecialchars($product['nama']) . '">';
        echo $badge_html;
        echo '  </div>';
        echo '  <div class="product-info">';
        echo '    <div class="product-category">' . htmlspecialchars($product['kategori'] ?? 'Kopi') . '</div>';
        echo '    <h3 class="product-name">' . htmlspecialchars($product['nama']) . '</h3>';
        echo '    <p class="product-description">' . substr(htmlspecialchars($product['deskripsi'] ?? 'Produk berkualitas'), 0, 100) . '...</p>';
        echo '    <div class="product-rating">';
        for ($i = 0; $i < 5; $i++) {
            echo '<span class="star"><i class="fas fa-star"></i></span>';
        }
        echo '    </div>';
        echo '    <div class="product-footer">';
        echo '      <div class="product-price">' . formatRupiah($product['harga']) . '</div>';
        echo '      <button class="btn-add-cart" onclick="addToCart(' . $product['id'] . ', \'' . htmlspecialchars($product['nama']) . '\', ' . $product['harga'] . ')">';
        echo '        <i class="fas fa-shopping-cart"></i> Beli';
        echo '      </button>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }

    echo '</div>';
} else {
    echo '<div class="empty-state">';
    echo '  <i class="fas fa-inbox"></i>';
    echo '  <h2>Tidak ada produk</h2>';
    echo '  <p>Silakan coba kategori atau pencarian lainnya</p>';
    echo '  <a href="' . BASE_URL . 'index.php?page=user/menu" class="btn btn-primary">';
    echo '    <i class="fas fa-redo"></i> Kembali ke Menu';
    echo '  </a>';
    echo '</div>';
}
?>

<script>
    function addToCart(id, name, price) {
        const cartManager = new CartManager();
        if (cartManager.addItem(id, name, price, 1)) {
            const notif = new NotificationManager();
            notif.success(`${name} ditambahkan ke keranjang!`);
        }
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', (e) => {
        const query = e.target.value;
        if (query) {
            window.location.href = `<?php echo BASE_URL; ?>index.php?page=user/menu&search=${encodeURIComponent(query)}`;
        }
    });

    // Sort functionality
    document.getElementById('sortSelect').addEventListener('change', (e) => {
        const sort = e.target.value;
        // TODO: Implement sorting in backend
        console.log('Sort by:', sort);
    });
</script>

<?php include __DIR__ . '/../../view/layout_footer_professional.php'; ?>
