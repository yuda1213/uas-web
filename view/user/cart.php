<?php
$title = 'Keranjang Belanja';
include __DIR__ . '/../layout_header.php';

$product_model = new Product($conn);
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

    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }

    @keyframes textGlow {
        0%, 100% { text-shadow: 0 0 10px rgba(212, 165, 116, 0.4); }
        50% { text-shadow: 0 0 20px rgba(212, 165, 116, 0.8); }
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

    .cart-container {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 24px;
        margin: 24px 0;
    }
    
    .cart-items {
        background: rgba(255, 255, 255, 0.96);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.08),
                    0 0 1px rgba(45, 27, 0, 0.08),
                    inset 0 1px 0 rgba(255,255,255,0.8);
        border: 1.5px solid rgba(212, 165, 116, 0.2);
        animation: slideInLeft 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        backdrop-filter: blur(12px);
    }
    
    .cart-item {
        padding: 20px 18px;
        border-bottom: 1px solid rgba(239, 230, 221, 0.5);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.25s ease;
        gap: 14px;
    }

    .cart-item:hover {
        background: rgba(255, 248, 241, 0.8);
    }
    
    .item-info {
        flex: 1;
    }
    
    .item-info h4 {
        margin: 0 0 5px 0;
        color: var(--coffee-dark);
        font-weight: 800;
        font-size: 15px;
        letter-spacing: -0.3px;
        line-height: 1.3;
    }
    
    .item-info p {
        margin: 0;
        color: #999;
        font-size: 12px;
        letter-spacing: 0.2px;
    }
    
    .item-qty {
        display: flex;
        gap: 8px;
        align-items: center;
        margin: 0 12px;
    }
    
    .item-qty button {
        width: 32px;
        height: 32px;
        border: 1.5px solid rgba(212, 165, 116, 0.3);
        background: linear-gradient(135deg, rgba(255,248,241,0.8) 0%, rgba(255,253,251,0.8) 100%);
        cursor: pointer;
        border-radius: 10px;
        color: var(--coffee);
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 12px rgba(45, 27, 0, 0.04);
    }

    .item-qty button:hover {
        border-color: rgba(212, 165, 116, 0.6);
        background: linear-gradient(135deg, rgba(255,243,232,0.96) 0%, rgba(212, 165, 116, 0.1) 100%);
        transform: scale(1.12);
        box-shadow: 0 8px 20px rgba(45, 27, 0, 0.12);
    }
    
    .item-price {
        text-align: right;
        min-width: 110px;
    }
    
    .item-price strong {
        display: block;
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #D4A574 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 900;
        font-size: 18px;
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
        margin: 0 0 18px 0;
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
        transition: all 0.2s ease;
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
    
    .empty-cart {
        text-align: center;
        padding: 60px 20px;
        animation: fadeUp 0.6s ease-out;
    }
    
    .empty-cart i {
        font-size: 64px;
        color: #E3D5C8;
        margin-bottom: 20px;
        display: block;
    }

    .btn {
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 14px 20px;
        font-weight: 700;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: pointer;
        font-size: 14px;
        width: 100%;
        box-shadow: 0 12px 32px rgba(45, 27, 0, 0.15);
        letter-spacing: 0.3px;
    }

    .btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.25);
    }

    .btn-secondary {
        background: #FFF;
        color: var(--coffee);
        border: 1.5px solid #E3D5C8;
    }

    .btn-secondary:hover {
        background: #FFF6ED;
        border-color: #D4A574;
    }

    .btn-remove {
        margin-top: 8px;
        padding: 7px 12px;
        background: #FDECEC;
        color: #B91C1C;
        border: 1px solid #F5C2C2;
        border-radius: 9px;
        cursor: pointer;
        font-size: 12px;
        width: 100%;
        transition: all 0.2s ease;
    }

    .btn-remove:hover {
        background: #F5B3B3;
        border-color: #C81E1E;
    }
    
    @media (max-width: 768px) {
        .cart-container {
            grid-template-columns: 1fr;
        }
        
        .summary-card {
            position: static;
        }
    }
