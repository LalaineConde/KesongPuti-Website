<?php
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $store_name = $_POST['store_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    if ($id && $store_name && $email && $phone && $address) {
        $stmt = $connection->prepare("UPDATE store_contacts SET store_name=?, email=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("ssssi", $store_name, $email, $phone, $address, $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Database error: " . $connection->error;
        }
    } else {
        echo "Invalid input";
    }
} else {
    echo "Invalid request";
}
?>
