<?php


// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php'); // Redirect to login if not logged in
    exit();
}

// Retrieve the admin's username from the session
$admin_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>
    <link rel="stylesheet" href="../css/admin.css" />
    <link rel="icon" href="../assets/NU logo.png" type="image/x-icon" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>


    <div class="sidebar">
      <!-- TOP SIDEBAR -->
      <div class="header">
        <div class="sidebar-header">
          <span>Admin Panel</span>
        </div>
        <i class="bi bi-three-dots" id="btn"></i>
      </div>

      <div class="user">
        <img src="../../assets/logo.png" alt="admin" class="profile-img" />
        <div>
          <p class="name">Arlene Macalinao</p>
          <p class="type">Super Admin</p>
        </div>
      </div>
      <!-- TOP SIDEBAR -->

      <!-- MIDDLE SIDEBAR -->
      <div class="sidebar-middle">
        <ul>
          <!-- DASHBOARD -->
          <li class="nav-tab dashboard-tab">
            <a href="#" class="main-tab" id="dashboard-link">
              <i class="bi bi-pie-chart-fill"></i>
              <span class="nav-item">Dashboard</span>
              <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <ul class="sub-menu">
              <li>
                <a
                  href="../admin/admin-overview.php"
                  class="sub-tab active-sub"
                  data-content="overview-content"
                  >Overview</a
                >
              </li>
              <li>
                <a href="../admin/inventory.php" class="sub-tab" data-content="inventory-content"
                  >Inventory</a
                >
              </li>
            </ul>
          </li>

          <!-- SHOP -->
          <li class="nav-tab shop-tab">
            <a href="#" class="main-tab" id="shop-link">
              <i class="bi bi-shop"></i>
              <span class="nav-item">Shop</span>
              <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <ul class="sub-menu">
              <li>
                <a href="../admin/products.php" class="sub-tab" data-content="products-content"
                  >All Products</a
                >
              </li>
              <li>
                <a href="../admin/orders.php" class="sub-tab" data-content="orders-content"
                  >Orders</a
                >
              </li>
              <li>
                <a href="../admin/payment.php" class="sub-tab" data-content="payment-content"
                  >Payment Method</a
                >
              </li>
            </ul>
          </li>

          

          <!-- INBOX -->
          <li>
            <a
              href="../admin/inbox.php"
              class="main-tab single-tab"
              data-content="inbox-content"
            >
              <i class="bi bi-envelope-fill"></i>
              <span class="nav-item">Inbox</span>
            </a>
          </li>
          <!-- INBOX -->

          <!-- FEEDBACKS -->
          <li>
            <a
              href="../admin/feedbacks.php"
              class="main-tab single-tab"
              data-content="feedback-content"
            >
              <i class="bi bi-hand-thumbs-up-fill"></i>
              <span class="nav-item">Feedbacks</span>
            </a>
          </li>
          <!-- FEEDBACKS -->

          
        </ul>
      </div>
      <!-- MIDDLE SIDEBAR -->

      <!-- BOTTOM SIDEBAR -->
      <div class="sidebar-bottom">
        <ul>
          <li>
            <a
              href="../admin/settings.php"
              class="main-tab single-tab"
              data-content="account-settings-content"
            >
              <i class="bi bi-gear"></i>
              <span class="nav-item">Account Settings</span>
            </a>
          </li>
          <li>
            <a href="../../login.php" id="logout-button">
              <i class="bi bi-box-arrow-right"></i>
              <span class="nav-item">Logout</span>
            </a>
          </li>
        </ul>
      </div>
      <!-- BOTTOM SIDEBAR -->
    </div>

    <script src="../../includes/dashboard.js"></script>
    
  </body>
</html>
