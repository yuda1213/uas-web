<?php
$title = 'Manajemen User';
include __DIR__ . '/../../layout_header.php';

$user_model = new User($conn);

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Prevent deleting yourself
    if ($id === getCurrentUser()['id']) {
        setAlert('danger', 'Anda tidak dapat menghapus akun sendiri');
    } elseif ($user_model->deleteUser($id)) {
        setAlert('success', 'User berhasil dihapus');
    } else {
        setAlert('danger', 'Gagal menghapus user');
    }
    
    redirect(BASE_URL . 'index.php?page=admin/users');
}

// Get data
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$page_data = getPaginationData($user_model->countUsers($search));

$users = $user_model->getAllUsers(
    $page_data['items_per_page'],
    $page_data['offset'],
    $search
);
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Manajemen User</h2>
        <a href="<?php echo BASE_URL . 'index.php?page=admin/users/create'; ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
    
    <!-- Search -->
    <div class="card">
        <div class="card-body">
            <form method="GET" action="">
                <input type="hidden" name="page" value="admin/users">
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="search" placeholder="Cari user..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; padding: 10px;">
                    <button type="submit" class="btn">Cari</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No Telepon</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = $page_data['offset'] + 1;
                    if ($users && $users->num_rows > 0) {
                        while ($user = $users->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $no++ . '</td>';
                            echo '<td><strong>' . htmlspecialchars($user['nama']) . '</strong></td>';
                            echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['no_telepon'] ?? '-') . '</td>';
                            echo '<td>';
                            $role_class = $user['role'] === 'admin' ? 'badge-primary' : 'badge-success';
                            echo '<span class="badge ' . $role_class . '">' . ucfirst($user['role']) . '</span>';
                            echo '</td>';
                            echo '<td><span class="badge ' . ($user['status'] === 'aktif' ? 'badge-success' : 'badge-danger') . '">' . ucfirst($user['status']) . '</span></td>';
                            echo '<td class="table-actions">';
                            echo '<a href="' . BASE_URL . 'index.php?page=admin/users/edit&id=' . $user['id'] . '" class="btn btn-sm">Edit</a>';
                            if ($user['id'] !== getCurrentUser()['id']) {
                                echo '<a href="' . BASE_URL . 'index.php?page=admin/users&action=delete&id=' . $user['id'] . '" class="btn btn-sm btn-danger" onclick="return confirmDelete();">Hapus</a>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7" style="text-align: center;">Tidak ada user ditemukan</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Pagination -->
    <?php if ($page_data['total_pages'] > 1) { ?>
        <div class="pagination">
            <?php
            if ($page_data['current_page'] > 1) {
                echo '<a href="' . BASE_URL . 'index.php?page=admin/users&search=' . urlencode($search) . '&p=' . ($page_data['current_page'] - 1) . '">← Sebelumnya</a>';
            }
            
            for ($i = 1; $i <= $page_data['total_pages']; $i++) {
                if ($i === $page_data['current_page']) {
                    echo '<span class="active">' . $i . '</span>';
                } else {
                    echo '<a href="' . BASE_URL . 'index.php?page=admin/users&search=' . urlencode($search) . '&p=' . $i . '">' . $i . '</a>';
                }
            }
            
            if ($page_data['current_page'] < $page_data['total_pages']) {
                echo '<a href="' . BASE_URL . 'index.php?page=admin/users&search=' . urlencode($search) . '&p=' . ($page_data['current_page'] + 1) . '">Selanjutnya →</a>';
            }
            ?>
        </div>
    <?php } ?>
</div>

<?php include __DIR__ . '/../../layout_footer.php'; ?>
