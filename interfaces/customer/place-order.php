<?php
require '../../connection.php';
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'invalid_method']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) { 
    echo json_encode(['ok'=>false,'error'=>'invalid_json','raw'=>$raw]); 
    exit; 
}

$customer = $data['customer'] ?? [];
$items = $data['items'] ?? [];
$paymentMethod = trim($data['payment_method'] ?? 'cash');
$paymentMethodId = intval($data['payment_method_id'] ?? 0);
$orderType = in_array($data['order_type'] ?? '', ['delivery','pickup']) ? $data['order_type'] : 'delivery';
$deliveryAddress = trim($data['delivery_address'] ?? '');
$recipient = trim($data['recipient'] ?? '');
$storeName = trim($data['store_name'] ?? '');

if (!$items || count($items) === 0) {
    echo json_encode(['ok'=>false,'error'=>'empty_cart']);
    exit;
}

$firstName = trim($customer['first_name'] ?? '');
$lastName  = trim($customer['last_name'] ?? '');
$email     = trim($customer['email'] ?? '');
$phone     = trim($customer['phone'] ?? '');

if (!$firstName || !$lastName || !$email || !$phone) {
    echo json_encode(['ok'=>false,'error'=>'missing_customer_info']);
    exit;
}

if (!$recipient && $storeName) {
    $stmt = mysqli_prepare($connection, 'SELECT recipient FROM store WHERE store_name = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $storeName);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $recipient = $row['recipient'];
    }
    mysqli_stmt_close($stmt);
}

$ownerType = null; $ownerId = null;
if ($recipient && preg_match('/^(admin|super)_?(\d+)$/', $recipient, $m)) {
    $ownerType = $m[1] === 'admin' ? 'admin' : 'superadmin';
    $ownerId   = intval($m[2]);
}

// ---- Save or find customer ----
$customerId = null;
$fullName   = $firstName . ' ' . $lastName;

// Check if customer already exists
$checkCustomer = mysqli_prepare($connection, 'SELECT `c.id` FROM customers WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($checkCustomer, 's', $email);
mysqli_stmt_execute($checkCustomer);
$result = mysqli_stmt_get_result($checkCustomer);
if ($existing = mysqli_fetch_assoc($result)) {
    $customerId = $existing['c.id'];
}
mysqli_stmt_close($checkCustomer);

if (!$customerId) {
    $insertCustomer = mysqli_prepare(
        $connection,
        'INSERT INTO customers (fullname, phone_number, email, address) VALUES (?, ?, ?, ?)'
    );
    mysqli_stmt_bind_param($insertCustomer, 'ssss', $fullName, $phone, $email, $deliveryAddress);
    if (mysqli_stmt_execute($insertCustomer)) {
        $customerId = mysqli_insert_id($connection);
    }
    mysqli_stmt_close($insertCustomer);
}

if (!$customerId) {
    echo json_encode(['ok'=>false,'error'=>'customer_save_failed']);
    exit;
}

// ---- Compute totals ----
$total = 0.0;
foreach ($items as $it) {
    $qty = intval($it['qty'] ?? $it['quantity'] ?? 1);
    $price = floatval($it['price'] ?? 0.0);
    $total += $qty * $price;
}

// ---- Insert order ----
$orderSql = 'INSERT INTO orders (c_id, handled_by, total_amount, payment_status, order_status, delivery_address, owner_id) 
             VALUES (?, ?, ?, ?, ?, ?, ?)';
$stmt = mysqli_prepare($connection, $orderSql);

$paymentStatus = ($paymentMethod === 'cash') ? 'pending' : 'pending';
$orderStatus = 'pending';

mysqli_stmt_bind_param(
    $stmt,
    'isdsssi',
    $customerId, $recipient, $total, $paymentStatus, $orderStatus,
    $deliveryAddress, $ownerId
);

if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['ok'=>false,'error'=>'order_insert_failed','mysql_error'=>mysqli_stmt_error($stmt)]);
    exit;
}

$orderId = mysqli_insert_id($connection);
mysqli_stmt_close($stmt);

// ---- Insert items ----
$itemSql = 'INSERT INTO order_items (o_id, product_id, quantity, price_at_purchase) VALUES (?,?,?,?)';
$istmt = mysqli_prepare($connection, $itemSql);

foreach ($items as $it) {
    $productId = intval($it['id'] ?? $it['product_id'] ?? 0);
    $qty = intval($it['qty'] ?? $it['quantity'] ?? 1);
    $price = floatval($it['price'] ?? 0.0);
    if ($productId <= 0 || $qty <= 0) continue;
    mysqli_stmt_bind_param($istmt, 'iiid', $orderId, $productId, $qty, $price);
    if (!mysqli_stmt_execute($istmt)) {
        echo json_encode(['ok'=>false,'error'=>'item_insert_failed','mysql_error'=>mysqli_stmt_error($istmt)]);
        exit;
    }
}
mysqli_stmt_close($istmt);

// ---- Redirect ----
$redirect = (strpos($recipient, 'admin_') === 0)
    ? '../../interfaces/admin/orders.php'
    : '../../interfaces/superadmin/orders.php';

echo json_encode(['ok'=>true,'order_id'=>$orderId,'redirect'=>$redirect]);
?>
