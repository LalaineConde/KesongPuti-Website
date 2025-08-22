
<?php
$page_title = 'Customer Products | Kesong Puti';
require '../../connection.php';

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
    <!-- NAVBAR -->
    <nav
      class="navbar navbar-expand-lg fixed-top navbar-transparent navbar-hidden navbar-visible"
      id="mainNavbar"
    >
      <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#"
          ><img src="../../assets/logo.png" alt="Kesong Puti"
        /></a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div
          class="collapse navbar-collapse justify-content-center"
          id="navbarNav"
        >
          <ul class="navbar-nav mx-auto">
            <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#">About</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
            <li class="nav-item">
              <a class="nav-link" href="#">Contact Us</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="#">Feedback</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Order Now</a></li>
          </ul>
        </div>

        <!-- CART ICON -->
        <div
          class="cart-icon position-relative d-none d-lg-block"
          id="cartBtn"
          style="cursor: pointer"
        >
          <i class="bi bi-bag-fill fs-4"></i>
          <span class="cart-badge" id="cartCount">2</span>
        </div>
        <!-- CART ICON -->
      </div>
    </nav>
    <!-- NAVBAR -->

    <!-- CART SIDEBAR -->
    <div class="cart-sidebar" id="cartSidebar">
      <div
        class="cart-header d-flex justify-content-between align-items-center p-3 border-bottom"
      >
        <h5 class="mb-0">My Cart</h5>
        <button class="btn btn-md" id="closeCart">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <div class="cart-body p-3" id="cartItems">
        <!-- Select All -->
        <div class="d-flex align-items-center mb-2">
          <input type="checkbox" id="selectAll" class="me-2" />
          <label for="selectAll" class="mb-0 fw-bold">Select All</label>
        </div>

        <!-- Example Cart Item -->
        <div class="cart-item d-flex align-items-center border-bottom py-2">
          <input type="checkbox" class="cart-check me-2" checked />
          <img
            src="../../assets/kesong puti.png"
            alt="Product"
            class="cart-img me-2"
          />
          <div class="flex-grow-1">
            <h6 class="mb-1">Kesong Puti Classic</h6>
            <div class="d-flex align-items-center">
              <button class="btn-qty minus">−</button>
              <span class="qty mx-2">1</span>
              <button class="btn-qty plus">+</button>
            </div>
            <strong class="item-price d-block mt-1" data-price="25">₱25</strong>
            <span class="cart-branch">Branch 1</span>
          </div>
          <button class="btn-delete"><i class="bi bi-trash"></i></button>
        </div>

        <div class="cart-item d-flex align-items-center border-bottom py-2">
          <input type="checkbox" class="cart-check me-2" checked />
          <img
            src="../../assets/kesong puti.png"
            alt="Product"
            class="cart-img me-2"
          />
          <div class="flex-grow-1">
            <h6 class="mb-1">Kesong Puti Premium</h6>
            <div class="d-flex align-items-center">
              <button class="btn-qty minus">−</button>
              <span class="qty mx-2">2</span>
              <button class="btn-qty plus">+</button>
            </div>
            <strong class="item-price d-block mt-1" data-price="40">₱40</strong>
          </div>
          <button class="btn-delete"><i class="bi bi-trash"></i></button>
        </div>
      </div>

      <!-- Footer -->
      <div class="cart-footer p-3 border-top">
        <div class="d-flex justify-content-between mb-2">
          <strong>Total:</strong>
          <span id="cartTotal">₱65</span>
        </div>
        <button class="btn btn-dark w-100">Checkout</button>
      </div>
    </div>
    <!-- CART SIDEBAR -->

    <!-- CART OVERLAY -->
    <div class="overlay" id="overlay"></div>
    <!-- CART OVERLAY -->

    <!-- PRODUCTS PAGE HEADER -->
    <section class="product-page">
      <div>
        <h1 class="mt-5">OUR PRODUCTS</h1>
      </div>
    </section>
    <!-- PRODUCTS PAGE HEADER -->

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

    <!-- FOOTER -->
    <footer>
      <div class="container-fluid footer-container">
        <div id="leaves">
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
          <i></i>
        </div>
        <div class="row gy-4">
          <!-- Logo + Social -->
          <div class="col-md-4 text-center footer-logo">
            <img src="../../assets/logo.png" alt="Kesong Puti Logo" />
            <div class="social-icons">
              <div class="social-circle facebook">
                <i class="bi bi-facebook"></i>
              </div>
              <div class="social-circle instagram">
                <i class="bi bi-instagram"></i>
              </div>
            </div>
          </div>

          <!-- Contact Form -->
          <div class="col-md-4 contact-form">
            <h5 class="fw-bold mb-3">Contact Us</h5>
            <p class="small">
              We’d love to hear from you! Send us a message—we’ll get back to
              you as soon as we can!
            </p>
            <form>
              <input type="text" class="form-control" placeholder="Name" />
              <input type="email" class="form-control" placeholder="Email" />
              <input
                type="text"
                class="form-control"
                placeholder="Contact Number"
              />
              <textarea
                class="form-control"
                rows="3"
                placeholder="Message"
              ></textarea>
              <button type="submit" class="submit-btn mt-2">Submit</button>
            </form>
          </div>

          <!-- Links & Info -->
          <div class="col-md-4">
            <div class="footer-links">
              <h6 class="footer-title">Quick Links</h6>
              <a href="#">Home</a>
              <a href="#">Products</a>
              <a href="#">About Us</a>
              <a href="#">FAQ</a>
            </div>
            <div class="contact-info">
              <h6 class="footer-title mt-3">Contact Information</h6>
              <p><i class="bi bi-envelope"></i> info@kesongputi.com</p>
              <p><i class="bi bi-telephone"></i> +63 912 345 6789</p>
              <p>
                <i class="bi bi-geo-alt"></i> 123 Kesong St., Laguna,
                Philippines
              </p>
            </div>
          </div>
        </div>

        <!-- Bottom -->
        <div class="footer-bottom">Kesong Puti © 2025 All Rights Reserved</div>
      </div>
    </footer>
    <!-- FOOTER -->

    <!-- BOOTSTRAP JS -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
      crossorigin="anonymous"
    ></script>

    <!-- SCROLL NAVBAR -->
    <script>
      (function () {
        const navbar = document.getElementById("mainNavbar");
        const hero = document.querySelector(".product-page");

        function setTopState() {
          // At the very top: transparent + visible
          navbar.classList.add("navbar-transparent", "navbar-visible");
          navbar.classList.remove("navbar-scrolled", "navbar-hidden");
        }

        function setHiddenTransparent() {
          // While scrolling inside hero: hide (slide up) + keep transparent
          navbar.classList.add("navbar-hidden", "navbar-transparent");
          navbar.classList.remove("navbar-visible", "navbar-scrolled");
        }

        function setVisibleColored() {
          // After hero (second section and beyond): show (slide down) + colored background
          navbar.classList.add("navbar-visible", "navbar-scrolled");
          navbar.classList.remove("navbar-hidden", "navbar-transparent");
        }

        function updateNavbar() {
          const y = window.scrollY;
          const navH = navbar.offsetHeight || 0;
          const heroH = (hero && hero.offsetHeight) || 0;
          const heroBottom = Math.max(0, heroH - navH); // threshold to "second page"

          if (y <= 0) {
            setTopState();
            return;
          }

          if (y < heroBottom) {
            // Still within the hero area → keep it hidden while scrolling down the hero
            setHiddenTransparent();
          } else {
            // Past the hero → show it with background color
            setVisibleColored();
          }
        }

        // Init + on scroll
        window.addEventListener("scroll", updateNavbar, { passive: true });
        window.addEventListener("load", updateNavbar);
        document.addEventListener("DOMContentLoaded", updateNavbar);
      })();
    </script>

    <!-- CART SIDEBAR -->
    <script>
      // Cart sidebar functionality
      const cartBtn = document.getElementById("cartBtn");
      const cartSidebar = document.getElementById("cartSidebar");
      const closeCart = document.getElementById("closeCart");
      const overlay = document.getElementById("overlay");

      cartBtn.addEventListener("click", () => {
        cartSidebar.classList.add("active");
        overlay.classList.add("active");
      });

      closeCart.addEventListener("click", () => {
        cartSidebar.classList.remove("active");
        overlay.classList.remove("active");
      });

      overlay.addEventListener("click", () => {
        cartSidebar.classList.remove("active");
        overlay.classList.remove("active");
      });

      // Cart items functionality
      function updateCartTotal() {
        let total = 0;
        document.querySelectorAll(".cart-item").forEach((item) => {
          const checkbox = item.querySelector(".cart-check");
          const priceEl = item.querySelector(".item-price");
          const qty = parseInt(item.querySelector(".qty").textContent);
          const pricePerItem =
            parseFloat(priceEl.getAttribute("data-price")) / qty;

          if (checkbox.checked) {
            total += pricePerItem * qty;
          }
        });
        document.getElementById("cartTotal").textContent = `₱${total}`;
      }

      // Quantity controls
      document.addEventListener("click", function (e) {
        if (e.target.classList.contains("plus")) {
          let qtyEl = e.target.previousElementSibling;
          let qty = parseInt(qtyEl.textContent);
          qty++;
          qtyEl.textContent = qty;

          let priceEl = e.target
            .closest(".cart-item")
            .querySelector(".item-price");
          let pricePerItem =
            parseFloat(priceEl.getAttribute("data-price")) / (qty - 1);
          let newPrice = qty * pricePerItem;
          priceEl.setAttribute("data-price", newPrice);
          priceEl.textContent = `₱${newPrice}`;
          updateCartTotal();
        }

        if (e.target.classList.contains("minus")) {
          let qtyEl = e.target.nextElementSibling;
          let qty = parseInt(qtyEl.textContent);
          if (qty > 1) {
            qty--;
            qtyEl.textContent = qty;

            let priceEl = e.target
              .closest(".cart-item")
              .querySelector(".item-price");
            let pricePerItem =
              parseFloat(priceEl.getAttribute("data-price")) / (qty + 1);
            let newPrice = qty * pricePerItem;
            priceEl.setAttribute("data-price", newPrice);
            priceEl.textContent = `₱${newPrice}`;
          }
          updateCartTotal();
        }

        if (e.target.closest(".btn-delete")) {
          e.target.closest(".cart-item").remove();
          updateCartTotal();
        }
      });

      // Checkbox updates
      document.addEventListener("change", function (e) {
        if (e.target.classList.contains("cart-check")) {
          updateCartTotal();
        }

        if (e.target.id === "selectAll") {
          let checked = e.target.checked;
          document.querySelectorAll(".cart-check").forEach((cb) => {
            cb.checked = checked;
          });
          updateCartTotal();
        }
      });

      updateCartTotal();
    </script>

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


  </body>
</html>