</style>

<div class="container">
    <div class="page-hero">
        <h2>Keranjang Belanja</h2>
        <p>Kelola produk yang ingin kamu pesan.</p>
    </div>
    
    <div id="cartContent">
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h3>Keranjang Kosong</h3>
            <p>Belum ada produk di keranjang Anda</p>
            <a href="<?php echo BASE_URL . 'index.php?page=user/menu'; ?>" class="btn">Mulai Berbelanja</a>
        </div>
    </div>
</div>

<script>
    // Cart management
    class CartManager {
        constructor() {
            this.cart = JSON.parse(localStorage.getItem('cart')) || [];
            this.init();
        }
        
        init() {
            this.render();
        }
        
        addItem(id, name, price) {
            const existing = this.cart.find(item => item.id === id);
            
            if (existing) {
                existing.quantity += 1;
            } else {
                this.cart.push({ id, name, price, quantity: 1 });
            }
            
            this.save();
            this.render();
            if (typeof showToast === 'function') {
                showToast('Produk ditambahkan ke keranjang!', 'success');
            } else {
                alert('Produk ditambahkan ke keranjang!');
            }
        }
        
        removeItem(id) {
            this.cart = this.cart.filter(item => item.id !== id);
            this.save();
            this.render();
        }
        
        updateQuantity(id, qty) {
            const item = this.cart.find(item => item.id === id);
            if (item) {
                item.quantity = Math.max(1, qty);
                this.save();
                this.render();
            }
        }
        
        getTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        }
        
        save() {
            localStorage.setItem('cart', JSON.stringify(this.cart));
        }
        
        render() {
            const container = document.getElementById('cartContent');
            
            if (this.cart.length === 0) {
                container.innerHTML = `
                    <div class="empty-cart">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>Keranjang Kosong</h3>
                        <p>Belum ada produk di keranjang Anda</p>
                        <a href="<?php echo BASE_URL; ?>index.php?page=user/menu" class="btn">Mulai Berbelanja</a>
                    </div>
                `;
                return;
            }
            
            let html = '<div class="cart-container"><div class="cart-items">';
            
            this.cart.forEach((item, index) => {
                html += `
                    <div class="cart-item">
                        <div class="item-info">
                            <h4>${escapeHtml(item.name)}</h4>
                            <p>${formatCurrency(item.price)} per item</p>
                        </div>
                        <div class="item-qty">
                            <button class="qty-btn" onclick="cartManager.updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                            <span>${item.quantity}</span>
                            <button class="qty-btn" onclick="cartManager.updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                        </div>
                        <div class="item-price">
                            <strong>${formatCurrency(item.price * item.quantity)}</strong>
                            <button class="btn-remove" onclick="cartManager.removeItem(${item.id})">Hapus</button>
                        </div>
                    </div>
                `;
            });
            
            const total = this.getTotal();
            html += '</div><div class="summary-card">';
            html += '<h3>Ringkasan</h3>';
            html += `<div class="summary-row"><span>Subtotal:</span><span>${formatCurrency(total)}</span></div>`;
            html += `<div class="summary-row"><span>Pajak (10%):</span><span>${formatCurrency(total * 0.1)}</span></div>`;
            html += `<div class="summary-row total"><span>Total:</span><span>${formatCurrency(total * 1.1)}</span></div>`;
            html += '<a href="<?php echo BASE_URL; ?>index.php?page=user/checkout" class="btn btn-block" style="margin-top: 20px;">Lanjut ke Checkout</a>';
            html += '<a href="<?php echo BASE_URL; ?>index.php?page=user/menu" class="btn btn-secondary btn-block" style="margin-top: 10px;">Lanjut Belanja</a>';
            html += '</div></div>';
            
            container.innerHTML = html;
        }
        
        clear() {
            this.cart = [];
            this.save();
            this.render();
        }
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
    
    function addToCart(id, name, price) {
        cartManager.addItem(id, name, price);
    }
    
    const cartManager = new CartManager();
</script>

<?php include __DIR__ . '/../layout_footer.php'; ?>
