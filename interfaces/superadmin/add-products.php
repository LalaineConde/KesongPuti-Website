<?php
require '../../connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validate session user
    if (!isset($_SESSION['admin_id']) && !isset($_SESSION['superadmin_id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    }

    // Determine who is logged in
    $owner_type = isset($_SESSION['superadmin_id']) ? 'superadmin' : 'admin';
    $admin_id = isset($_SESSION['superadmin_id']) ? $_SESSION['superadmin_id'] : $_SESSION['admin_id'];

    // Retrieve and sanitize form inputs
    $product_name = mysqli_real_escape_string($connection, $_POST['product_name']);
    $category_id = intval($_POST['category_id']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $price = floatval($_POST['price']);
    $stock_qty = intval($_POST['stock_qty']);
    $variation_size = isset($_POST['variation_size']) ? mysqli_real_escape_string($connection, $_POST['variation_size']) : null;

    // 🟢 Get the correct store_id and owner_id from the store table
    $store_query = mysqli_query($connection, "SELECT store_id, owner_id FROM store WHERE owner_id = '$admin_id' LIMIT 1");

    if (mysqli_num_rows($store_query) === 0) {
        echo json_encode(['success' => false, 'message' => 'Store not found for this account']);
        exit;
    }

    $store = mysqli_fetch_assoc($store_query);
    $store_id = $store['store_id'];
    $owner_id = $store['owner_id'];

    // 🖼️ Handle product image upload
    $image_path = null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/products/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = time() . '_' . basename($_FILES['product_image']['name']);
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            $image_path = 'uploads/products/' . $image_name; // relative path for DB
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
            exit;
        }
    }

    // 🧩 Insert product into the database
    $insert_query = "
        INSERT INTO products 
        (product_name, category_id, description, price, stock_qty, variation_size, product_image, owner_id, store_id, owner_type, date_added, date_updated)
        VALUES (
            '$product_name',
            '$category_id',
            '$description',
            '$price',
            '$stock_qty',
            " . ($variation_size ? "'$variation_size'" : "NULL") . ",
            " . ($image_path ? "'$image_path'" : "NULL") . ",
            '$owner_id',
            '$store_id',
            '$owner_type',
            NOW(),
            NOW()
        )
    ";

    if (mysqli_query($connection, $insert_query)) {
        echo json_encode(['success' => true, 'message' => 'Product added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($connection)]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
