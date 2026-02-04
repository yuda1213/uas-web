<?php
/**
 * Professional Footer Template v2.0
 * Matches professional header design and styling
 */
?>

<?php if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin'): ?>
<footer class="professional-footer">
    <div class="footer-container">
        <!-- Footer Top Section -->
        <div class="footer-content">
            <!-- Company Info -->
            <div class="footer-section">
                <div class="footer-brand">
                    <i class="fas fa-coffee"></i>
                    <h4>Coffee Shop Premium</h4>
                </div>
                <p class="footer-description">
                    Nikmati pengalaman kopi berkualitas tinggi dengan layanan terbaik di kelasnya.
                </p>
                <div class="social-links">
                    <a href="#" title="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h5 class="footer-title">
                    <i class="fas fa-link"></i> Link Cepat
                </h5>
                <ul class="footer-links">
                    <li><a href="<?php echo BASE_URL; ?>">Beranda</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?page=user/menu">Menu</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?page=user/orders">Pesanan Saya</a></li>
                    <li><a href="<?php echo BASE_URL; ?>index.php?page=auth/profile">Profil</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section">
                <h5 class="footer-title">
                    <i class="fas fa-phone"></i> Hubungi Kami
                </h5>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Alamat</strong>
                            <p>Jl. Kopi No. 123, Jakarta</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Telepon</strong>
                            <p>+62 812-3456-7890</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email</strong>
                            <p>info@coffeeshop.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Hours -->
            <div class="footer-section">
                <h5 class="footer-title">
                    <i class="fas fa-clock"></i> Jam Operasional
                </h5>
                <div class="business-hours">
                    <div class="hour-item">
                        <span class="day">Senin - Jumat</span>
                        <span class="time">06:00 - 22:00</span>
                    </div>
                    <div class="hour-item">
                        <span class="day">Sabtu</span>
                        <span class="time">07:00 - 23:00</span>
                    </div>
                    <div class="hour-item">
                        <span class="day">Minggu</span>
                        <span class="time">07:00 - 22:00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Divider -->
        <div class="footer-divider"></div>

        <!-- Footer Bottom Section -->
        <div class="footer-bottom">
            <div class="footer-bottom-left">
                <p>&copy; 2026 Coffee Shop Premium. All rights reserved.</p>
                <div class="footer-links-bottom">
                    <a href="#">Kebijakan Privasi</a>
                    <span class="separator">•</span>
                    <a href="#">Syarat & Ketentuan</a>
                    <span class="separator">•</span>
                    <a href="#">Hubungi Kami</a>
                </div>
            </div>
            <div class="footer-bottom-right">
                <p>Made with <i class="fas fa-heart"></i> by Your Company</p>
            </div>
        </div>
    </div>
</footer>

<style>
    .professional-footer {
        background: linear-gradient(135deg, var(--primary) 0%, #1a252f 100%);
        color: var(--light);
        padding: 60px 0 0;
        margin-top: auto;
        border-top: 5px solid var(--accent);
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        padding: 40px 0;
    }

    .footer-section {
        animation: fadeIn 0.6s ease-in;
    }

    .footer-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .footer-brand i {
        font-size: 32px;
        color: var(--accent);
    }

    .footer-brand h4 {
        font-size: 20px;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .footer-description {
        font-size: 14px;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 20px;
    }

    .social-links {
        display: flex;
        gap: 15px;
        margin-top: 15px;
    }

    .social-links a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        transition: var(--transition);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .social-links a:hover {
        background: var(--accent);
        transform: translateY(-3px);
        border-color: var(--accent);
    }

    .footer-title {
        font-size: 16px;
        font-weight: 700;
        color: white;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-title i {
        color: var(--accent);
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: var(--transition);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-links a::before {
        content: '→';
        opacity: 0;
        transition: var(--transition);
    }

    .footer-links a:hover {
        color: var(--accent);
        padding-left: 8px;
    }

    .footer-links a:hover::before {
        opacity: 1;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .contact-item {
        display: flex;
        gap: 12px;
        font-size: 14px;
    }

    .contact-item i {
        color: var(--accent);
        font-size: 18px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .contact-item strong {
        display: block;
        color: white;
        margin-bottom: 5px;
    }

    .contact-item p {
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
        line-height: 1.4;
    }

    .business-hours {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .hour-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 14px;
    }

    .hour-item:last-child {
        border-bottom: none;
    }

    .hour-item .day {
        color: white;
        font-weight: 600;
    }

    .hour-item .time {
        color: var(--accent);
        font-weight: 700;
    }

    .footer-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        margin: 30px 0;
    }

    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 30px 0;
        flex-wrap: wrap;
        gap: 20px;
    }

    .footer-bottom-left,
    .footer-bottom-right {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.7);
    }

    .footer-bottom-left p,
    .footer-bottom-right p {
        margin: 0 0 10px 0;
    }

    .footer-links-bottom {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .footer-links-bottom a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        transition: var(--transition);
        font-size: 13px;
    }

    .footer-links-bottom a:hover {
        color: var(--accent);
    }

    .footer-bottom-right i {
        color: var(--accent);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
            justify-content: center;
        }

        .footer-bottom-left,
        .footer-bottom-right {
            text-align: center;
        }

        .footer-links-bottom {
            justify-content: center;
        }

        .social-links {
            justify-content: center;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<?php endif; ?>
