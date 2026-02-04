<?php
/**
 * Admin User Create & Edit
 * Modul untuk membuat dan edit user oleh admin
 */

$title = isset($_GET['id']) ? 'Edit User' : 'Tambah User';
include __DIR__ . '/../../layout_header.php';

$user_model = new User($conn);

// Untuk edit, get user data
$edit_user = null;
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $edit_user = $user_model->getUserById($id);
    
    if (!$edit_user) {
        setAlert('danger', 'User tidak ditemukan');
        redirect(BASE_URL . 'index.php?page=admin/users');
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = sanitize($_POST['password'] ?? '');
    $no_telepon = sanitize($_POST['no_telepon'] ?? '');
    $alamat = sanitize($_POST['alamat'] ?? '');
    $role = sanitize($_POST['role'] ?? 'user');
    $status = sanitize($_POST['status'] ?? 'aktif');
    
    // Validasi
    $errors = [];
    
    if (empty($nama)) {
        $errors[] = 'Nama harus diisi';
    }
    
    if (empty($email)) {
        $errors[] = 'Email harus diisi';
    }
    
    if (!validateEmail($email)) {
        $errors[] = 'Format email tidak valid';
    }
    
    if (!$edit_user && empty($password)) {
        $errors[] = 'Password harus diisi untuk user baru';
    }
    
    if (!$edit_user && strlen($password) < 6 && !empty($password)) {
        $errors[] = 'Password minimal 6 karakter';
    }
    
    // Check email uniqueness
    if (!$edit_user) {
        if ($user_model->emailExists($email)) {
            $errors[] = 'Email sudah terdaftar';
        }
    } else {
        // When editing, check if email changed and is unique
        if ($email !== $edit_user['email'] && $user_model->emailExists($email)) {
            $errors[] = 'Email sudah terdaftar';
        }
    }
    
    if (!empty($errors)) {
        foreach ($errors as $error) {
            setAlert('danger', $error);
        }
    } else {
        if (!$edit_user) {
            // Create new user
            $user_id = $user_model->register($nama, $email, $password, $no_telepon, $alamat);
            
            if ($user_id) {
                // Update role
                $user_model->updateUser($user_id, $nama, $email, $no_telepon, $alamat, $role, $status);
                setAlert('success', 'User berhasil ditambahkan');
                redirect(BASE_URL . 'index.php?page=admin/users');
            } else {
                setAlert('danger', 'Gagal menambahkan user');
            }
        } else {
            // Update existing user
            if ($user_model->updateUser($edit_user['id'], $nama, $email, $no_telepon, $alamat, $role, $status)) {
                setAlert('success', 'User berhasil diperbarui');
                redirect(BASE_URL . 'index.php?page=admin/users');
            } else {
                setAlert('danger', 'Gagal memperbarui user');
            }
        }
    }
}
?>

<div class="container">
    <h2><?php echo $title; ?></h2>
    
    <?php 
    $alert = getAlert();
    if ($alert) {
        echo '<div class="alert alert-' . htmlspecialchars($alert['type']) . '">';
        echo htmlspecialchars($alert['message']);
        echo '</div>';
    }
    ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="nama">Nama Lengkap *</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($edit_user['nama'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($edit_user['email'] ?? ''); ?>" required>
                </div>
                
                <?php if (!$edit_user) { ?>
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                <?php } ?>
                
                <div class="form-group">
                    <label for="no_telepon">No Telepon</label>
                    <input type="tel" id="no_telepon" name="no_telepon" value="<?php echo htmlspecialchars($edit_user['no_telepon'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($edit_user['alamat'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="user" <?php echo (($edit_user['role'] ?? 'user') === 'user') ? 'selected' : ''; ?>>User (Customer)</option>
                        <option value="admin" <?php echo (($edit_user['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="aktif" <?php echo (($edit_user['status'] ?? 'aktif') === 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="nonaktif" <?php echo (($edit_user['status'] ?? '') === 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                    </select>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?php echo BASE_URL . 'index.php?page=admin/users'; ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layout_footer.php'; ?>
