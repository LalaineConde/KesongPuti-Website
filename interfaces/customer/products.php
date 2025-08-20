<?php
$page_title = 'Overview | Kesong Puti';
require '../../connection.php'; 


$toast_message = ''; // Initialize variable for toast message


  

// Close the connection
mysqli_close($connection);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
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
          ><img 
            src="../../assets/logo.png" 
            alt="Kesong Puti" /></a>
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
      <div class="cart-header">
        <h5>Your Cart</h5>
        <button class="btn btn-sm btn-outline-secondary" id="closeCart">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>
      <div class="cart-body">
        <div class="cart-item">
          <img src="https://picsum.photos/60" alt="Product" />
          <div>
            <h6>Product 1</h6>
            <p class="mb-1">Qty: 1</p>
            <strong>$25</strong>
          </div>
        </div>
        <div class="cart-item">
          <img src="https://picsum.photos/61" alt="Product" />
          <div>
            <h6>Product 2</h6>
            <p class="mb-1">Qty: 2</p>
            <strong>$40</strong>
          </div>
        </div>
      </div>
      <div class="cart-footer">
        <button class="btn btn-dark w-100">Checkout</button>
      </div>
    </div>

    <!-- CART OVERLAY -->
    <div class="overlay" id="overlay"></div>

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
          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img 
                src="../../assets/banana-leaf.png" 
                class="card-img-top" 
                lt="Product Image" 
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Another Product</h5>
                <p class="product-price">₱2,499.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-6">
            <div class="card product-card">
              <img
                src="../../assets/banana-leaf.png"
                class="card-img-top"
                alt="Product Image"
              />
              <div class="card-body">
                <h5 class="card-title">Product Name</h5>
                <p class="product-price">₱1,250.00</p>
                <button class="btn btn-view">View</button>
              </div>
            </div>
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
            <img src="../../assets/logo.png" alt="Kesong Puti" />
            <div class="social-icons">
              <div class="social-circle facebook">
                <a href="https://www.facebook.com/AlohaKesorbetes" target="_blank" class="social-circle facebook">
                  <i class="bi bi-facebook"></i>
                </a>
              </div>
              <div class="social-circle instagram">
                <a href="https://www.instagram.com/arlene_macalinao_kesongputi/" target="_blank" class="social-circle instagram">
                  <i class="bi bi-instagram"></i>
                </a>
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
<form action="contact.php" method="POST">
  <input type="text" name="name" class="form-control" placeholder="Name" required />
  <input type="email" name="email" class="form-control" placeholder="Email" required />
  <input type="text" name="contact" class="form-control" placeholder="Contact Number" required />
  
  <select name="recipient" class="form-control" required>
    <option value="">-- Select Recipient --</option>
    <?php
      require '../../connection.php';

      // Fetch all super admins
      $superQuery = "SELECT super_id, username FROM super_admin";
      $superResult = mysqli_query($connection, $superQuery);

      while ($row = mysqli_fetch_assoc($superResult)) {
          echo '<option value="super_' . $row['super_id'] . '">'
            . htmlspecialchars($row['username']) . ' (Super Admin)'
            . '</option>';
      }

      // Fetch all admins
      $adminQuery = "SELECT admin_id, username FROM admins";
      $adminResult = mysqli_query($connection, $adminQuery);

      while ($row = mysqli_fetch_assoc($adminResult)) {
          echo '<option value="admin_' . $row['admin_id'] . '">'
            . htmlspecialchars($row['username']) . ' (Admin)'
            . '</option>';
      }

      
    ?>
  </select>
  <textarea name="message" class="form-control" rows="3" placeholder="Message" required></textarea>
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
              
                <p><a href="https://www.instagram.com/arlene_macalinao_kesongputi/" target="_blank" class="contact-info"><i class="bi bi-envelope"></i> hernandezshy00@gmail.com </a></p>
              
              <p><a href="tel:+639997159226"><i class="bi bi-telephone"></i> +63 999 715 9226 </a></p>
              <p>
                <a href="https://maps.app.goo.gl/XhDrJM3vM9fk9WPg9" target="_blank">
                <i class="bi bi-geo-alt"></i> 4883 Sitio 3 Brgy. Bagumbayan, Santa Cruz, Philippines, 4009
              </a>
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
      // Cart functionality
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
  </body>
</html>



