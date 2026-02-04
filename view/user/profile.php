<?php
$title = 'Profile Saya';
include __DIR__ . '/../layout_header.php';

$user_model = new User($conn);
$user_id = getCurrentUser()['id'];
$user = $user_model->getUserById($user_id);

// Handle update profile
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $nama = sanitize($_POST['nama'] ?? '');
    $no_telepon = sanitize($_POST['no_telepon'] ?? '');
    $alamat = sanitize($_POST['alamat'] ?? '');
    
    if (empty($nama)) {
        setAlert('danger', 'Nama tidak boleh kosong');
    } else {
        if ($user_model->updateProfile($user_id, $nama, $no_telepon, $alamat)) {
            setAlert('success', 'Profile berhasil diperbarui');
            redirect(BASE_URL . 'index.php?page=user/profile');
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
    
    // Verify old password
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
            redirect(BASE_URL . 'index.php?page=user/profile');
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
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }

    @keyframes glow {
        0%, 100% { box-shadow: 0 0 20px rgba(212, 165, 116, 0.3); }
        50% { box-shadow: 0 0 40px rgba(212, 165, 116, 0.6); }
    }

    .page-hero {
        background: linear-gradient(135deg, #2D1B00 0%, #6F4E37 50%, #8B6F47 100%);
        color: #fff;
        padding: 32px 28px;
        border-radius: 20px;
        margin: 10px 0 24px;
        box-shadow: 0 24px 64px rgba(45, 27, 0, 0.3),
                    inset 0 1px 0 rgba(255,255,255,0.15);
        position: relative;
        overflow: hidden;
        animation: slideDown 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        backdrop-filter: blur(12px);
        border: 1.5px solid rgba(255,255,255,0.12);
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
        animation: float 5s ease-in-out infinite;
    }
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

    .profile-container {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 24px;
        margin: 24px 0;
    }
    
    .profile-sidebar {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 18px;
        padding: 24px 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06),
                    inset 0 1px 0 rgba(255,255,255,0.6);
        border: 1px solid rgba(239, 230, 221, 0.8);
        height: fit-content;
        animation: slideInLeft 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: sticky;
        top: 100px;
        backdrop-filter: blur(10px);
    }
    
    .profile-avatar {
        width: 100%;
        height: 220px;
        border-radius: 14px;
        background: linear-gradient(135deg, #6F4E37 0%, #D4A574 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 90px;
        margin-bottom: 24px;
        color: white;
        box-shadow: 0 12px 28px rgba(45, 27, 0, 0.15);
        transition: all 0.3s ease;
    }

    .profile-sidebar:hover .profile-avatar {
        transform: translateY(-4px);
    }
    
    .profile-info {
        text-align: center;
        color: #2D1B00;
    }
    
    .profile-info h3 {
        margin: 0 0 6px 0;
        font-size: 16px;
        font-weight: 900;
        letter-spacing: -0.4px;
        line-height: 1.2;
    }
    
    .profile-info p {
        margin: 0 0 4px 0;
        color: #999;
        font-size: 13px;
        word-break: break-word;
    }

    .profile-info .join-date {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #EFE6DD;
        color: #999;
        font-size: 12px;
    }

    .card {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(239, 230, 221, 0.8);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06),
                    inset 0 1px 0 rgba(255,255,255,0.6);
        animation: fadeUp 0.6s ease-out backwards;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .card:nth-child(1) { animation-delay: 0.2s; }
    .card:nth-child(2) { animation-delay: 0.3s; }

    .card-header { 
        background: linear-gradient(135deg, rgba(255, 248, 241, 0.9) 0%, rgba(255, 246, 237, 0.9) 100%);
        border-bottom: 1px solid rgba(239, 230, 221, 0.5);
        padding: 18px;
        backdrop-filter: blur(5px);
    }

    .card-header h3 {
        margin: 0;
        font-size: 16px;
        color: #6F4E37;
        font-weight: 900;
        letter-spacing: -0.4px;
        line-height: 1.2;
    }

    .card-body {
        padding: 24px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group:last-child {
        margin-bottom: 0;
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

    .form-group input,
    .form-group textarea {
        width: 100%;
        border: 1.5px solid rgba(212, 165, 116, 0.3);
        border-radius: 14px;
        padding: 14px 16px;
        font-family: inherit;
        font-size: 14px;
        background: rgba(255, 253, 251, 0.9);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-sizing: border-box;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 12px rgba(45, 27, 0, 0.03);
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: rgba(212, 165, 116, 0.8);
        box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.15),
                    0 8px 24px rgba(45, 27, 0, 0.08),
                    inset 0 1px 2px rgba(212, 165, 116, 0.1);
        background: rgba(255, 255, 255, 0.98);
    }

    .form-group input[readonly] {
        background: #F5F3F0;
        cursor: not-allowed;
        color: #999;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .btn {
        background: linear-gradient(135deg, #6F4E37 0%, #8B6F47 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 14px 22px;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        font-size: 14px;
        width: 100%;
        box-shadow: 0 12px 32px rgba(45, 27, 0, 0.15);
        letter-spacing: 0.5px;
    }

    .btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 48px rgba(45, 27, 0, 0.25);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.95);
        color: var(--coffee);
        border: 1.5px solid rgba(227, 213, 200, 0.8);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .btn-secondary:hover {
        background: rgba(255, 246, 237, 0.95);
        border-color: rgba(212, 165, 116, 0.9);
        box-shadow: 0 8px 20px rgba(45, 27, 0, 0.08);
    }

    @media (max-width: 768px) {
        .profile-container {
            grid-template-columns: 1fr;
        }

        .profile-sidebar {
            position: static;
        }
    }
</style>

<div class="container">
    <div class="page-hero">
        <h2>Profile Saya</h2>
        <p>Kelola data akun dan keamanan Anda.</p>
    </div>
    
    <?php 
    $alert = getAlert();
    if ($alert) {
        echo '<div class="alert alert-' . htmlspecialchars($alert['type']) . '">';
        echo htmlspecialchars($alert['message']);
        echo '</div>';
    }
    ?>
    
    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-avatar">👤</div>
            <div class="profile-info">
                <h3><?php echo htmlspecialchars($user['nama']); ?></h3>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
                <p style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee;">
                    Bergabung: <?php echo formatTanggalId($user['tanggal_daftar']); ?>
                </p>
            </div>
        </div>
        
        <div>
            <!-- Edit Profile -->
            <div class="card">
                <div class="card-header">
                    <h3>Edit Profile</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email (tidak dapat diubah)</label>
                            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="no_telepon">No Telepon</label>
                            <input type="tel" id="no_telepon" name="no_telepon" value="<?php echo htmlspecialchars($user['no_telepon'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="4"><?php echo htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
            
            <!-- Change Password -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3>Ubah Password</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="form-group">
                            <label for="old_password">Password Lama</label>
                            <input type="password" id="old_password" name="old_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout_footer.php'; ?>
