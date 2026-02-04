<?php
$title = 'Checkout';

$order_model = new Order($conn);
$product_model = new Product($conn);
$user_id = getCurrentUser()['id'];

// Handle AJAX form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    $catatan = sanitize($_POST['catatan'] ?? '');
    $metode_pembayaran = sanitize($_POST['metode_pembayaran'] ?? 'cash');
    $tipe_pesanan = sanitize($_POST['tipe_pesanan'] ?? 'dine_in');
    
    // Get delivery address data if delivery
    $delivery_address = '';
    $delivery_lat = 0;
    $delivery_lng = 0;
    if ($tipe_pesanan === 'delivery') {
        $delivery_address = sanitize($_POST['delivery_address'] ?? '');
        $delivery_lat = floatval($_POST['delivery_lat'] ?? 0);
        $delivery_lng = floatval($_POST['delivery_lng'] ?? 0);
        
        // Validate delivery address
        if (empty($delivery_address)) {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Alamat pengiriman harus diisi untuk pesanan delivery']);
                exit;
            }
            setAlert('danger', 'Alamat pengiriman harus diisi untuk pesanan delivery');
            header('Location: ' . BASE_URL . 'index.php?page=user/checkout');
            exit;
        }
    }
    
    // Get cart data from request (dari JSON)
    $cart_json = isset($_POST['cart']) ? $_POST['cart'] : '[]';
    $cart_items = json_decode($cart_json, true);
    
    if (empty($cart_items)) {
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Keranjang Anda kosong']);
            exit;
        }
        setAlert('danger', 'Keranjang Anda kosong');
    } else {
        // Calculate total
        $total_harga = 0;
        foreach ($cart_items as $item) {
            $total_harga += $item['price'] * $item['quantity'];
        }
        
        // Add tax (10%)
        $total_harga = $total_harga * 1.1;
        
        // Add delivery fee if delivery
        if ($tipe_pesanan === 'delivery') {
            $total_harga += 10000; // Ongkos kirim Rp 10.000
        }
        
        // Create order with delivery info
        $order_id = $order_model->createOrder($user_id, $total_harga, $catatan, $tipe_pesanan, $delivery_address);
        
        if ($order_id) {
            // Add order items
            $success = true;
            foreach ($cart_items as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                if (!$order_model->addOrderItem($order_id, $item['id'], $item['quantity'], $item['price'], $subtotal)) {
                    $success = false;
                    break;
                }
            }
            
            if ($success) {
                // Generate order number
                $order_number = 'ORDER-' . date('YmdHis') . '-' . str_pad($order_id, 4, '0', STR_PAD_LEFT);
                
                if ($is_ajax) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Pesanan berhasil dibuat',
                        'order_number' => $order_number,
                        'order_id' => $order_id
                    ]);
                    exit;
                }
                
                setAlert('success', 'Pesanan berhasil dibuat. No Pesanan: ' . $order_number);
                redirect(BASE_URL . 'index.php?page=user/orders');
            } else {
                if ($is_ajax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Gagal menyimpan item pesanan']);
                    exit;
                }
                setAlert('danger', 'Gagal menyimpan item pesanan');
            }
        } else {
            if ($is_ajax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal membuat pesanan']);
                exit;
            }
            setAlert('danger', 'Gagal membuat pesanan');
        }
    }
}

include __DIR__ . '/../layout_header.php';
?>
?>

