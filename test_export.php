<?php
// Test export functionality
include __DIR__ . '/config/database.php';
include __DIR__ . '/config/konstanta.php';
include __DIR__ . '/helpers/functions.php';
include __DIR__ . '/helpers/session.php';
include __DIR__ . '/models/Order.php';
include __DIR__ . '/export/ExcelExport.php';

echo "Test Export - Debug Info<br>";

// Check koneksi
if ($conn->connect_error) {
    die("DB Connection Error: " . $conn->connect_error);
}
echo "✓ Database Connected<br>";

// Check orders table
$result = $conn->query("SELECT COUNT(*) as total FROM orders");
if ($result) {
    $row = $result->fetch_assoc();
    echo "✓ Orders Table: " . $row['total'] . " records<br>";
} else {
    echo "✗ Orders Query Error: " . $conn->error . "<br>";
}

// Test Order Model
$order_model = new Order($conn);
echo "✓ Order Model Initialized<br>";

// Get orders
$orders_result = $order_model->getOrdersWithPagination(10, 0, '', 'selesai');
if ($orders_result) {
    echo "✓ Query Executed - Rows: " . $orders_result->num_rows . "<br>";
    
    $orders = [];
    if ($orders_result->num_rows > 0) {
        while ($order = $orders_result->fetch_assoc()) {
            $orders[] = $order;
        }
    }
    echo "✓ Orders Array: " . count($orders) . " orders<br>";
    
    // Test exportOrdersToExcel function
    if (function_exists('exportOrdersToExcel')) {
        echo "✓ exportOrdersToExcel function exists<br>";
        // Uncomment below to actually test export
        // exportOrdersToExcel($orders);
    } else {
        echo "✗ exportOrdersToExcel function NOT found<br>";
    }
} else {
    echo "✗ Query Error: " . $conn->error . "<br>";
}

echo "<hr>";
echo "<a href='index.php?page=admin/reports'>Back to Reports</a>";
?>
