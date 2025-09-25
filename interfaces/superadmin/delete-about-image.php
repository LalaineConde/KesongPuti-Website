<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../../connection.php';

if (!empty($_POST['id']) && !empty($_POST['table'])) {
    $id = intval($_POST['id']);
    $table = $_POST['table'];

    // Whitelist allowed tables
    $allowedTables = ['home_about_slider', 'about_images'];
    if (!in_array($table, $allowedTables)) {
        echo "error";
        exit;
    }

    // Get the image path
    $stmt = mysqli_prepare($connection, "SELECT image_path FROM $table WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row) {
        $file = "../../" . $row['image_path'];

        // Only delete files in 'uploads/uploaded/' to avoid deleting default assets
        if (strpos($file, '/uploads/uploaded/') !== false && file_exists($file)) {
            unlink($file);
        }

        // Delete the database record
        $stmtDel = mysqli_prepare($connection, "DELETE FROM $table WHERE id = ?");
        mysqli_stmt_bind_param($stmtDel, 'i', $id);
        mysqli_stmt_execute($stmtDel);
        $affected = mysqli_stmt_affected_rows($stmtDel);
        mysqli_stmt_close($stmtDel);

        if ($affected > 0) {
            echo "success";
            exit;
        }
    }
}

echo "error";
