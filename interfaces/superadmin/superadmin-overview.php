<?php
$page_title = 'Overview | Kesong Puti';
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
    <title>Overview | Kesong Puti</title>

    <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../css/admin.css"/>

</head>
<body>

    <!-- OVERALL CONTENTS -->
    <div class="main-content">
      <!-- DASHBOARD TAB -->
      <div class="box" id="overview-content">
        <h1>Dashboard Overview</h1>

        <div class="overview">
          <div class="container products">1</div>
          <div class="container pending-orders">2</div>
          <div class="container new-orders">3</div>
          <div class="container completed-orders">4</div>
          <div class="container top-selling">5</div>
          <div class="container top-viewed">6</div>
          <div class="container low-stock">7</div>
        </div>
      </div>
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