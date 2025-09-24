<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../../connection.php';

if (!empty($_POST['id'])) {
    $id = intval($_POST['id']);

    // Get image path first
    $result = mysqli_query($connection, "SELECT image_path FROM home_about_slider WHERE id=$id LIMIT 1");

    if ($row = mysqli_fetch_assoc($result)) {
        $file = "../../" . $row['image_path'];

        // Delete file from server if exists
        if (file_exists($file)) {
            unlink($file);
        }

        // Delete DB row
        $delete = mysqli_query($connection, "DELETE FROM home_about_slider WHERE id=$id");

        if ($delete) {
            echo "success";
            exit;
        }
    }
}

echo "error";
