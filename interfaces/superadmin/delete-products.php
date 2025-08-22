<?php
require '../../connection.php';
session_start();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM products WHERE product_id = $id";
    if (mysqli_query($connection, $sql)) {
        $_SESSION['toast_message'] = "Product deleted successfully!";
    } else {
        $_SESSION['toast_message'] = "Error deleting product: " . mysqli_error($connection);
    }
}

// Redirect back to products page
header("Location: products.php");
exit();
?>
