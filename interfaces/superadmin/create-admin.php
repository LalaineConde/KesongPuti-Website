<?php
$page_title = 'Create Admin | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$username = isset($_POST['username']) ? mysqli_real_escape_string($connection, $_POST['username']) : '';
$email = isset($_POST['email']) ? mysqli_real_escape_string($connection, $_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
$loggedInAdminId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;


    // Check if passwords match
    if ($password !== $confirm_password) {
        $toast_message = "Passwords do not match.";
       
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/', $password)) {
        $toast_message = "Password must be at least 6 characters and contain a lowercase letter, uppercase letter, number, and special character.";

      } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $email_check_query = "SELECT * FROM admins WHERE email='$email'";
        $result = mysqli_query($connection, $email_check_query);
        if (mysqli_num_rows($result) > 0) {
            $toast_message = "Email is already registered.";

        } else {
            // Insert the new admin into the database
            $sql = "INSERT INTO admins (username, email, password)
                    VALUES ('$username', '$email', '$hashed_password')";

            if (mysqli_query($connection, $sql)) {
                $toast_message = "Admin added successfully!";
                

            } else {
                $toast_message = "Error: " . mysqli_error($connection);
            }
        }
    }
}

// Fetch all admins
$admins_query = "SELECT admin_id, username, email FROM admins";
$admins_result = mysqli_query($connection, $admins_query);

// Close the connection
mysqli_close($connection);


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin | Kesong Puti</title>

    <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../css/admin.css"/>

</head>
<body>



<!-- CREATE ADMIN -->

  <div class="main-content">
       <form id="loginForm" method="post">
      <div class="box" id="create-admin-content">
        <h1>Create Admin</h1>

        <div class="form-group">
          <label for="adminName">Name</label>
          <input type="text" id="adminName" name="username" placeholder="Enter admin username" />
        </div>

        <div class="form-group">
          <label for="adminEmail">Email</label>
          <input type="email" id="adminEmail" name="email" placeholder="Enter admin email" />
        </div>

        <div class="form-group">
          <label for="adminPassword">Password</label>
          <input type="password" id="adminPassword" name="password" placeholder="Enter admin password" />
        </div>

        <div class="form-group">
          <label for="adminConfirmPassword">Confirm Password</label>
          <input type="password" id="adminConfirmPassword" name="confirm_password" placeholder="Confirm admin password" />
        </div>

        <button class="save-btn">
          Create Admin
        </button>
      </div>
      </form>

</div>
      <!-- CREATE ADMIN -->

    <!-- FUNCTIONS -->

    
 <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    var toastMessage = "<?php echo isset($toast_message) ? $toast_message : ''; ?>";
    if (toastMessage) {
        Swal.fire({
            icon: 'info', // can be 'success', 'error', 'warning', 'info', 'question'
            text: toastMessage,
            confirmButtonColor: '#ff6b6b'
        });
    }
    </script>   
    
    <!-- FUNCTIONS -->
</body>
</html>



