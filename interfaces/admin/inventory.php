<?php
$page_title = 'Inventory | Kesong Puti';
require '../../connection.php'; 
include ('../../includes/admin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message


// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../login.php');
    exit();
}

// Get logged-in owner's ID
$admin_id = (int)$_SESSION['admin_id'];


// --- STATUS FILTER ---
$status_filter = $_GET['status'] ?? 'all'; // default = all

$where_clause = "WHERE owner_id = $admin_id";
if ($status_filter !== 'all') {
    $status_filter_safe = mysqli_real_escape_string($connection, $status_filter);
    $where_clause .= " AND status = '$status_filter_safe'";
}

// INVENTORY (all products by admin)

$inventory_q = $connection->query("
    SELECT product_id, product_name, variation_size, stock_qty, price, status, date_added, updated_at
    FROM products
    $where_clause
    ORDER BY product_name ASC
");
$inventory = [];
while ($row = $inventory_q->fetch_assoc()) {
    $inventory[] = $row;
}

// Close the connection
mysqli_close($connection);


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory | Kesong Puti</title>

    <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../css/admin.css"/>

</head>
<body>


  
   <!-- INVENTORY -->

<div class="main-content">
     <div class="box" id="inventory-content">
        <h1>Inventory</h1>
      </div> 


      <!-- Inventory -->
<div class="table-card">
  <div class="card-header">
    <h2>Product Inventory</h2>
          <!-- Filter -->
    <form method="GET" class="filter-form">
        <!-- <label for="status">Status:</label> -->
        <select name="status" id="status" class="table-inventory" onchange="this.form.submit()">
            <option value="all" <?= $status_filter==='all' ? 'selected' : '' ?>>All</option>
            <option value="available" <?= $status_filter==='available' ? 'selected' : '' ?>>Available</option>
            <option value="out of stock" <?= $status_filter==='out of stock' ? 'selected' : '' ?>>Out of Stock</option>
        </select>
    </form>


  </div>
  <table class="table-order-status">
    <thead>
      <tr>
        <th>Product ID</th>
        <th>Name</th>
        <th>Variation</th>
        <th>Stock</th>
        <th>Price</th>
        <th>Status</th>
        <th>Last Updated</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($inventory)): ?>
        <?php foreach ($inventory as $item): ?>
          <tr>
            <td>#<?= $item['product_id']; ?></td>
            <td><?= htmlspecialchars($item['product_name']); ?></td>
            <td><?= htmlspecialchars($item['variation_size']); ?></td>
            <td><?= $item['stock_qty']; ?></td>
            <td>₱<?= number_format($item['price'], 2); ?></td>
            <td><?= ucfirst($item['status']); ?></td>
            <td><?= date("M d, Y h:i A", strtotime($item['updated_at'])); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" style="text-align:center;">No products in inventory</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>


</div>
<!-- INVENTORY -->


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