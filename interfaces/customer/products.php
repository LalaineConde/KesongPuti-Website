
<?php
$page_title = 'Customer Products | Kesong Puti';
require '../../connection.php';
$page_header = "PRODUCTS";
include ('../../includes/customer-dashboard.php');

$toast_message = ''; // Initialize variable for toast message

// Fetch all products
$sql = "SELECT * FROM products";
$result = mysqli_query($connection, $sql);



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

    <!-- PRODUCTS -->
    <section class="product-section">
      <div class="container product-contents">
        <div class="pt-5 filter-bar">
          <!-- filter -->
          <div class="d-flex align-items-center filter-options">
            <span class="filter-label">Filter:</span>

            <!-- Type Dropdown -->
            <div class="dropdown">
              <button
                class="dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Type <i class="bi bi-chevron-down"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Keso</a></li>
                <li><a class="dropdown-item" href="#">Ice Cream</a></li>
                <li><a class="dropdown-item" href="#">Kotse</a></li>
              </ul>
            </div>

            <!-- Availability Dropdown -->
            <div class="dropdown">
              <button
                class="dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Availability <i class="bi bi-chevron-down"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">In Stock</a></li>
                <li><a class="dropdown-item" href="#">Out of Stock</a></li>
              </ul>
            </div>

            <!-- Branches Dropdown -->
            <div class="dropdown">
              <button
                class="dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Branches <i class="bi bi-chevron-down"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Branch 1</a></li>
                <li><a class="dropdown-item" href="#">Branch 2</a></li>
                <li><a class="dropdown-item" href="#">Branch 3</a></li>
              </ul>
            </div>
          </div>
          <!-- product number -->
          <div class="product-count">50 products</div>
        </div>

        <!-- product list -->
          <div class="row g-4 mt-2">
          <?php if (mysqli_num_rows($result) > 0) { ?>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
              <div class="col-md-3 col-sm-6">
                <div class="card product-card h-100">
                  <img 
                    src="../../<?= htmlspecialchars($row['product_image'] ?: '/assets/default.png') ?>" 
                    class="card-img-top"
                    alt="<?= htmlspecialchars($row['product_name']) ?>"
                  >
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($row['product_name']) ?></h5>
                    <p class="product-price">₱<?= number_format($row['price'], 2) ?></p>
                    <p class="small text-muted flex-grow-1"><?= htmlspecialchars($row['description']) ?></p>
                    
                    <!-- View / Add to Bag Buttons -->
                    <button 
                      type="button" 
                      class="btn btn-outline-dark view-btn"
                      data-id="<?= $row['product_id'] ?>"
                      data-name="<?= htmlspecialchars($row['product_name']) ?>"
                      data-desc="<?= htmlspecialchars($row['description']) ?>"
                      data-price="<?= htmlspecialchars($row['price']) ?>"
                      data-stock="<?= htmlspecialchars($row['stock_qty']) ?>"
                      data-category="<?= htmlspecialchars($row['category']) ?>"
                      data-image="../../<?= htmlspecialchars($row['product_image'] ?: 'assets/default.png') ?>">
                      View
                    </button>

                    <form method="POST" action="add-to-cart.php">
                      <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">

                      <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-bag-plus"></i> Add to Bag
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
          <p><strong>Description:</strong> <span id="modalProductDesc"></span></p>
          <p><strong>Price:</strong> ₱<span id="modalProductPrice"></span></p>
          <p><strong>Availability:</strong> <span id="modalProductStock"></span></p>
          <p><strong>Category:</strong> <span id="modalProductCategory"></span></p>

          <form method="POST" action="add-to-cart.php">
            <input type="hidden" name="product_id" id="modalProductId">
            <button type="submit" class="btn btn-dark w-100 mt-2">
              <i class="bi bi-bag-plus"></i> Add to Bag
            </button>
          </form>
        </div>
      </div>

        <!-- pagination -->
        <nav>
          <ul class="pagination pb-5">
            <li class="page-item disabled">
              <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
            <li class="page-item active">
              <a class="page-link shadow-none" href="#">1</a>
            </li>
            <li class="page-item">
              <a class="page-link shadow-none" href="#">2</a>
            </li>
            <li class="page-item">
              <a class="page-link shadow-none" href="#">3</a>
            </li>
            <li class="page-item">
              <a class="page-link shadow-none" href="#">Next</a>
            </li>
          </ul>
        </nav>
      </div>
    </section>
    <!-- PRODUCTS -->

    <!-- BOOTSTRAP JS -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
      crossorigin="anonymous"
    ></script>




    <!-- PRODUCT FILTER FUNCTION -->
    <script>
      // Replace dropdown button text when option clicked
      document
        .querySelectorAll(".dropdown-menu .dropdown-item")
        .forEach((item) => {
          item.addEventListener("click", function (e) {
            e.preventDefault();

            let dropdown = this.closest(".dropdown");
            let button = dropdown.querySelector(".dropdown-toggle");

            // Update button text with chosen option
            button.innerHTML =
              this.textContent + ' <i class="bi bi-chevron-down"></i>';
          });
        });
    </script>


    <!-- VIEW DETAILS -->
    <script>
      const customerModal = document.getElementById("customerViewModal");
      const closeCustomerModal = document.querySelector(".close-customer-view");

      document.querySelectorAll(".view-btn").forEach(btn => {
        btn.addEventListener("click", function() {
          // Fill modal with data
          document.getElementById("modalProductId").value = this.dataset.id;
          document.getElementById("modalProductName").textContent = this.dataset.name;
          document.getElementById("modalProductDesc").textContent = this.dataset.desc;
          document.getElementById("modalProductPrice").textContent = parseFloat(this.dataset.price).toFixed(2);
          document.getElementById("modalProductStock").textContent = this.dataset.stock > 0 ? "In Stock" : "Out of Stock";
          document.getElementById("modalProductCategory").textContent = this.dataset.category;
          document.getElementById("modalProductImage").src = this.dataset.image;

          customerModal.style.display = "flex";
        });
      });

      // Close modal
      closeCustomerModal.onclick = () => { customerModal.style.display = "none"; };
      window.onclick = (e) => { if (e.target === customerModal) customerModal.style.display = "none"; };
    </script>

    <?php include('../../includes/footer.php'); ?>
  </body>
</html>
