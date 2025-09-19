<?php
$page_title = 'Orders | Kesong Puti';
require '../../connection.php';
include ('../../includes/admin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message

// Get current admin info from session
$current_admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;
$current_store_name = isset($_SESSION['store_name']) ? $_SESSION['store_name'] : null;

// Fetch orders for this admin's store (filter by numeric owner_id)
$orders = [];
if ($current_admin_id) {
    $orders_query = "SELECT o.*, c.fullname, c.phone_number, c.email, c.address
    FROM orders o 
    LEFT JOIN customers c ON o.c_id = c.`c.id`
    WHERE o.owner_id = ? 
    ORDER BY o.order_date DESC";

    $stmt = mysqli_prepare($connection, $orders_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $current_admin_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        mysqli_stmt_close($stmt);
    }
}

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
    <style>
        .order-details {
            max-width: 100%;
            margin: 0 auto;
        }
        .order-section {
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .order-section h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #333;
            font-size: 1.2em;
        }
        .order-section p {
            margin: 8px 0;
            line-height: 1.5;
        }
        .order-items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .order-items-table th,
        .order-items-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .order-items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .order-items-table tfoot td {
            font-weight: bold;
            background-color: #e9ecef;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: bold;
        }
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status.processing {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status.shipped {
            background-color: #cce5ff;
            color: #004085;
        }
        .status.delivered {
            background-color: #d4edda;
            color: #155724;
        }
        .status.cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>

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
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="ordersTableBody">
              <?php if (empty($orders)): ?>
                <tr>
                  <td colspan="7" style="text-align: center; padding: 20px;">No orders found</td>
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

    // Order details modal functions
    function viewOrderDetails(orderId) {
        // Show loading state
        document.getElementById('orderDetailsContent').innerHTML = '<div style="text-align: center; padding: 20px;">Loading order details...</div>';
        document.getElementById('orderDetailsModal').style.display = 'block';
        
        // Fetch order details via AJAX
        fetch('get-order-details.php?order_id=' + orderId)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayOrderDetails(data.order);
                } else {
                    document.getElementById('orderDetailsContent').innerHTML = '<div style="text-align: center; padding: 20px; color: red;">Error loading order details: ' + data.error + '</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('orderDetailsContent').innerHTML = '<div style="text-align: center; padding: 20px; color: red;">Error loading order details</div>';
            });
    }

    function displayOrderDetails(order) {
        const orderDate = new Date(order.order_date);
        const formattedDate = orderDate.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        const formattedTime = orderDate.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
        
        const content = `
            <div class="order-details">
                <div class="order-section">
                    <h3>Order Information</h3>
                    <p><strong>Order ID:</strong> #${String(order['o_id']).padStart(5, '0')}</p>
                    <p><strong>Order Date:</strong> ${formattedDate}</p>
                    <p><strong>Order Time:</strong> ${formattedTime}</p>
                    <p><strong>Order Type:</strong> Delivery</p>
                    <p><strong>Order Status:</strong> <span class="status ${order.order_status}">${order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1)}</span></p>
                    <p><strong>Payment Status:</strong> <span class="status ${order.payment_status}">${order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1)}</span></p>
                    <p><strong>Payment Method:</strong> ${order.payment_method || 'Cash on Delivery'}</p>
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

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('orderDetailsModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    </script>   

<!-- Order Details Modal -->
<div id="orderDetailsModal" class="modal" style="display: none;">
  <div class="modal-content">
    <div class="modal-header">
      <h2>Order Details</h2>
      <span class="close" onclick="closeOrderModal()">&times;</span>
    </div>
    <div class="modal-body" id="orderDetailsContent">
      <!-- Order details will be loaded here -->
    </div>
  </div>
</div>

<!-- FUNCTIONS -->