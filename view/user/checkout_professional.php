<?php
/**
 * Professional Checkout Page
 * Complete checkout experience with order summary
 */

$title = 'Checkout';
include __DIR__ . '/../../view/layout_header_professional.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'index.php?page=auth/login');
    exit;
}

$user_model = new User($conn);
$user = $user_model->getUserById($_SESSION['user_id']);
?>

<style>
    .checkout-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .checkout-header {
        margin-bottom: 40px;
    }

    .checkout-title {
        font-size: 32px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 10px;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--border);
        z-index: 1;
    }

    .progress-step {
        flex: 1;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .progress-number {
        width: 40px;
        height: 40px;
        background: white;
        border: 2px solid var(--border);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--gray);
        margin: 0 auto 10px;
        transition: var(--transition);
    }

    .progress-step.active .progress-number {
        background: var(--secondary);
        border-color: var(--secondary);
        color: white;
        transform: scale(1.2);
    }

    .progress-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--gray);
    }

    .progress-step.active .progress-label {
        color: var(--secondary);
        font-weight: 700;
    }

    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }

    .checkout-form {
        background: white;
        border-radius: var(--radius-lg);
        padding: 30px;
        box-shadow: var(--shadow);
    }

    .form-section {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid var(--border);
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--secondary);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 2px solid var(--border);
        border-radius: var(--radius);
        font-size: 14px;
        font-family: inherit;
        transition: var(--transition);
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        outline: none;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .payment-methods {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .payment-option {
        padding: 15px;
        border: 2px solid var(--border);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
    }

    .payment-option:hover {
        border-color: var(--secondary);
    }

    .payment-option input[type="radio"] {
        display: none;
    }

    .payment-option input[type="radio"]:checked + label {
        color: var(--secondary);
    }

    .payment-option input[type="radio"]:checked ~ .payment-option {
        border-color: var(--secondary);
        background: rgba(52, 152, 219, 0.05);
    }

    .payment-option i {
        font-size: 28px;
        margin-bottom: 10px;
        color: var(--gray);
    }

    .payment-option input[type="radio"]:checked ~ i {
        color: var(--secondary);
    }

    .payment-option label {
        font-weight: 600;
        cursor: pointer;
        display: block;
    }

    .order-summary {
        background: white;
        border-radius: var(--radius-lg);
        padding: 25px;
        box-shadow: var(--shadow);
        position: sticky;
        top: 100px;
    }

    .summary-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 20px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid var(--border);
        font-size: 14px;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-item-name {
        color: var(--dark);
        font-weight: 600;
    }

    .summary-item-qty {
        color: var(--gray);
        font-size: 13px;
    }

    .summary-item-price {
        color: var(--dark);
        font-weight: 600;
    }

    .summary-divider {
        height: 2px;
        background: var(--border);
        margin: 15px 0;
    }

    .summary-subtotal,
    .summary-tax,
    .summary-shipping {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 14px;
        color: var(--gray);
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
    }

    .summary-total-price {
        color: var(--accent);
        font-size: 20px;
    }

    .btn-checkout {
        width: 100%;
        margin-top: 20px;
        padding: 12px;
        background: linear-gradient(135deg, var(--secondary), #2980b9);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-checkout:active {
        transform: translateY(0);
    }

    .empty-cart {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-cart i {
        font-size: 64px;
        color: var(--border);
        margin-bottom: 20px;
    }

    .empty-cart h2 {
        color: var(--dark);
        margin-bottom: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .checkout-content {
            grid-template-columns: 1fr;
        }

        .order-summary {
            position: static;
            top: auto;
        }

        .form-row,
        .payment-methods {
            grid-template-columns: 1fr;
        }

        .progress-steps {
            flex-wrap: wrap;
        }

        .checkout-title {
            font-size: 24px;
        }
    }
</style>

<!-- Checkout Header -->
<div class="checkout-container">
    <div class="checkout-header">
        <h1 class="checkout-title">🛒 Checkout</h1>
    </div>

    <!-- Progress Steps -->
    <div class="progress-steps">
        <div class="progress-step active">
            <div class="progress-number">1</div>
            <div class="progress-label">Keranjang</div>
        </div>
        <div class="progress-step active">
            <div class="progress-number">2</div>
            <div class="progress-label">Pengiriman</div>
        </div>
        <div class="progress-step active">
            <div class="progress-number">3</div>
            <div class="progress-label">Pembayaran</div>
        </div>
        <div class="progress-step">
            <div class="progress-number">4</div>
            <div class="progress-label">Konfirmasi</div>
        </div>
    </div>

    <form action="<?php echo BASE_URL; ?>index.php?action=process_checkout" method="POST" class="checkout-form-wrapper">
        <div class="checkout-content">
            <!-- Main Form -->
            <div class="checkout-form">
                <!-- Shipping Information -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-map-marker-alt"></i> Alamat Pengiriman
                    </h2>

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="tel" name="no_telepon" value="<?php echo htmlspecialchars($user['no_telepon'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" rows="3" placeholder="Jalan, No. rumah, Kelurahan, Kecamatan" required><?php echo htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Kota/Kabupaten</label>
                            <input type="text" name="kota" value="<?php echo htmlspecialchars($user['kota'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Kode Pos</label>
                            <input type="text" name="kode_pos" value="<?php echo htmlspecialchars($user['kode_pos'] ?? ''); ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-truck"></i> Metode Pengiriman
                    </h2>

                    <div class="form-group">
                        <label>
                            <input type="radio" name="metode_pengiriman" value="reguler" checked required>
                            Regular (3-5 hari) - Gratis
                        </label>
                        <label>
                            <input type="radio" name="metode_pengiriman" value="express">
                            Express (1-2 hari) - Rp 50.000
                        </label>
                        <label>
                            <input type="radio" name="metode_pengiriman" value="same_day">
                            Same Day Delivery - Rp 100.000
                        </label>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-credit-card"></i> Metode Pembayaran
                    </h2>

                    <div class="payment-methods">
                        <div class="payment-option">
                            <input type="radio" id="payment_transfer" name="metode_pembayaran" value="transfer" required>
                            <label for="payment_transfer">
                                <i class="fas fa-bank"></i>
                                Transfer Bank
                            </label>
                        </div>

                        <div class="payment-option">
                            <input type="radio" id="payment_card" name="metode_pembayaran" value="card">
                            <label for="payment_card">
                                <i class="fas fa-credit-card"></i>
                                Kartu Kredit
                            </label>
                        </div>

                        <div class="payment-option">
                            <input type="radio" id="payment_wallet" name="metode_pembayaran" value="wallet">
                            <label for="payment_wallet">
                                <i class="fas fa-wallet"></i>
                                E-Wallet
                            </label>
                        </div>

                        <div class="payment-option">
                            <input type="radio" id="payment_cod" name="metode_pembayaran" value="cod">
                            <label for="payment_cod">
                                <i class="fas fa-money-bill"></i>
                                COD
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-sticky-note"></i> Catatan Tambahan
                    </h2>

                    <div class="form-group">
                        <label>Catatan untuk kurir (opsional)</label>
                        <textarea name="catatan" rows="3" placeholder="Mis: Rumah berlayar merah, mohon jangan dibunyikan bel..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h3 class="summary-title">📋 Ringkasan Pesanan</h3>

                <div id="cartItems">
                    <!-- Cart items will be loaded here -->
                </div>

                <div class="summary-divider"></div>

                <div class="summary-subtotal">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp 0</span>
                </div>

                <div class="summary-shipping">
                    <span>Pengiriman</span>
                    <span id="shipping">Gratis</span>
                </div>

                <div class="summary-tax">
                    <span>Pajak (10%)</span>
                    <span id="tax">Rp 0</span>
                </div>

                <div class="summary-divider"></div>

                <div class="summary-total">
                    <span>Total</span>
                    <span class="summary-total-price" id="total">Rp 0</span>
                </div>

                <button type="submit" class="btn-checkout">
                    <i class="fas fa-check-circle"></i> Lanjutkan Pembayaran
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Load cart from localStorage
    const cartManager = new CartManager();
    const cart = cartManager.getCart();

    if (cart.length === 0) {
        document.body.innerHTML = `
            <div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
                <div class="empty-cart">
                    <i class="fas fa-inbox"></i>
                    <h2>Keranjang Kosong</h2>
                    <p>Silakan tambahkan produk terlebih dahulu sebelum checkout</p>
                    <a href="${BASE_URL}index.php?page=user/menu" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Lanjutkan Belanja
                    </a>
                </div>
            </div>
        `;
    } else {
        // Display cart items
        let cartHTML = '';
        let subtotal = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            cartHTML += `
                <div class="summary-item">
                    <div>
                        <div class="summary-item-name">${item.name}</div>
                        <div class="summary-item-qty">x${item.quantity}</div>
                    </div>
                    <div class="summary-item-price">${formatCurrency(itemTotal)}</div>
                </div>
            `;
        });

        document.getElementById('cartItems').innerHTML = cartHTML;

        // Calculate totals
        const tax = subtotal * 0.1;
        const total = subtotal + tax;

        document.getElementById('subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('tax').textContent = formatCurrency(tax);
        document.getElementById('total').textContent = formatCurrency(total);
    }

    // Handle shipping method change
    document.querySelectorAll('input[name="metode_pengiriman"]').forEach(option => {
        option.addEventListener('change', () => {
            let shippingCost = 0;
            if (option.value === 'express') shippingCost = 50000;
            if (option.value === 'same_day') shippingCost = 100000;

            document.getElementById('shipping').textContent = shippingCost > 0 ? formatCurrency(shippingCost) : 'Gratis';

            // Update total
            const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace(/[^0-9,-]/g, '').replace('.', '').replace(',', '.'));
            const tax = subtotal * 0.1;
            const total = subtotal + tax + shippingCost;
            document.getElementById('total').textContent = formatCurrency(total);
        });
    });
</script>

<?php include __DIR__ . '/../../view/layout_footer_professional.php'; ?>
