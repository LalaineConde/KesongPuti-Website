<?php
require '../../connection.php';
header('Content-Type: application/json');

// Get current admin info from session
$current_admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

if (!$current_admin_id) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if (!$order_id) {
    echo json_encode(['success' => false, 'error' => 'Invalid order ID']);
    exit;
}

// Fetch order details with customer information (filter by numeric owner_id)
$order_query = "SELECT o.*, c.fullname, c.phone_number, c.email, c.address
                FROM orders o 
                LEFT JOIN customers c ON o.c_id = c.`c.id`
                WHERE o.o_id = ? AND o.owner_id = ?";

$stmt = mysqli_prepare($connection, $order_query);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
    exit;
}

mysqli_stmt_bind_param($stmt, 'ii', $order_id, $current_admin_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$order = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$order) {
    echo json_encode(['success' => false, 'error' => 'Order not found']);
    exit;
}

// Fetch order items
$items_query = "SELECT oi.*, p.product_name 
                FROM order_items oi 
                LEFT JOIN products p ON oi.product_id = p.product_id 
                WHERE oi.o_id = ?";

$stmt = mysqli_prepare($connection, $items_query);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
    exit;
}

mysqli_stmt_bind_param($stmt, 'i', $order_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}
mysqli_stmt_close($stmt);

$order['items'] = $items;

// Add payment method information (defaulting to cash for now)
$order['payment_method'] = 'Cash on Delivery';

echo json_encode(['success' => true, 'order' => $order]);
?>
