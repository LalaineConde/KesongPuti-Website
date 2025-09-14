<?php


// Fetch site settings
$settings = [];
$query = mysqli_query($connection, "SELECT * FROM site_settings");
while ($row = mysqli_fetch_assoc($query)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Default fallback colors
$primaryColor = $settings['primary_color'] ?? '#4e6f47';
$secondaryColor = $settings['secondary_color'] ?? '#f4c400';
$fontFamily = $settings['font_family'] ?? 'Fredoka';
?>


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
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../../css/styles.css" >

    <style>
:root {
  /* --primary-color: <?= htmlspecialchars($primaryColor) ?>;
  --secondary-color: <?= htmlspecialchars($secondaryColor) ?>; */

  --primary-font: "<?= $settings['primary_font'] ?? 'Fredoka' ?>", sans-serif;
  --page-header-font: "<?= $settings['page_header_font'] ?? 'Arial' ?>", sans-serif;

  --navbar-color: <?= $settings['navbar_color'] ?? '#000000' ?>;
  --subtitle-font-color: <?= $settings['subtitle_font_color'] ?? '#333333' ?>;
  --price-color: <?= $settings['price_color'] ?? '#333333' ?>;
  --description-color: <?= $settings['description_color'] ?? '#555555' ?>;
  
  --button1-color: <?= $settings['button1_color'] ?? '#87c159' ?>;
  --button2-color: <?= $settings['button2_color'] ?? '#f4c400' ?>;

  --faq-button-bg: <?= $settings['faq_button_bg'] ?? '#000000' ?>;
  --faq-answer-bg: <?= $settings['faq_answer_bg'] ?? '#ffffff' ?>;
  --product-page-number-bg: <?= $settings['product_page_number_bg'] ?? '#000000' ?>;
}

body {
    font-family: var(--font-family);
}

.navbar {
    position: fixed;   /* make it stick */
    top: 0;
    left: 0;
    right: 0;
    z-index: 1050;     /* above all content */
    transition: background-color 0.4s ease, transform 0.4s ease;
}


/* Apply primary only when scrolled */
.navbar-scrolled {
    background-color: var(--navbar-color) !important;  
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Transparent at top */
.navbar-transparent {
    background: transparent !important;
}

/* Other elements keep primary */
.cart-header, .cart-footer, .btn-dark {
    background-color: var(--primary-color) !important;
}




    
</style>
  </head>
<body>
    
    <!-- NAVBAR --> 
<nav class="navbar navbar-expand-lg fixed-top navbar-transparent navbar-hidden navbar-visible" id="mainNavbar">

   
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
            <li class="nav-item"><a class="nav-link" href="FAQ.php">FAQ</a></li>
            <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact Us</a>
            </li>          
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
 
    <!-- PRODUCTS PAGE HEADER -->

    
    <!-- CART SIDEBAR -->
    <div class="cart-sidebar" id="cartSidebar">
      <!-- header -->
      <div class="cart-header">
        <h5><i class="bi bi-bag me-2"></i> Your Bag</h5>
        <button class="close-btn" id="closeCart">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <!-- Body -->
      <div class="cart-body" id="cartItems">
        <!-- checkbox -->
        <div class="select-all">
          <input type="checkbox" id="selectAll" />
          <label for="selectAll">Select All</label>
        </div>

        <!-- items -->
        
      </div>

      <!-- footer -->
      <div class="cart-footer">
        <div class="total">
          <span>Total:</span>
          <strong id="cartTotal">₱0.00</strong>
        </div>
        <button class="checkout-btn">Checkout</button>
      </div>
    </div>

    <div class="overlay" id="overlay"></div>
    <!-- CART SIDEBAR -->

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

  function updateNavbar() {
    if (window.scrollY <= 0) {
      navbar.classList.add("navbar-transparent");
      navbar.classList.remove("navbar-scrolled");
    } else {
      navbar.classList.add("navbar-scrolled");
      navbar.classList.remove("navbar-transparent");
    }
  }

  window.addEventListener("scroll", updateNavbar, { passive: true });
  window.addEventListener("load", updateNavbar);
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
                <span class="cart-branch">${product.store}</span>
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