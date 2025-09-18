
<?php
$page_title = 'Customer Products | Kesong Puti';
require '../../connection.php';
$current_page = 'products'; 
$page_subheader = "Explore our Kesong Puti Delicacy";
include ('../../includes/customer-dashboard.php');

$toast_message = ''; // Initialize variable for toast message


// Fetch settings
$settings = [];
$query = mysqli_query($connection, "SELECT * FROM site_settings");
while ($row = mysqli_fetch_assoc($query)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Fetch header text
$result = mysqli_query($connection, "SELECT header_text FROM page_headers WHERE page_name='$current_page' LIMIT 1");
$row = mysqli_fetch_assoc($result);
$page_header = $row['header_text'] ?? "WELCOME";

$where = [];

if (!empty($_GET['type'])) {
    $type = strtolower($_GET['type']); 
    $type = mysqli_real_escape_string($connection, $type);

    $where[] = "LOWER(p.category) = '$type'";
}


if (!empty($_GET['availability'])) {
    if ($_GET['availability'] === "Available") {
        $where[] = "p.stock_qty > 0";
    } elseif ($_GET['availability'] === "Out of Stock") {
        $where[] = "p.stock_qty = 0";
    }
}

if (!empty($_GET['store'])) {
    $store_id = (int) $_GET['store'];
    $where[] = "st.store_id = $store_id";
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

// Pagination 
$limit = 8;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Count total filtered products
$count_sql = "
    SELECT COUNT(*) AS total
    FROM products p
    LEFT JOIN store st 
      ON p.owner_id = st.owner_id OR p.owner_id = st.super_id
    $where_sql
";




$count_result = mysqli_query($connection, $count_sql);
$total_products = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_products / $limit);

// Fetch filtered products
$sql = "
    SELECT p.*, COALESCE(st.store_name, 'Superadmin') AS recipient
    FROM products p
    LEFT JOIN store st 
      ON p.owner_id = st.owner_id OR p.owner_id = st.super_id
    $where_sql
    ORDER BY p.product_id DESC
    LIMIT $limit OFFSET $offset
";


$result = mysqli_query($connection, $sql);


$query_params = $_GET;
unset($query_params['page']);
$base_url = '?' . http_build_query($query_params);

// Fetch all stores for Store dropdown
$store_sql = "SELECT store_id, store_name FROM store";
$store_result = mysqli_query($connection, $store_sql);
?>





<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kesong Puti - Products</title>

    <!-- BOOTSTRAP -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr"
      crossorigin="anonymous"
    >

    <!-- ICONS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    >

    <!-- CSS -->
    <link rel="stylesheet" href="../../css/styles.css" >




  </head>

  <body>


    <!-- BRANDING -->
    <section id="branding-section">
      <div class="container">
        <div class="row g-4">
          <!--  -->
          <div class="col-lg-4 branding">
            <i class="bi bi-heart"></i>
            <h2>Authentic Filipino Tradition</h2>
            <p>
              Taste a Piece of Filipino Heritage. Our kesong puti is crafted
              with a time-honored recipe passed down through generations
            </p>
          </div>

          <!--  -->
          <div class="col-lg-4 branding">
            <i class="bi bi-person"></i>
            <h2>Freshness from Local Farms</h2>
            <p>
              Farm-Fresh Goodness. We use carabao’s milk sourced daily from
              local farmers to ensure maximum freshness and flavor.
            </p>
          </div>

          <!--  -->
          <div class="col-lg-4 branding">
            <i class="bi bi-leaf"></i>
            <h2>Simple, Pure Ingredients</h2>
            <p>
              Pure and Simple. Absolutely Delicious. Made only with fresh
              carabao’s milk, salt, and rennet, our cheese has no
              preservatives—just natural flavor.
            </p>
          </div>
        </div>
      </div>
    </section>
    <!-- BRANDING -->



    <!-- PRODUCTS -->
    <section class="product-section">
      <div class="container product-contents">
        <div class="pt-5 filter-bar">
          <!-- filter -->

          <!-- filter -->
        <div class="d-flex align-items-center filter-options">
          <span class="filter-label">Filter:</span>

          <!-- Type Dropdown -->
          <div class="dropdown">
            <button id="typeDropdown" 
            class="dropdown-toggle" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">
              Type <i class="bi bi-chevron-down"></i>
            </button>
            <ul class="dropdown-menu">
              <li><button class="dropdown-item type-option" data-type="">All Categories</button></li>
              <li><button class="dropdown-item type-option" data-type="cheese">Cheese</button></li>
              <li><button class="dropdown-item type-option" data-type="ice-cream">Ice Cream</button></li>
            </ul>
          </div>

          <!-- Availability Dropdown -->
          <div class="dropdown">
            <button id="availabilityDropdown" 
            class="dropdown-toggle" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">
              Availability <i class="bi bi-chevron-down"></i>
            </button>
            <ul class="dropdown-menu">
                <li><button class="dropdown-item availability-option" data-availability="">All Availability</button></li>
                <li><button class="dropdown-item availability-option" data-availability="Available">In Stock</button></li>
                <li><button class="dropdown-item availability-option" data-availability="Out of Stock">Out of Stock</button></li>
            </ul> 
          </div>

          <!-- Stores Dropdown -->
          <div class="dropdown">
            <button id="storeDropdown" 
            class="dropdown-toggle" 
            type="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false">
              Stores <i class="bi bi-chevron-down"></i>
            </button>
            <ul class="dropdown-menu">
              <li><button class="dropdown-item store-option" data-store="">All Stores</button></li>
              <?php while ($store = mysqli_fetch_assoc($store_result)) { ?>
                <li>
                  <button class="dropdown-item store-option" data-store="<?= $store['store_id'] ?>">
                    <?= htmlspecialchars($store['store_name']) ?>
                  </button>
                </li>
              <?php } ?>
            </ul>
          </div>
              
        </div>


          <!-- product number -->
          <div class="product-count">
            <?= $total_products ?> products
          </div>
        </div>

        <!-- product list -->
          <div class="row g-4 mt-2">
          <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
              <div class="col-md-3 col-sm-6">
                <div class="card product-card">
                  <img 
                    src="../../<?= htmlspecialchars($row['product_image'] ?: '/assets/default.png') ?>" 
                    class="card-img-top"
                    alt="<?= htmlspecialchars($row['product_name']) ?>"
                  >
                  <div class="card-body">
                    <p class="admin-label">Store: <?= htmlspecialchars($row['recipient'] ?? 'Unknown') ?></p>
                    <h5 class="card-title"><?= htmlspecialchars($row['product_name']) ?></h5>
                    <p class="product-price">₱<?= number_format($row['price'], 2) ?></p>
                    
                     <!-- text-muted flex-grow-1 -->
                    <!-- View / Add to Bag Buttons -->
                    <button 
                      type="button" 
                      class="btn-view mb-1"
                      data-id="<?= $row['product_id'] ?>"
                      data-name="<?= htmlspecialchars($row['product_name']) ?>"
                      data-desc="<?= htmlspecialchars($row['description']) ?>"
                      data-price="<?= htmlspecialchars($row['price']) ?>"
                      data-stock="<?= htmlspecialchars($row['stock_qty']) ?>"
                      data-category="<?= htmlspecialchars($row['category']) ?>"
                      data-image="../../<?= htmlspecialchars($row['product_image'] ?: 'assets/default.png') ?>"
                      data-admin="<?= htmlspecialchars($row['recipient'] ?? 'Unknown') ?>">
                      View
                    </button>

                    <form method="POST" action="add-to-cart.php" class="cart-form-btn">
                      <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">

                      <button 
                        type="button" 
                        class="btn-add-to-cart"
                        data-id="<?= $row['product_id'] ?>"
                        data-name="<?= htmlspecialchars($row['product_name']) ?>"
                        data-price="<?= htmlspecialchars($row['price']) ?>"
                        data-image="../../<?= htmlspecialchars($row['product_image'] ?: 'assets/default.png') ?>"
                        data-store="<?= htmlspecialchars($row['recipient'] ?? 'Unknown') ?>">
                        Add to Bag
                      </button>

                    </form>
                  </div>
                </div>
              </div>
            <?php } ?>
          <?php } else { ?>
            <p class="text-center">No products available.</p>
          <?php } ?>
        </div>
      </div>

      <!-- View Product Modal for Customers -->
      <div class="product-modal" id="customerViewModal">
        <div class="modal-content">
          <span class="close-customer-view">&times;</span>
          <h2 id="modalProductName"></h2>
          <img id="modalProductImage" src="" alt="Product Image" style="max-width:200px; display:block; margin-bottom:10px;">
          <p><strong>Store:</strong> <span id="view_recipient"></span></p>
          <p><strong>Description:</strong> <span id="modalProductDesc"></span></p>
          <p><strong>Price:</strong> ₱<span id="modalProductPrice"></span></p>
          <p><strong>Availability:</strong> <span id="modalProductStock"></span></p>
          <p><strong>Category:</strong> <span id="modalProductCategory"></span></p>

          <form method="POST" action="add-to-cart.php" class="cart-form-btn">
          <input type="hidden" name="product_id" id="modalProductId">
          <button 
            type="button"
            class="btn-add-to-cart" 
            
              data-id=""
              data-name=""
              data-price=""
              data-image=""
              data-store="">
              Add to Bag
            </button> 
          </form>
        </div>
      </div>

        <!-- pagination -->
        <nav aria-label="Page navigation" class="pagination-wrapper mb-5">
          <ul class="pagination pb-5 justify-content-center">
            <!-- Previous -->
            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $base_url ?>&page=<?= max(1, $page - 1) ?>">Previous</a>
            </li>

            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" href="<?= $base_url ?>&page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>

            <!-- Next -->
            <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= $base_url ?>&page=<?= min($total_pages, $page + 1) ?>">Next</a>
            </li>
          </ul>
        </nav>
      </div>
    </section>
    <!-- PRODUCTS -->

    <!-- BOOTSTRAP JS -->
    <script
      src="hhttps://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
      crossorigin="anonymous"
    ></script>




    <!-- PRODUCT FILTER FUNCTION -->
    <script>
      function updateFilters(param, value) {
        const urlParams = new URLSearchParams(window.location.search);

        // Always reset to page 1 when changing filters
        urlParams.set("page", 1);

        if (value && value !== "") {
          urlParams.set(param, value);
        } else {
          urlParams.delete(param); // Remove filter if "All" selected
        }

        window.location.search = urlParams.toString();
      }

      // Apply event listeners
      document.querySelectorAll(".type-option").forEach(item => {
        item.addEventListener("click", () => {
          updateFilters("type", item.dataset.type);
        });
      });

      document.querySelectorAll(".availability-option").forEach(item => {
        item.addEventListener("click", () => {
          updateFilters("availability", item.dataset.availability);
        });
      });

      document.querySelectorAll(".store-option").forEach(item => {
        item.addEventListener("click", () => {
          updateFilters("store", item.dataset.store);
        });
      });

      // Set dropdown labels based on current filters
      const urlParams = new URLSearchParams(window.location.search);

      // Type
      const typeParam = urlParams.get("type");
      if (typeParam && typeParam !== "") {
        let displayType = typeParam.replace("-", " "); 
        displayType = displayType.replace(/\b\w/g, l => l.toUpperCase()); 
        document.getElementById("typeDropdown").innerHTML =
          displayType + ' <i class="bi bi-chevron-down"></i>';
      } else {
        document.getElementById("typeDropdown").innerHTML =
          'All Categories <i class="bi bi-chevron-down"></i>';
      }

      // Availability
      const availParam = urlParams.get("availability");
      let availLabel = "All Availability";
      if (availParam === "Available") availLabel = "In Stock";
      else if (availParam === "Out of Stock") availLabel = "Out of Stock";
      document.getElementById("availabilityDropdown").innerHTML =
        availLabel + ' <i class="bi bi-chevron-down"></i>';

      // Store
      const storeParam = urlParams.get("store");
      if (storeParam && storeParam !== "") {
        const selectedStore = document.querySelector(
          `.store-option[data-store="${storeParam}"]`
        );
        if (selectedStore) {
          document.getElementById("storeDropdown").innerHTML =
            selectedStore.textContent + ' <i class="bi bi-chevron-down"></i>';
        }
      } else {
        document.getElementById("storeDropdown").innerHTML =
          'All Stores <i class="bi bi-chevron-down"></i>';
      }
    </script>


    


    <!-- VIEW DETAILS -->
    <script>
      const customerModal = document.getElementById("customerViewModal");
      const closeCustomerModal = document.querySelector(".close-customer-view");

      document.querySelectorAll(".btn-view").forEach(btn => {
        btn.addEventListener("click", function() {
          // Fill modal with data
          document.getElementById("modalProductId").value = this.dataset.id;
          document.getElementById("modalProductName").textContent = this.dataset.name;
          document.getElementById("modalProductDesc").textContent = this.dataset.desc;
          document.getElementById("modalProductPrice").textContent = parseFloat(this.dataset.price).toFixed(2);
          document.getElementById("modalProductStock").textContent = this.dataset.stock > 0 ? "In Stock" : "Out of Stock";
          document.getElementById("modalProductCategory").textContent = this.dataset.category;
          document.getElementById("modalProductImage").src = this.dataset.image;
          document.getElementById("view_recipient").textContent = this.dataset.admin;

          const modalAddBtn = customerModal.querySelector(".btn-add-to-cart");
          modalAddBtn.dataset.id = this.dataset.id;
          modalAddBtn.dataset.name = this.dataset.name;
          modalAddBtn.dataset.price = this.dataset.price;
          modalAddBtn.dataset.image = this.dataset.image;
          modalAddBtn.dataset.store = this.dataset.admin;

          customerModal.style.display = "flex";
        });
      });

      // Close modal
      closeCustomerModal.onclick = () => { customerModal.style.display = "none"; };
      window.onclick = (e) => { if (e.target === customerModal) customerModal.style.display = "none"; };
    </script>


      <!-- ADD TO CART -->
       <script>
        document.querySelectorAll(".btn-add-to-cart").forEach(btn => {
          btn.addEventListener("click", () => {
            const product = {
              id: btn.dataset.id,
              name: btn.dataset.name,
              price: parseFloat(btn.dataset.price),
              image: btn.dataset.image,
              store: btn.dataset.store
            };
            addToCart(product); 
          });
        });
      </script>

    <?php include('../../includes/footer.php'); ?>
  </body>
</html>
