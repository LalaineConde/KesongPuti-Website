<?php
$page_title = 'Orders | Kesong Puti';
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
    <title>Orders | Kesong Puti</title>

    <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../css/admin.css"/>

</head>
<body>

 <!-- ORDERS -->
   <div class="main-content">

      <div class="box" id="orders-content">
        <h1>Orders Overview</h1>

        <div class="orders-container">
          <div class="orders-header">
            <input
              type="text"
              id="orderSearch"
              placeholder="Search by customer or order ID..."
            />
            <select id="orderStatusFilter">
              <option value="all">All Status</option>
              <option value="pending">Pending</option>
              <option value="processing">Processing</option>
              <option value="shipped">Shipped</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
            <select id="orderTypeFilter">
              <option value="all">All Types</option>
              <option value="pickup">Pickup</option>
              <option value="delivery">Delivery</option>
            </select>
          </div>

          <table class="orders-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Status</th>
                <th>Type</th>
                <th>Total</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="ordersTableBody">
              <tr data-status="pending" data-type="pickup">
                <td>#00123</td>
                <td>Juan Dela Cruz</td>
                <td>Aug 04, 2025</td>
                <td><span class="status pending">Pending</span></td>
                <td><span class="type-label pickup">Pickup</span></td>
                <td>₱360</td>
                <td><button class="view-btn">View</button></td>
              </tr>
              <tr data-status="completed" data-type="delivery">
                <td>#00124</td>
                <td>Maria Santos</td>
                <td>Aug 03, 2025</td>
                <td><span class="status completed">Completed</span></td>
                <td><span class="type-label delivery">Delivery</span></td>
                <td>₱540</td>
                <td><button class="view-btn">View</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
</div>
 <!-- ORDERS -->      

<!-- FUNCTIONS -->
    

    <script>
      const orderSearch = document.getElementById("orderSearch");
      const orderStatusFilter = document.getElementById("orderStatusFilter");
      const orderRows = document.querySelectorAll("#ordersTableBody tr");

      function filterOrders() {
        const searchTerm = orderSearch.value.toLowerCase();
        const selectedStatus = orderStatusFilter.value;

        orderRows.forEach((row) => {
          const customerName = row.children[1].textContent.toLowerCase();
          const orderId = row.children[0].textContent.toLowerCase();
          const status = row.getAttribute("data-status");

          const matchesSearch =
            customerName.includes(searchTerm) || orderId.includes(searchTerm);
          const matchesStatus =
            selectedStatus === "all" || status === selectedStatus;

          row.style.display = matchesSearch && matchesStatus ? "" : "none";
        });
      }

      orderSearch.addEventListener("input", filterOrders);
      orderStatusFilter.addEventListener("change", filterOrders);
    </script>


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