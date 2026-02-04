<?php
$title = 'Profile Admin';
include __DIR__ . '/../layout_header.php';

$user_model = new User($conn);
$order_model = new Order($conn);
$product_model = new Product($conn);

$user_id = getCurrentUser()['id'];
$user = $user_model->getUserById($user_id);

// Get admin stats
$order_stats = $order_model->getOrderStats();
$product_stats = $product_model->getProductStats();
$total_users = $user_model->countUsers();

// Handle update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $nama = sanitize($_POST['nama'] ?? '');
    $no_telepon = sanitize($_POST['no_telepon'] ?? '');
    $alamat = sanitize($_POST['alamat'] ?? '');
    
    if (empty($nama)) {
        setAlert('danger', 'Nama tidak boleh kosong');
    } else {
        if ($user_model->updateProfile($user_id, $nama, $no_telepon, $alamat)) {
            // Update session
            $_SESSION['user']['nama'] = $nama;
            setAlert('success', 'Profile berhasil diperbarui');
            redirect(BASE_URL . 'index.php?page=admin/profile');
        } else {
            setAlert('danger', 'Gagal memperbarui profile');
        }
    }
}

// Handle change password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'change_password') {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (!verifyPassword($old_password, $user['password'])) {
        setAlert('danger', 'Password lama tidak sesuai');
    } elseif (empty($new_password)) {
        setAlert('danger', 'Password baru tidak boleh kosong');
    } elseif (strlen($new_password) < 6) {
        setAlert('danger', 'Password minimal 6 karakter');
    } elseif ($new_password !== $confirm_password) {
        setAlert('danger', 'Konfirmasi password tidak cocok');
    } else {
        if ($user_model->changePassword($user_id, $new_password)) {
            setAlert('success', 'Password berhasil diubah');
            redirect(BASE_URL . 'index.php?page=admin/profile');
        } else {
            setAlert('danger', 'Gagal mengubah password');
        }
    }
}

