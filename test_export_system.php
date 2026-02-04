<?php
/**
 * Test Export System
 * Halaman untuk testing export functionality
 */

// Setup
include __DIR__ . '/config/database.php';
include __DIR__ . '/config/konstanta.php';
include __DIR__ . '/helpers/functions.php';
include __DIR__ . '/helpers/session.php';
include __DIR__ . '/models/Order.php';
include __DIR__ . '/export/ExportManager.php';
include __DIR__ . '/export/ExportHelper.php';

// Check session
if (!isset($_SESSION['user_id'])) {
    redirect('/index.php');
}

// Get user role
$user_id = $_SESSION['user_id'];
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

// Verify admin
if ($user_role !== ROLE_ADMIN) {
    die('Access Denied. Admin role required.');
}

$order_model = new Order($conn);

// Get order data
$orders_result = $order_model->getCompletedOrders();
$orders = [];
if ($orders_result && $orders_result->num_rows > 0) {
    while ($order = $orders_result->fetch_assoc()) {
        $orders[] = $order;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Export System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            color: #667eea;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .button-group.full {
            grid-template-columns: 1fr;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: #48bb78;
            color: white;
        }
        
        .btn-success:hover {
            background: #38a169;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(72, 187, 120, 0.4);
        }
        
        .btn-info {
            background: #4299e1;
            color: white;
        }
        
        .btn-info:hover {
            background: #3182ce;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66, 153, 225, 0.4);
        }
        
        .info-box {
            background: #e6f2ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #333;
        }
        
        .info-box strong {
            color: #667eea;
        }
        
        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        
        .stat-card {
            background: #f7fafc;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
        }
        
        .stat-label {
            color: #718096;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .stat-value {
            color: #2d3748;
            font-size: 24px;
            font-weight: 700;
        }
        
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        
        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Test Export System</h1>
        <p class="subtitle">Test export functionality untuk Orders dan Products</p>
        
        <div class="info-box">
            <strong>ℹ️ Informasi:</strong> Folder download otomatis dibuat di <code>downloads/YYYY/MM/</code> sesuai tanggal saat ini.
        </div>
        
        <div class="section">
            <div class="section-title">Export Orders (Pesanan)</div>
            <div class="button-group">
                <form method="get" action="/view/admin/reports/orders.php" style="width: 100%;">
                    <input type="hidden" name="export" value="excel">
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">
                        📄 Export Excel
                    </button>
                </form>
                <form method="get" action="/view/admin/reports/orders.php" style="width: 100%;">
                    <input type="hidden" name="export" value="pdf">
                    <button type="submit" class="btn btn-success" style="width: 100%;">
                        📋 Export PDF
                    </button>
                </form>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">Export Products (Produk)</div>
            <div class="button-group">
                <form method="get" action="/view/admin/reports/products.php" style="width: 100%;">
                    <input type="hidden" name="export" value="excel">
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">
                        📄 Export Excel
                    </button>
                </form>
                <form method="get" action="/view/admin/reports/products.php" style="width: 100%;">
                    <input type="hidden" name="export" value="pdf">
                    <button type="submit" class="btn btn-success" style="width: 100%;">
                        📋 Export PDF
                    </button>
                </form>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">Data Statistics</div>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-value"><?php echo count($orders); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">User Logged In</div>
                    <div class="stat-value">✓</div>
                </div>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">Download Folder Structure</div>
            <div class="info-box" style="background: #f0fdf4; border-left-color: #48bb78;">
                <strong>Expected Location:</strong><br>
                <code style="color: #22863a;">/downloads/<?php echo date('Y/m'); ?>/</code>
                <br><br>
                <strong>File Pattern:</strong><br>
                <code style="color: #22863a;">Laporan_[Type]_YYYY-MM-DD_H-i-s.[ext]</code>
            </div>
        </div>
        
        <a href="/index.php" class="back-btn">← Kembali ke Home</a>
    </div>
</body>
</html>
