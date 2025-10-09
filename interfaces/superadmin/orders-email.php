<?php
require '../../connection.php';
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);

    $order_id = intval($data['order_id'] ?? 0);
    $new_status = trim($data['new_status'] ?? '');

    if (!$order_id || !$new_status) {
        echo json_encode(['success' => false, 'error' => 'Missing required fields']);
        exit;
    }

    // Update the status in the database
    $update_query = "UPDATE orders SET order_status = ? WHERE o_id = ?";
    $stmt = mysqli_prepare($connection, $update_query);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $order_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo json_encode(['success' => $success]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>