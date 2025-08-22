<?php
require '../../connection.php';
session_start();

if (isset($_SESSION['toast_message'])) {
    $toast_message = $_SESSION['toast_message'];
    unset($_SESSION['toast_message']); // clear it immediately
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM products WHERE product_id = $id";
    if (mysqli_query($connection, $sql)) {
        $_SESSION['toast_message'] = "Product deleted successfully!";
    } else {
        $_SESSION['toast_message'] = "Error deleting product: " . mysqli_error($connection);
    }
}

if ($_SESSION['role'] === 'admin') {
    $owner_id = $_SESSION['admin_id'];
    $delete = "DELETE FROM products WHERE product_id='$id' AND owner_id='$owner_id'";
} else {
    $delete = "DELETE FROM products WHERE product_id='$id'";
}

// Redirect back to products page
header("Location: products.php");
exit();
?>
