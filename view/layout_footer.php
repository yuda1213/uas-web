            </div>
        </div>

    <!-- Professional Footer -->
    <?php if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin'): ?>
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3>☕ Coffee Shop</h3>
                    <p>Menyajikan kopi berkualitas tinggi dari biji pilihan nusantara. Rasakan kenikmatan di setiap tegukan.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=user/dashboard'; ?>">Dashboard</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=user/menu'; ?>">Menu Kami</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=user/cart'; ?>">Keranjang</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=user/orders'; ?>">Pesanan Saya</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=user/profile'; ?>">Profile</a></li>
                    </ul>
                </div>

                <div class="footer-contact">
                    <h4>Hubungi Kami</h4>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> <span>Jl. Kopi Nikmat No. 123<br>Jakarta Selatan, 12345</span></li>
                        <li><i class="fas fa-phone"></i> <span>+62 812 3456 7890</span></li>
                        <li><i class="fas fa-envelope"></i> <span>hello@coffeeshop.com</span></li>
                    </ul>
                </div>

                <div class="footer-hours">
                    <h4>Jam Operasional</h4>
                    <ul>
                        <li><span>Senin - Jumat:</span> 08:00 - 22:00</li>
                        <li><span>Sabtu - Minggu:</span> 09:00 - 23:00</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Coffee Shop. All rights reserved.</p>
                <p class="made-with">Dibuat dengan <i class="fas fa-heart" style="color: #D4A574;"></i> dan Kopi</p>
            </div>
        </div>

        <style>
            .site-footer {
                background: linear-gradient(135deg, #2D1B00 0%, #1A1000 100%);
                color: #B0A090;
                padding: 60px 0 20px;
                font-family: 'Plus Jakarta Sans', sans-serif;
                margin-top: auto;
                position: relative;
                overflow: hidden;
            }

            .site-footer::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #D4A574, #6F4E37, #D4A574);
            }

            .site-footer .footer-content {
                display: grid;
                grid-template-columns: 1.5fr 1fr 1fr 1fr;
                gap: 40px;
                margin-bottom: 40px;
            }

            .site-footer h3 {
                color: #fff;
                font-size: 24px;
                font-weight: 800;
                margin-bottom: 16px;
                background: linear-gradient(135deg, #fff 0%, #D4A574 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                letter-spacing: -0.5px;
            }

            .site-footer h4 {
                color: #fff;
                font-size: 16px;
                font-weight: 700;
                margin-bottom: 20px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }

            .footer-brand p {
                line-height: 1.6;
                margin-bottom: 24px;
                font-size: 14px;
            }

            .social-links {
                display: flex;
                gap: 12px;
            }

            .social-links a {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.05);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                text-decoration: none;
                transition: all 0.3s ease;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .social-links a:hover {
                background: #D4A574;
                color: #2D1B00;
                transform: translateY(-4px);
            }

            .footer-links ul, .footer-contact ul, .footer-hours ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            .footer-links li {
                margin-bottom: 12px;
            }

            .footer-links a {
                color: #B0A090;
                text-decoration: none;
                transition: all 0.2s ease;
                font-size: 14px;
                display: inline-block;
            }

            .footer-links a:hover {
                color: #D4A574;
                transform: translateX(4px);
            }

            .footer-contact li {
                display: flex;
                gap: 12px;
                margin-bottom: 16px;
                font-size: 14px;
                align-items: flex-start;
            }

            .footer-contact i {
                color: #D4A574;
                margin-top: 4px;
            }

            .footer-hours li {
                margin-bottom: 12px;
                font-size: 14px;
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid rgba(255,255,255,0.05);
                padding-bottom: 8px;
            }

            .footer-hours li span {
                color: #fff;
                font-weight: 600;
            }

            .footer-bottom {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                padding-top: 24px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 13px;
            }

            .made-with {
                display: flex;
                align-items: center;
                gap: 6px;
            }
            
            @media (max-width: 900px) {
                .site-footer .footer-content {
                    grid-template-columns: 1fr 1fr;
                }
            }

            @media (max-width: 600px) {
                .site-footer .footer-content {
                    grid-template-columns: 1fr;
                    gap: 30px;
                }
                .footer-bottom {
                    flex-direction: column;
                    gap: 12px;
                    text-align: center;
                }
            }
        </style>
    </footer>
    <?php endif; ?>

    <script src="<?php echo ASSETS_URL; ?>js/main.js?v=<?php echo filemtime(__DIR__ . '/../assets/js/main.js'); ?>"></script>
</body>
</html>

