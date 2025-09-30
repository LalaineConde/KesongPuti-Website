<?php
$page_title = 'Analytics | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

// --- Fetch all owners from orders ---
$owners_result = $connection->query("SELECT DISTINCT owner_id FROM orders");
$owners = [];
while ($row = $owners_result->fetch_assoc()) {
    $owners[] = $row['owner_id'];
}

// --- Selected owner (default: all) ---
$selected_owner = $_GET['owner_id'] ?? 'all';

// Helper for WHERE clause
$where_clause = "";
if ($selected_owner !== 'all') {
    $owner_id = (int)$selected_owner;
    $where_clause = " AND owner_id = $owner_id ";
}

// --- TOTAL PENDING ORDERS ---
$pending = $connection->query("
    SELECT COUNT(*) AS total 
    FROM orders 
    WHERE order_status = 'pending' $where_clause
")->fetch_assoc()['total'];

// --- ORDERS TOTAL (Day/Week) ---
// Total orders per day
$orders_day = $connection->query("
    SELECT COUNT(o_id) AS total
    FROM orders o
    WHERE DATE(o.order_date) = CURDATE() $where_clause
")->fetch_assoc()['total'];

// Total orders per week
$orders_week = $connection->query("
    SELECT COUNT(o_id) AS total
    FROM orders o
    WHERE YEARWEEK(o.order_date, 1) = YEARWEEK(CURDATE(), 1) $where_clause
")->fetch_assoc()['total'];

// Total orders this month
$orders_month = $connection->query("
    SELECT COUNT(o_id) AS total
    FROM orders o
    WHERE YEAR(o.order_date) = YEAR(CURDATE())
      AND MONTH(o.order_date) = MONTH(CURDATE()) $where_clause
")->fetch_assoc()['total'];

// --- SALES TOTAL (Day/Week/Month) ---
// Sales today
$sales_day = $connection->query("
    SELECT SUM(total_amount) AS total
    FROM orders 
    WHERE order_status = 'completed' 
      AND DATE(order_date) = CURDATE() $where_clause
")->fetch_assoc()['total'] ?? 0;

// Sales this week
$sales_week = $connection->query("
    SELECT SUM(total_amount) AS total
    FROM orders 
    WHERE order_status = 'completed' 
      AND YEARWEEK(order_date, 1) = YEARWEEK(CURDATE(), 1) $where_clause
")->fetch_assoc()['total'] ?? 0;

// Sales this month
$sales_month = $connection->query("
    SELECT SUM(total_amount) AS total
    FROM orders 
    WHERE order_status = 'completed' 
      AND YEAR(order_date) = YEAR(CURDATE())
      AND MONTH(order_date) = MONTH(CURDATE()) $where_clause
")->fetch_assoc()['total'] ?? 0;


// helper to normalize status keys so DB values and $all_statuses keys match
function norm_status($s) {
    $s = trim(strtolower($s));
    $s = preg_replace('/[\s_]+/', '-', $s);        // spaces/underscores -> hyphen
    $s = preg_replace('/[^a-z0-9\-]/', '', $s);   // remove other chars
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
    WHERE DATE(order_date) = CURDATE() $where_clause 
    GROUP BY order_status
");
$statuses_day = $all_statuses;
while ($row = $status_day_q->fetch_assoc()) {
    $k = norm_status($row['order_status']);
    if (array_key_exists($k, $statuses_day)) {
        $statuses_day[$k] = (int)$row['total'];
    } else {
        // if DB has unexpected status, include it anyway
        $statuses_day[$k] = (int)$row['total'];
    }
}

// WEEK
$status_week_q = $connection->query("
    SELECT order_status, COUNT(*) AS total 
    FROM orders 
    WHERE YEARWEEK(order_date, 1) = YEARWEEK(CURDATE(), 1) $where_clause 
    GROUP BY order_status
");
$statuses_week = $all_statuses;
while ($row = $status_week_q->fetch_assoc()) {
    $k = norm_status($row['order_status']);
    $statuses_week[$k] = (int)$row['total'];
}

// MONTH
$status_month_q = $connection->query("
    SELECT order_status, COUNT(*) AS total 
    FROM orders 
    WHERE YEAR(order_date) = YEAR(CURDATE()) 
      AND MONTH(order_date) = MONTH(CURDATE()) $where_clause
    GROUP BY order_status
");
$statuses_month = $all_statuses;
while ($row = $status_month_q->fetch_assoc()) {
    $k = norm_status($row['order_status']);
    $statuses_month[$k] = (int)$row['total'];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics | Kesong Puti</title>

  <!-- BOOTSTRAP ICONS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="../../css/admin.css"/>
 

</head>
<body>
<div class="box" id="overview-content">

<div class="filter-container" style="margin-bottom:20px;">
    <form method="GET" action="">
        <label for="owner_id"><strong>Filter by Owner:</strong></label>
        <select name="owner_id" id="owner_id" onchange="this.form.submit()">
            <option value="all" <?= $selected_owner === 'all' ? 'selected' : '' ?>>All Owners</option>
            <?php foreach ($owners as $owner): ?>
                <option value="<?= $owner ?>" <?= $selected_owner == $owner ? 'selected' : '' ?>>
                    Owner <?= $owner ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>
        <h1>Order Analytics</h1>
    <div class="overview-container">




<div class="cards overview-row">

    <!-- Total Pending Orders -->
    <!-- Pending Orders -->
    <div class="card">
        <div class="card-header">
        <h4>Pending Orders</h4>
        </div>      
        <p><?= $pending; ?></p>
    </div>


<!-- Orders Dropdown -->
    <div class="card">
        <div class="card-header">
        <h4>Total Orders</h4>
            <select class="orders-select" onchange="toggleCards('orders', this.value)">
                <option value="day">This Day</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
        </div>


        <div id="orders_day" class="orders" style="display: block;">
            <p><?= $orders_day; ?> Orders</p>
        </div>
        <div id="orders_week" class="orders" style="display: none;">
            <p><?= $orders_week; ?> Orders</p>
        </div>
        <div id="orders_month" class="orders" style="display: none;">
            <p><?= $orders_month; ?> Orders</p>
        </div>
    </div>


<!-- Sales Dropdown -->
    <div class="card">
        <div class="card-header">
        <h4>Total Sales</h4>
            <select class="sales-select" onchange="toggleCards('sales', this.value)">
                <option value="day">This Day</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
        </div>
        <div id="sales_day" class="sales" style="display: block;">
            <p>₱<?= number_format($sales_day, 2); ?></p>
        </div>
        <div id="sales_week" class="sales" style="display: none;">
            <p>₱<?= number_format($sales_week, 2); ?></p>
        </div>
        <div id="sales_month" class="sales" style="display: none;">
            <p>₱<?= number_format($sales_month, 2); ?></p>
        </div>
    </div>
</div>



<!-- Order Status Dropdown -->

<div class="table-card">
    <div class="card-header">
<h2>Order Status</h2>
    <select class="order-status-select" onchange="toggleCards('status', this.value)">
        <option value="day">This Day</option>
        <option value="week">This Week</option>
        <option value="month">This Month</option>
    </select>
    </div>
<!-- Day -->
<div id="status_day" class="status cards" style="display: grid;">
    <table class="table-order-status">
        <thead>
            <tr>
                <th>Status</th>
                <th>Count</th>
            </tr>
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
<div id="status_week" class="status cards" style="display:none;">
    <table class="table-order-status">
        <thead>
            <tr>
                <th>Status</th>
                <th>Count</th>
            </tr>
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
<div id="status_month" class="status cards" style="display:none;">
    <table class="table-order-status">
        <thead>
            <tr>
                <th>Status</th>
                <th>Count</th>
            </tr>
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
</div>
</div>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleCards(group, option) {
        let sets = document.querySelectorAll("." + group);
        sets.forEach(s => s.style.display = "none");
        document.getElementById(group + "_" + option).style.display = "grid";
    }
</script>
</body>
</html>
