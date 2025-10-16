<?php
$page_title = 'Orders | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message

// Determine user type and set owner filter
$orders = [];
$owner_filter = null;

if (isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin') {
    $owner_filter = isset($_SESSION['super_id']) ? intval($_SESSION['super_id']) : null;
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $owner_filter = isset($_SESSION['admin_id']) ? intval($_SESSION['admin_id']) : null;
}

if ($owner_filter !== null) {
    $orders_query = "SELECT o.o_id, o.reference_number, o.order_date, o.order_status,
                            o.total_amount, o.payment_method, o.proof_of_payment, 
                            o.delivery_address, o.order_type,
                            c.fullname, c.phone_number, c.email, c.address
                     FROM orders o 
                     LEFT JOIN customers c ON o.c_id = c.c_id
                     WHERE o.owner_id = ? 
                     ORDER BY o.order_date DESC";

    $stmt = mysqli_prepare($connection, $orders_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $owner_filter);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders | Kesong Puti</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="../../css/admin.css"/>

  <style>
    .orders-table img {
      width: 50px;
      height: auto;
      border-radius: 4px;
      border: 1px solid #ccc;
      cursor: pointer;
    }
    .status {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.9em;
      font-weight: bold;
    }
    .status.pending { background-color: #fff3cd; color: #856404; }
    .status.processing { background-color: #d1ecf1; color: #0c5460; }
    .status.shipped { background-color: #cce5ff; color: #004085; }
    .status.delivered { background-color: #d4edda; color: #155724; }
    .status.cancelled { background-color: #f8d7da; color: #721c24; }
    .payment-method {
      display: inline-block;
      padding: 8px 16px;
      background-color: #0D8540;
      color: white;
      border-radius: 4px;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 0.9em;
    }
  </style>
</head>
<body>

<div class="main-content">
  <div class="box" id="orders-content">
    <h1>Orders Overview</h1>

    <div class="orders-container">
      <div class="orders-header">
        <input type="text" id="orderSearch" placeholder="Search by customer or order ID..."/>
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


      <div class="orders-table-wrapper">
        <table class="orders-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Reference No.</th>
              <th>Customer</th>
              <th>Date</th>
              <th>Status</th>
              <th>Type</th>
              <th>Total</th>
              <th>Proof</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="ordersTableBody">
            <?php if (empty($orders)): ?>
              <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">No orders found</td>
              </tr>
            <?php else: ?>
              <?php foreach ($orders as $order): ?>
              <tr data-status="<?php echo htmlspecialchars($order['order_status']); ?>" data-type="<?php echo htmlspecialchars($order['order_type']); ?>">
                <td>#<?php echo str_pad($order['o_id'], 5, '0', STR_PAD_LEFT); ?></td>
                <td><?php echo htmlspecialchars($order['reference_number'] ?: 'N/A'); ?></td>
                <td>
                  <div><?php echo htmlspecialchars($order['fullname'] ?: 'Guest Customer'); ?></div>
                  <small style="color: #666;"><?php echo htmlspecialchars($order['email'] ?: ''); ?></small>
                  <br><small style="color: #666;"><?php echo htmlspecialchars($order['phone_number'] ?: ''); ?></small>
                </td>
                <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                <td>
                  <select class="order-status" 
                          data-order-id="<?= $order['o_id']; ?>" 
                          data-customer-email="<?= $order['email']; ?>"
                          data-reference-number="<?= $order['reference_number']; ?>">
                      <option value="pending" <?= $order['order_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                      <option value="processing" <?= $order['order_status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                      <option value="shipped" <?= $order['order_status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                      <option value="delivered" <?= $order['order_status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                      <option value="cancelled" <?= $order['order_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                  </select>
                </td>
                <td><span class="type-label <?php echo htmlspecialchars($order['order_type']); ?>">
                    <?php echo ucfirst(htmlspecialchars($order['order_type'])); ?>
                </span></td>
                <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                <td>
                  <?php if (!empty($order['proof_of_payment'])): ?>
                    <a href="../../uploads/payment_proofs/<?php echo htmlspecialchars($order['proof_of_payment']); ?>" target="_blank">
                      <img src="../../uploads/payment_proofs/<?php echo htmlspecialchars($order['proof_of_payment']); ?>" class="proof-thumb" alt="Proof">
                    </a>
                  <?php else: ?>
                    <span style="color:#888;">None</span>
                  <?php endif; ?>
                </td>
                <td><button class="view-btn" onclick="viewOrderDetails(<?php echo $order['o_id']; ?>)">View</button></td>
              </tr>
              <?php endforeach; ?>

            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Proof Image Modal -->
<div id="imageModal" class="modal" style="display:none;">
  <div class="modal-content" style="max-width:600px;">
    <span class="close" onclick="closeImageModal()">&times;</span>
    <img id="modalImage" src="" style="width:100%; border-radius:8px;"/>
  </div>
</div>





<!-- Order Details Modal -->
<div id="orderDetailsModal" class="modal" style="display: none;">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Order Details</h2>
      <span class="close" onclick="closeOrderModal()">&times;</span>
    </div>
    <div class="modal-body" id="orderDetailsContent"></div>
  </div>
</div>






<!-- FUNCTIONS -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

  // Search & Filter
  const orderSearch = document.getElementById("orderSearch");
  const orderStatusFilter = document.getElementById("orderStatusFilter");
  const orderTypeFilter = document.getElementById("orderTypeFilter");
  const orderRows = document.querySelectorAll("#ordersTableBody tr");

  function filterOrders() {
    const searchTerm = orderSearch.value.toLowerCase();
    const selectedStatus = orderStatusFilter.value;
    const selectedType = orderTypeFilter  .value;

    orderRows.forEach((row) => {
      const customerName = row.children[1].textContent.toLowerCase();
      const orderId = row.children[0].textContent.toLowerCase();
      const status = row.getAttribute("data-status")?.toLowerCase();
      const type = row.getAttribute("data-type")?.toLowerCase();

      // Check matches
      const matchesSearch =
        customerName.includes(searchTerm) || orderId.includes(searchTerm);
      const matchesStatus =
        selectedStatus === "all" || status === selectedStatus;
      const matchesType =
        selectedType === "all" || type === selectedType;

      // Show only rows that match ALL conditions
      row.style.display =
        matchesSearch && matchesStatus && matchesType ? "" : "none";
    });
  }

  // Event listeners
  orderSearch.addEventListener("input", filterOrders);
  orderStatusFilter.addEventListener("change", filterOrders);
  orderTypeFilter.addEventListener("change", filterOrders);



  // Close modals when clicking outside
  window.onclick = function(event) {
    const orderModal = document.getElementById('orderDetailsModal');
    const imageModal = document.getElementById('imageModal');

    if (event.target === orderModal) {
      orderModal.style.display = 'none';
    }
    if (event.target === imageModal) {
      imageModal.style.display = 'none';
    }
  };

  // Proof image modal
  function viewImage(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').style.display = 'block';
  }
  function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
  }

  // Order details modal
  function viewOrderDetails(orderId) {
    document.getElementById('orderDetailsContent').innerHTML = '<div style="text-align: center; padding: 20px;">Loading order details...</div>';
    document.getElementById('orderDetailsModal').style.display = 'block';



    fetch('get-order-details.php?order_id=' + orderId)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          displayOrderDetails(data.order);
        } else {
          document.getElementById('orderDetailsContent').innerHTML = '<div style="color:red; text-align:center;">Error: ' + data.error + '</div>';
        }
      })
      .catch(err => {
        document.getElementById('orderDetailsContent').innerHTML = '<div style="color:red; text-align:center;">Error loading order details</div>';
      });
  }



  function displayOrderDetails(order) {
    const orderDate = new Date(order.order_date);
    const formattedDate = orderDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    const formattedTime = orderDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });


    const content = `
      <div class="order-details">
        <div class="order-section">
          <h3>Order Information</h3>
          <p><strong>Order ID:</strong> #${String(order['o_id']).padStart(5, '0')}</p>
          <p><strong>Reference Number:</strong> ${order.reference_number || 'N/A'}</p>
          <p><strong>Order Date:</strong> ${formattedDate}</p>
          <p><strong>Order Time:</strong> ${formattedTime}</p>
          <p><strong>Order Type:</strong> ${order.order_type ? order.order_type.charAt(0).toUpperCase() + order.order_type.slice(1) : 'Delivery'}</p>
          <p><strong>Order Status:</strong> 
                      <span class="status ${order.order_status}">
                          ${order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1)}
                      </span>
                  </p>
          <p><strong>Payment Method:</strong> 
                      <span class="payment-method">${order.payment_method || 'Cash'}</span>
                  </p>
                  <p><strong>Proof of Payment:</strong><br>
                      ${order.proof_of_payment 
                          ? `<a href="../../uploads/payment_proofs/${order.proof_of_payment}" target="_blank">
                                <img src="../../uploads/payment_proofs/${order.proof_of_payment}" 
                                      alt="Proof of Payment" style="max-width:200px; margin-top:8px; border:1px solid #ccc; border-radius:6px;">
                            </a>`
                          : '<span style="color:#888;">None</span>'
                      }
            </p>
        </div>

        <div class="order-section">
          <h3>Customer Information</h3>
          <p><strong>Full Name:</strong> ${order.fullname || 'Guest Customer'}</p>
          <p><strong>Email:</strong> ${order.email || 'N/A'}</p>
          <p><strong>Phone Number:</strong> ${order.phone_number || 'N/A'}</p>
          <p><strong>Delivery Address:</strong> ${order.delivery_address || order.address || 'N/A'}</p>
        </div>


        <div class="order-section">
          <h3>Order Items</h3>
          <table class="order-items-table">
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              ${order.items.map(item => `
                <tr>
                  <td>${item.product_name || 'Unknown Product'}</td>
                  <td>${item.quantity}</td>
                  <td>₱${parseFloat(item.price_at_purchase).toFixed(2)}</td>
                  <td>₱${(parseFloat(item.price_at_purchase) * parseInt(item.quantity)).toFixed(2)}</td>
                </tr>
              `).join('')}
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3"><strong>Total Amount:</strong></td>
                <td><strong>₱${parseFloat(order.total_amount).toFixed(2)}</strong></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    `;
    document.getElementById('orderDetailsContent').innerHTML = content;
  }

  function closeOrderModal() {
    document.getElementById('orderDetailsModal').style.display = 'none';
  }


  //Sends email for change order status
  document.addEventListener("DOMContentLoaded", function () {
  const dropdowns = document.querySelectorAll(".order-status");
  const loggedInAdminEmail = "<?php echo $_SESSION['email']; ?>"; 
  dropdowns.forEach(select => {
    select.addEventListener("change", async function () {
      const orderId = this.dataset.orderId;
      const customerEmail = this.dataset.customerEmail;
      const reference = this.dataset.referenceNumber;
      const newStatus = this.value;

      const row = this.closest("tr");
      const customerName = row.querySelector("td:nth-child(3) div")?.innerText.trim() || "Valued Customer";
      const totalText = row.querySelector("td:nth-child(7)")?.innerText.trim() || "₱0.00";
      const total = totalText.replace("₱", "").trim();

      if (!orderId || !customerEmail) {
        Swal.fire({
          icon: "error",
          title: "Missing Data",
          text: "Missing order or customer information!",
        });
        return;
      }

      // Confirm status change
      const confirmResult = await Swal.fire({
        title: `Update order status to "${newStatus}"?`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it",
        cancelButtonText: "Cancel"
      });

      if (!confirmResult.isConfirmed) {
        // Revert dropdown selection
        this.selectedIndex = [...this.options].findIndex(opt => opt.defaultSelected);
        return;
      }

      // --- Build Gmail message ---
      let subject = "";
      let body = "";

      switch (newStatus.toLowerCase()) {
        case "processing":
          subject = "Your Order is Now Being Processed";
          body = `Good Day, ${customerName}!\n\nYour order (Ref #${reference}) is now being processed. Our team is preparing your items for shipment.\n\nTotal: ₱${total}\n\nThank you for choosing Kesong Puti!\n\nBest regards,\n${loggedInAdminEmail}\nKesong Puti Admin`;
          break;
        case "shipped":
          subject = "Your Order Has Been Shipped!";
          body = `Good Day, ${customerName}!\n\nYour order (Ref #${reference}) has been shipped and is on its way to you!\n\nTotal: ₱${total}\n\nWe appreciate your patience and support.\n\nBest regards,\n${loggedInAdminEmail}\nKesong Puti Admin`;
          break;
        case "delivered":
          subject = "Your Order Has Been Delivered";
          body = `Good Day, ${customerName}!\n\nYour order (Ref #${reference}) has been successfully delivered.\n\nTotal: ₱${total}\n\nWe hope you enjoy your purchase! Don’t forget to leave feedback on our website.\n\nThank you for trusting Kesong Puti!\n\nBest regards,\n${loggedInAdminEmail}\nKesong Puti Admin`;
          break;
        case "cancelled":
          subject = "Your Order Has Been Cancelled";
          body = `Good Day, ${customerName}!\n\nWe're sorry to inform you that your order (Ref #${reference}) has been cancelled.\n\nTotal: ₱${total}\n\nIf this was a mistake or you have any concerns, please contact us for assistance.\n\nBest regards,\n${loggedInAdminEmail}\nKesong Puti Admin`;
          break;
        default:
          subject = "Order Status Updated";
          body = `Hi ${customerName},\n\nYour order (Ref #${reference}) status has been updated to "${newStatus}".\n\nTotal: ₱${total}\n\nThank you for shopping with Kesong Puti!\n\nBest regards,\n${loggedInAdminEmail}\nKesong Puti Admin`;
          break;
      }

      // --- Update database first ---
      try {
        const response = await fetch("orders-email.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ order_id: orderId, new_status: newStatus })
        });

        const result = await response.json();

        if (result.success) {
          // --- Open Gmail compose after successful DB update ---
          const gmailUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(customerEmail)}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
          window.open(gmailUrl, "_blank");

          Swal.fire({
            icon: "success",
            title: "Status Updated!",
            text: "The order status has been successfully updated.",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "OK"
          });

        } else {
          Swal.fire({
            icon: "error",
            title: "Update Failed",
            text: result.error || "Failed to update order status.",
          });
        }
      } catch (error) {
        Swal.fire({
          icon: "error",
          title: "Server Error",
          text: "There was an error updating the order.",
        });
        console.error(error);
      }
    });
  });
});



</script>
<!-- FUNCTIONS -->

</body>
</html>