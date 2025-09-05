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

         
        </div>


      <!-- Footer -->
      <div class="cart-footer p-3 border-top">
        <div class="d-flex justify-content-between mb-2">
          <strong>Total: </strong><span id="cartTotal">₱0.00</span>
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


      <script>
        const cartContainer = document.getElementById("cartItems");
        const cartTotalEl = document.getElementById("cartTotal");
        const cartBadge = document.getElementById("cartCount");

        // Load cart from localStorage or empty array
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        // Save to localStorage
        function saveCart() {
          localStorage.setItem("cart", JSON.stringify(cart));
        }

        // Update cart badge (icon number)
        function updateCartBadge() {
          let totalQty = cart.reduce((sum, p) => sum + p.qty, 0);
          cartBadge.textContent = totalQty;
        }

        // Render cart items
        function renderCart() {
          cartContainer.innerHTML = `
            <div class="d-flex align-items-center mb-2">
              <input type="checkbox" id="selectAll" class="me-2" />
              <label for="selectAll" class="mb-0 fw-bold">Select All</label>
            </div>
          `;

          if (cart.length === 0) {
            cartContainer.innerHTML += `<p class="text-muted empty-message">Your cart is empty.</p>`;
            cartTotalEl.textContent = "₱0.00";
            updateCartBadge();
            return;
          }

          cart.forEach(product => {
            const item = document.createElement("div");
            item.classList.add("cart-item", "d-flex", "align-items-center", "border-bottom", "py-2");
            item.setAttribute("data-id", product.id);
            item.innerHTML = `
              <input type="checkbox" class="cart-check me-2" checked />
              <img src="${product.image}" alt="${product.name}" class="cart-img me-2" width="50"/>
              <div class="flex-grow-1">
                <h6 class="mb-1">${product.name}</h6>
                <div class="d-flex align-items-center">
                  <button class="btn-qty minus">−</button>
                  <span class="qty mx-2">${product.qty}</span>
                  <button class="btn-qty plus">+</button>
                </div>
                <strong class="item-price d-block mt-1" data-price="${product.price}">
                  ₱${(product.price * product.qty).toFixed(2)}
                </strong>
              </div>
              <button class="btn-delete"><i class="bi bi-trash"></i></button>
            `;
            cartContainer.appendChild(item);
          });

          updateCartTotal();
          updateCartBadge();
        }

        // Update total
        function updateCartTotal() {
          let total = cart.reduce((sum, p) => sum + (p.price * p.qty), 0);
          cartTotalEl.textContent = `₱${total.toFixed(2)}`;
        }

        // Add to cart
        function addToCart(product) {
          let existing = cart.find(p => p.id === product.id);
          if (existing) {
            existing.qty++;
          } else {
            cart.push({ ...product, qty: 1 });
          }
          saveCart();
          renderCart();
        }

        // Event delegation for plus/minus/delete
        document.addEventListener("click", e => {
          let itemEl = e.target.closest(".cart-item");
          if (!itemEl) return;
          let id = itemEl.dataset.id;
          let product = cart.find(p => p.id === id);

          if (e.target.classList.contains("plus")) {
            product.qty++;
          }

          if (e.target.classList.contains("minus")) {
            if (product.qty > 1) {
              product.qty--;
            } else {
              cart = cart.filter(p => p.id !== id);
            }
          }

          if (e.target.closest(".btn-delete")) {
            cart = cart.filter(p => p.id !== id);
          }

          saveCart();
          renderCart();
        });

        // Restore cart on page load
        renderCart();
      </script>


     

<!-- FUNCTIONS -->
</body>
</html>