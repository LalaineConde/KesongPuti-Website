<?php
$page_title = 'Create Admin | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$username = isset($_POST['username']) ? mysqli_real_escape_string($connection, $_POST['username']) : '';
$store_name = isset($_POST['store_name']) ? mysqli_real_escape_string($connection, $_POST['store_name']) : '';
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
            $sql = "INSERT INTO admins (username, email, password, store_name)
                    VALUES ('$username', '$email', '$hashed_password', '$store_name')";

            if (mysqli_query($connection, $sql)) {
                $toast_message = "Admin added successfully!";
                

            } else {
                $toast_message = "Error: " . mysqli_error($connection);
            }
        }
    }
}

// Fetch all admins
$admins_query = "SELECT admin_id, username, store_name, email FROM admins";
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
            <label for="adminStoreName">Store Name</label>
            <input type="text" id="adminStoreName" name="store_name" placeholder="Enter store name" />
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

      <br>
      <br>


    <!-- ADMINS TABLE -->
    <h1>Admin List</h1>
    <div class="admin-table-wrapper">
    <table class="contact-table">
        <thead>
            <tr>
                <th>Admin ID</th>
                <th>Username</th>
                <th>Store Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="contactTableBody">
        <?php if (mysqli_num_rows($admins_result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($admins_result)): ?>
                <tr>
                    <td><?php echo $row['admin_id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['store_name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
              <td>
                <!-- Update Button -->
                <button 
                  class="update-btn" 
                  data-id="<?php echo $row['admin_id']; ?>"
                  data-username="<?php echo htmlspecialchars($row['username']); ?>"
                  data-store="<?php echo htmlspecialchars($row['store_name']); ?>"
                  data-email="<?php echo htmlspecialchars($row['email']); ?>"
                  title="Update Admin"
                >
                  <i class="bi bi-pencil-square"></i>
                </button>
               <!-- Delete Button -->
                <button 
                  class="delete-btn" 
                  data-id="<?php echo $row['admin_id']; ?>" 
                  title="Delete Admin"
                >
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No admins found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>

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

    <script>
document.addEventListener("DOMContentLoaded", function() {
    
    // DELETE ADMIN
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function() {
            let adminId = this.getAttribute("data-id");

            Swal.fire({
                title: "Are you sure?",
                text: "This admin will be deleted permanently.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("delete-admin.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + adminId
                    })
                    .then(response => response.text())
                    .then(data => {
                        Swal.fire("Deleted!", data, "success")
                            .then(() => location.reload());
                    });
                }
            });
        });
    });

    // UPDATE ADMIN
    document.querySelectorAll(".update-btn").forEach(button => {
        button.addEventListener("click", function() {
            let adminId = this.getAttribute("data-id");
            let currentUsername = this.getAttribute("data-username");
            let currentEmail = this.getAttribute("data-email");
            let currentStore = this.getAttribute("data-store");

            Swal.fire({
                title: "Update Admin",
                html: `
                    <input id="swal-username" class="swal2-input" placeholder="Username" value="${currentUsername}">
                    <input id="swal-store" class="swal2-input" placeholder="Store Name" value="${currentStore}">
                    <input id="swal-email" type="email" class="swal2-input" placeholder="Email" value="${currentEmail}">
                `,
                confirmButtonText: "Update",
                showCancelButton: true,
                preConfirm: () => {
                    return {
                        username: document.getElementById("swal-username").value,
                        store: document.getElementById("swal-store").value,
                        email: document.getElementById("swal-email").value
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("update-admin.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + adminId + 
                            "&username=" + encodeURIComponent(result.value.username) + 
                            "&store_name=" + encodeURIComponent(result.value.store) + 
                            "&email=" + encodeURIComponent(result.value.email)
                    })
                    .then(response => response.text())
                    .then(data => {
                        Swal.fire("Updated!", data, "success")
                            .then(() => location.reload());
                    });
                }
            });
        });
    });

});
</script>
    
    <!-- FUNCTIONS -->
</body>
</html>



