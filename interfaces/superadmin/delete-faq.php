<?php
require '../../connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM faqs WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: faq.php?msg=deleted");
        exit();
    } else {
        echo "Error deleting FAQ: " . $connection->error;
    }
} else {
    echo "Invalid request.";
}
