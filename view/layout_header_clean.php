<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Coffee Shop</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Clean Professional Navigation */
        :root {
            --nav-height: 70px;
            --coffee-primary: #2D1B00;
            --coffee-accent: #6F4E37;
            --gold-accent: #D4A574;
        }

        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FDFBF9;
        }

        .navbar {
            height: var(--nav-height);
            background: white;
            border-bottom: 1px solid #F0E6DC;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100%;
            padding: 0 40px;
            max-width: 1200px;
        }

        /* Brand */
        .navbar-brand a {
            font-size: 22px;
            font-weight: 800;
            color: var(--coffee-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: opacity 0.2s;
        }

        .navbar-brand a:hover {
            opacity: 0.7;
        }

        /* Menu */
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .navbar-menu > li > a {
            color: #5C4A3A;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 18px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .navbar-menu > li > a:hover {
            color: var(--coffee-primary);
            background-color: #FDF6F0;
        }

        .navbar-menu > li > a.active {
            color: var(--coffee-primary);
            background-color: #FFF0E1;
            font-weight: 700;
        }

        /* User Profile */
        .user-menu {
            margin-left: 20px;
            padding-left: 20px;
            border-left: 1px solid #F0E6DC;
            position: relative;
        }

        .user-menu > a {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: var(--coffee-primary);
            color: white !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease !important;
            text-decoration: none;
        }

        .user-menu > a:hover {
            background-color: #1A1000 !important;
        }

        .user-menu > a i {
            font-size: 14px;
        }

        /* Dropdown */
        .dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 200px;
            background: white;
            border-radius: 12px;
            border: 1px solid #F0E6DC;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 8px;
            display: none;
            flex-direction: column;
            gap: 2px;
        }

        .user-menu:hover .dropdown {
            display: flex;
        }

        .dropdown li a {
            padding: 10px 14px;
            border-radius: 8px;
            color: #5C4A3A;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            font-size: 14px;
        }

        .dropdown li a:hover {
            background-color: #FDF6F0;
            color: var(--coffee-primary);
        }

        .dropdown li a i {
            width: 16px;
            text-align: center;
        }

        .main-content {
            padding-top: 30px;
        }
    </style>
</head>
<body>
    <?php if (isLoggedIn()) { ?>
        <!-- Navigation untuk user yang login -->
        <nav class="navbar">
            <div class="container">
                <div class="navbar-brand">
                    <a href="<?php echo BASE_URL; ?>">☕ Coffee Shop</a>
                </div>
                <ul class="navbar-menu">
                    <?php if (getCurrentUser()['role'] === 'admin') { ?>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=admin/dashboard'; ?>">Dashboard</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=admin/products'; ?>">Produk</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=admin/categories'; ?>">Kategori</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=admin/users'; ?>">User</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=admin/orders'; ?>">Pesanan</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=admin/reports'; ?>">Laporan</a></li>
                    <?php } else { ?>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=user/dashboard'; ?>">Dashboard</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=user/menu'; ?>">Menu</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=user/cart'; ?>">Keranjang</a></li>
                        <li><a href="<?php echo BASE_URL . 'index.php?page=user/orders'; ?>">Pesanan Saya</a></li>
                    <?php } ?>
                    <li class="user-menu">
                        <a href="#"><i class="fas fa-user"></i> <?php echo getCurrentUser()['nama']; ?></a>
                        <ul class="dropdown">
                            <li><a href="<?php echo BASE_URL . 'index.php?page=user/profile'; ?>"><i class="fas fa-user-circle"></i> Profile</a></li>
                            <li><a href="<?php echo BASE_URL . 'index.php?page=logout'; ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                <!-- Alert Messages -->
                <?php 
                $alert = getAlert();
                if ($alert) {
                    echo '<div class="alert alert-' . $alert['type'] . '">';
                    echo '<i class="fas fa-check-circle"></i> ' . $alert['message'];
                    echo '</div>';
                }
                ?>
    <?php } ?>
