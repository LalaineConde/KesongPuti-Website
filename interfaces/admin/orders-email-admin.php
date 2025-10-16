<?php

ini_set('display_errors', 0); 
error_reporting(0);
ob_start(); 

require '../../connection.php';
header('Content-Type: application/json');
session_start();

$response = ['success' => false, 'error' => ''];

// Read JSON input safely
$data = json_decode(file_get_contents('php://input'), true);
$order_id = intval($data['order_id'] ?? 0);
$new_status = trim($data['new_status'] ?? '');

if (!$order_id || !$new_status) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

try {
    $stmt = $connection->prepare("UPDATE orders SET order_status = ? WHERE o_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
    } else {
        $response['error'] = 'No changes made (status already same)';
    }

    $stmt->close();
} catch (Exception $e) {
    $response['error'] = 'Database error: ' . $e->getMessage();
}

ob_end_clean(); // Clear output buffer
echo json_encode($response);
exit;
?>