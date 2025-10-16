<?php


// Fetch site settings
$settings = [];
$query = mysqli_query($connection, "SELECT * FROM site_settings");
while ($row = mysqli_fetch_assoc($query)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$header_image = "assets/header.png";

// Default homepage header parts
$home_header_part1 = "PURE";
$home_header_part2 = "CHEESE";
$home_header_part3 = "BLISS";

// Fetch homepage header font colors
$header_colors = [];
$result = mysqli_query($connection, "SELECT * FROM home_settings");
while ($row = mysqli_fetch_assoc($result)) {
    $header_colors[$row['setting_key']] = $row['setting_value'];
}

// Optional: fetch from DB if you have a table for homepage settings
$result = mysqli_query($connection, "SELECT part1, part2, part3 FROM page_headers WHERE page_name='home' LIMIT 1");
if ($row = mysqli_fetch_assoc($result)) {
    $home_header_part1 = $row['part1'] ?? $home_header_part1;
    $home_header_part2 = $row['part2'] ?? $home_header_part2;
    $home_header_part3 = $row['part3'] ?? $home_header_part3;
}

if (!empty($current_page)) {
    $result = mysqli_query($connection, 
        "SELECT header_text, header_image 
         FROM page_headers 
         WHERE page_name='$current_page' 
         LIMIT 1");

    if ($row = mysqli_fetch_assoc($result)) {
        $page_header = $row['header_text'] ?? "WELCOME";
        $header_image = $row['header_image'] ?? "uploads/header/header.png";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>

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
  /* ===== Backgrounds ===== */
  --page-bg: <?= $settings['page_bg'] ?? '#FEFAF6' ?>;
  --headers-bg: <?= $settings['headers_bg'] ?? '#FBF1D7' ?>;
  --footer-bg: <?= $settings['footer_bg'] ?? '#FBF1D7' ?>;
  --wave-background: <?= $settings['wave_background'] ?? '#058240' ?>;

  /* ===== Fonts ===== */
  --primary-font: "<?= $settings['primary_font'] ?? 'Fredoka' ?>", sans-serif;
  --page-header-font: "<?= $settings['page_header_font'] ?? 'Lilita One' ?>", sans-serif;

  /* ===== Font Colors ===== */
  --heading-font-color: <?= $settings['heading_font_color'] ?? '#0D8540' ?>;
  --second-heading-font-color: <?= $settings['second_heading_font_color'] ?? '#FFFFFF' ?>;
  --body-font-color: <?= $settings['body_font_color'] ?? '#000000' ?>;
  --navbar-hover-color: <?= $settings['navbar_hover_color'] ?? '#87B86B' ?>;
  --price-color: <?= $settings['price_color'] ?? '#F4C40F' ?>;


  --home-header-font-color-part1: <?= $settings['home_header_font_color_part1'] ?? '#0D8540' ?>;
  --home-header-font-color-part2: <?= $settings['home_header_font_color_part2'] ?? '#F4C40F' ?>;
  --home-header-font-color-part3: <?= $settings['home_header_font_color_part3'] ?? '#0D8540' ?>;

  /* ===== Buttons ===== */
  --checkout-button-color: <?= $settings['checkout_button_color'] ?? '#F4C40F' ?>;
  --checkout-button-hover: <?= $settings['checkout_button_hover'] ?? '#0D8540' ?>;

  --button1-color: <?= $settings['button1_color'] ?? '#0D8540' ?>;
  --button1-font-color: <?= $settings['button1_font_color'] ?? '#0D8540' ?>;
  --button1-hover: <?= $settings['button1_hover'] ?? '#FFFFFF' ?>;
  --button1-hover-font: <?= $settings['button1_hover_font'] ?? '#0D8540' ?>;

  --button2-color: <?= $settings['button2_color'] ?? '#FFFFFF' ?>;
  --button2-font-color: <?= $settings['button2_font_color'] ?? '#FFFFFF' ?>;
  --button2-hover: <?= $settings['button2_hover'] ?? '#0D8540' ?>;
  --button2-hover-font: <?= $settings['button2_hover_font'] ?? '#FFFFFF' ?>;

  --button3-color: <?= $settings['button3_color'] ?? '#F4C40F' ?>;
  --button3-font-color: <?= $settings['button3_font_color'] ?? '#000000' ?>;

  /* ===== Products ===== */
  --product-font-color: <?= $settings['product_font_color'] ?? '#000000' ?>;
  --icon-color: <?= $settings['icon_color'] ?? '#0D8540' ?>;
  --page-number-active: <?= $settings['page_number_active'] ?? '#0D8540' ?>;
  --page-number-hover: <?= $settings['page_number_hover'] ?? '#F4C40F' ?>;

  /* ===== FAQ ===== */
  --faq-button-bg: <?= $settings['faq_button_bg'] ?? '#0D8540' ?>;
  --faq-question-font-color: <?= $settings['faq_question_font_color'] ?? '#FFFFFF' ?>;
  --faq-answer-bg: <?= $settings['faq_answer_bg'] ?? '#87B86B' ?>;
  --faq-answer-font-color: <?= $settings['faq_answer_font_color'] ?? '#000000' ?>;
}




body {
    font-family: var(--font-family);
}

.navbar {
    position: fixed;   
    top: 0;
    left: 0;
    right: 0;
    z-index: 1050;     
    transition: background-color 0.4s ease, transform 0.4s ease;
}


.navbar-scrolled {
    background-color: var(--headers-bg) !important;  
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}


.navbar-transparent {
    background: transparent !important;
}

.navbar .nav-link:hover {
    color: var(--navbar-hover-color) !important;
    transition: all 0.5s ease;
}

.cart-icon:hover {
    color: var(--navbar-hover-color) !important;
    transition: all 0.5s ease;
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
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
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


<?php if($isHomePage): ?>
<section class="home-header-section">
  <div class="header-text">
    <h1>
      <span style="color: <?= htmlspecialchars($header_colors['home_header_font_color_part1'] ?? '#0D8540') ?>;">
        <?= htmlspecialchars($home_header_part1) ?>
      </span>
      <span style="color: <?= htmlspecialchars($header_colors['home_header_font_color_part2'] ?? '#F4C40F') ?>;">
        <?= htmlspecialchars($home_header_part2) ?>
      </span>
      <span style="color: <?= htmlspecialchars($header_colors['home_header_font_color_part3'] ?? '#0D8540') ?>;">
        <?= htmlspecialchars($home_header_part3) ?>
      </span>
    </h1>
  </div>
</section>
<?php else: ?>
    <!-- DEFAULT PAGE HEADER -->
    <section class="product-page" 
             style="background-image: url('../../<?= htmlspecialchars($header_image) ?>');">
      <div class="header-text">
        <h1><?= htmlspecialchars($page_header) ?></h1>
        <h2><?= htmlspecialchars($page_subheader ?? '') ?></h2>
        <div class="mt-4 breadcrumb">
          <a href="index.php"><span>Home</span></a>
          <p class="separator">-</p>
          <span class="current-page"><?= ucfirst($current_page) ?></span>
        </div>
      </div>
      <!-- wave separator -->
      <svg class="wave" viewBox="0 0 1440 320" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,224 L48,213.3 C96,203 192,181 288,165.3 C384,149 480,139 576,149.3 C672,160 768,192 864,202.7 C960,213 1056,203 1152,186.7 C1248,171 1344,149 1392,138.7 L1440,128 L1440,320 L1392,320 C1344,320 1248,320 1152,320 C1056,320 960,320 864,320 C768,320 672,320 576,320 C480,320 384,320 288,320 C192,320 96,320 48,320 L0,320 Z" fill="#fefaf6"/>
      </svg>
    </section>
<?php endif; ?>

 
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


        <!-- items -->
        
      </div>
      <!-- footer -->
      <div class="cart-footer">
        <div class="select-all">
          <input type="checkbox" id="selectAll" />
          <label for="selectAll">Select All</label>
        </div>
        <div class="total">
          <span>Total:</span>
          <strong id="cartTotal">₱0.00</strong>
        </div>
        <button class="checkout-btn" id="checkoutBtn">Checkout</button>
      </div>
    </div>
    <!-- overlay -->
    <div class="overlay" id="overlay"></div>
    <!-- CART SIDEBAR -->

<!-- FUNCTIONS -->
  <!-- BOOTSTRAP JS -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
      crossorigin="anonymous"
    ></script>

    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
      // --- Cart Sidebar Functionality ---
     // --- GLOBAL VARIATIONS (from PHP) ---
  const variations = <?= json_encode($variations ?? []) ?>;

  // --- CART ELEMENTS ---
  const cartBtn = document.getElementById("cartBtn");
const cartSidebar = document.getElementById("cartSidebar");
const closeCart = document.getElementById("closeCart");
const overlay = document.getElementById("overlay");
const cartContainer = document.getElementById("cartItems");
const cartTotalEl = document.getElementById("cartTotal");
const cartBadge = document.getElementById("cartCount");

// --- LOAD CART FROM LOCALSTORAGE ---
let cart = JSON.parse(localStorage.getItem("cart")) || [];

// --- SAVE CART ---
function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
}

// --- UPDATE CART BADGE ---
function updateCartBadge() {
    const totalQty = cart.reduce((sum, p) => sum + p.qty, 0);
    if (cartBadge) cartBadge.textContent = totalQty;
}

// --- RENDER CART SIDEBAR ---
function renderCart() {
    cartContainer.innerHTML = "";

    if (cart.length === 0) {
        cartContainer.innerHTML = `<p class="text-muted empty-message">Your cart is empty.</p>`;
        cartTotalEl.textContent = "₱0.00";
        updateCartBadge();
        return;
    }

    cart.forEach(product => {
        const item = document.createElement("div");
        item.classList.add("cart-item", "d-flex", "align-items-center", "border-bottom", "py-2");
        item.setAttribute("data-id", product.id);
        item.setAttribute("data-size", product.size || "");

        item.innerHTML = `
            <input type="checkbox" class="cart-check me-2" checked />
            <img src="${product.image}" alt="${product.name}" class="cart-img me-2" width="50"/>
            <div class="flex-grow-1">
                <h6 class="mb-1">${product.name}</h6>
                ${product.size ? `<p class="cart-size">Size: ${product.size}</p>` : ""}
                <div class="d-flex align-items-center">
                    <button class="btn-qty minus">−</button>
                    <span class="qty mx-2">${product.qty}</span>
                    <button class="btn-qty plus">+</button>
                </div>
                <strong class="item-price d-block mt-1" data-price="${product.price}">
                    ₱${(product.price * product.qty).toFixed(2)}
                </strong>
                ${product.store ? `<span class="cart-branch">${product.store}</span>` : ""}
            </div>
            <button class="btn-delete"><i class="bi bi-trash"></i></button>
        `;
        cartContainer.appendChild(item);
    });

    updateCartTotal();
    updateCartBadge();
}

// --- UPDATE TOTAL ---
function updateCartTotal() {
    let total = 0;
    document.querySelectorAll(".cart-item").forEach(item => {
        const checkbox = item.querySelector(".cart-check");
        if (!checkbox.checked) return;

        const priceEl = item.querySelector(".item-price");
        const qty = parseInt(item.querySelector(".qty").textContent || 0);
        const price = parseFloat(priceEl.dataset.price || 0);

        total += price * qty;
    });
    cartTotalEl.textContent = `₱${total.toFixed(2)}`;
}

// --- ADD TO CART ---
function addToCart(product) {
    // SINGLE-STORE RESTRICTION
    if (cart.length > 0 && product.store !== cart[0].store) {
        if (window.Swal) {
            Swal.fire({
                icon: 'warning',
                title: 'Single-store cart',
                text: 'You can only add items from one store per cart. Please checkout or clear your cart first.',
                confirmButtonColor: '#ff6b6b'
            });
        } else {
            alert('You can only add items from one store per cart.');
        }
        return;
    }

    // CHECK FOR EXISTING ITEM WITH SAME SIZE
    let existing = cart.find(p => p.id === product.id && p.size === product.size);
    if (existing) {
        existing.qty += product.qty;
    } else {
        cart.push(product);
    }

    saveCart();
    renderCart();
}

// --- CART SIDEBAR TOGGLE ---
if (cartBtn) cartBtn.addEventListener("click", () => {
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

// --- EVENT DELEGATION: PLUS, MINUS, DELETE ---
document.addEventListener("click", e => {
    const itemEl = e.target.closest(".cart-item");
    if (!itemEl) return;

    const id = itemEl.dataset.id;
    const size = itemEl.dataset.size || null;
    const product = cart.find(p => p.id === id && p.size === size);
    if (!product) return;

    if (e.target.classList.contains("plus")) product.qty++;
    if (e.target.classList.contains("minus")) {
        product.qty--;
        if (product.qty <= 0) cart = cart.filter(p => p.id !== id || p.size !== size);
    }
    if (e.target.closest(".btn-delete")) cart = cart.filter(p => p.id !== id || p.size !== size);

    saveCart();
    renderCart();
});

// --- CHECKBOX HANDLING ---
document.addEventListener("change", e => {
    if (e.target.classList.contains("cart-check")) updateCartTotal();
    if (e.target.id === "selectAll") {
        const checked = e.target.checked;
        document.querySelectorAll(".cart-check").forEach(cb => cb.checked = checked);
        updateCartTotal();
    }
});

// --- ADD TO CART BUTTONS ---
document.addEventListener("click", e => {
    const btn = e.target.closest(".add-to-cart");
    if (!btn) return;

    // Quantity
    const qtyTarget = btn.dataset.qtyTarget ? document.querySelector(btn.dataset.qtyTarget) : null;
    const chosenQty = qtyTarget ? parseInt(qtyTarget.value || "1") : 1;

    // Variation / size
    const variationSelect = btn.dataset.variationSelect ? document.querySelector(btn.dataset.variationSelect) : null;
    let chosenSize = null;
    if (variationSelect && variationSelect.value !== "") {
        // Use selected option text instead of value
        chosenSize = variationSelect.options[variationSelect.selectedIndex].text;
    }

    const product = {
        id: String(btn.dataset.id || ""),
        product_id: String(btn.dataset.product_id || btn.dataset.id || ""),
        name: btn.dataset.name || "",
        price: parseFloat(btn.dataset.price || "0"),
        image: btn.dataset.image || "../../assets/kesong puti.png",
        store: btn.dataset.store || "",
        size: chosenSize,  // <-- now text like "Medium" or "Large"
        qty: Math.max(1, chosenQty)
    };

    addToCart(product);
});

// --- CLEAR INVALID CART & INITIAL RENDER ---
cart = cart.filter(item => item && item.id && item.name && !isNaN(item.price) && !isNaN(item.qty) && item.price >= 0 && item.qty > 0);
saveCart();
renderCart();

 


      document.getElementById('checkoutBtn').addEventListener('click', function (e) {
        e.preventDefault();

        Swal.fire({
          title: 'Data Privacy Agreement',
          html: `
            <p style="text-align: left; font-size: 15px;">
              Before proceeding, please read and agree to our <strong>Data Privacy Policy</strong>.
              Your personal information and order details will be collected and processed solely
              for transaction and record purposes.
            </p>
            <p style="text-align: left; font-size: 15px;">
              By clicking <strong>"I Agree"</strong>, you consent to the collection and use of your
              data as stated in our policy.
            </p>
          `,
          icon: 'info',
          showCancelButton: true,
          confirmButtonText: 'I Agree',
          cancelButtonText: 'Cancel',
          confirmButtonColor: '#0D8540',
          cancelButtonColor: '#3085d6',
          allowOutsideClick: false
        }).then((result) => {
          if (result.isConfirmed) {
            // Redirect to checkout
            window.location.href = 'checkout.php';
          } else {
            // Stay on the same page (do nothing)
            Swal.fire({
              icon: 'info',
              title: 'Cancelled',
              text: 'You must agree to the data privacy policy to continue.',
              confirmButtonColor: '#0D8540'
            });
          }
        });
      });


      </script>


     

<!-- FUNCTIONS -->
</body>
</html>