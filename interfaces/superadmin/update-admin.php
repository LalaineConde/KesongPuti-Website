<?php
require '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $username = isset($_POST['username']) ? mysqli_real_escape_string($connection, $_POST['username']) : '';
    $store_name = isset($_POST['store_name']) ? mysqli_real_escape_string($connection, $_POST['store_name']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($connection, $_POST['email']) : '';

    if ($id > 0 && !empty($username) && !empty($email)) {
        // Check if email already exists for another admin
        $check_query = "SELECT admin_id FROM admins WHERE email='$email' AND admin_id!='$id'";
        $check_result = mysqli_query($connection, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            echo "Error: Email is already registered with another account.";
        } else {
            $sql = "UPDATE admins 
                    SET username='$username', store_name='$store_name', email='$email' 
                    WHERE admin_id='$id'";

            if (mysqli_query($connection, $sql)) {
                echo "Admin updated successfully!";
            } else {
                echo "Error: " . mysqli_error($connection);
            }
        }
    } else {
        echo "Error: Missing required fields.";
    }
}

mysqli_close($connection);
?>
