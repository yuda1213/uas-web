<?php
/**
 * Admin Products Sales Report Page
 */

// Include konfigurasi dan koneksi database
if (!isset($conn)) {
    include __DIR__ . '/../../../config/database.php';
}
include __DIR__ . '/../../../config/konstanta.php';
include __DIR__ . '/../../../helpers/functions.php';
include __DIR__ . '/../../../helpers/session.php';
include __DIR__ . '/../../../models/Order.php';

requireRole(ROLE_ADMIN);

// Check if export requested
$export_type = isset($_GET['export']) ? sanitize($_GET['export']) : null;

if ($export_type) {
    // Include export functions only when exporting
    include __DIR__ . '/../../../export/ExportManager.php';
    include __DIR__ . '/../../../export/ExportHelper.php';
    
    $order_model = new Order($conn);
    
    // Get product sales report
    $products_result = $order_model->getProductSalesReport();
    $products = [];
    if ($products_result && $products_result->num_rows > 0) {
        while ($product = $products_result->fetch_assoc()) {
            $products[] = $product;
        }
    }
    
    if ($export_type === 'pdf') {
        // Export PDF
        exportProductsToPDFProper($products);
    } else {
        // Export Excel
        exportProductsToExcelProper($products);
    }
    exit;
}

// Get product sales report for display
$order_model = new Order($conn);
$products_result = $order_model->getProductSalesReport();
$products = [];
if ($products_result && $products_result->num_rows > 0) {
    while ($product = $products_result->fetch_assoc()) {
        $products[] = $product;
    }
}

// Set page title
$page_title = 'Laporan Penjualan Produk';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Coffee Shop</title>
    <link rel="stylesheet" href="/assets/style_professional.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .report-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #1e40af;
        }
        .report-header h1 {
            color: #1e40af;
            margin: 0;
        }
        .export-buttons {
            display: flex;
            gap: 10px;
        }
        .btn-export {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .btn-export-pdf {
            background: #dc2626;
            color: white;
        }
        .btn-export-pdf:hover {
            background: #991b1b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.3);
        }
        .btn-export-excel {
            background: #059669;
            color: white;
        }
        .btn-export-excel:hover {
            background: #047857;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(5, 150, 105, 0.3);
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .report-table thead {
            background: #1e40af;
            color: white;
        }
        .report-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
        }
        .report-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .report-table tbody tr:hover {
            background: #f0f9ff;
        }
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-primary {
            background: #dbeafe;
            color: #1e40af;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #1e40af;
            text-decoration: none;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .back-link:hover {
            color: #1e3a8a;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <a href="/index.php?page=admin/dashboard" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        
        <div class="report-header">
            <h1><i class="fas fa-chart-bar"></i> <?php echo $page_title; ?></h1>
            <div class="export-buttons">
                <a href="?export=pdf" class="btn-export btn-export-pdf">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="?export=excel" class="btn-export btn-export-excel">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>
        
        <div style="margin-bottom: 20px; color: #666; font-size: 14px;">
            <p><strong>Total Produk Terjual:</strong> <?php echo count($products); ?> produk</p>
            <p><strong>Generated:</strong> <?php echo date('d-m-Y H:i:s'); ?></p>
        </div>
        
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th style="text-align: right;">Jumlah Terjual</th>
                    <th style="text-align: right;">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $grand_total_qty = 0;
                $grand_total_sales = 0;
                
                if (count($products) > 0) {
                    foreach ($products as $product) {
                        $grand_total_qty += $product['total_jumlah'];
                        $grand_total_sales += $product['total_penjualan'];
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($product['nama_produk'] ?? ''); ?></strong></td>
                            <td>
                                <span class="badge badge-primary">
                                    <?php echo htmlspecialchars($product['nama_kategori'] ?? ''); ?>
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: 600;"><?php echo number_format($product['total_jumlah'] ?? 0, 0, ',', '.'); ?></td>
                            <td style="text-align: right; font-weight: 600;">Rp <?php echo number_format($product['total_penjualan'] ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px; color: #999;">
                            <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                            Tidak ada data penjualan produk untuk ditampilkan
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr style="background: #f3f4f6; font-weight: 600;">
                    <td colspan="3" style="text-align: right;">TOTAL:</td>
                    <td style="text-align: right;">
                        <?php echo number_format($grand_total_qty, 0, ',', '.'); ?>
                    </td>
                    <td style="text-align: right;">
                        Rp <?php echo number_format($grand_total_sales, 0, ',', '.'); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
