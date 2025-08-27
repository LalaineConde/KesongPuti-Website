<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

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
            <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact Us</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
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
     
    <!-- PAGE HEADER -->
    <section class="product-page">
    <div>
        <h1 class="mt-5"><?= isset($page_header) ? $page_header : "WELCOME"; ?></h1>
    </div>
    </section>
    <!-- PRODUCTS PAGE HEADER -->

    
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
            src="assets/kesong puti.png"
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
            src="assets/kesong puti.png"
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

<!-- FUNCTIONS -->
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
<!-- FUNCTIONS -->
</body>
</html>