// Refresh user data
$user = $user_model->getUserById($user_id);
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
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-40px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-12px) rotate(2deg); }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @keyframes glow {
        0%, 100% { box-shadow: 0 0 20px rgba(212, 165, 116, 0.3); }
        50% { box-shadow: 0 0 40px rgba(212, 165, 116, 0.5); }
    }

    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .profile-page-hero {
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #8B6F47 100%);
        color: #fff;
        padding: 40px 36px;
        border-radius: 28px;
        margin: 10px 0 32px;
        box-shadow: 0 32px 80px rgba(45, 27, 0, 0.4),
                    inset 0 1px 0 rgba(255,255,255,0.15);
        position: relative;
        overflow: hidden;
        animation: slideDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 2px solid rgba(255,255,255,0.1);
    }

    .profile-page-hero::before {
        content: '';
        position: absolute;
        top: -60%;
        right: -20%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0.15), transparent 70%);
        animation: float 8s ease-in-out infinite;
    }

    .profile-page-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(212, 165, 116, 0.2), transparent 70%);
        animation: float 6s ease-in-out infinite reverse;
    }

    .profile-page-hero .hero-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .profile-page-hero .hero-icon {
        width: 70px;
        height: 70px;
        background: rgba(255,255,255,0.15);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .profile-page-hero h2 { 
        margin: 0 0 6px 0; 
        font-size: 30px; 
        font-weight: 900; 
        text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        letter-spacing: -0.5px;
        line-height: 1.2;
    }

    .profile-page-hero p { 
        margin: 0; 
        opacity: 0.92; 
        font-size: 15px;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .profile-page-hero .breadcrumb {
        position: absolute;
        top: 16px;
        right: 24px;
        font-size: 13px;
        opacity: 0.8;
        z-index: 2;
    }

    .profile-page-hero .breadcrumb a {
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        transition: color 0.2s;
    }

    .profile-page-hero .breadcrumb a:hover {
        color: #fff;
    }

    .profile-container {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 28px;
        margin-bottom: 40px;
    }
    
    .profile-sidebar {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        padding: 0;
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.1),
                    inset 0 1px 0 rgba(255,255,255,0.9);
        border: 2px solid rgba(212, 165, 116, 0.15);
        height: fit-content;
        animation: slideInLeft 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: sticky;
        top: 100px;
        overflow: hidden;
    }
    
    .profile-avatar-section {
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 60%, #8B6F47 100%);
        padding: 32px 24px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .profile-avatar-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.08) 50%, transparent 60%);
        animation: shimmer 4s ease-in-out infinite;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #D4A574 0%, #C49668 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 56px;
        margin: 0 auto 16px;
        color: #2D1B00;
        box-shadow: 0 12px 32px rgba(0,0,0,0.3),
                    0 0 0 4px rgba(255,255,255,0.2);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        z-index: 1;
    }

    .profile-sidebar:hover .profile-avatar {
        transform: scale(1.08);
        box-shadow: 0 16px 40px rgba(0,0,0,0.4),
                    0 0 0 6px rgba(255,255,255,0.25);
    }

    .profile-avatar-section .admin-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.2);
        color: #fff;
        padding: 8px 18px;
        border-radius: 25px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        position: relative;
        z-index: 1;
    }

    .profile-avatar-section h3 {
        color: #fff;
        margin: 16px 0 4px;
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -0.3px;
        position: relative;
        z-index: 1;
    }

    .profile-avatar-section .email {
        color: rgba(255,255,255,0.85);
        font-size: 14px;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .profile-details {
        padding: 24px;
    }

    .profile-info-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid rgba(212, 165, 116, 0.12);
    }

    .profile-info-item:last-child {
        border-bottom: none;
    }

    .profile-info-item i {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, rgba(255, 248, 241, 0.95) 0%, rgba(255, 246, 237, 0.95) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6F4E37;
        font-size: 16px;
        flex-shrink: 0;
    }

    .profile-info-item .info-content {
        flex: 1;
        min-width: 0;
    }

    .profile-info-item .info-label {
        font-size: 11px;
        color: #A89080;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .profile-info-item .info-value {
        font-size: 14px;
        color: #2D1B00;
        font-weight: 600;
        word-break: break-word;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        padding: 0 24px 24px;
    }

    .stat-item {
        background: linear-gradient(135deg, rgba(255, 248, 241, 0.95) 0%, rgba(255, 253, 251, 0.95) 100%);
        border-radius: 16px;
        padding: 18px 14px;
        text-align: center;
        border: 1.5px solid rgba(212, 165, 116, 0.12);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .stat-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(45, 27, 0, 0.1);
        border-color: rgba(212, 165, 116, 0.3);
    }

    .stat-item .stat-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 14px;
        margin: 0 auto 10px;
    }

    .stat-item .number {
        font-size: 22px;
        font-weight: 900;
        color: #2D1B00;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-item .label {
        font-size: 10px;
        color: #8B7355;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
    }

    .quick-actions {
        padding: 20px 24px;
        background: linear-gradient(135deg, rgba(255, 248, 241, 0.5) 0%, rgba(255, 246, 237, 0.5) 100%);
        border-top: 1.5px solid rgba(212, 165, 116, 0.12);
    }

    .quick-actions h4 {
        font-size: 11px;
        color: #8B7355;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin: 0 0 14px 0;
        font-weight: 800;
    }

    .quick-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        text-decoration: none;
        color: #2D1B00;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 8px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 1.5px solid transparent;
    }

    .quick-link:last-child {
        margin-bottom: 0;
    }

    .quick-link:hover {
        background: #fff;
        border-color: rgba(212, 165, 116, 0.3);
        transform: translateX(6px);
        box-shadow: 0 8px 20px rgba(45, 27, 0, 0.08);
    }

    .quick-link i {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 14px;
    }

    .profile-main {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .profile-card {
        background: rgba(255, 255, 255, 0.98);
        border-radius: 24px;
        overflow: hidden;
        border: 2px solid rgba(212, 165, 116, 0.12);
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.08);
        animation: fadeUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) backwards;
    }

    .profile-card:nth-child(1) { animation-delay: 0.1s; }
    .profile-card:nth-child(2) { animation-delay: 0.2s; }

    .profile-card-header { 
        background: linear-gradient(135deg, rgba(255, 248, 241, 0.98) 0%, rgba(255, 246, 237, 0.98) 100%);
        border-bottom: 2px solid rgba(212, 165, 116, 0.1);
        padding: 22px 28px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .profile-card-header .header-icon {
        width: 46px;
        height: 46px;
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        box-shadow: 0 8px 20px rgba(45, 27, 0, 0.15);
    }

    .profile-card-header .header-text h3 {
        margin: 0;
        font-size: 18px;
        color: #2D1B00;
        font-weight: 800;
        letter-spacing: -0.3px;
    }

    .profile-card-header .header-text p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #8B7355;
    }

    .profile-card-body {
        padding: 28px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-row.single {
        grid-template-columns: 1fr;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
        color: #2D1B00;
        font-weight: 800;
        font-size: 12px;
        letter-spacing: 0.8px;
        text-transform: uppercase;
    }

    .form-group label i {
        color: #6F4E37;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        border: 2px solid rgba(212, 165, 116, 0.25);
        border-radius: 14px;
        padding: 16px 20px;
        font-family: inherit;
        font-size: 15px;
        background: rgba(255, 253, 251, 0.98);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-sizing: border-box;
        box-shadow: 0 4px 16px rgba(45, 27, 0, 0.04);
        color: #2D1B00;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #B5A595;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #6F4E37;
        box-shadow: 0 0 0 4px rgba(111, 78, 55, 0.1),
                    0 8px 28px rgba(45, 27, 0, 0.1);
        background: #fff;
    }

    .form-group input[readonly] {
        background: linear-gradient(135deg, #F5F3F0 0%, #EBE8E4 100%);
        cursor: not-allowed;
        color: #8B7355;
        border-color: rgba(212, 165, 116, 0.15);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 110px;
    }

    .form-hint {
        font-size: 12px;
        color: #A89080;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .form-hint i {
        font-size: 12px;
    }

    .btn-profile {
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 18px 28px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        font-size: 14px;
        width: 100%;
        box-shadow: 0 12px 36px rgba(45, 27, 0, 0.2);
        letter-spacing: 0.8px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-profile:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 52px rgba(45, 27, 0, 0.3);
        background: linear-gradient(135deg, #5A3E2C 0%, #7A5E3B 100%);
    }

    .btn-profile:active {
        transform: translateY(-2px);
    }

    .btn-password {
        background: linear-gradient(135deg, #D4A574 0%, #C49668 100%);
        color: #2D1B00;
        box-shadow: 0 12px 36px rgba(212, 165, 116, 0.3);
    }

    .btn-password:hover {
        background: linear-gradient(135deg, #C49668 0%, #B48858 100%);
        box-shadow: 0 20px 52px rgba(212, 165, 116, 0.4);
    }

    .alert-profile {
        padding: 18px 22px;
        border-radius: 14px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 14px;
        animation: fadeUp 0.4s ease-out;
        font-weight: 600;
        font-size: 14px;
    }

    .alert-profile.alert-success {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.12) 0%, rgba(67, 160, 71, 0.08) 100%);
        border: 2px solid rgba(76, 175, 80, 0.25);
        color: #2e7d32;
    }

    .alert-profile.alert-danger {
        background: linear-gradient(135deg, rgba(244, 67, 54, 0.12) 0%, rgba(229, 57, 53, 0.08) 100%);
        border: 2px solid rgba(244, 67, 54, 0.25);
        color: #c62828;
    }

    .alert-profile i {
        font-size: 20px;
    }

    @media (max-width: 1024px) {
        .profile-container {
            grid-template-columns: 1fr;
        }

        .profile-sidebar {
            position: static;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .profile-page-hero {
            padding: 28px 20px;
        }

        .profile-page-hero h2 {
            font-size: 24px;
        }

        .profile-page-hero .hero-content {
            flex-direction: column;
            text-align: center;
        }

        .profile-card-body {
            padding: 20px;
        }
    }
</style>

<div class="container">
    <div class="profile-page-hero">
        <div class="breadcrumb">
            <a href="<?php echo BASE_URL; ?>index.php?page=admin/dashboard"><i class="fas fa-home"></i></a> / Profile
        </div>
        <div class="hero-content">
            <div class="hero-icon">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <h2>Profile Admin</h2>
                <p>Kelola informasi akun dan keamanan sistem Anda</p>
            </div>
        </div>
    </div>
    
    <?php 
    $alert = getAlert();
    if ($alert) {
        $icon = $alert['type'] === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        echo '<div class="alert-profile alert-' . htmlspecialchars($alert['type']) . '">';
        echo '<i class="' . $icon . '"></i>';
        echo '<span>' . htmlspecialchars($alert['message']) . '</span>';
        echo '</div>';
    }
    ?>
    
    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-avatar-section">
                <div class="profile-avatar">👨‍💼</div>
                <div class="admin-badge">
                    <i class="fas fa-shield-alt"></i> Administrator
                </div>
                <h3><?php echo htmlspecialchars($user['nama']); ?></h3>
                <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            
            <div class="profile-details">
                <?php if (!empty($user['no_telepon'])) { ?>
                <div class="profile-info-item">
                    <i class="fas fa-phone"></i>
                    <div class="info-content">
                        <div class="info-label">Telepon</div>
                        <div class="info-value"><?php echo htmlspecialchars($user['no_telepon']); ?></div>
                    </div>
                </div>
                <?php } ?>
                
                <div class="profile-info-item">
                    <i class="fas fa-calendar-check"></i>
                    <div class="info-content">
                        <div class="info-label">Bergabung Sejak</div>
                        <div class="info-value"><?php echo formatTanggalId($user['tanggal_daftar']); ?></div>
                    </div>
                </div>

                <div class="profile-info-item">
                    <i class="fas fa-user-tag"></i>
                    <div class="info-content">
                        <div class="info-label">Role</div>
                        <div class="info-value" style="text-transform: capitalize;"><?php echo htmlspecialchars($user['role']); ?></div>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-shopping-bag"></i></div>
                    <div class="number"><?php echo $order_stats['total_pesanan'] ?? 0; ?></div>
                    <div class="label">Pesanan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-coffee"></i></div>
                    <div class="number"><?php echo $product_stats['total_produk'] ?? 0; ?></div>
                    <div class="label">Produk</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="number"><?php echo $total_users ?? 0; ?></div>
                    <div class="label">Users</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="number"><?php echo $order_stats['selesai'] ?? 0; ?></div>
                    <div class="label">Selesai</div>
                </div>
            </div>

            <div class="quick-actions">
                <h4>Akses Cepat</h4>
                <a href="<?php echo BASE_URL; ?>index.php?page=admin/dashboard" class="quick-link">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="<?php echo BASE_URL; ?>index.php?page=admin/products" class="quick-link">
                    <i class="fas fa-coffee"></i> Kelola Produk
                </a>
                <a href="<?php echo BASE_URL; ?>index.php?page=admin/orders" class="quick-link">
                    <i class="fas fa-shopping-bag"></i> Kelola Pesanan
                </a>
                <a href="<?php echo BASE_URL; ?>index.php?page=admin/users" class="quick-link">
                    <i class="fas fa-users"></i> Kelola User
                </a>
            </div>
        </div>
        
        <div class="profile-main">
            <!-- Edit Profile -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="header-icon">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div class="header-text">
                        <h3>Edit Profile</h3>
                        <p>Perbarui informasi profil Anda</p>
                    </div>
                </div>
                <div class="profile-card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="nama"><i class="fas fa-user"></i> Nama Lengkap</label>
                                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required placeholder="Masukkan nama lengkap">
                            </div>
                            
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                <div class="form-hint"><i class="fas fa-info-circle"></i> Email tidak dapat diubah</div>
                            </div>
                        </div>
                        
                        <div class="form-row single">
                            <div class="form-group">
                                <label for="no_telepon"><i class="fas fa-phone"></i> No Telepon</label>
                                <input type="tel" id="no_telepon" name="no_telepon" value="<?php echo htmlspecialchars($user['no_telepon'] ?? ''); ?>" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        
                        <div class="form-row single">
                            <div class="form-group">
                                <label for="alamat"><i class="fas fa-map-marker-alt"></i> Alamat</label>
                                <textarea id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap Anda..."><?php echo htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-profile">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Change Password -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="header-icon" style="background: linear-gradient(135deg, #D4A574 0%, #C49668 100%);">
                        <i class="fas fa-lock" style="color: #2D1B00;"></i>
                    </div>
                    <div class="header-text">
                        <h3>Ubah Password</h3>
                        <p>Jaga keamanan akun dengan password yang kuat</p>
                    </div>
                </div>
                <div class="profile-card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="form-row single">
                            <div class="form-group">
                                <label for="old_password"><i class="fas fa-key"></i> Password Saat Ini</label>
                                <input type="password" id="old_password" name="old_password" required placeholder="Masukkan password lama">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_password"><i class="fas fa-lock"></i> Password Baru</label>
                                <input type="password" id="new_password" name="new_password" required placeholder="Minimal 6 karakter">
                                <div class="form-hint"><i class="fas fa-shield-alt"></i> Gunakan kombinasi huruf, angka & simbol</div>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password"><i class="fas fa-check-double"></i> Konfirmasi Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Ulangi password baru">
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-profile btn-password">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
