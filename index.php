<?php
/**
 * Index.php - Entry Point Aplikasi
 * File utama yang menangani routing dan loading halaman
 */

// Include konfigurasi dan helper
include __DIR__ . '/config/database.php';
include __DIR__ . '/config/konstanta.php';
include __DIR__ . '/helpers/functions.php';
include __DIR__ . '/helpers/session.php';

// Include models
include __DIR__ . '/models/User.php';
include __DIR__ . '/models/Category.php';
include __DIR__ . '/models/Product.php';
include __DIR__ . '/models/Order.php';

// Include controllers
include __DIR__ . '/controller/AuthController.php';

// Routing
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Sanitasi input
$page = preg_replace('/[^a-zA-Z0-9\/_-]/', '', $page);

// Load page berdasarkan request
switch ($page) {
    // Public Pages
    case 'login':
        include __DIR__ . '/view/auth/login.php';
        break;
    
    case 'register':
        include __DIR__ . '/view/auth/register.php';
        break;
    
    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;
    
    // Admin Pages
    case 'admin/dashboard':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/dashboard.php';
        break;
    
    case 'admin/products':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/products/index.php';
        break;
    
    case 'admin/products/create':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/products/create.php';
        break;
    
    case 'admin/products/edit':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/products/edit.php';
        break;
    
    case 'admin/categories':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/categories/index.php';
        break;
    
    case 'admin/categories/create':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/categories/create.php';
        break;
    
    case 'admin/categories/edit':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/categories/edit.php';
        break;
    
    case 'admin/users':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/users/index.php';
        break;
    
    case 'admin/users/create':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/users/create.php';
        break;
    
    case 'admin/users/edit':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/users/edit.php';
        break;
    
    case 'admin/orders':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/orders/index.php';
        break;
    
    case 'admin/orders/detail':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/orders/detail.php';
        break;
    
    case 'admin/reports':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/reports/index.php';
        break;
    
    case 'admin/reports/orders':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/reports/orders.php';
        break;
    
    case 'admin/reports/products':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/reports/products.php';
        break;
    
    case 'admin/profile':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/profile.php';
        break;
    
    case 'admin/export':
        requireRole(ROLE_ADMIN);
        include __DIR__ . '/view/admin/export.php';
        break;
    
    // User Pages
    case 'user/dashboard':
        requireRole(ROLE_USER);
        include __DIR__ . '/view/user/dashboard.php';
        break;
    
    case 'user/menu':
        requireRole(ROLE_USER);
        include __DIR__ . '/view/user/menu.php';
        break;
    
    case 'user/cart':
        requireRole(ROLE_USER);
        include __DIR__ . '/view/user/cart.php';
        break;
    
    case 'user/checkout':
        requireRole(ROLE_USER);
        include __DIR__ . '/view/user/checkout.php';
        break;
    
    case 'user/orders':
        requireRole(ROLE_USER);
        include __DIR__ . '/view/user/orders.php';
        break;
    
    case 'user/orders/detail':
        requireRole(ROLE_USER);
        include __DIR__ . '/view/user/order-detail.php';
        break;
    
    case 'user/profile':
        requireRole(ROLE_USER);
        include __DIR__ . '/view/user/profile.php';
        break;
    
    // Default
    default:
        include __DIR__ . '/view/home.php';
        break;
}
