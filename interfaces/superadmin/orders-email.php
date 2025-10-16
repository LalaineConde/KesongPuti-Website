<?php
require '../../connection.php';
header('Content-Type: application/json');

try {
    // Decode incoming JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    $order_id = intval($data['order_id'] ?? 0);
    $new_status = trim($data['new_status'] ?? '');

    // Validate inputs
    if (!$order_id || $new_status === '') {
        echo json_encode([
            'success' => false,
            'error' => 'Missing order ID or status.'
        ]);
        exit;
    }

    // Prepare and execute update query
    $update_query = "UPDATE orders SET order_status = ? WHERE o_id = ?";
    $stmt = mysqli_prepare($connection, $update_query);

    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'error' => 'Database prepare failed: ' . mysqli_error($connection)
        ]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "si", $new_status, $order_id);
    $exec = mysqli_stmt_execute($stmt);

    if ($exec) {
        // Check if any rows were affected (to verify that the order exists)
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'No matching order found or status unchanged.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Query execution failed: ' . mysqli_stmt_error($stmt)
        ]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
?>