<?php

require '../../connection.php';


// === DEBUG BLOCK ===
// echo "<pre>SESSION:\n";
// print_r($_SESSION);
// echo "</pre>";

$toast_message = ''; // Initialize variable for toast message

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    header('Location: ../../login.php');
    exit();
}

$page_title = 'Analytics | Kesong Puti';
include ('../../includes/superadmin-dashboard.php');

// Get logged-in owner's ID
$owner_id = null;

if (isset($_SESSION['role']) && $_SESSION['role'] === 'superadmin' && isset($_SESSION['super_id'])) {
    $owner_id = intval($_SESSION['super_id']);
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_SESSION['admin_id'])) {
    $owner_id = intval($_SESSION['admin_id']);
}
// echo "<pre>DEBUG: admin_id = $admin_id</pre>";

// PENDING ORDERS
$pending = $connection->query("
    SELECT COUNT(*) AS total 
    FROM orders o
    WHERE o.order_status = 'pending'
      AND owner_id = $owner_id
")->fetch_assoc()['total'];
// echo "<pre>DEBUG: pending = $pending</pre>";

// ORDERS TOTAL (Day/Week/Month)
$orders_day = $connection->query("
    SELECT COUNT(*) AS total
    FROM orders o
    WHERE DATE(o.order_date) = CURDATE()
      AND owner_id = $owner_id
")->fetch_assoc()['total'];
// echo "<pre>DEBUG: orders_day = $orders_day</pre>";

$orders_week = $connection->query("
    SELECT COUNT(*) AS total
    FROM orders o
    WHERE YEARWEEK(o.order_date, 1) = YEARWEEK(CURDATE(), 1)
      AND owner_id = $owner_id
")->fetch_assoc()['total'];
// echo "<pre>DEBUG: orders_week = $orders_week</pre>";

$orders_month = $connection->query("
    SELECT COUNT(*) AS total
    FROM orders o
    WHERE YEAR(o.order_date) = YEAR(CURDATE())
      AND MONTH(o.order_date) = MONTH(CURDATE())
      AND owner_id = $owner_id
")->fetch_assoc()['total'];
// echo "<pre>DEBUG: orders_month = $orders_month</pre>";

// LAST MONTH ORDERS
$orders_last_month = $connection->query("
    SELECT COUNT(*) AS total
    FROM orders o
    WHERE YEAR(o.order_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)
      AND MONTH(o.order_date) = MONTH(CURDATE() - INTERVAL 1 MONTH)
      AND owner_id = $owner_id
")->fetch_assoc()['total'];

// SALES TOTAL (Day/Week/Month)
$sales_day = $connection->query("
    SELECT COALESCE(SUM(total_amount), 0) AS total
    FROM orders
    WHERE order_status = 'completed'
      AND DATE(order_date) = CURDATE()
      AND owner_id = $owner_id
")->fetch_assoc()['total'];
// echo "<pre>DEBUG: sales_day = $sales_day</pre>";

$sales_week = $connection->query("
    SELECT COALESCE(SUM(total_amount), 0) AS total
    FROM orders
    WHERE order_status = 'completed'
      AND YEARWEEK(order_date, 1) = YEARWEEK(CURDATE(), 1)
      AND owner_id = $owner_id
")->fetch_assoc()['total'];
// echo "<pre>DEBUG: sales_week = $sales_week</pre>";

$sales_month = $connection->query("
    SELECT COALESCE(SUM(total_amount), 0) AS total
    FROM orders
    WHERE order_status = 'completed'
      AND YEAR(order_date) = YEAR(CURDATE())
      AND MONTH(order_date) = MONTH(CURDATE())
      AND owner_id = $owner_id
")->fetch_assoc()['total'];
// echo "<pre>DEBUG: sales_month = $sales_month</pre>";

// LAST MONTH SALES
$sales_last_month = $connection->query("
    SELECT COALESCE(SUM(total_amount), 0) AS total
    FROM orders
    WHERE order_status = 'completed'
      AND YEAR(order_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)
      AND MONTH(order_date) = MONTH(CURDATE() - INTERVAL 1 MONTH)
      AND owner_id = $owner_id
")->fetch_assoc()['total'];

// helper to normalize statuses
function norm_status($s) {
    $s = trim(strtolower($s));
    $s = preg_replace('/[\s_]+/', '-', $s);
    $s = preg_replace('/[^a-z0-9\-]/', '', $s);
    return $s;
}

// Define all statuses
$all_statuses = [
    'pending' => 0,
    'verified' => 0,
    'payment-failed' => 0,
    'processing' => 0,
    'ready-to-pick-up' => 0,
    'out-for-delivery' => 0,
    'completed' => 0,
    'declined area' => 0,
    'cancelled' => 0,
    'returned' => 0
];

// DAY
$status_day_q = $connection->query("
    SELECT order_status, COUNT(*) AS total 
    FROM orders 
    WHERE DATE(order_date) = CURDATE() 
    AND owner_id = $owner_id
    GROUP BY order_status
");
$statuses_day = $all_statuses;
while ($row = $status_day_q->fetch_assoc()) {
    $statuses_day[norm_status($row['order_status'])] = (int)$row['total'];
}
// echo "<pre>DEBUG: statuses_day = "; print_r($statuses_day); echo "</pre>";

// WEEK
$status_week_q = $connection->query("
    SELECT order_status, COUNT(*) AS total 
    FROM orders 
    WHERE YEARWEEK(order_date, 1) = YEARWEEK(CURDATE(), 1) 
    AND owner_id = $owner_id
    GROUP BY order_status
");
$statuses_week = $all_statuses;
while ($row = $status_week_q->fetch_assoc()) {
    $statuses_week[norm_status($row['order_status'])] = (int)$row['total'];
}
// echo "<pre>DEBUG: statuses_week = "; print_r($statuses_week); echo "</pre>";

// MONTH
$status_month_q = $connection->query("
    SELECT order_status, COUNT(*) AS total 
    FROM orders 
    WHERE YEAR(order_date) = YEAR(CURDATE())
    AND MONTH(order_date) = MONTH(CURDATE()) 
    AND owner_id = $owner_id
    GROUP BY order_status
");
$statuses_month = $all_statuses;
while ($row = $status_month_q->fetch_assoc()) {
    $statuses_month[norm_status($row['order_status'])] = (int)$row['total'];
}
// echo "<pre>DEBUG: statuses_month = "; print_r($statuses_month); echo "</pre>";

// LAST MONTH STATUSES
$status_last_month_q = $connection->query("
    SELECT order_status, COUNT(*) AS total 
    FROM orders 
    WHERE YEAR(order_date) = YEAR(CURDATE() - INTERVAL 1 MONTH)
      AND MONTH(order_date) = MONTH(CURDATE() - INTERVAL 1 MONTH)
      AND owner_id = $owner_id
    GROUP BY order_status
");
$statuses_last_month = $all_statuses;
while ($row = $status_last_month_q->fetch_assoc()) {
    $statuses_last_month[norm_status($row['order_status'])] = (int)$row['total'];
}


// Close the connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Overview</title>
  <!-- BOOTSTRAP ICONS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="../../css/admin.css"/>

</head>
<body>
<div class="box" id="overview-content">
  <h1>Order Analytics</h1>
  <div class="overview-container">
    <div class="cards overview-row">
      <!-- Pending Orders -->
      <div class="card">
        <h4 class="card-header">Pending Orders</h4>
        <p><?= $pending; ?></p>
      </div>
      <!-- Orders Dropdown -->
      <div class="card">
        <div class="card-header">
          <h4 class="card-header">Total Orders</h4>
          <select class="orders-select" onchange="showOrders(this.value)">
            <option value="day">This Day</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="last_month">Last Month</option>
          </select>
        </div>
        <div id="orders_day" class="orders-section"><p><?= $orders_day; ?> Orders</p></div>
        <div id="orders_week" class="orders-section" style="display:none"><p><?= $orders_week; ?> Orders</p></div>
        <div id="orders_month" class="orders-section" style="display:none"><p><?= $orders_month; ?> Orders</p></div>
        <div id="orders_last_month" class="orders-section" style="display:none"><p><?= $orders_last_month; ?> Orders</p></div>
      </div>
      <!-- Sales Dropdown -->
      <div class="card">
        <div class="card-header">
          <h4 class="card-header">Total Sales</h4>
          <select class="sales-select" onchange="showSales(this.value)">
            <option value="day">This Day</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="last_month">Last Month</option>
          </select>
        </div>
        <div id="sales_day" class="sales-section"><p>₱<?= number_format($sales_day, 2); ?></p></div>
        <div id="sales_week" class="sales-section" style="display:none"><p>₱<?= number_format($sales_week, 2); ?></p></div>
        <div id="sales_month" class="sales-section" style="display:none"><p>₱<?= number_format($sales_month, 2); ?></p></div>
        <div id="sales_last_month" class="sales-section" style="display:none"><p>₱<?= number_format($sales_last_month, 2); ?></p></div>
      </div>
    </div>

    <!-- Order Status Dropdown -->
    <div class="table-card">
      <div class="card-header">
        <h2>Order Status</h2>
        <select class="order-status-select" onchange="showStatus(this.value)">
          <option value="day">This Day</option>
          <option value="week">This Week</option>
          <option value="month">This Month</option>
          <option value="last_month">Last Month</option>
        </select>
      </div>
      <!-- Day -->
      <div id="status_day" class="status-section">
        <table class="table-order-status">
          <thead>
            <tr><th>Status</th><th>Count</th></tr>
          </thead>              
          <tbody>
          <?php foreach ($statuses_day as $status => $total): ?>
            <tr>
              <td><?= ucwords(str_replace('-', ' ', $status)); ?></td>
              <td><?= $total; ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <!-- Week -->
      <div id="status_week" class="status-section" style="display:none;">
        <table class="table-order-status">
          <thead>
            <tr><th>Status</th><th>Count</th></tr>
          </thead>
          <tbody>
          <?php foreach ($statuses_week as $status => $total): ?>
            <tr>
              <td><?= ucwords(str_replace('-', ' ', $status)); ?></td>
              <td><?= $total; ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <!-- Month -->
      <div id="status_month" class="status-section" style="display:none;">
        <table class="table-order-status">
          <thead>
            <tr><th>Status</th><th>Count</th></tr>
          </thead>
          <tbody>
          <?php foreach ($statuses_month as $status => $total): ?>
            <tr>
              <td><?= ucwords(str_replace('-', ' ', $status)); ?></td>
              <td><?= $total; ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

       <!-- Last Month -->
        <div id="status_last_month" class="status-section" style="display:none;">
        <table class="table-order-status">
            <thead>
            <tr><th>Status</th><th>Count</th></tr>
            </thead>
            <tbody>
            <?php foreach ($statuses_last_month as $status => $total): ?>
            <tr>
                <td><?= ucwords(str_replace('-', ' ', $status)); ?></td>
                <td><?= $total; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
  </div>
</div>

<!-- Dropdown Switching Scripts -->
<script>
function showOrders(period) {
  document.getElementById('orders_day').style.display = (period === 'day') ? 'block' : 'none';
  document.getElementById('orders_week').style.display = (period === 'week') ? 'block' : 'none';
  document.getElementById('orders_month').style.display = (period === 'month') ? 'block' : 'none';
  document.getElementById('orders_last_month').style.display = (period === 'last_month') ? 'block' : 'none';
}

function showSales(period) {
  document.getElementById('sales_day').style.display = (period === 'day') ? 'block' : 'none';
  document.getElementById('sales_week').style.display = (period === 'week') ? 'block' : 'none';
  document.getElementById('sales_month').style.display = (period === 'month') ? 'block' : 'none';
  document.getElementById('sales_last_month').style.display = (period === 'last_month') ? 'block' : 'none';
}

function showStatus(period) {
  document.getElementById('status_day').style.display = (period === 'day') ? 'block' : 'none';
  document.getElementById('status_week').style.display = (period === 'week') ? 'block' : 'none';
  document.getElementById('status_month').style.display = (period === 'month') ? 'block' : 'none';
  document.getElementById('status_last_month').style.display = (period === 'last_month') ? 'block' : 'none';
}
</script>

<!-- SweetAlert2 Library for Toast Messages -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var toastMessage = "<?php echo isset($toast_message) ? $toast_message : ''; ?>";
if (toastMessage) {
  Swal.fire({
    icon: 'info',
    text: toastMessage,
    confirmButtonColor: '#ff6b6b'
  });
}
</script>
</body>
</html>