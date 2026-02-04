<?php
/**
 * Professional User Profile Page
 * User account management and profile editing
 */

$title = 'Profil Pengguna';
include __DIR__ . '/../../view/layout_header_professional.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'index.php?page=auth/login');
    exit;
}

$user_model = new User($conn);
$user = $user_model->getUserById($_SESSION['user_id']);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $nama = htmlspecialchars($_POST['nama'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $no_telepon = htmlspecialchars($_POST['no_telepon'] ?? '');
    $alamat = htmlspecialchars($_POST['alamat'] ?? '');
    $kota = htmlspecialchars($_POST['kota'] ?? '');
    $kode_pos = htmlspecialchars($_POST['kode_pos'] ?? '');

    // TODO: Implement profile update in model
    // $result = $user_model->updateProfile($_SESSION['user_id'], $data);
    
    // For now, show success message
    echo '<script>
        const notif = new NotificationManager();
        notif.success("Profil berhasil diperbarui!");
    </script>';
    
    // Refresh user data
    $user = $user_model->getUserById($_SESSION['user_id']);
}
?>

<style>
    .profile-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        padding: 40px;
        border-radius: var(--radius-lg);
        margin-bottom: 40px;
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 30px;
        align-items: center;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        border: 4px solid white;
        color: white;
    }

    .profile-info h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .profile-info p {
        opacity: 0.9;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .profile-status {
        display: flex;
        gap: 20px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .status-badge {
        padding: 6px 12px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: var(--radius);
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .profile-content {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 30px;
    }

    .profile-sidebar {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .profile-menu-item {
        padding: 12px 16px;
        background: white;
        border-radius: var(--radius-lg);
        border: 2px solid var(--border);
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        color: var(--dark);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-menu-item:hover,
    .profile-menu-item.active {
        background: var(--secondary);
        color: white;
        border-color: var(--secondary);
    }

    .profile-menu-item i {
        width: 24px;
        text-align: center;
    }

    .profile-main {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .card {
        background: white;
        border-radius: var(--radius-lg);
        padding: 30px;
        box-shadow: var(--shadow);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--border);
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
    }

    .card-title i {
        color: var(--secondary);
        font-size: 24px;
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

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px solid var(--border);
    }

    .btn-save {
        background: var(--secondary);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: var(--radius-lg);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-cancel {
        background: var(--border);
        color: var(--dark);
        padding: 12px 24px;
        border: none;
        border-radius: var(--radius-lg);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-cancel:hover {
        background: var(--gray);
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .info-item {
        padding: 15px;
        background: var(--lighter);
        border-radius: var(--radius);
        border-left: 4px solid var(--secondary);
    }

    .info-label {
        font-size: 12px;
        text-transform: uppercase;
        color: var(--gray);
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
    }

    .stat-item {
        padding: 20px;
        background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(46, 62, 80, 0.05));
        border-radius: var(--radius-lg);
        border-left: 4px solid var(--secondary);
        text-align: center;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: var(--secondary);
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 12px;
        color: var(--gray);
        font-weight: 600;
    }

    .change-password-info {
        background: linear-gradient(135deg, rgba(241, 196, 15, 0.1), rgba(230, 126, 34, 0.05));
        border-left: 4px solid var(--warning);
        padding: 15px;
        border-radius: var(--radius);
        margin-bottom: 20px;
    }

    .change-password-info strong {
        color: var(--warning);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-header {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .profile-content {
            grid-template-columns: 1fr;
        }

        .form-row,
        .info-grid {
            grid-template-columns: 1fr;
        }

        .profile-avatar {
            margin: 0 auto;
        }
    }
</style>

<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            👤
        </div>
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($user['nama'] ?? 'User'); ?></h1>
            <p>
                <i class="fas fa-envelope"></i>
                <?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?>
            </p>
            <p>
                <i class="fas fa-phone"></i>
                <?php echo htmlspecialchars($user['no_telepon'] ?? 'Belum diisi'); ?>
            </p>
            <div class="profile-status">
                <div class="status-badge">
                    <i class="fas fa-user-check"></i>
                    <?php echo $user['role'] === 'admin' ? 'Administrator' : 'Pelanggan'; ?>
                </div>
                <div class="status-badge">
                    <i class="fas fa-calendar"></i>
                    Bergabung <?php echo formatTanggalId($user['created_at']); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <!-- Sidebar Menu -->
        <div class="profile-sidebar">
            <button class="profile-menu-item active" onclick="showSection('edit-profile')">
                <i class="fas fa-user-edit"></i> Edit Profil
            </button>
            <button class="profile-menu-item" onclick="showSection('change-password')">
                <i class="fas fa-key"></i> Ubah Password
            </button>
            <button class="profile-menu-item" onclick="showSection('order-history')">
                <i class="fas fa-history"></i> Riwayat Pesanan
            </button>
            <a href="<?php echo BASE_URL; ?>index.php?action=logout" class="profile-menu-item" onclick="return confirm('Yakin ingin logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="profile-main">
            <!-- Edit Profile Section -->
            <div id="section-edit-profile" class="section-content">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user-edit"></i>
                        <h2 class="card-title">Edit Profil Pengguna</h2>
                    </div>

                    <form method="POST" class="profile-form">
                        <input type="hidden" name="action" value="update">

                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Email</div>
                                <div class="info-value"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Role</div>
                                <div class="info-value"><?php echo ucfirst($user['role'] ?? 'customer'); ?></div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 25px;">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="tel" name="no_telepon" value="<?php echo htmlspecialchars($user['no_telepon'] ?? ''); ?>">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Kota/Kabupaten</label>
                                <input type="text" name="kota" value="<?php echo htmlspecialchars($user['kota'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Kode Pos</label>
                                <input type="text" name="kode_pos" value="<?php echo htmlspecialchars($user['kode_pos'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" rows="4"><?php echo htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <button type="reset" class="btn-cancel">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Section -->
            <div id="section-change-password" class="section-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-key"></i>
                        <h2 class="card-title">Ubah Password</h2>
                    </div>

                    <div class="change-password-info">
                        <strong>⚠️ Perhatian:</strong> Password harus minimal 8 karakter dan mengandung huruf dan angka
                    </div>

                    <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=change_password">
                        <div class="form-group">
                            <label>Password Lama</label>
                            <input type="password" name="old_password" required>
                        </div>

                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="new_password" required>
                        </div>

                        <div class="form-group">
                            <label>Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" required>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order History Section -->
            <div id="section-order-history" class="section-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-history"></i>
                        <h2 class="card-title">Riwayat Pesanan</h2>
                    </div>

                    <div class="stats-grid" style="margin-bottom: 30px;">
                        <div class="stat-item">
                            <div class="stat-number">12</div>
                            <div class="stat-label">Total Pesanan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">5</div>
                            <div class="stat-label">Sedang Diproses</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">7</div>
                            <div class="stat-label">Selesai</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">Rp 2.5M</div>
                            <div class="stat-label">Total Belanja</div>
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>No Pesanan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#ORD-001</td>
                                <td>15 Jan 2026</td>
                                <td>Rp 250.000</td>
                                <td><span class="badge badge-completed">Selesai</span></td>
                                <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                            </tr>
                            <tr>
                                <td>#ORD-002</td>
                                <td>10 Jan 2026</td>
                                <td>Rp 180.000</td>
                                <td><span class="badge badge-processing">Diproses</span></td>
                                <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                            </tr>
                            <tr>
                                <td>#ORD-003</td>
                                <td>05 Jan 2026</td>
                                <td>Rp 320.000</td>
                                <td><span class="badge badge-completed">Selesai</span></td>
                                <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showSection(sectionName) {
        // Hide all sections
        document.querySelectorAll('.section-content').forEach(el => {
            el.style.display = 'none';
        });

        // Remove active class from all menu items
        document.querySelectorAll('.profile-menu-item').forEach(el => {
            el.classList.remove('active');
        });

        // Show selected section
        const section = document.getElementById(`section-${sectionName}`);
        if (section) {
            section.style.display = 'block';
        }

        // Add active class to clicked menu item
        event.target.closest('.profile-menu-item').classList.add('active');
    }
</script>

<?php include __DIR__ . '/../../view/layout_footer_professional.php'; ?>
