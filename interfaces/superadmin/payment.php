<?php
$page_title = 'Payment Method | Kesong Puti';
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
    <title>Payment Method | Kesong Puti</title>

    <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../css/admin.css"/>

</head>
<body>

<!-- PAYMENT METHODS -->

 <div class="main-content">
      <div class="box" id="payment-content">
        <h1>Payment Methods</h1>

        <div class="payment-form">
          <div class="form-group">
            <label for="gcashName">GCash Account Name</label>
            <input
              type="text"
              id="gcashName"
              placeholder="Enter GCash account name"
            />
          </div>

          <div class="form-group">
            <label for="gcashNumber">GCash Number</label>
            <input type="text" id="gcashNumber" placeholder="09XXXXXXXXX" />
          </div>

          <div class="form-group">
            <label for="qrUpload">Upload QR Code</label>
            <input type="file" id="qrUpload" accept="image/*" />
            <div class="qr-preview">
              <img
                id="qrPreviewImage"
                src="assets/sample-qr.png"
                alt="QR Preview"
              />
            </div>
          </div>

          <button class="save-btn" onclick="savePaymentMethod()">
            Save Changes
          </button>
        </div>
      </div>
</div>
    <!-- PAYMENT METHODS -->

<!-- FUNCTIONS -->
    <script>
      const qrUpload = document.getElementById("qrUpload");
      const qrPreview = document.getElementById("qrPreviewImage");

      qrUpload.addEventListener("change", function () {
        const file = qrUpload.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            qrPreview.src = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      });

      function savePaymentMethod() {
        const gcashName = document.getElementById("gcashName").value.trim();
        const gcashNumber = document.getElementById("gcashNumber").value.trim();

        if (!gcashName || !gcashNumber) {
          alert("Please fill out all fields.");
          return;
        }

        // Simulate save process (replace with backend call later)
        alert("GCash payment method has been saved successfully!");
      }
    </script>

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