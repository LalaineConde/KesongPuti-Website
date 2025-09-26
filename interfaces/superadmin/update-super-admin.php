<?php
$page_title = 'Create Admin | Kesong Puti';
require '../../connection.php';

$toast_message = ''; // Initialize variable for toast message


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_super'])) {
    $id = mysqli_real_escape_string($connection, $_POST['id']);
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $store_name = mysqli_real_escape_string($connection, $_POST['store_name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);

    $query = "UPDATE super_admin 
              SET username='$username', store_name='$store_name', email='$email' 
              WHERE super_id='$id'";

    if (mysqli_query($connection, $query)) {
        echo "Super Admin updated successfully!";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
    exit; // stop here so it doesn’t fall into the create admin logic
}

mysqli_close($connection);
?>