<?php
require '../../connection.php'; 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = mysqli_real_escape_string($connection, $_POST['first_name']);
    $lastName  = mysqli_real_escape_string($connection, $_POST['last_name']);
    $email     = mysqli_real_escape_string($connection, $_POST['email']);
    $contact   = mysqli_real_escape_string($connection, $_POST['contact']);
    $message   = mysqli_real_escape_string($connection, $_POST['message']);
    $recipient = mysqli_real_escape_string($connection, $_POST['recipient']);

    // Combine first and last name into "name"
    $name = trim($firstName . " " . $lastName);

    $sql = "INSERT INTO inbox_messages (name, email, contact, message, recipient) 
            VALUES ('$name', '$email', '$contact', '$message', '$recipient')";

    if (mysqli_query($connection, $sql)) {
        $_SESSION['toast_message'] = "Message submitted successfully!";
    } else {
        $_SESSION['toast_message'] = "Error: " . mysqli_error($connection);
    }

    mysqli_close($connection);

    // Redirect back
    $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: $redirect");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>contact Us</title>

     <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../css/admin.css"/>

</head>
<body>
    

  

<!-- FUNCTIONS -->

<!-- FUNCTIONS -->
</body>
</html>


