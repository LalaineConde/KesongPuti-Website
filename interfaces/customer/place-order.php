<?php
require '../../connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'error' => 'invalid_method']);
    exit;
}

$data = json_decode($_POST['payload'] ?? '', true);
if (!is_array($data)) {
    echo json_encode(['ok'=>false,'error'=>'invalid_json']);
    exit;
}

$customer = $data['customer'] ?? [];
$items = $data['items'] ?? [];
$paymentMethod = trim($data['payment_method'] ?? 'cash');
$orderType = in_array($data['order_type'] ?? '', ['delivery','pickup']) ? $data['order_type'] : 'delivery';
$deliveryAddress = trim($data['delivery_address'] ?? '');
$recipient = trim($data['recipient'] ?? '');
$storeName = trim($data['store_name'] ?? '');

if (!$items) {
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

// Resolve recipient -> ownerId
$ownerId = null;
if ($recipient && preg_match('/^(admin|super)_?(\d+)$/', $recipient, $m)) {
    $ownerId = intval($m[2]);
}

// ---- Save or find customer ----
$customerId = null;
$fullName = "$firstName $lastName";

// check existing
$stmt = mysqli_prepare($connection,'SELECT id FROM customers WHERE email=? LIMIT 1');
mysqli_stmt_bind_param($stmt,'s',$email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($res)) $customerId = $row['id'];
mysqli_stmt_close($stmt);

if (!$customerId) {
    $stmt = mysqli_prepare($connection,'INSERT INTO customers (fullname, phone_number, email, address) VALUES (?,?,?,?)');
    mysqli_stmt_bind_param($stmt,'ssss',$fullName,$phone,$email,$deliveryAddress);
    if (mysqli_stmt_execute($stmt)) $customerId = mysqli_insert_id($connection);
    mysqli_stmt_close($stmt);
}

if (!$customerId) { echo json_encode(['ok'=>false,'error'=>'customer_save_failed']); exit; }

// ---- Compute total ----
$total = 0.0;
foreach ($items as $it) {
    $qty = intval($it['qty'] ?? $it['quantity'] ?? 1);
    $price = floatval($it['price'] ?? 0);
    $total += $qty * $price;
}

// ---- Handle proof upload ----
$proofFile = null;
$uploadDir = '../../uploads/payment_proofs/';
if (!is_dir($uploadDir)) mkdir($uploadDir,0777,true);

foreach (['ewallet_proof','bank_proof'] as $field) {
    if (isset($_FILES[$field]) && $_FILES[$field]['error']===UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES[$field]['name'],PATHINFO_EXTENSION);
        $filename = uniqid('proof_') . '.' . strtolower($ext);
        if (move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir.$filename)) {
            $proofFile = $filename;
        }
    }
}

// ---- Insert order ----
$orderSql = 'INSERT INTO orders (c_id, owner_id, total_amount, payment_status, order_status, delivery_address, payment_method, proof_of_payment) VALUES (?,?,?,?,?,?,?,?)';
$stmt = mysqli_prepare($connection,$orderSql);
$paymentStatus = 'pending';
$orderStatus = 'pending';
mysqli_stmt_bind_param($stmt,'iddsssss',$customerId,$ownerId,$total,$paymentStatus,$orderStatus,$deliveryAddress,$paymentMethod,$proofFile);
if (!mysqli_stmt_execute($stmt)) {
    echo json_encode(['ok'=>false,'error'=>'order_insert_failed','mysql_error'=>mysqli_stmt_error($stmt)]);
    exit;
}
$orderId = mysqli_insert_id($connection);
mysqli_stmt_close($stmt);

// ---- Insert items ----
$stmt = mysqli_prepare($connection,'INSERT INTO order_items (o_id, product_id, quantity, price_at_purchase) VALUES (?,?,?,?)');
foreach ($items as $it) {
    $productId = intval($it['id'] ?? $it['product_id'] ?? 0);
    $qty = intval($it['qty'] ?? $it['quantity'] ?? 1);
    $price = floatval($it['price'] ?? 0);
    if ($productId>0 && $qty>0) {
        mysqli_stmt_bind_param($stmt,'iiid',$orderId,$productId,$qty,$price);
        mysqli_stmt_execute($stmt);
    }
}
mysqli_stmt_close($stmt);

$redirect = (strpos($recipient,'admin_')===0)? '../../interfaces/admin/orders.php':'../../interfaces/superadmin/orders.php';
echo json_encode(['ok'=>true,'order_id'=>$orderId,'redirect'=>$redirect]);
?>
