<?php
$page_title = 'Inbox | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message


// Close the connection
mysqli_close($connection);


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox | Kesong Puti</title>

    <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../css/admin.css"/>

</head>
<body>
  <div class="main-content">

      <!-- INBOX -->
      <div class="box" id="inbox-content">
        <h1>Inbox Messages</h1>

        <div class="contact-table-container">
          <input
            type="text"
            id="contactSearch"
            placeholder="Search by name or email..."
          />

          <table class="contact-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No.</th>
                <th>Message</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="contactTableBody">
              <tr>
                <td>Juan Dela Cruz</td>
                <td>juan@example.com</td>
                <td>09123456789</td>
                <td>Do you ship nationwide?</td>
                <td>Aug 5, 2025</td>
                <td>
                  <button class="view-btn">
                    <i class="bi bi-eye-fill"></i>
                  </button>
                  <button class="delete-btn">
                    <i class="bi bi-trash-fill"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>Maria Santos</td>
                <td>maria.s@example.com</td>
                <td>09123456789</td>
                <td>I love your product!</td>
                <td>Aug 3, 2025</td>
                <td>
                  <button class="view-btn">
                    <i class="bi bi-eye-fill"></i>
                  </button>
                  <button class="delete-btn">
                    <i class="bi bi-trash-fill"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
</div>      
      <!-- INBOX -->
      
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