<style>
    :root {
        --coffee-dark: #2D1B00;
        --coffee: #6F4E37;
        --coffee-light: #8B6F47;
        --gold: #D4A574;
        --cream: #FAF7F4;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
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

    .checkout-container {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 24px;
        margin: 24px 0;
    }
    
    .checkout-form {
        background: rgba(255, 255, 255, 0.96);
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.08),
                    0 0 1px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        border: 1.5px solid rgba(212, 165, 116, 0.2);
        animation: slideInLeft 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        backdrop-filter: blur(12px);
    }
    
    .checkout-form h3 {
        margin: 28px 0 18px 0;
        font-size: 16px;
        font-weight: 900;
        border-top: 1.5px solid rgba(212, 165, 116, 0.2);
        padding-top: 20px;
        color: var(--coffee-dark);
        letter-spacing: -0.4px;
        line-height: 1.2;
    }
    
    .checkout-form h3:first-child {
        border-top: none;
        padding-top: 0;
        margin-top: 0;
    }
    
    .summary-card {
        background: rgba(255, 255, 255, 0.96);
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.08),
                    0 0 1px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        border: 1.5px solid rgba(212, 165, 116, 0.2);
        position: sticky;
        top: 120px;
        animation: fadeUp 0.6s ease-out 0.2s backwards;
        backdrop-filter: blur(12px);
    }
    
    .summary-card h3 {
        margin: 0 0 16px 0;
        font-size: 18px;
        font-weight: 900;
        color: #2D1B00;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid rgba(239, 230, 221, 0.5);
        font-size: 14px;
        color: #666;
    }
    
    .summary-row.total {
        border-bottom: none;
        font-weight: 900;
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #D4A574 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 22px;
        padding-top: 16px;
        margin-top: 14px;
        border-top: 1.5px solid rgba(212, 165, 116, 0.2);
    }

    .checkout-form input,
    .checkout-form textarea,
    .checkout-form select {
        border: 1.5px solid rgba(212, 165, 116, 0.3);
        border-radius: 12px;
        background: rgba(255, 253, 251, 0.85);
        padding: 14px 16px;
        font-family: inherit;
        font-size: 14px;
        transition: all 0.3s ease;
        width: 100%;
        backdrop-filter: blur(8px);
        box-shadow: 0 2px 8px rgba(45, 27, 0, 0.02);
    }

    .checkout-form input:focus,
    .checkout-form textarea:focus,
    .checkout-form select:focus {
        outline: none;
        border-color: rgba(212, 165, 116, 0.8);
        box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.2),
                    inset 0 2px 4px rgba(212, 165, 116, 0.1);
        background: rgba(255, 255, 255, 0.96);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #2D1B00;
        font-weight: 800;
        font-size: 13px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
        font-size: 14px;
        line-height: 1.6;
        letter-spacing: 0.2px;
    }

    .form-group.radio-group {
        margin-bottom: 20px;
    }

    .form-group.radio-group label {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .form-group.radio-group label:hover {
        color: var(--coffee);
    }

    .form-group.radio-group input[type="radio"] {
        margin-right: 10px;
        cursor: pointer;
        width: 18px;
        height: 18px;
    }

    .btn-success {
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        padding: 14px 20px;
        font-size: 15px;
        width: 100%;
        transition: all 0.25s ease;
        cursor: pointer;
        margin-top: 4px;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45, 27, 0, 0.15);
    }

    .btn-secondary {
        background: #FFF;
        color: var(--coffee);
        border: 1.5px solid #E3D5C8;
        border-radius: 12px;
        font-weight: 700;
        padding: 12px 16px;
        cursor: pointer;
        width: 100%;
        transition: all 0.25s ease;
    }

    .btn-secondary:hover {
        background: #FFF6ED;
        border-color: #D4A574;
    }

    .payment-option {
        display: flex;
        align-items: center;
        padding: 14px 16px;
        border: 2px solid rgba(212, 165, 116, 0.3);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: rgba(255, 253, 251, 0.85);
        margin-bottom: 12px !important;
    }

    .payment-option:hover {
        border-color: rgba(212, 165, 116, 0.6);
        background: rgba(255, 248, 240, 0.9);
        transform: translateX(4px);
    }

    .payment-option input[type="radio"] {
        margin-right: 12px;
        cursor: pointer;
        width: 18px;
        height: 18px;
    }

    .payment-option input[type="radio"]:checked + .payment-label {
        color: var(--coffee-dark);
        font-weight: 700;
    }

    .payment-option:has(input:checked) {
        border-color: var(--coffee);
        background: linear-gradient(135deg, rgba(111, 78, 55, 0.1) 0%, rgba(212, 165, 116, 0.15) 100%);
        box-shadow: 0 4px 12px rgba(111, 78, 55, 0.15);
    }

    .payment-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #5C4A3A;
        transition: all 0.3s ease;
    }

    .payment-label i {
        font-size: 18px;
        color: var(--coffee);
    }

    /* Order Type Options */
    .order-type-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .order-type-option {
        position: relative;
        cursor: pointer;
    }

    .order-type-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .order-type-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 24px 16px;
        border: 2.5px solid rgba(212, 165, 116, 0.3);
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(255, 253, 251, 0.95) 0%, rgba(255, 248, 240, 0.95) 100%);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        text-align: center;
    }

    .order-type-card:hover {
        border-color: rgba(212, 165, 116, 0.6);
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(45, 27, 0, 0.1);
    }

    .order-type-option input[type="radio"]:checked + .order-type-card {
        border-color: var(--coffee);
        background: linear-gradient(135deg, rgba(111, 78, 55, 0.08) 0%, rgba(212, 165, 116, 0.15) 100%);
        box-shadow: 0 8px 24px rgba(111, 78, 55, 0.2);
        transform: translateY(-2px);
    }

    .order-type-card .type-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(212, 165, 116, 0.2) 0%, rgba(212, 165, 116, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 14px;
        transition: all 0.3s ease;
    }

    .order-type-option input[type="radio"]:checked + .order-type-card .type-icon {
        background: linear-gradient(135deg, var(--coffee) 0%, var(--coffee-light) 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(111, 78, 55, 0.3);
    }

    .order-type-card .type-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--coffee-dark);
        margin-bottom: 6px;
        letter-spacing: -0.3px;
    }

    .order-type-card .type-desc {
        font-size: 12px;
        color: #8B7355;
        line-height: 1.4;
    }

    .order-type-card .type-badge {
        margin-top: 10px;
        padding: 4px 12px;
        background: rgba(76, 175, 80, 0.15);
        color: #2e7d32;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .order-type-card .type-badge.delivery-fee {
        background: rgba(255, 152, 0, 0.15);
        color: #e65100;
    }

    .delivery-address-box {
        margin-top: 16px;
        padding: 18px;
        border: 2px solid rgba(212, 165, 116, 0.3);
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(255, 248, 240, 0.8) 0%, rgba(253, 246, 240, 0.6) 100%);
        animation: slideDown 0.3s ease;
        display: none;
    }

    .delivery-address-box.active {
        display: block;
    }

    .delivery-address-box h4 {
        margin: 0 0 12px 0;
        font-size: 14px;
        font-weight: 800;
        color: var(--coffee-dark);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .delivery-address-box h4 i {
        color: var(--coffee);
    }

    .delivery-address-box .address-preview {
        background: white;
        padding: 14px;
        border-radius: 10px;
        border: 1px solid rgba(212, 165, 116, 0.2);
        color: #5C4A3A;
        font-size: 14px;
        line-height: 1.6;
    }

    .delivery-address-box .delivery-note {
        margin-top: 12px;
        padding: 10px 14px;
        background: rgba(255, 152, 0, 0.1);
        border-radius: 8px;
        font-size: 12px;
        color: #e65100;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    @media (max-width: 480px) {
        .order-type-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Google Maps Styles */
    .map-container {
        position: relative;
        width: 100%;
        height: 280px;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 16px;
        border: 2px solid rgba(212, 165, 116, 0.3);
        background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
    }

    #deliveryMap {
        width: 100%;
        height: 100%;
    }

    .map-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(45, 27, 0, 0.75);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        backdrop-filter: blur(2px);
    }

    .map-overlay:hover {
        background: rgba(45, 27, 0, 0.65);
    }

    .map-overlay i {
        font-size: 48px;
        margin-bottom: 12px;
        color: var(--gold);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .map-overlay span {
        font-size: 14px;
        font-weight: 600;
    }

    .btn-get-location {
        width: 100%;
        padding: 12px 16px;
        margin-bottom: 16px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .btn-get-location:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    }

    .btn-get-location:active {
        transform: translateY(0);
    }

    .btn-get-location i {
        font-size: 16px;
    }

    .address-search-box {
        position: relative;
        margin-bottom: 16px;
    }

    .address-search-box i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--coffee);
        font-size: 16px;
    }

    .address-search-box input {
        width: 100%;
        padding: 14px 16px 14px 46px;
        border: 2px solid rgba(212, 165, 116, 0.3);
        border-radius: 12px;
        font-size: 14px;
        background: white;
        transition: all 0.3s ease;
    }

    .address-search-box input:focus {
        outline: none;
        border-color: var(--coffee);
        box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1);
    }

    .selected-address {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px;
        background: white;
        border-radius: 12px;
        border: 2px solid rgba(76, 175, 80, 0.3);
        margin-bottom: 16px;
    }

    .selected-address .address-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }

    .selected-address .address-details {
        flex: 1;
        min-width: 0;
    }

    .selected-address .address-label {
        font-size: 11px;
        color: #4CAF50;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .selected-address .address-text {
        font-size: 13px;
        color: var(--coffee-dark);
        font-weight: 500;
        line-height: 1.7;
        max-height: 120px;
        overflow-y: auto;
        padding-right: 8px;
    }
    
    .selected-address .address-text::-webkit-scrollbar {
        width: 4px;
    }
    
    .selected-address .address-text::-webkit-scrollbar-thumb {
        background: var(--gold);
        border-radius: 4px;
    }

    .btn-edit-address {
        width: 36px;
        height: 36px;
        border: none;
        background: rgba(111, 78, 55, 0.1);
        border-radius: 10px;
        color: var(--coffee);
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .btn-edit-address:hover {
        background: var(--coffee);
        color: white;
    }

    /* Map marker animation */
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .map-marker-pulse {
        animation: bounce 1s ease-in-out infinite;
    }

    /* Autocomplete dropdown styling */
    .pac-container {
        border-radius: 12px;
        border: 2px solid rgba(212, 165, 116, 0.3);
        box-shadow: 0 8px 32px rgba(45, 27, 0, 0.15);
        margin-top: 8px;
        font-family: inherit;
    }

    .pac-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid rgba(212, 165, 116, 0.1);
    }

    .pac-item:hover {
        background: rgba(255, 248, 240, 0.8);
    }

    .pac-icon {
        margin-right: 12px;
    }

    .pac-item-query {
        font-weight: 700;
        color: var(--coffee-dark);
    }

    .payment-form-box {
        margin-top: 20px;
        padding: 20px;
        border: 2px solid rgba(212, 165, 116, 0.3);
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(255, 248, 240, 0.5) 0%, rgba(253, 246, 240, 0.3) 100%);
        animation: slideDown 0.3s ease;
    }

    .payment-form-box h4 {
        margin-top: 0;
        margin-bottom: 16px;
        color: var(--coffee-dark);
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bank-info {
        background: white;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 12px;
        border: 1px solid rgba(212, 165, 116, 0.2);
    }

    .bank-info p {
        margin: 8px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bank-info strong {
        color: var(--coffee-dark);
        font-weight: 700;
    }

    .copy-btn {
        background: var(--coffee);
        color: white;
        border: none;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .copy-btn:hover {
        background: var(--coffee-dark);
        transform: scale(1.05);
    }

    .card-input-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 12px;
    }

    .card-input-group.full {
        grid-template-columns: 1fr;
    }

    #paymentSuccessModal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }

    .success-modal-content {
        background: white;
        padding: 40px;
        border-radius: 20px;
        text-align: center;
        max-width: 500px;
        width: 90%;
        animation: slideDown 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .success-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: white;
        animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s backwards;
    }

    @keyframes scaleIn {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }

    .success-modal-content h2 {
        color: var(--coffee-dark);
        margin-bottom: 12px;
        font-size: 28px;
    }

    .success-modal-content p {
        color: #666;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .order-number {
        background: linear-gradient(135deg, rgba(111, 78, 55, 0.1) 0%, rgba(212, 165, 116, 0.15) 100%);
        padding: 12px;
        border-radius: 8px;
        margin: 20px 0;
        font-weight: 700;
        color: var(--coffee-dark);
        font-size: 18px;
    }
    
    @media (max-width: 768px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
        
        .summary-card {
            position: static;
        }
    }
</style>

<div class="container">
    <div class="page-hero">
        <h2>Checkout</h2>
        <p>Konfirmasi detail pesanan dan pembayaran.</p>
    </div>
    
    <?php 
    $alert = getAlert();
    if ($alert) {
        echo '<div class="alert alert-' . htmlspecialchars($alert['type']) . '">';
        echo htmlspecialchars($alert['message']);
        echo '</div>';
    }
    ?>
    
    <div class="checkout-container">
        <form method="POST" id="checkoutForm" class="checkout-form">
            <h3>Tipe Pesanan</h3>
            
            <div class="order-type-grid">
                <label class="order-type-option">
                    <input type="radio" name="tipe_pesanan" value="dine_in" checked onchange="toggleOrderType(this.value)">
                    <div class="order-type-card">
                        <div class="type-icon">
                            <i class="fas fa-mug-hot"></i>
                        </div>
                        <div class="type-title">Minum di Sini</div>
                        <div class="type-desc">Nikmati kopi langsung di tempat kami</div>
                        <div class="type-badge">Tanpa Ongkir</div>
                    </div>
                </label>
                
                <label class="order-type-option">
                    <input type="radio" name="tipe_pesanan" value="delivery" onchange="toggleOrderType(this.value)">
                    <div class="order-type-card">
                        <div class="type-icon">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                        <div class="type-title">Di Kirim</div>
                        <div class="type-desc">Pesanan diantar ke alamat Anda</div>
                        <div class="type-badge delivery-fee">+ Rp 10.000</div>
                    </div>
                </label>
            </div>

            <div id="deliveryAddressBox" class="delivery-address-box">
                <h4><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman</h4>
                
                <!-- Map Container -->
                <div class="map-container">
                    <div id="deliveryMap"></div>
                    <div class="map-overlay" id="mapOverlay">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Klik untuk aktifkan peta & deteksi lokasi</span>
                    </div>
                </div>
                
                <!-- Location Button -->
                <button type="button" class="btn-get-location" onclick="getUserCurrentLocation()">
                    <i class="fas fa-crosshairs"></i> Gunakan Lokasi Saya Saat Ini
                </button>
                
                <!-- Search Address -->
                <div class="address-search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="addressSearch" placeholder="Cari alamat, jalan, atau tempat..." autocomplete="off">
                </div>
                
                <!-- Selected Address Display -->
                <div class="selected-address" id="selectedAddress">
                    <div class="address-icon">
                        <i class="fas fa-map-pin"></i>
                    </div>
                    <div class="address-details">
                        <div class="address-label">Alamat Pengiriman</div>
                        <div class="address-text" id="addressText">
                            <?php 
                            $alamat = getCurrentUser()['alamat'] ?? '';
                            echo !empty($alamat) ? htmlspecialchars($alamat) : 'Pilih lokasi di peta atau cari alamat';
                            ?>
                        </div>
                    </div>
                    <button type="button" class="btn-edit-address" onclick="focusAddressSearch()" title="Edit Alamat">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                
                <!-- Hidden inputs -->
                <input type="hidden" id="deliveryLat" name="delivery_lat" value="">
                <input type="hidden" id="deliveryLng" name="delivery_lng" value="">
                <input type="hidden" id="deliveryAddress" name="delivery_address" value="<?php echo htmlspecialchars($alamat); ?>">
                
                <div class="delivery-note">
                    <i class="fas fa-info-circle"></i>
                    Pin lokasi di peta untuk pengiriman akurat. Ongkir: <strong>Rp 10.000</strong>
                </div>
            </div>
            
            <h3>Informasi Pemesan</h3>
            
            <div class="form-group">
                <label>Nama</label>
                <input type="text" value="<?php echo htmlspecialchars(getCurrentUser()['nama']); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" value="<?php echo htmlspecialchars(getCurrentUser()['email']); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label>No Telepon</label>
                <input type="tel" value="<?php echo htmlspecialchars(getCurrentUser()['no_telepon'] ?? ''); ?>" readonly>
            </div>
            
            <h3>Metode Pembayaran</h3>
            
            <div class="form-group">
                <label class="payment-option">
                    <input type="radio" name="metode_pembayaran" value="cash" checked onclick="showPaymentForm('cash')"> 
                    <span class="payment-label">
                        <i class="fas fa-money-bill-wave"></i>
                        Bayar di Tempat (Cash)
                    </span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="payment-option">
                    <input type="radio" name="metode_pembayaran" value="transfer" onclick="showPaymentForm('transfer')"> 
                    <span class="payment-label">
                        <i class="fas fa-university"></i>
                        Transfer Bank
                    </span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="payment-option">
                    <input type="radio" name="metode_pembayaran" value="kartu_kredit" onclick="showPaymentForm('kartu_kredit')"> 
                    <span class="payment-label">
                        <i class="fas fa-credit-card"></i>
                        Kartu Kredit
                    </span>
                </label>
            </div>

            <!-- Payment Forms Container -->
            <div id="paymentFormsContainer"></div>
            
            <h3>Catatan (Optional)</h3>
            
            <div class="form-group">
                <textarea name="catatan" rows="4" placeholder="Catatan tambahan untuk pesanan Anda..."></textarea>
            </div>
            
            <input type="hidden" id="cartInput" name="cart">
            
            <button type="submit" class="btn btn-success" style="width: 100%; padding: 12px; font-size: 16px;">
                Konfirmasi Pesanan
            </button>
            
            <a href="<?php echo BASE_URL . 'index.php?page=user/cart'; ?>" class="btn btn-secondary" style="width: 100%; padding: 12px; margin-top: 10px; text-align: center;">
                Kembali ke Keranjang
            </a>
        </form>
        
        <div class="summary-card">
            <h3>Ringkasan Pesanan</h3>
            <div id="orderSummary">
                <p>Memuat ringkasan...</p>
            </div>
        </div>
    </div>
</div>

<!-- Payment Success Modal -->
<div id="paymentSuccessModal">
    <div class="success-modal-content">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h2>Pembayaran Berhasil!</h2>
        <p>Terima kasih atas pesanan Anda. Pesanan Anda sedang diproses.</p>
        <div class="order-number">
            No. Pesanan: <span id="orderNumberDisplay"></span>
        </div>
        <button onclick="redirectToOrders()" class="btn btn-success">Lihat Pesanan Saya</button>
    </div>
</div>

<script>
    function showPaymentForm(method) {
        const container = document.getElementById('paymentFormsContainer');
        
        if (method === 'cash') {
            container.innerHTML = `
                <div class="payment-form-box">
                    <h4><i class="fas fa-info-circle"></i> Informasi Pembayaran</h4>
                    <p style="color: #666; line-height: 1.6;">
                        <i class="fas fa-check-circle" style="color: #4CAF50;"></i> 
                        Pembayaran akan dilakukan secara tunai saat pesanan Anda tiba atau saat Anda mengambil pesanan di toko.
                    </p>
                    <p style="color: #666; line-height: 1.6; margin-top: 12px;">
                        <i class="fas fa-wallet" style="color: var(--coffee);"></i> 
                        Silakan siapkan uang pas untuk mempercepat proses transaksi.
                    </p>
                </div>
            `;
        } else if (method === 'transfer') {
            container.innerHTML = `
                <div class="payment-form-box">
                    <h4><i class="fas fa-university"></i> Informasi Transfer Bank</h4>
                    <div class="bank-info">
                        <p><strong>Bank:</strong> BCA</p>
                        <p>
                            <strong>No. Rekening:</strong> 
                            <span id="accountNumber">1234567890</span>
                            <button class="copy-btn" onclick="copyToClipboard('accountNumber')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </p>
                        <p><strong>Atas Nama:</strong> Coffee Shop Indonesia</p>
                    </div>
                    <div class="bank-info">
                        <p><strong>Bank:</strong> Mandiri</p>
                        <p>
                            <strong>No. Rekening:</strong> 
                            <span id="accountNumber2">9876543210</span>
                            <button class="copy-btn" onclick="copyToClipboard('accountNumber2')">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                        </p>
                        <p><strong>Atas Nama:</strong> Coffee Shop Indonesia</p>
                    </div>
                    <p style="color: #ff6b6b; font-size: 13px; margin-top: 12px;">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Harap transfer sesuai total yang tertera dan konfirmasi pembayaran Anda.
                    </p>
                </div>
            `;
        } else if (method === 'kartu_kredit') {
            container.innerHTML = `
                <div class="payment-form-box">
                    <h4><i class="fas fa-credit-card"></i> Informasi Kartu Kredit</h4>
                    <div class="card-input-group full">
                        <div class="form-group">
                            <label>Nomor Kartu</label>
                            <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" 
                                   onkeyup="formatCardNumber(this)" required>
                        </div>
                    </div>
                    <div class="card-input-group full">
                        <div class="form-group">
                            <label>Nama Pemegang Kartu</label>
                            <input type="text" id="cardName" placeholder="NAMA SESUAI KARTU" required>
                        </div>
                    </div>
                    <div class="card-input-group">
                        <div class="form-group">
                            <label>Tanggal Kadaluarsa</label>
                            <input type="text" id="cardExpiry" placeholder="MM/YY" maxlength="5" 
                                   onkeyup="formatExpiry(this)" required>
                        </div>
                        <div class="form-group">
                            <label>CVV</label>
                            <input type="text" id="cardCVV" placeholder="123" maxlength="3" 
                                   onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                        </div>
                    </div>
                    <p style="color: #666; font-size: 12px; margin-top: 8px;">
                        <i class="fas fa-lock" style="color: #4CAF50;"></i> 
                        Transaksi Anda aman dan terenkripsi
                    </p>
                </div>
            `;
        }
    }

    function formatCardNumber(input) {
        let value = input.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        input.value = formattedValue;
    }

    function formatExpiry(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        input.value = value;
    }

    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        const text = element.textContent;
        navigator.clipboard.writeText(text).then(() => {
            const btn = element.nextElementSibling;
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            btn.style.background = '#4CAF50';
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.style.background = '';
            }, 2000);
        });
    }

    function showSuccessModal(orderNumber) {
        document.getElementById('orderNumberDisplay').textContent = orderNumber;
        document.getElementById('paymentSuccessModal').style.display = 'flex';
        
        // Clear cart
        localStorage.removeItem('cart');
        
        // Auto redirect after 5 seconds
        setTimeout(() => {
            redirectToOrders();
        }, 5000);
    }

    function redirectToOrders() {
        window.location.href = '<?php echo BASE_URL; ?>index.php?page=user/orders';
    }

    // Toggle Order Type (Dine In / Delivery)
    let currentOrderType = 'dine_in';
    
    function toggleOrderType(type) {
        currentOrderType = type;
        const addressBox = document.getElementById('deliveryAddressBox');
        
        if (type === 'delivery') {
            addressBox.classList.add('active');
        } else {
            addressBox.classList.remove('active');
        }
        
        // Update summary with delivery fee
        updateSummary();
    }

    function validateCardPayment() {
        const cardNumber = document.getElementById('cardNumber');
        const cardName = document.getElementById('cardName');
        const cardExpiry = document.getElementById('cardExpiry');
        const cardCVV = document.getElementById('cardCVV');
        
        if (!cardNumber || !cardName || !cardExpiry || !cardCVV) {
            return true; // Not credit card payment
        }
        
        if (!cardNumber.value || !cardName.value || !cardExpiry.value || !cardCVV.value) {
            alert('Harap lengkapi semua informasi kartu kredit');
            return false;
        }
        
        const cardNum = cardNumber.value.replace(/\s/g, '');
        if (cardNum.length < 13) {
            alert('Nomor kartu tidak valid');
            return false;
        }
        
        if (cardCVV.value.length < 3) {
            alert('CVV tidak valid');
            return false;
        }
        
        return true;
    }
    
    function updateSummary() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        document.getElementById('cartInput').value = JSON.stringify(cart);
        
        let subtotal = 0;
        let html = '';
        
        cart.forEach(item => {
            subtotal += item.price * item.quantity;
            html += `<div class="summary-row"><span>${item.quantity}x ${escapeHtml(item.name)}</span><span>${formatCurrency(item.price * item.quantity)}</span></div>`;
        });
        
        const tax = subtotal * 0.1;
        const deliveryFee = currentOrderType === 'delivery' ? 10000 : 0;
        const total = subtotal + tax + deliveryFee;
        
        html += `<div class="summary-row"><span>Subtotal:</span><span>${formatCurrency(subtotal)}</span></div>`;
        html += `<div class="summary-row"><span>Pajak (10%):</span><span>${formatCurrency(tax)}</span></div>`;
        
        if (currentOrderType === 'delivery') {
            html += `<div class="summary-row" style="color: #e65100;"><span><i class="fas fa-motorcycle" style="margin-right: 6px;"></i>Ongkos Kirim:</span><span>${formatCurrency(deliveryFee)}</span></div>`;
        }
        
        // Order type badge
        const orderTypeBadge = currentOrderType === 'delivery' 
            ? '<span style="background: rgba(255, 152, 0, 0.15); color: #e65100; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 700;"><i class="fas fa-motorcycle"></i> Delivery</span>'
            : '<span style="background: rgba(76, 175, 80, 0.15); color: #2e7d32; padding: 3px 10px; border-radius: 12px; font-size: 11px; font-weight: 700;"><i class="fas fa-mug-hot"></i> Dine In</span>';
        
        html += `<div class="summary-row" style="border-bottom: none; padding-bottom: 0;"><span>Tipe:</span>${orderTypeBadge}</div>`;
        html += `<div class="summary-row total"><span>Total:</span><span>${formatCurrency(total)}</span></div>`;
        
        if (cart.length === 0) {
            html = '<p style="text-align: center; color: #999;">Keranjang kosong</p>';
        }
        
        document.getElementById('orderSummary').innerHTML = html;
    }
    
    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value);
    }
    
    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }
    
    updateSummary();
    
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        if (cart.length === 0) {
            alert('Keranjang Anda kosong!');
            return false;
        }
        
        // Validate delivery address if delivery selected
        const orderType = document.querySelector('input[name="tipe_pesanan"]:checked').value;
        if (orderType === 'delivery') {
            const deliveryAddress = document.getElementById('deliveryAddress').value;
            if (!deliveryAddress || deliveryAddress.trim() === '') {
                alert('Silakan pilih alamat pengiriman terlebih dahulu!');
                document.getElementById('addressSearch').focus();
                return false;
            }
        }
        
        const paymentMethod = document.querySelector('input[name="metode_pembayaran"]:checked').value;
        
        // Validate card payment if selected
        if (paymentMethod === 'kartu_kredit' && !validateCardPayment()) {
            return false;
        }
        
        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses Pembayaran...';
        submitBtn.disabled = true;
        
        // Add cart data to hidden input
        let cartInput = document.getElementById('cartInput');
        if (cartInput) {
            cartInput.value = JSON.stringify(cart);
        }
        
        // Create FormData
        const formData = new FormData(this);
        
        // Submit via AJAX
        fetch(window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear cart
                localStorage.removeItem('cart');
                
                // Show success modal with order number
                showSuccessModal(data.order_number);
            } else {
                alert(data.message || 'Terjadi kesalahan saat memproses pesanan');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });

    // Initialize payment form on page load
    showPaymentForm('cash');

    // =====================
    // DELIVERY MAP INTEGRATION
    // Using Leaflet + OpenStreetMap (Free, No API Key)
    // =====================
    
    let map = null;
    let marker = null;
    let mapInitialized = false;
    let leafletLoaded = false;
    let searchTimeout = null;

    // Default location (Bandung, Indonesia)
    const defaultLocation = { lat: -6.9175, lng: 107.6191 };
    
    // User's saved address from database
    const userSavedAddress = <?php echo json_encode(getCurrentUser()['alamat'] ?? ''); ?>;

    // Load Leaflet library dynamically
    function loadLeaflet() {
        return new Promise((resolve, reject) => {
            if (leafletLoaded && typeof L !== 'undefined') {
                resolve();
                return;
            }

            // Load CSS first
            if (!document.querySelector('link[href*="leaflet.css"]')) {
                const css = document.createElement('link');
                css.rel = 'stylesheet';
                css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                document.head.appendChild(css);
            }

            // Load JS
            if (!document.querySelector('script[src*="leaflet.js"]')) {
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                script.onload = () => {
                    leafletLoaded = true;
                    console.log('Leaflet loaded successfully');
                    resolve();
                };
                script.onerror = reject;
                document.head.appendChild(script);
            } else {
                leafletLoaded = true;
                resolve();
            }
        });
    }

    // Initialize the map
    async function initializeMap() {
        if (mapInitialized && map) {
            map.invalidateSize();
            return;
        }

        try {
            await loadLeaflet();
        } catch (error) {
            console.error('Failed to load Leaflet:', error);
            return;
        }

        const mapElement = document.getElementById('deliveryMap');
        if (!mapElement || typeof L === 'undefined') {
            console.error('Map element not found or Leaflet not loaded');
            return;
        }

        // Create map
        map = L.map('deliveryMap', {
            center: [defaultLocation.lat, defaultLocation.lng],
            zoom: 15,
            zoomControl: true
        });

        // Add tile layer (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19
        }).addTo(map);

        // Create custom marker icon
        const markerIcon = L.divIcon({
            className: 'delivery-marker',
            html: `<div style="
                background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                width: 32px; height: 32px;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                border: 3px solid white;
                box-shadow: 0 4px 15px rgba(0,0,0,0.4);
            "></div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 32]
        });

        // Add draggable marker
        marker = L.marker([defaultLocation.lat, defaultLocation.lng], {
            draggable: true,
            icon: markerIcon
        }).addTo(map);

        // Event: Marker drag end
        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            reverseGeocode(pos.lat, pos.lng);
        });

        // Event: Map click
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        mapInitialized = true;
        console.log('Map initialized successfully');

        // Fix map size after initialization
        setTimeout(() => map.invalidateSize(), 100);
        
        // Auto-sync with user's saved address if available
        if (userSavedAddress && userSavedAddress.trim() !== '') {
            console.log('Syncing map with user address:', userSavedAddress);
            geocodeUserAddress(userSavedAddress);
        }
    }
    
    // Geocode user's saved address and move map to that location
    function geocodeUserAddress(address) {
        showAddressStatus('Menyinkronkan alamat...', 'loading');
        
        const url = `https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(address)}&countrycodes=id&limit=1&addressdetails=1&accept-language=id`;
        
        console.log('Geocoding user address:', address);
        
        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(results => {
                console.log('Geocode results:', results);
                
                if (results && results.length > 0) {
                    const lat = parseFloat(results[0].lat);
                    const lng = parseFloat(results[0].lon);
                    
                    // Move map and marker
                    if (map && marker) {
                        map.setView([lat, lng], 17);
                        marker.setLatLng([lat, lng]);
                    }
                    
                    // Use display_name or build from address
                    let detailedAddress = results[0].display_name || address;
                    if (results[0].address) {
                        const built = buildDetailedAddress(results[0].address);
                        if (built) detailedAddress = built;
                    } else if (results[0].display_name) {
                        detailedAddress = results[0].display_name;
                    }
                    
                    // Update address display
                    setDeliveryAddress(detailedAddress, lat, lng);
                    
                    // Hide overlay since we found the address
                    const overlay = document.getElementById('mapOverlay');
                    if (overlay) overlay.style.display = 'none';
                    
                    console.log('Address synced successfully:', results[0].display_name);
                } else {
                    // Address not found, show original address anyway
                    setDeliveryAddress(address, null, null);
                    console.log('Address not found in geocoding, using original');
                }
            })
            .catch(err => {
                console.error('Geocoding error:', err);
                setDeliveryAddress(address, null, null);
            });
    }

    // Get user's current location
    function getUserCurrentLocation() {
        // Hide overlay
        const overlay = document.getElementById('mapOverlay');
        if (overlay) overlay.style.display = 'none';

        if (!navigator.geolocation) {
            showAddressStatus('Browser tidak mendukung geolocation', 'error');
            return;
        }

        showAddressStatus('Mencari lokasi Anda...', 'loading');

        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Initialize map first if needed
                if (!mapInitialized) {
                    loadLeaflet().then(() => {
                        initializeMap().then(() => {
                            moveMapTo(lat, lng);
                        });
                    });
                } else {
                    moveMapTo(lat, lng);
                }
            },
            function(error) {
                console.error('Geolocation error:', error);
                let msg = 'Gagal mendapatkan lokasi. ';
                if (error.code === 1) msg += 'Izin ditolak.';
                else if (error.code === 2) msg += 'Lokasi tidak tersedia.';
                else if (error.code === 3) msg += 'Waktu habis.';
                showAddressStatus(msg + ' Pilih manual di peta.', 'error');
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    // Move map and marker to location
    function moveMapTo(lat, lng) {
        if (map && marker) {
            map.setView([lat, lng], 19);
            marker.setLatLng([lat, lng]);
            reverseGeocode(lat, lng);
        }
    }

    // Reverse geocode coordinates to address - Get SUPER detailed address
    function reverseGeocode(lat, lng) {
        // Save coordinates immediately
        document.getElementById('deliveryLat').value = lat;
        document.getElementById('deliveryLng').value = lng;

        showAddressStatus('Mencari alamat detail...', 'loading');

        // Try multiple geocoding APIs for most detailed result
        Promise.all([
            // API 1: Nominatim with maximum detail (zoom=21)
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&zoom=21&addressdetails=1&extratags=1&namedetails=1&accept-language=id`)
                .then(res => res.ok ? res.json() : null)
                .catch(() => null),
            
            // API 2: BigDataCloud for additional details
            fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=id`)
                .then(res => res.ok ? res.json() : null)
                .catch(() => null)
        ])
        .then(([nominatimData, bigDataCloudData]) => {
            console.log('Nominatim response:', nominatimData);
            console.log('BigDataCloud response:', bigDataCloudData);
            
            // Build comprehensive address from all sources
            const fullAddress = buildComprehensiveAddress(nominatimData, bigDataCloudData, lat, lng);
            setDeliveryAddress(fullAddress, lat, lng);
        })
        .catch(err => {
            console.error('Geocoding error:', err);
            fallbackToCoordinates(lat, lng);
        });
    }
    
    // Build detailed address from Nominatim address object (untuk hasil pencarian)
    // Format: Nama Perumahan, Blok X No.X, Jalan, RT.X/RW.X, Desa.X.Kec.X, Kota, Provinsi, KodePos
    function buildDetailedAddress(addr) {
        if (!addr) return null;
        
        let addressParts = [];
        
        // 1. Nama Tempat/Perumahan/Kompleks
        const placeName = addr.residential || addr.hamlet || addr.amenity || addr.building || 
                          addr.shop || addr.office || addr.neighbourhood;
        if (placeName) {
            addressParts.push(placeName);
        }
        
        // 2. Blok & Nomor Rumah
        let blokNo = [];
        if (addr.block) blokNo.push('Blok ' + addr.block);
        if (addr.house_number) blokNo.push('No.' + addr.house_number);
        if (blokNo.length > 0) {
            addressParts.push(blokNo.join(' '));
        }
        
        // 3. Jalan
        const road = addr.road || addr.street || addr.pedestrian || addr.footway || addr.path;
        if (road && road !== placeName) {
            addressParts.push(road);
        }
        
        // 4. RT/RW
        if (addr.quarter) {
            if (addr.quarter.includes('RT') || addr.quarter.includes('RW')) {
                addressParts.push(addr.quarter);
            } else {
                addressParts.push('RT.' + addr.quarter);
            }
        }
        
        // 5. Desa/Kelurahan + Kecamatan (format: Desa.X.Kec.X)
        let desaKec = [];
        const desa = addr.village || addr.suburb || addr.town_block;
        const kecamatan = addr.subdistrict || addr.city_district || addr.district || addr.borough;
        
        if (desa) {
            desaKec.push('Desa.' + desa.replace(/\s+/g, '.'));
        }
        if (kecamatan) {
            desaKec.push('Kec.' + kecamatan.replace(/\s+/g, '.'));
        }
        if (desaKec.length > 0) {
            addressParts.push(desaKec.join('.'));
        }
        
        // 6. Kota/Kabupaten
        const kota = addr.city || addr.town || addr.county || addr.municipality || addr.state_district;
        if (kota) {
            addressParts.push(kota);
        }
        
        // 7. Provinsi (singkat)
        const provinsi = addr.state || addr.province || addr.region;
        if (provinsi) {
            let provShort = provinsi
                .replace('Jawa Barat', 'Jabar')
                .replace('Jawa Tengah', 'Jateng')
                .replace('Jawa Timur', 'Jatim')
                .replace('DKI Jakarta', 'Jakarta')
                .replace('Daerah Istimewa Yogyakarta', 'DIY');
            addressParts.push(provShort);
        }
        
        // 8. Kode Pos
        if (addr.postcode) {
            addressParts.push(addr.postcode);
        }
        
        // 9. Indonesia
        addressParts.push('Indonesia');
        
        const result = addressParts.filter(p => p && p.trim()).join(', ');
        console.log('Built detailed address:', result);
        return result.length > 10 ? result : null;
    }
    
    // Build comprehensive address from multiple API sources
    // Format: Nama Perumahan, Blok X No.X, RT.X/RW.X, Desa.X.Kec.X, Kota
    function buildComprehensiveAddress(nominatimData, bigDataCloudData, lat, lng) {
        let addressParts = [];
        
        // === FROM NOMINATIM ===
        if (nominatimData && nominatimData.address) {
            const addr = nominatimData.address;
            
            // 1. Nama Tempat/Perumahan/Kompleks
            const placeName = addr.residential || addr.hamlet || addr.amenity || addr.building || 
                              addr.shop || addr.office || nominatimData.name || addr.neighbourhood;
            if (placeName) {
                addressParts.push(placeName);
            }
            
            // 2. Blok & Nomor Rumah
            let blokNo = [];
            if (addr.block) blokNo.push('Blok ' + addr.block);
            if (addr.house_number) blokNo.push('No.' + addr.house_number);
            if (blokNo.length > 0) {
                addressParts.push(blokNo.join(' '));
            }
            
            // 3. Jalan (jika ada dan berbeda dari nama tempat)
            const road = addr.road || addr.street || addr.pedestrian || addr.footway || addr.path;
            if (road && road !== placeName) {
                addressParts.push(road);
            }
            
            // 4. RT/RW
            let rtRw = [];
            if (addr.quarter) {
                // Cek apakah sudah format RT/RW
                if (addr.quarter.includes('RT') || addr.quarter.includes('RW')) {
                    rtRw.push(addr.quarter);
                } else {
                    rtRw.push('RT.' + addr.quarter);
                }
            }
            if (rtRw.length > 0) {
                addressParts.push(rtRw.join('/'));
            }
            
            // 5. Desa/Kelurahan + Kecamatan (format: Desa.X.Kec.X)
            let desaKec = [];
            const desa = addr.village || addr.suburb || addr.town_block;
            const kecamatan = addr.subdistrict || addr.city_district || addr.district || addr.borough;
            
            if (desa) {
                desaKec.push('Desa.' + desa.replace(/\s+/g, '.'));
            }
            if (kecamatan) {
                desaKec.push('Kec.' + kecamatan.replace(/\s+/g, '.'));
            }
            if (desaKec.length > 0) {
                addressParts.push(desaKec.join('.'));
            }
            
            // 6. Kota/Kabupaten
            const kota = addr.city || addr.town || addr.county || addr.municipality || addr.state_district;
            if (kota) {
                addressParts.push(kota);
            }
            
            // 7. Provinsi (singkat)
            const provinsi = addr.state || addr.province || addr.region;
            if (provinsi) {
                // Singkatan provinsi
                let provShort = provinsi
                    .replace('Jawa Barat', 'Jabar')
                    .replace('Jawa Tengah', 'Jateng')
                    .replace('Jawa Timur', 'Jatim')
                    .replace('DKI Jakarta', 'Jakarta')
                    .replace('Daerah Istimewa Yogyakarta', 'DIY');
                addressParts.push(provShort);
            }
            
            // 8. Kode Pos
            if (addr.postcode) {
                addressParts.push(addr.postcode);
            }
        }
        
        // === ENRICH FROM BIGDATACLOUD ===
        if (bigDataCloudData) {
            // Add postal code if missing
            if (bigDataCloudData.postcode && !addressParts.some(p => /^\d{5}$/.test(p))) {
                addressParts.push(bigDataCloudData.postcode);
            }
            
            // Add locality info if we don't have much detail
            if (addressParts.length < 3) {
                if (bigDataCloudData.locality) addressParts.push(bigDataCloudData.locality);
                if (bigDataCloudData.city) addressParts.push(bigDataCloudData.city);
                if (bigDataCloudData.principalSubdivision) addressParts.push(bigDataCloudData.principalSubdivision);
            }
        }
        
        // Add country
        addressParts.push('Indonesia');
        
        // If still too short, add coordinates
        if (addressParts.length < 3) {
            addressParts.unshift(`Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`);
        }
        
        // Join with proper separator
        const result = addressParts.filter(p => p && p.trim()).join(', ');
        console.log('Final comprehensive address:', result);
        return result;
    }
    
    // Fallback to coordinates display
    function fallbackToCoordinates(lat, lng) {
        const coordText = `Lokasi: ${lat.toFixed(6)}, ${lng.toFixed(6)} (Silakan cari alamat manual)`;
        setDeliveryAddress(coordText, lat, lng);
    }
    
    // Try alternative geocoding service (BigDataCloud - free, no key needed)
    function tryAlternativeGeocode(lat, lng) {
        console.log('Trying alternative geocoding...');
        
        fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=id`)
            .then(res => res.json())
            .then(data => {
                console.log('BigDataCloud response:', data);
                
                if (data) {
                    let parts = [];
                    if (data.locality) parts.push(data.locality);
                    if (data.city) parts.push(data.city);
                    if (data.principalSubdivision) parts.push(data.principalSubdivision);
                    if (data.postcode) parts.push('Kode Pos: ' + data.postcode);
                    if (data.countryName) parts.push(data.countryName);
                    
                    if (parts.length > 0) {
                        setDeliveryAddress(parts.join(', '), lat, lng);
                    } else {
                        fallbackToCoordinates(lat, lng);
                    }
                } else {
                    fallbackToCoordinates(lat, lng);
                }
            })
            .catch(err => {
                console.error('Alternative geocoding also failed:', err);
                fallbackToCoordinates(lat, lng);
            });
    }

    // Set delivery address in UI
    function setDeliveryAddress(address, lat, lng) {
        document.getElementById('addressText').textContent = address;
        document.getElementById('deliveryAddress').value = address;
        if (lat && lng) {
            document.getElementById('deliveryLat').value = lat;
            document.getElementById('deliveryLng').value = lng;
        }
    }

    // Show address status message
    function showAddressStatus(message, type) {
        const addressText = document.getElementById('addressText');
        if (type === 'loading') {
            addressText.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${message}`;
        } else if (type === 'error') {
            addressText.innerHTML = `<i class="fas fa-exclamation-circle" style="color: #e74c3c;"></i> ${message}`;
        } else {
            addressText.textContent = message;
        }
    }

    // Search address function
    function searchAddress(query) {
        if (!query || query.length < 3) return;

        console.log('Searching:', query);
        showSearchLoading();

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=id&limit=5&addressdetails=1&accept-language=id`, {
            headers: {
                'Accept': 'application/json',
                'User-Agent': 'CoffeeShopApp/1.0'
            }
        })
            .then(res => res.json())
            .then(results => {
                console.log('Results:', results);
                showSearchResults(results);
            })
            .catch(err => {
                console.error('Search error:', err);
                hideSearchDropdown();
            });
    }

    // Show search loading
    function showSearchLoading() {
        hideSearchDropdown();
        const searchBox = document.querySelector('.address-search-box');
        if (!searchBox) return;

        const dropdown = document.createElement('div');
        dropdown.id = 'searchDropdown';
        dropdown.style.cssText = `
            position: absolute; top: 100%; left: 0; right: 0;
            background: white; border-radius: 12px; z-index: 9999;
            border: 2px solid rgba(212, 165, 116, 0.3);
            box-shadow: 0 8px 32px rgba(45, 27, 0, 0.2);
            padding: 16px; text-align: center; margin-top: 4px;
        `;
        dropdown.innerHTML = '<i class="fas fa-spinner fa-spin" style="color: #6F4E37;"></i> Mencari alamat...';
        searchBox.style.position = 'relative';
        searchBox.appendChild(dropdown);
    }

    // Show search results
    function showSearchResults(results) {
        hideSearchDropdown();
        
        const searchBox = document.querySelector('.address-search-box');
        if (!searchBox) return;

        if (!results || results.length === 0) {
            const dropdown = document.createElement('div');
            dropdown.id = 'searchDropdown';
            dropdown.style.cssText = `
                position: absolute; top: 100%; left: 0; right: 0;
                background: white; border-radius: 12px; z-index: 9999;
                border: 2px solid rgba(212, 165, 116, 0.3);
                box-shadow: 0 8px 32px rgba(45, 27, 0, 0.2);
                padding: 16px; text-align: center; margin-top: 4px;
                color: #999;
            `;
            dropdown.innerHTML = '<i class="fas fa-search"></i> Tidak ditemukan hasil';
            searchBox.appendChild(dropdown);
            return;
        }

        const dropdown = document.createElement('div');
        dropdown.id = 'searchDropdown';
        dropdown.style.cssText = `
            position: absolute; top: 100%; left: 0; right: 0;
            background: white; border-radius: 12px; z-index: 9999;
            border: 2px solid rgba(212, 165, 116, 0.3);
            box-shadow: 0 8px 32px rgba(45, 27, 0, 0.2);
            max-height: 280px; overflow-y: auto; margin-top: 4px;
        `;

        results.forEach((result, index) => {
            const item = document.createElement('div');
            item.style.cssText = `
                padding: 14px 16px; cursor: pointer;
                border-bottom: 1px solid rgba(212, 165, 116, 0.1);
                font-size: 13px; line-height: 1.5;
                transition: all 0.2s ease;
            `;
            item.innerHTML = `<i class="fas fa-map-marker-alt" style="color: #6F4E37; margin-right: 10px;"></i>${result.display_name}`;
            
            item.onmouseenter = () => item.style.background = 'rgba(255, 248, 240, 0.95)';
            item.onmouseleave = () => item.style.background = 'white';
            item.onmousedown = (e) => {
                e.preventDefault();
                selectAddress(result);
            };

            dropdown.appendChild(item);
        });

        searchBox.style.position = 'relative';
        searchBox.appendChild(dropdown);
    }

    // Hide search dropdown
    function hideSearchDropdown() {
        const dropdown = document.getElementById('searchDropdown');
        if (dropdown) dropdown.remove();
    }

    // Select address from search results
    function selectAddress(result) {
        const lat = parseFloat(result.lat);
        const lng = parseFloat(result.lon);

        // Clear search input
        document.getElementById('addressSearch').value = '';
        hideSearchDropdown();

        // Build detailed address if address components available
        let detailedAddress = result.display_name;
        if (result.address) {
            const built = buildDetailedAddress(result.address);
            if (built) detailedAddress = built;
        }

        // Set address
        setDeliveryAddress(detailedAddress, lat, lng);

        // Move map if initialized
        if (map && marker) {
            map.setView([lat, lng], 17);
            marker.setLatLng([lat, lng]);
        }
    }

    // Focus address search
    function focusAddressSearch() {
        const input = document.getElementById('addressSearch');
        if (input) input.focus();
    }

    // Toggle order type (Dine In / Delivery)
    function toggleOrderType(type) {
        currentOrderType = type;
        const addressBox = document.getElementById('deliveryAddressBox');
        const overlay = document.getElementById('mapOverlay');

        if (type === 'delivery') {
            addressBox.classList.add('active');
            
            // Initialize map when delivery is selected
            setTimeout(async () => {
                await loadLeaflet();
                await initializeMap();
                
                // If user has saved address, hide overlay (auto-synced in initializeMap)
                // If no saved address, show overlay to prompt user
                if (userSavedAddress && userSavedAddress.trim() !== '') {
                    if (overlay) overlay.style.display = 'none';
                } else {
                    if (overlay) overlay.style.display = 'flex';
                }
            }, 150);
        } else {
            addressBox.classList.remove('active');
        }

        updateSummary();
    }

    // Initialize address search input events
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('addressSearch');
        
        if (searchInput) {
            // Auto-search on input
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                if (query.length < 3) {
                    hideSearchDropdown();
                    return;
                }

                searchTimeout = setTimeout(() => searchAddress(query), 600);
            });

            // Handle Enter key
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();
                    if (query.length >= 3) {
                        searchAddress(query);
                    }
                    return false;
                }
            });

            // Hide dropdown on blur
            searchInput.addEventListener('blur', function() {
                setTimeout(hideSearchDropdown, 250);
            });
        }

        // Map overlay click handler
        const overlay = document.getElementById('mapOverlay');
        if (overlay) {
            overlay.addEventListener('click', function() {
                this.style.display = 'none';
                getUserCurrentLocation();
            });
        }

        console.log('Checkout page initialized');
    });
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
