<?php
$page_title = 'Forgot Password | Kesong Puti';
require 'connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


if (isset($_SESSION['toast_message'])) {
    $toast_message = $_SESSION['toast_message'];
    unset($_SESSION['toast_message']);
}

// Password policy function
function is_valid_password($password) {
    // At least one lowercase, one uppercase, one digit, one special char, min 6 chars
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/', $password);
}

// Step 1: Send OTP
if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];

    // Check if email exists in super_admin
    $super_admin_query = "SELECT * FROM super_admin WHERE email = '$email'";
    $super_admin_result = mysqli_query($connection, $super_admin_query);

    // Check if email exists in admins
    $admin_query = "SELECT * FROM admins WHERE email = '$email'";
    $admin_result = mysqli_query($connection, $admin_query);

    if (mysqli_num_rows($super_admin_result) == 1) {
        $_SESSION['reset_table'] = 'super_admin';
    } elseif (mysqli_num_rows($admin_result) == 1) {
        $_SESSION['reset_table'] = 'admins';
    } else {
        $toast_message = "Invalid email! Please try again.";
    }

    if (isset($_SESSION['reset_table'])) {
        $otp = rand(100000, 999999);
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_otp'] = $otp;

        $subject = "Your OTP Code";
        $message = "Your OTP code for password reset is: <b>$otp</b>";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kesongputiscl@gmail.com';
            $mail->Password = 'jslt royn kkrg ocww';
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->isHTML(true);
            $mail->setFrom('kesongputiscl@gmail.com', "Kesong Puti");
            $mail->addAddress($email);
            $mail->Subject = $subject;
            $mail->Body = $message;
            $mail->send();

            $toast_message = 'OTP sent to your email.';
            $_SESSION['otp_sent'] = true;
        } catch (Exception $e) {
            $toast_message = 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    }
}

// Step 2: Verify OTP
if (isset($_POST['verify_otp'])) {
    $input_otp = $_POST['otp'];
    if (isset($_SESSION['reset_otp']) && $input_otp == $_SESSION['reset_otp']) {
        $_SESSION['otp_verified'] = true;
        $toast_message = "OTP verified. You may now set a new password.";
    } else {
        $toast_message = "Incorrect OTP. Please try again.";
    }
}

// Step 3: Set new password
if (isset($_POST['set_password'])) {
    if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        if ($new_password !== $confirm_password) {
            $toast_message = "Passwords do not match. Please try again.";
        } elseif (!is_valid_password($new_password)) {
            $toast_message = "Password must be at least 6 characters and contain a lowercase letter, uppercase letter, number, and special character.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $email = $_SESSION['reset_email'];
            $table = $_SESSION['reset_table'];

            $update_query = "UPDATE $table SET password = '$hashed_password' WHERE email = '$email'";
            mysqli_query($connection, $update_query);

            $toast_message = "Password changed successfully. You may now log in.";
            // Clear session
            unset($_SESSION['reset_email'], $_SESSION['reset_otp'], $_SESSION['otp_verified'], $_SESSION['otp_sent'], $_SESSION['reset_table']);
            header("Location: login.php");
            exit();
        }
    } else {
        $toast_message = "OTP verification required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password | Kesong Puti</title>
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

<main>
    <section class="form-section">
        
    <a href="login.php">
        <img src="assets/logo.png" alt="NU Laguna Logo" class="logo-container"/>
    </a>
    

        <div class="title-container">
            

            <p>
                Please enter your registered email. We will send an OTP to your email so you can reset your password.
            </p>
        </div>
        <div class="form-container">
            <?php if (!isset($_SESSION['otp_sent'])): ?>
            <!-- Step 1: Request OTP -->
            <form action="" method="post">
                <input type="email" name="email" placeholder="Email" required />
                <button type="submit" name="send_otp" class="btn">Send OTP</button>
            </form>
            <?php elseif (!isset($_SESSION['otp_verified'])): ?>
            <!-- Step 2: Verify OTP -->
            <form action="" method="post">
                <input type="text" name="otp" placeholder="Enter OTP" required maxlength="6" />
                <button type="submit" name="verify_otp" class="btn">Verify OTP</button>
            </form>
            <?php else: ?>
            <!-- Step 3: Set New Password -->
            <form action="" method="post">
                <input type="password" name="new_password" placeholder="New Password" required minlength="6" />
                <input type="password" name="confirm_password" placeholder="Confirm Password" required minlength="6" />
                <small>Password must be at least 6 characters and contain a lowercase letter, uppercase letter, number, and special character.</small>
                <button type="submit" name="set_password" class="btn">Set Password</button>
            </form>
            <?php endif; ?>
            <p>
                Back to <a href="login.php">Log In</a>
            </p>
        </div>
    </section>
</main>

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