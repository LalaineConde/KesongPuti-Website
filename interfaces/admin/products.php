<?php
$page_title = 'Products | Kesong Puti';
require '../../connection.php';
include ('../../includes/admin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message


// Close the connection
mysqli_close($connection);


?>

<script>
// Display the toast message if set
var toastMessage = "<?php echo isset($toast_message) ? $toast_message : ''; ?>";
if (toastMessage) {
    alert(toastMessage); // Display the message in a simple alert (you can replace it with a toast)
}
</script>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | Kesong Puti</title>

    <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../css/admin.css"/>

</head>
<body>

      <!-- SHOP TAB -->
      <!-- PRODUCTS -->
 <div class="main-content">

      <div class="box content-shop" id="products-content" >
        <h1>Products Management</h1>

        <div class="filter-bar">
          <input
            type="text"
            id="productSearch"
            placeholder="Search product by name..."
          />

          <select id="categoryFilter">
            <option value="all">All Categories</option>
            <option value="cheese">Cheese</option>
            <option value="ice-cream">Ice Cream</option>
            <option value="snack">Salad</option>
          </select>

          <h2>Total Products: 100</h2>
        </div>


 <!-- PRODUCTS -->
        <div class="product-grid" id="productGrid">
          <!-- Product -->
          <div class="product-card" data-name="Classic Kesong Puti">
            <img src="../../assets/kesong puti.png" alt="Product 1" />
            <div class="product-info">
              <h3>Classic Kesong Puti</h3>
              <p>₱120</p>
              <button class="view-btn">View Details</button>
            </div>
          </div>

          <div class="product-card" data-name="Classic Kesong Puti">
            <img src="../../assets/kesong puti.png" alt="Product 1" />
            <div class="product-info">
              <h3>Classic Kesong Puti</h3>
              <p>₱120</p>
              <button class="view-btn">View Details</button>
            </div>
          </div>

          <div class="product-card" data-name="Classic Kesong Puti">
            <img src="../../assets/kesong puti.png" alt="Product 1" />
            <div class="product-info">
              <h3>Classic Kesong Puti</h3>
              <p>₱120</p>
              <button class="view-btn">View Details</button>
            </div>
          </div>

          <div class="product-card" data-name="Classic Kesong Puti">
            <img src="../../assets/kesong puti.png" alt="Product 1" />
            <div class="product-info">
              <h3>Classic Kesong Puti</h3>
              <p>₱120</p>
              <button class="view-btn">View Details</button>
            </div>
          </div>

          <div class="product-card" data-name="Classic Kesong Puti">
            <img src="../../assets/kesong puti.png" alt="Product 1" />
            <div class="product-info">
              <h3>Classic Kesong Puti</h3>
              <p>₱120</p>
              <button class="view-btn">View Details</button>
            </div>
          </div>

          <div class="product-card" data-name="Classic Kesong Puti">
            <img src="../../assets/kesong puti.png" alt="Product 1" />
            <div class="product-info">
              <h3>Classic Kesong Puti</h3>
              <p>₱120</p>
              <button class="view-btn">View Details</button>
            </div>
          </div>

          <div class="product-card" data-name="Classic Kesong Puti">
            <img src="../../assets/kesong puti.png" alt="Product 1" />
            <div class="product-info">
              <h3>Classic Kesong Puti</h3>
              <p>₱120</p>
              <button class="view-btn">View Details</button>
            </div>
          </div>

          <div class="product-card" data-name="Classic Kesong Puti">
            <img src="../../assets/kesong puti.png" alt="Product 1" />
            <div class="product-info">
              <h3>Classic Kesong Puti</h3>
              <p>₱120</p>
              <button class="view-btn">View Details</button>
            </div>
          </div>

          <div class="product-card" data-name="Classic Kesong Puti">
            <img src="../../assets/kesong puti.png" alt="Product 1" />
            <div class="product-info">
              <h3>Classic Kesong Puti</h3>
              <p>₱120</p>
              <button class="view-btn">View Details</button>
            </div>
          </div>

          <div class="product-card" data-name="Classic Kesong Puti">
            <img src="../../assets/kesong puti.png" alt="Product 1" />
            <div class="product-info">
              <h3>Classic Kesong Puti</h3>
              <p>₱120</p>
              <button class="view-btn">View Details</button>
            </div>
          </div>
        </div>
        <!-- PRODUCTS -->
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