<?php
/**
 * ENHANCED LAYOUT HEADER - Professional Enterprise Theme
 * Dengan navbar yang responsive dan fitur modern
 */

// Ensure konstanta is loaded
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/konstanta.php';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?php echo BASE_URL; ?>">
    <meta name="description" content="Coffee Shop Management System - Professional Enterprise Solution">
    <meta name="theme-color" content="#2c3e50">
    
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' - Coffee Shop' : 'Coffee Shop'; ?></title>
    
    <!-- Professional CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style_professional.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Additional professional enhancements */
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }
        
        .navbar-brand:hover {
            color: #ecf0f1;
        }
        
        .navbar-icon {
            font-size: 28px;
        }
        
        .user-profile-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            z-index: 1000;
            margin-top: 10px;
            overflow: hidden;
        }
        
        .dropdown-menu.active {
            display: block;
        }
        
        .dropdown-menu a {
            display: block;
            padding: 12px 18px;
            color: #2c3e50;
            text-decoration: none;
            border-bottom: 1px solid #ecf0f1;
            transition: all 0.3s ease;
        }
        
        .dropdown-menu a:last-child {
            border-bottom: none;
        }
        
        .dropdown-menu a:hover {
            background-color: #f8f9fa;
            padding-left: 22px;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
        }
        
        .breadcrumb {
            background: white;
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-size: 13px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .breadcrumb a {
            color: #3498db;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <header>
        <div class="container">
            <nav class="navbar">
                <!-- Logo/Brand -->
                <div class="navbar-brand">
                    <i class="fas fa-coffee navbar-icon"></i>
                    <span>Coffee Shop Pro</span>
                </div>

                <!-- Main Menu -->
                <div class="navbar-menu">
                    <?php if (isset($_SESSION['role'])) { ?>
                        <?php if ($_SESSION['role'] === 'admin') { ?>
                            <!-- Admin Menu -->
                            <a href="<?php echo BASE_URL; ?>index.php?page=admin/dashboard" class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'admin/dashboard') ? 'active' : ''; ?>">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                            <a href="<?php echo BASE_URL; ?>index.php?page=admin/products" class="<?php echo (isset($_GET['page']) && strpos($_GET['page'], 'admin/products') === 0) ? 'active' : ''; ?>">
                                <i class="fas fa-box"></i> Produk
                            </a>
                            <a href="<?php echo BASE_URL; ?>index.php?page=admin/categories" class="<?php echo (isset($_GET['page']) && strpos($_GET['page'], 'admin/categories') === 0) ? 'active' : ''; ?>">
                                <i class="fas fa-tags"></i> Kategori
                            </a>
                            <a href="<?php echo BASE_URL; ?>index.php?page=admin/users" class="<?php echo (isset($_GET['page']) && strpos($_GET['page'], 'admin/users') === 0) ? 'active' : ''; ?>">
                                <i class="fas fa-users"></i> User
                            </a>
                            <a href="<?php echo BASE_URL; ?>index.php?page=admin/orders" class="<?php echo (isset($_GET['page']) && strpos($_GET['page'], 'admin/orders') === 0) ? 'active' : ''; ?>">
                                <i class="fas fa-shopping-cart"></i> Pesanan
                            </a>
                            <a href="<?php echo BASE_URL; ?>index.php?page=admin/reports" class="<?php echo (isset($_GET['page']) && strpos($_GET['page'], 'admin/reports') === 0) ? 'active' : ''; ?>">
                                <i class="fas fa-file-pdf"></i> Laporan
                            </a>
                        <?php } else { ?>
                            <!-- Customer Menu -->
                            <a href="<?php echo BASE_URL; ?>index.php?page=user/dashboard" class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'user/dashboard') ? 'active' : ''; ?>">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                            <a href="<?php echo BASE_URL; ?>index.php?page=user/menu" class="<?php echo (isset($_GET['page']) && $_GET['page'] === 'user/menu') ? 'active' : ''; ?>">
                                <i class="fas fa-mug-hot"></i> Menu
                            </a>
                            <a href="<?php echo BASE_URL; ?>index.php?page=user/orders" class="<?php echo (isset($_GET['page']) && strpos($_GET['page'], 'user/orders') === 0) ? 'active' : ''; ?>">
                                <i class="fas fa-list"></i> Pesanan Saya
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>

                <!-- Right Menu -->
                <div class="navbar-right">
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') { ?>
                        <!-- Cart Icon for Customer -->
                        <a href="<?php echo BASE_URL; ?>index.php?page=user/cart" title="Keranjang Belanja">
                            <i class="fas fa-shopping-cart" style="font-size: 18px;"></i>
                            <span class="cart-badge" style="display: none;">0</span>
                        </a>
                    <?php } ?>

                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <!-- User Profile Dropdown -->
                        <div class="user-profile-dropdown">
                            <a href="#" onclick="toggleDropdown(event)" title="Profile">
                                <i class="fas fa-user-circle" style="font-size: 20px;"></i>
                            </a>
                            <div class="dropdown-menu" id="profileDropdown">
                                <a href="<?php echo BASE_URL; ?>index.php?page=user/profile">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                                <a href="<?php echo BASE_URL; ?>index.php?page=user/profile">
                                    <i class="fas fa-cog"></i> Pengaturan
                                </a>
                                <a href="<?php echo BASE_URL; ?>index.php?page=logout" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <!-- Login Button -->
                        <a href="<?php echo BASE_URL; ?>index.php?page=auth/login" class="btn btn-primary" style="margin: 0;">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    <?php } ?>
                </div>
            </nav>
        </div>
    </header>

    <!-- Page Content -->
    <main>
        <div class="container">
            <?php
            // Display any stored alerts
            if (function_exists('getAlert')) {
                $alert = getAlert();
                if ($alert) {
                    echo '<div class="alert alert-' . htmlspecialchars($alert['type']) . '">';
                    echo '<i class="fas fa-';
                    echo match($alert['type']) {
                        'success' => 'check',
                        'danger' => 'exclamation-circle',
                        'warning' => 'exclamation-triangle',
                        'info' => 'info-circle',
                        default => 'info-circle'
                    };
                    echo '"></i>';
                    echo htmlspecialchars($alert['message']);
                    echo '</div>';
                }
            }
            ?>

<script>
function toggleDropdown(event) {
    event.preventDefault();
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('active');
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function closeDropdown(e) {
        if (!e.target.closest('.user-profile-dropdown')) {
            dropdown.classList.remove('active');
            document.removeEventListener('click', closeDropdown);
        }
    });
}
</script>
