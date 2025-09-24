<?php
$page_title = 'Orders | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message

// Determine user type and set owner filter
$orders = [];
$owner_filter = null;

if ($_SESSION['role'] === 'superadmin') {
    $owner_filter = intval($_SESSION['super_id']);
} elseif ($_SESSION['role'] === 'admin') {
    $owner_filter = intval($_SESSION['admin_id']);
}

// Fetch orders for this owner
if ($owner_filter !== null) {
    $orders_query = "SELECT o.*, c.fullname, c.phone_number, c.email, c.address
                     FROM orders o 
                     LEFT JOIN customers c ON o.c_id = c.`c_id`
                     WHERE o.owner_id = ? 
                     ORDER BY o.order_date DESC";

    $stmt = mysqli_prepare($connection, $orders_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $owner_filter);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders | Kesong Puti</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
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
                <tr data-status="<?php echo htmlspecialchars($order['order_status']); ?>" data-type="delivery">
                  <td>#<?php echo str_pad($order['o_id'], 5, '0', STR_PAD_LEFT); ?></td>
                  <td>
                    <div><?php echo htmlspecialchars($order['fullname'] ?: 'Guest Customer'); ?></div>
                    <small style="color: #666;"><?php echo htmlspecialchars($order['email'] ?: ''); ?></small>
                    <br><small style="color: #666;"><?php echo htmlspecialchars($order['phone_number'] ?: ''); ?></small>
                  </td>
                  <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                  <td><span class="status <?php echo htmlspecialchars($order['order_status']); ?>"><?php echo ucfirst(htmlspecialchars($order['order_status'])); ?></span></td>
                  <td><span class="type-label delivery">Delivery</span></td>
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
<script>
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
        <p><strong>Order Date:</strong> ${formattedDate}</p>
        <p><strong>Order Time:</strong> ${formattedTime}</p>
        <p><strong>Order Type:</strong> Delivery</p>
        <p><strong>Order Status:</strong> 
            <span class="status ${order.order_status}">
                ${order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1)}
            </span>
        </p>
        <p><strong>Payment Status:</strong> 
            <span class="status ${order.payment_status}">
                ${order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1)}
            </span>
        </p>
        <p><strong>Payment Method:</strong> 
            <span class="payment-method">${order.payment_method || 'Cash on Delivery'}</span>
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
</script>
<!-- FUNCTIONS -->

</body>
</html>
