<?php
$page_title = 'Login | Kesong Puti';
require 'connection.php';

if (isset($_SESSION['toast_message'])) {
    $toast_message = $_SESSION['toast_message'];
    unset($_SESSION['toast_message']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = $_POST['password'];

    // Check in super_admin table
    $super_admin_check_query = "SELECT * FROM super_admin WHERE username='$username'";
    $super_admin_result = mysqli_query($connection, $super_admin_check_query);

    if (mysqli_num_rows($super_admin_result) == 1) {
        $super_admin = mysqli_fetch_assoc($super_admin_result);

        if (password_verify($password, $super_admin['password'])) {
            $_SESSION['super_id'] = $super_admin['super_id'];
            $_SESSION['username'] = $super_admin['username'];
            $_SESSION['email'] = $super_admin['email'];
            $_SESSION['role'] = 'superadmin';
            header('Location: interfaces/superadmin/superadmin-overview.php');
            exit();
        } else {
            $toast_message = "Incorrect password for super admin.";
        }
    } else {
        // Check in admins table
        $admin_check_query = "SELECT * FROM admins WHERE username='$username'";
        $admin_result = mysqli_query($connection, $admin_check_query);

        if (mysqli_num_rows($admin_result) == 1) {
            $admin = mysqli_fetch_assoc($admin_result);

            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['username'] = $admin['username'];
                $_SESSION['email'] = $admin['email'];
                $_SESSION['role'] = 'admin';
                header('Location: interfaces/admin/admin-overview.php');
                exit();
            } else {
                $toast_message = "Incorrect password for admin.";
            }
        } else {
            $toast_message = "Username not found. Please try again.";
        }
    }
    mysqli_close($connection);
}


?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Login</title>

    <!-- BOOTSTRAP -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <!-- CSS -->
    <link rel="stylesheet" href="css/login.css"/>
  </head>
  <body>
    <div class="auth-container">
      <img src="assets/logo.png" alt="Kesong Puti Logo" class="auth-logo" />

      <!-- Login Form -->
      <form id="loginForm" method="post">
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-person"></i></span>
          <input
            type="text"
            class="form-control"
            name="username"
            placeholder="Enter your username"
            required
          />
        </div>
        <div class="mb-3 input-group">
          <span class="input-group-text"><i class="bi bi-lock"></i></span>
          <input
            type="password"
            class="form-control"
            name="password"
            placeholder="Enter your password"
            required
          />
        </div>
        <div class="text-start mb-4">
          <p><a href="forgotpass.php" class="text-dark">Forgot Password?</a></p>
        </div>
        <button type="submit" class="btn login-btn w-100 mb-3">LOGIN</button>
        <!-- <p class="text-muted">
          Don’t have an account?
          <span class="form-toggle" onclick="toggleForm()">Register</span>
        </p> -->
      </form>
    </div>


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

