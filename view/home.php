<?php
$title = 'Beranda';

// Redirect admin ke dashboard admin
if (isLoggedIn() && getCurrentUser()['role'] === 'admin') {
    redirect(BASE_URL . 'index.php?page=admin/dashboard');
}

include __DIR__ . '/layout_header.php';
?>

<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    @keyframes glow {
        0%, 100% { box-shadow: 0 0 20px rgba(212, 165, 116, 0.5); }
        50% { box-shadow: 0 0 40px rgba(212, 165, 116, 0.8); }
    }

    @keyframes shimmerSweep {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    
    /* Hero */
    .hero {
        background: linear-gradient(135deg, rgba(45, 27, 0, 0.85) 0%, rgba(60, 40, 20, 0.8) 100%),
                    url('<?php echo BASE_URL . 'assets/img/hero-coffee.jpg'; ?>') center/cover no-repeat;
        color: white;
        padding: 80px 40px;
        position: relative;
        overflow: hidden;
        min-height: 700px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('<?php echo BASE_URL . 'assets/img/hero-coffee.jpg'; ?>') center/cover no-repeat;
        opacity: 0.15;
        z-index: 0;
        filter: blur(8px);
    }
    
    .hero-wrapper {
        position: relative;
        z-index: 1;
        max-width: 1400px;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
    }
    
    .hero-content {
        animation: fadeInLeft 0.9s ease-out;
        max-width: 500px;
    }

    @keyframes fadeInLeft {
        from { opacity: 0; transform: translateX(-50px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(50px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .hero-badge {
        display: inline-block;
        background: rgba(212, 165, 116, 0.25);
        border: 1.5px solid #D4A574;
        color: #D4A574;
        padding: 10px 22px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 25px;
        animation: fadeInDown 0.8s ease-out;
    }
    
    .hero h1 {
        font-size: 64px;
        font-weight: 900;
        margin-bottom: 25px;
        line-height: 1.15;
        letter-spacing: -1.5px;
        animation: fadeInUp 0.9s ease-out 0.1s both;
    }
    
    .hero p {
        font-size: 18px;
        opacity: 0.85;
        margin-bottom: 45px;
        animation: fadeInUp 0.9s ease-out 0.2s both;
        font-weight: 300;
        line-height: 1.8;
    }
    
    .hero-buttons {
        display: flex;
        gap: 18px;
        animation: fadeInUp 0.9s ease-out 0.3s both;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .hero .btn {
        padding: 16px 44px;
        font-size: 15px;
        font-weight: 700;
        border: none;
        border-radius: 50px;
        transition: all 0.3s ease;
        text-decoration: none;
        cursor: pointer;
        letter-spacing: 0.8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.25);
    }
    
    .btn-primary {
        background: #D4A574;
        color: #2D1B00;
    }
    
    .btn-primary:hover {
        background: #E8C4A0;
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(212, 165, 116, 0.45);
    }
    
    .btn-secondary {
        background: rgba(255, 255, 255, 0.12);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(3px);
    }
    
    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.7);
        transform: translateY(-4px);
    }

    .hero-image {
        position: relative;
        animation: fadeInRight 0.9s ease-out 0.2s both;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 600px;
    }

    .hero-image-content {
        width: 100%;
        height: 100%;
        max-width: 600px;
        max-height: 600px;
        background: linear-gradient(180deg, rgba(212, 165, 116, 0.15) 0%, rgba(212, 165, 116, 0.05) 100%);
        border-radius: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2.5px solid rgba(212, 165, 116, 0.4);
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(3px);
    }

    .hero-image-content::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -40%;
        width: 700px;
        height: 700px;
        background: radial-gradient(circle, rgba(212, 165, 116, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
    }

    .hero-image-inner {
        position: relative;
        z-index: 1;
        text-align: center;
        max-width: 100%;
        height: auto;
    }

    .hero-image-inner img {
        max-width: 100%;
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        filter: drop-shadow(0 15px 35px rgba(212, 165, 116, 0.25));
    }
    
    /* Stats */
    .stats {
        background: white;
        padding: 100px 30px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .stat {
        text-align: center;
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
    }
    
    .stat:nth-child(1) { animation-delay: 0.2s; }
    .stat:nth-child(2) { animation-delay: 0.4s; }
    .stat:nth-child(3) { animation-delay: 0.6s; }
    .stat:nth-child(4) { animation-delay: 0.8s; }
    
    .stat-number {
        font-size: 52px;
        font-weight: 900;
        color: #D4A574;
        margin-bottom: 10px;
    }
    
    .stat-text {
        font-size: 16px;
        color: #6F4E37;
        font-weight: 600;
    }
    
    /* Features */
    .features {
        background: linear-gradient(180deg, #FAF7F4 0%, #FFFFFF 100%);
        padding: 120px 30px;
    }

    .features-header {
        text-align: center;
        max-width: 720px;
        margin: 0 auto 60px;
    }

    .features-kicker {
        font-size: 14px;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: #6F4E37;
        font-weight: 700;
    }

    .features-title {
        font-size: 38px;
        font-weight: 900;
        color: #2D1B00;
        margin-top: 12px;
        letter-spacing: -1px;
    }

    .features-subtitle {
        font-size: 16px;
        color: #6F4E37;
        margin-top: 12px;
        line-height: 1.7;
    }

    .features-divider {
        width: 90px;
        height: 4px;
        margin: 18px auto 0;
        border-radius: 999px;
        background: linear-gradient(90deg, #6F4E37 0%, #D4A574 100%);
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 50px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .feature-card {
        padding: 60px 40px;
        background: linear-gradient(180deg, #FFFFFF 0%, #FBF6F1 100%);
        border-radius: 20px;
        text-align: center;
        transition: all 0.4s ease;
        box-shadow: 0 12px 28px rgba(0,0,0,0.08);
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 22px;
        background: linear-gradient(135deg, rgba(212, 165, 116, 0.6), rgba(111, 78, 55, 0.6));
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 0;
    }

    .feature-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, rgba(255,255,255,0) 0%, rgba(212,165,116,0.18) 45%, rgba(255,255,255,0) 100%);
        background-size: 200% 100%;
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
    }
    
    .feature-card:nth-child(1) { animation-delay: 0.2s; }
    .feature-card:nth-child(2) { animation-delay: 0.4s; }
    .feature-card:nth-child(3) { animation-delay: 0.6s; }
    
    .feature-card:hover {
        border-color: #D4A574;
        box-shadow: 0 18px 45px rgba(212, 165, 116, 0.18);
        background: linear-gradient(180deg, #FFFFFF 0%, #FFF5EB 100%);
        transform: translateY(-6px);
    }

    .feature-card:hover::after {
        opacity: 1;
        animation: shimmerSweep 0.9s ease-out;
    }

    .feature-card:hover::before {
        opacity: 1;
    }
    
    .feature-icon {
        width: 96px;
        height: 96px;
        margin: 0 auto 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, #FFF7EF 0%, #F3E6D8 100%);
        animation: float 4s ease-in-out infinite;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .feature-card:hover .feature-icon {
        box-shadow: 0 12px 28px rgba(212, 165, 116, 0.25);
        transform: none;
    }

    .feature-icon svg {
        width: 56px;
        height: 56px;
        stroke: #6F4E37;
        fill: none;
        stroke-width: 2.2;
        stroke-linecap: round;
        stroke-linejoin: round;
        transition: stroke 0.3s ease, transform 0.3s ease;
    }

    .feature-card:hover .feature-icon svg {
        stroke: #D4A574;
        transform: scale(1.06);
    }
    
    .feature-card:nth-child(1) .feature-icon { animation-delay: 0s; }
    .feature-card:nth-child(2) .feature-icon { animation-delay: 0.5s; }
    .feature-card:nth-child(3) .feature-icon { animation-delay: 1s; }
    
    .feature-card h3 {
        font-size: 26px;
        color: #2D1B00;
        margin-bottom: 15px;
        font-weight: 800;
        position: relative;
        z-index: 1;
    }
    
    .feature-card p {
        color: #666;
        font-size: 16px;
        line-height: 1.8;
        position: relative;
        z-index: 1;
    }
    
    /* CTA */
    .cta {
        background: linear-gradient(rgba(0, 0, 0, 0.55), rgba(45, 27, 0, 0.55)),
                    url('<?php echo BASE_URL . 'assets/img/cta-coffee.jpg'; ?>') center/cover no-repeat;
        color: white;
        padding: 120px 30px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .cta::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(212, 165, 116, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 10s ease-in-out infinite;
    }
    
    .cta-content {
        position: relative;
        z-index: 2;
        max-width: 700px;
        margin: 0 auto;
        animation: fadeInUp 0.9s ease-out;
    }
    
    .cta h2 {
        font-size: 60px;
        font-weight: 900;
        margin-bottom: 20px;
        letter-spacing: -2px;
    }
    
    .cta p {
        font-size: 20px;
        opacity: 0.95;
        margin-bottom: 50px;
        line-height: 1.7;
        font-weight: 300;
    }
    
    .promo-badge {
        display: inline-block;
        background: #D4A574;
        color: #2D1B00;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 30px;
        animation: glow 2s ease-in-out infinite;
    }

    /* Products Menu */
    .products-section {
        padding: 80px 30px;
        background: white;
    }

    .products-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .products-title {
        font-size: 38px;
        font-weight: 900;
        color: #2D1B00;
        margin-bottom: 40px;
        text-align: center;
        animation: fadeInUp 0.6s ease-out;
        letter-spacing: -1px;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
        animation: fadeInUp 0.8s ease-out;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        border: 1px solid #f0f0f0;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(212, 165, 116, 0.15);
        border-color: #D4A574;
    }

    .product-image {
        width: 100%;
        height: 180px;
        background-color: #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 13px;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-info {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .product-category {
        font-size: 11px;
        color: #D4A574;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .product-name {
        font-size: 16px;
        font-weight: 700;
        color: #2D1B00;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .product-description {
        font-size: 13px;
        color: #666;
        margin-bottom: 12px;
        flex-grow: 1;
        line-height: 1.5;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid #f0f0f0;
    }

    .product-price {
        font-size: 18px;
        font-weight: 800;
        color: #D4A574;
    }

    .product-btn {
        padding: 8px 16px;
        background-color: #D4A574;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 11px;
        font-weight: 700;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        letter-spacing: 0.5px;
    }

    .product-btn:hover {
        background-color: #c49564;
        transform: scale(1.08);
    }

    .empty-message {
        text-align: center;
        padding: 60px 20px;
        color: #999;
        font-size: 16px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            padding: 50px 25px;
            min-height: auto;
        }

        .hero-wrapper {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        
        .hero-content {
            max-width: 100%;
        }

        .hero h1 {
            font-size: 42px;
            margin-bottom: 18px;
        }
        
        .hero p {
            font-size: 16px;
            margin-bottom: 35px;
            line-height: 1.7;
        }

        .hero-badge {
            font-size: 10px;
            padding: 8px 16px;
            margin-bottom: 20px;
        }

        .hero-image {
            min-height: 350px;
        }

        .hero-image-content {
            border-radius: 18px;
            padding: 25px;
        }

        .hero-image-inner {
            font-size: 120px;
        }

        .hero-buttons {
            gap: 12px;
        }

        .hero .btn {
            padding: 14px 32px;
            font-size: 14px;
        }
        
        .stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            padding: 60px 25px;
        }
        
        .stat-number {
            font-size: 40px;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        .feature-card {
            padding: 40px 30px;
        }
        
        .feature-icon {
            font-size: 56px;
        }
        
        .cta {
            padding: 80px 25px;
        }
        
        .cta h2 {
            font-size: 40px;
        }
        
        .cta p {
            font-size: 18px;
        }

        .products-section {
            padding: 60px 25px;
        }

        .products-title {
            font-size: 28px;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
        }
    }

    @media (max-width: 480px) {
        .hero {
            padding: 40px 20px;
            min-height: auto;
        }

        .hero h1 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 15px;
            margin-bottom: 30px;
        }

        .hero-badge {
            font-size: 9px;
            padding: 7px 14px;
        }

        .hero-image {
            min-height: 300px;
        }

        .hero-image-inner {
            font-size: 100px;
        }

        .hero .btn {
            padding: 13px 28px;
            font-size: 13px;
            width: 100%;
            text-align: center;
        }

        .hero-buttons {
            flex-direction: column;
            width: 100%;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }

        .cta h2 {
            font-size: 32px;
        }
    }
</style>

<?php if (!isLoggedIn()) { ?>
    <div class="hero">
        <div class="hero-wrapper">
            <div class="hero-content">
                <div class="hero-badge">Premium Coffee Collection</div>
                <h1>Kopi Premium Pilihan Anda</h1>
                <p>Koleksi kopi premium terbaik dengan konsultasi gratis – dari kopi single origin hingga blend eksklusif. Kualitas terjamin untuk setiap cangkir.</p>
                <div class="hero-buttons">
                    <a href="<?php echo BASE_URL . 'index.php?page=login'; ?>" class="btn btn-primary">Mulai Berbelanja</a>
                    <a href="<?php echo BASE_URL . 'index.php?page=register'; ?>" class="btn btn-secondary">Daftar Gratis</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-image-content">
                    <div class="hero-image-inner">
                        <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600&h=600&q=80" alt="Premium Coffee Cup">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="hero">
        <div class="hero-wrapper">
            <div class="hero-content">
                <div class="hero-badge">Premium Coffee Collection</div>
                <h1>Selamat Datang Kembali! ☕</h1>
                <p>Jelajahi koleksi kopi premium kami dan temukan cita rasa favorit Anda. Nikmati promo spesial untuk member setia.</p>
                <div class="hero-buttons">
                    <a href="<?php echo BASE_URL . 'index.php?page=user/menu'; ?>" class="btn btn-primary">Jelajahi Menu</a>
                    <a href="<?php echo BASE_URL . 'index.php?page=user/orders'; ?>" class="btn btn-secondary">Pesanan Saya</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-image-content">
                    <div class="hero-image-inner">☕</div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

    <div class="stats">
        <div class="stat">
            <div class="stat-number">5K+</div>
            <div class="stat-text">Pelanggan</div>
        </div>
        <div class="stat">
            <div class="stat-number">4.9★</div>
            <div class="stat-text">Rating</div>
        </div>  
        <div class="stat">
            <div class="stat-number">50+</div>
            <div class="stat-text">Pilihan Kopi</div>
        </div>
        <div class="stat">
            <div class="stat-number">24/7</div>
            <div class="stat-text">Support</div>
        </div>
    </div>
    
    <div class="features">
        <div class="features-header">
            <div class="features-kicker">Kenapa Memilih Kami</div>
            <div class="features-title">Kualitas Kopi yang Konsisten</div>
            <div class="features-subtitle">Dari biji terbaik hingga ke tangan Anda, kami jaga rasa dan pengalaman di setiap cangkir.</div>
            <div class="features-divider"></div>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 8h11a0 0 0 0 1 0 0v5a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V8z"></path>
                        <path d="M14 9h2.5a2.5 2.5 0 1 1 0 5H14"></path>
                        <path d="M6 5c0 1 1 1 1 2s-1 1-1 2"></path>
                        <path d="M9 5c0 1 1 1 1 2s-1 1-1 2"></path>
                    </svg>
                </div>
                <h3>Kopi Premium</h3>
                <p>Single origin & blend terbaik, roasted segar setiap hari agar aroma tetap maksimal</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 7h18l-2 10H5L3 7z"></path>
                        <path d="M7 7l2-3h6l2 3"></path>
                        <path d="M8 17v2"></path>
                        <path d="M16 17v2"></path>
                    </svg>
                </div>
                <h3>Pengiriman Cepat</h3>
                <p>Pengemasan rapi, tracking jelas, dan kopi tiba dalam kondisi tetap fresh</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3z"></path>
                        <path d="M9 12l2 2 4-4"></path>
                    </svg>
                </div>
                <h3>Terpercaya</h3>
                <p>Rating tinggi, layanan responsif, dan kualitas konsisten di setiap pembelian</p>
            </div>
        </div>
    </div>
    
    <div class="cta">
        <div class="cta-content">
            <h2>Diskon 20% Pembelian Pertama</h2>
            <p>Jangan lewatkan kesempatan emas untuk merasakan kopi premium kami dengan harga istimewa</p>
            <a href="<?php echo BASE_URL . 'index.php?page=register'; ?>" class="btn btn-primary">Mulai Sekarang</a>
        </div>
    </div>

    <!-- Menu Produk -->
    <div class="products-section">
        <div class="products-container">
            <h2 class="products-title">Menu Kopi Kami</h2>
            <div class="products-grid">
                <?php
                require_once __DIR__ . '/../models/Product.php';
                $product_model = new Product($conn);
                $products = $product_model->getAllProducts();
                
                if ($products && $products->num_rows > 0) {
                    while ($product = $products->fetch_assoc()) {
                        $foto_url = !empty($product['gambar']) 
                            ? BASE_URL . 'uploads/products/' . htmlspecialchars($product['gambar'])
                            : BASE_URL . 'assets/img/placeholder.jpg';
                        
                        $harga = isset($product['harga']) ? $product['harga'] : 0;
                        $nama = htmlspecialchars($product['nama_produk']);
                        $kategori = htmlspecialchars($product['nama_kategori'] ?? 'Kategori');
                        $deskripsi = htmlspecialchars(substr($product['deskripsi'] ?? '', 0, 50)) . (strlen($product['deskripsi'] ?? '') > 50 ? '...' : '');
                        ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo $foto_url; ?>" alt="<?php echo $nama; ?>">
                            </div>
                            <div class="product-info">
                                <div class="product-category"><?php echo $kategori; ?></div>
                                <h3 class="product-name"><?php echo $nama; ?></h3>
                                <p class="product-description"><?php echo $deskripsi; ?></p>
                            </div>
                            <div class="product-footer">
                                <span class="product-price">Rp<?php echo number_format($harga, 0, ',', '.'); ?></span>
                                <?php if (isLoggedIn()) { ?>
                                    <a href="<?php echo BASE_URL . 'index.php?page=user/menu'; ?>" class="product-btn">Lihat Detail</a>
                                <?php } else { ?>
                                    <a href="<?php echo BASE_URL . 'index.php?page=login'; ?>" class="product-btn">Masuk</a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="empty-message">Produk tidak tersedia</div>';
                }
                ?>
            </div>
        </div>
    </div>

<?php include __DIR__ . '/layout_footer.php'; ?>
