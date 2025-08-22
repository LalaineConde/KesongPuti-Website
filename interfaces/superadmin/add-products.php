<?php
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($connection, $_POST['product_name']);
    $category = mysqli_real_escape_string($connection, $_POST['category']);
    $price = (float) $_POST['price'];

    // Handle image upload
    $image = $_FILES['product_image']['name'];
    $target = "../../uploads/" . basename($image);

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
        $query = "INSERT INTO products (name, category, price, product_image) 
                  VALUES ('$name', '$category', '$price', '$image')";
        if (mysqli_query($connection, $query)) {
            $toast_message = "Product added successfully!";
        } else {
            $toast_message = "Database error: " . mysqli_error($connection);
        }
    } else {
        $toast_message = "Failed to upload image.";
    }

    mysqli_close($connection);

    // Redirect back to products.php with message
    header("Location: products.php?msg=" . urlencode($toast_message));
    exit;
}
?>
