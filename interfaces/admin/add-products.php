<?php
require '../../connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 🔒 Ensure only Admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    }

    $admin_id = intval($_SESSION['admin_id']);
    $owner_type = 'admin'; // Fixed for admin accounts

    // 🧩 Sanitize and validate inputs
    $product_name = mysqli_real_escape_string($connection, $_POST['product_name']);
    $category_id = intval($_POST['category_id']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $price = floatval($_POST['price']);
    $stock_qty = intval($_POST['stock_qty']);
    $variation_size = !empty($_POST['variation_size']) ? mysqli_real_escape_string($connection, $_POST['variation_size']) : null;

    if (empty($product_name) || $category_id <= 0 || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Please complete all required fields.']);
        exit;
    }

    // 🏪 Fetch correct store_id and owner_id from store table
    $store_stmt = $connection->prepare("SELECT store_id, owner_id FROM store WHERE owner_id = ? LIMIT 1");
    $store_stmt->bind_param("i", $admin_id);
    $store_stmt->execute();
    $store_result = $store_stmt->get_result();

    if ($store_result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'No store found for this admin.']);
        exit;
    }

    $store = $store_result->fetch_assoc();
    $store_id = $store['store_id'];
    $owner_id = $store['owner_id']; // ✅ Correctly fetched from store table

    // 🖼️ Handle image upload
    $image_path = null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/products/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $image_name = uniqid('prod_') . '_' . basename($_FILES['product_image']['name']);
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            $image_path = 'uploads/products/' . $image_name;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload product image.']);
            exit;
        }
    }

    // 🧾 Insert product using prepared statement
    $stmt = $connection->prepare("
        INSERT INTO products (
            product_name, category_id, description, price, stock_qty, variation_size,
            product_image, owner_id, owner_type, store_id, date_added, date_updated
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->bind_param(
        "sssdissssi",
        $product_name,
        $category_id,
        $description,
        $price,
        $stock_qty,
        $variation_size,
        $image_path,
        $owner_id,
        $owner_type,
        $store_id
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => '✅ Product added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
