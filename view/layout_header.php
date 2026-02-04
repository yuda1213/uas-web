<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Coffee Shop</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>">
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

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .navbar {
            height: var(--nav-height);
            background: linear-gradient(180deg, 
                rgba(255, 255, 255, 0.98) 0%, 
                rgba(253, 251, 249, 0.95) 100%);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(240, 230, 220, 0.8);
            box-shadow: 
                0 1px 0 0 rgba(255, 255, 255, 0.8) inset,
                0 2px 4px -1px rgba(45, 27, 0, 0.06),
                0 4px 12px -2px rgba(45, 27, 0, 0.05),
                0 12px 32px -4px rgba(45, 27, 0, 0.04);
            position: sticky;
            top: 0;
            z-index: 1000;
            animation: fadeInDown 0.4s ease-out;
            transition: all 0.3s ease;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100%;
            padding: 0 40px;
            max-width: 1200px;
        }

        /* Brand - Simple & Modern */
        .navbar-brand a {
            font-size: 20px;
            font-weight: 700;
            color: var(--coffee-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            letter-spacing: -0.5px;
            text-shadow: 0 1px 2px rgba(45, 27, 0, 0.1);
        }

        .navbar-brand a i {
            font-size: 22px;
            color: var(--coffee-accent);
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(111, 78, 55, 0.15));
        }

        .navbar-brand a:hover {
            text-shadow: 0 2px 8px rgba(45, 27, 0, 0.15);
        }

        .navbar-brand a:hover i {
            transform: rotate(-10deg) translateY(-1px);
            filter: drop-shadow(0 4px 8px rgba(111, 78, 55, 0.25));
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
            color: #6B5B4F;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.2s ease;
            position: relative;
            letter-spacing: 0.2px;
            border: 1px solid transparent;
        }

        .navbar-menu > li > a::after {
            content: '';
            position: absolute;
            bottom: 6px;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 20px;
            height: 2px;
            background: linear-gradient(90deg, var(--coffee-accent), var(--gold-accent));
            border-radius: 2px;
            transition: transform 0.2s ease;
            box-shadow: 0 1px 4px rgba(111, 78, 55, 0.3);
        }

        .navbar-menu > li > a:hover {
            color: var(--coffee-primary);
            background: linear-gradient(135deg, 
                rgba(253, 246, 240, 0.8) 0%, 
                rgba(255, 248, 240, 0.6) 100%);
            border-color: rgba(240, 230, 220, 0.5);
            box-shadow: 
                0 1px 0 0 rgba(255, 255, 255, 0.5) inset,
                0 2px 8px rgba(212, 165, 116, 0.15);
            transform: translateY(-1px);
        }

        .navbar-menu > li > a:hover::after {
            transform: translateX(-50%) scaleX(1);
        }

        .navbar-menu > li > a.active {
            color: var(--coffee-primary);
            background: linear-gradient(135deg, 
                rgba(255, 240, 225, 0.9) 0%, 
                rgba(255, 248, 240, 0.7) 100%);
            font-weight: 600;
            border-color: rgba(212, 165, 116, 0.3);
            box-shadow: 
                0 1px 0 0 rgba(255, 255, 255, 0.6) inset,
                0 2px 6px rgba(212, 165, 116, 0.2);
        }

        .navbar-menu > li > a.active::after {
            transform: translateX(-50%) scaleX(1);
        }

        /* User Profile */
        .user-menu {
            margin-left: 20px;
            padding-left: 20px;
            border-left: 1px solid #F0E6DC;
            position: relative;
        }

        /* Hover bridge untuk stabilitas dropdown */
        .user-menu::after {
            content: '';
            position: absolute;
            top: 100%;
            right: 0;
            width: 100%;
            height: 15px;
            display: block;
        }

        .user-menu > a {
            display: flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, 
                var(--coffee-primary) 0%, 
                #1F1200 100%);
            color: white !important;
            padding: 8px 16px !important;
            border-radius: 20px !important;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.2s ease !important;
            text-decoration: none;
            box-shadow: 
                0 1px 0 0 rgba(255, 255, 255, 0.1) inset,
                0 2px 4px rgba(45, 27, 0, 0.2),
                0 4px 12px rgba(45, 27, 0, 0.15),
                0 8px 20px rgba(45, 27, 0, 0.1);
            letter-spacing: 0.3px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .user-menu > a:hover {
            background: linear-gradient(135deg, 
                var(--coffee-accent) 0%, 
                var(--coffee-primary) 100%) !important;
            box-shadow: 
                0 1px 0 0 rgba(255, 255, 255, 0.15) inset,
                0 4px 8px rgba(45, 27, 0, 0.25),
                0 8px 16px rgba(45, 27, 0, 0.2),
                0 12px 28px rgba(45, 27, 0, 0.15) !important;
            transform: translateY(-2px) !important;
        }

        .user-menu > a i {
            font-size: 13px;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2));
        }

        /* Dropdown - Clean & Simple */
        .dropdown {
            position: absolute;
            top: calc(100% + 5px);
            right: 0;
            width: 180px;
            background: linear-gradient(180deg, 
                rgba(255, 255, 255, 0.98) 0%, 
                rgba(253, 251, 249, 0.95) 100%);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(240, 230, 220, 0.8);
            box-shadow: 
                0 1px 0 0 rgba(255, 255, 255, 0.8) inset,
                0 4px 8px rgba(45, 27, 0, 0.08),
                0 8px 20px rgba(45, 27, 0, 0.1),
                0 16px 32px rgba(45, 27, 0, 0.08);
            padding: 6px;
            opacity: 0;
            visibility: hidden;
            display: flex;
            flex-direction: column;
            gap: 2px;
            transform: translateY(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0s linear 0.2s;
        }

        .user-menu:hover .dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0s linear 0s;
        }

        .dropdown li a {
            padding: 9px 12px;
            border-radius: 6px;
            color: #6B5B4F;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.15s ease;
            font-size: 13px;
            letter-spacing: 0.2px;
            border: 1px solid transparent;
        }

        .dropdown li a:hover {
            background: linear-gradient(135deg, 
                rgba(253, 246, 240, 0.9) 0%, 
                rgba(255, 248, 240, 0.7) 100%);
            color: var(--coffee-primary);
            border-color: rgba(240, 230, 220, 0.4);
            box-shadow: 
                0 1px 0 0 rgba(255, 255, 255, 0.5) inset,
                0 2px 6px rgba(212, 165, 116, 0.15);
            transform: translateX(2px);
        }

        .dropdown li a i {
            width: 16px;
            text-align: center;
            font-size: 13px;
            opacity: 0.7;
            transition: all 0.15s ease;
        }

        .dropdown li a:hover i {
            opacity: 1;
            transform: scale(1.1);
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
                    <a href="<?php echo BASE_URL; ?>"><i class="fas fa-store"></i> Coffee Shop</a>
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
                            <?php if (getCurrentUser()['role'] === 'admin') { ?>
                                <li><a href="<?php echo BASE_URL . 'index.php?page=admin/profile'; ?>"><i class="fas fa-user-circle"></i> Profile</a></li>
                            <?php } else { ?>
                                <li><a href="<?php echo BASE_URL . 'index.php?page=user/profile'; ?>"><i class="fas fa-user-circle"></i> Profile</a></li>
                            <?php } ?>
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
