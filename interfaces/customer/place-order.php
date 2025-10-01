<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../../connection.php';
header('Content-Type: application/json');

mysqli_report(MYSQLI_REPORT_OFF);

try {
    // Customer info
    $fullname     = trim($_POST['fullname'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone'] ?? '');

    $owner_id       = intval($_POST['owner_id'] ?? 0);
    $payment_method = trim($_POST['payment_method'] ?? '');
    $order_type     = trim($_POST['delivery_type'] ?? ''); 
    $total_amount   = floatval($_POST['total'] ?? 0);      
    $items          = json_decode($_POST['cart'] ?? '[]', true); 

    // --- Validation (common fields)
    if (!$fullname || !$email || !$phone_number || !$payment_method || empty($items)) {
        echo json_encode(['status'=>'error','message'=>'Missing required fields (customer/payment/items)']);
        exit;
    }

    // --- Validate & build address / pickup details
    $address = '';
    if ($order_type === 'delivery') {
        if (empty($_POST['house']) || empty($_POST['street']) || empty($_POST['barangay']) ||
            empty($_POST['city']) || empty($_POST['province']) || empty($_POST['zip'])) {
            echo json_encode(['status'=>'error','message'=>'Missing required fields (delivery address)']);
            exit;
        }

        $address = $_POST['house'] . ', ' . $_POST['street'] . ', ' . $_POST['barangay'] . ', ' .
                   $_POST['city'] . ', ' . $_POST['province'] . ' ' . $_POST['zip'];

    } elseif ($order_type === 'pickup') {
        if (empty($_POST['pickup-date']) || empty($_POST['pickup-time'])) {
            echo json_encode(['status'=>'error','message'=>'Missing required fields (pickup details)']);
            exit;
        }

        $address = "Pickup on " . $_POST['pickup-date'] . " at " . $_POST['pickup-time'];
    } else {
        echo json_encode(['status'=>'error','message'=>'Invalid order type']);
        exit;
    }

    // --- Insert customer
    $stmt = mysqli_prepare($connection, 
        "INSERT INTO customers (fullname, phone_number, email, address) VALUES (?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "ssss", $fullname, $phone_number, $email, $address);
    mysqli_stmt_execute($stmt);
    $c_id = mysqli_insert_id($connection);
    mysqli_stmt_close($stmt);

    // --- Handle proof of payment upload
    $payment_proof = null;
    if (!empty($_FILES['payment_proof']['name'])) {
        $targetDir = "../../uploads/payment_proofs/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif','webp'];

        if (in_array(strtolower($ext), $allowed)) {
            $fileName = time() . "_" . uniqid() . "." . $ext;
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $targetFile)) {
                $payment_proof = $fileName;
            }
        }
    }

    // --- Insert order
    $stmt = mysqli_prepare($connection, 
        "INSERT INTO orders (c_id, total_amount, payment_method, proof_of_payment, delivery_address, owner_id, order_type) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, "idsssis", 
        $c_id, $total_amount, $payment_method, $payment_proof, $address, $owner_id, $order_type
    );
    mysqli_stmt_execute($stmt);
    $order_id = mysqli_insert_id($connection);
    mysqli_stmt_close($stmt);

    // --- Insert items
    $stmt = mysqli_prepare($connection, 
        "INSERT INTO order_items (o_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)"
    );
    foreach ($items as $item) {
        $product_id = intval($item['product_id'] ?? 0);
        $quantity   = intval($item['qty'] ?? $item['quantity'] ?? 1);
        $price      = floatval($item['price'] ?? 0);
        mysqli_stmt_bind_param($stmt, "iiid", $order_id, $product_id, $quantity, $price);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);

    echo json_encode(['status'=>'success','order_id'=>$order_id]);

} catch (Exception $e) {
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
?>
