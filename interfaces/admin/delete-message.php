<?php
require '../../connection.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM inbox_messages WHERE inbox_id = $id";
    if (mysqli_query($connection, $sql)) {
        echo "success";
    } else {
        echo "error: " . mysqli_error($connection);
    }
}
exit; 
?>