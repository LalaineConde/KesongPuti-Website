<?php

$page_title = 'Terms and Conditions | Kesong Puti';
require '../../connection.php';
$current_page = 'Terms and Conditions'; // or dynamically determine page
$isHomePage = ($current_page === 'home'); // check if this is the home page
$page_subheader = "Guidelines for Using Our Website";

include ('../../includes/customer-dashboard.php');


// Close the connection
mysqli_close($connection);
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kesong Puti - Contact Us</title>

    <!-- BOOTSTRAP -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr"
      crossorigin="anonymous"
    />

    <!-- ICONS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    />

    <!-- CSS -->
    <link rel="stylesheet" href="/css/styles.css" />
  </head>
  <body>
    <!-- NAVBAR -->
    <nav
      class="navbar navbar-expand-lg fixed-top navbar-custom"
      id="mainNavbar"
    >
      <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">
          <img src="assets/logo.png" alt="Kesong Puti" />
        </a>
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
            <li class="nav-item"><a class="nav-link" href="#">FAQ</a></li>
          </ul>
        </div>

        <!-- CART ICON -->
        <div
          class="cart-icon position-relative d-none d-lg-block"
          id="cartBtn"
          style="cursor: pointer"
        >
          <i class="fa-solid fa-bag-shopping fs-4"></i>
          <span class="cart-badge" id="cartCount">2</span>
        </div>
        <!-- CART ICON -->
      </div>
    </nav>
    <!-- NAVBAR -->

    <!-- HEADER -->
    <section class="header-section">
      <div class="header-text">
        <h1>TERMS AND CONDITIONS</h1>
        <h2>Guidelines for Using Our Website</h2>
        <div class="mt-4 breadcrumb">
          <a href="index.html"><span>Home</span> </a>
          <p class="separator">-</p>
          <span>Terms and Conditions</span>
        </div>
      </div>

      <!-- wave separator -->
      <svg
        class="wave"
        viewBox="0 0 1440 320"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <!-- white part -->
        <path
          d="M0,224 L48,213.3 C96,203 192,181 288,165.3 C384,149 480,139 576,149.3 C672,160 768,192 864,202.7 C960,213 1056,203 1152,186.7 C1248,171 1344,149 1392,138.7 L1440,128 L1440,320 L1392,320 C1344,320 1248,320 1152,320 C1056,320 960,320 864,320 C768,320 672,320 576,320 C480,320 384,320 288,320 C192,320 96,320 48,320 L0,320 Z"
          fill="#fefaf6"
        />
      </svg>
    </section>
    <!-- HEADER -->

    <!-- TERMS AND CONDITION -->
    <div class="terms container">
      <h1>Terms and Conditions</h1>
      <p><strong>Last Updated:</strong> October 17, 2025</p>

      <p>
        Welcome to <strong>Sta. Cruz Kesong Puti</strong>! These Terms and
        Conditions govern your use of our website, and your purchase of our
        products. By accessing or using this website, you agree to comply with
        these Terms. If you do not agree, please refrain from using our website.
      </p>

      <h2>1. General Information</h2>
      <p>
        <strong>Sta. Cruz Kesong Puti</strong> is an online store dedicated to
        promoting and selling high-quality <strong>kesong puti</strong> and
        other locally made dairy products from Sta. Cruz, Laguna. We may update
        or modify these Terms at any time without prior notice. Any changes will
        take effect once posted on this page.
      </p>

      <h2>2. Use of Our Website</h2>
      <ul>
        <li>Use the site only for lawful and personal purposes.</li>
        <li>
          Do not attempt to hack, disrupt, or misuse any part of the website.
        </li>
        <li>
          Provide accurate and truthful information when placing an order.
        </li>
      </ul>

      <h2>3. Product Information</h2>
      <p>
        We strive to provide accurate descriptions, images, and prices for all
        our products. However:
      </p>
      <ul>
        <li>
          Actual product appearance may slightly differ due to packaging or
          lighting.
        </li>
        <li>
          Prices and availability are subject to change without prior notice.
        </li>
        <li>
          We reserve the right to limit product quantities or sales to certain
          locations if needed.
        </li>
      </ul>

      <h2>4. Orders and Payments</h2>
      <ul>
        <li>
          By placing an order, you confirm that all details provided (such as
          delivery address and contact number) are accurate.
        </li>
        <li>Orders are subject to product availability and acceptance.</li>
        <li>
          Payment can be made through approved methods such as GCash, bank
          transfer, or cash on delivery (if available).
        </li>
        <li>
          We may cancel or refuse an order if fraudulent or suspicious activity
          is detected.
        </li>
      </ul>

      <h2>5. Shipping and Delivery</h2>
      <ul>
        <li>
          We deliver within the Laguna Province, mainly in Sta. Cruz, Laguna,
          and nearby areas.
        </li>
        <li>
          Delivery times may vary depending on your location, weather, or
          courier delays.
        </li>
        <li>
          Once the order is handed to the courier, we are not responsible for
          loss, delay, or damage during transit.
        </li>
      </ul>

      <h2>6. Return, Refund, and Replacement Policy</h2>
      <p>
        Because our products are <strong>fresh and perishable</strong>, returns
        are generally not accepted except when:
      </p>
      <ul>
        <li>The wrong item was delivered, or</li>
        <li>The product arrived damaged or spoiled.</li>
      </ul>
      <p>
        If this happens, please contact us within <strong>24 hours</strong> with
        proof (such as a photo). We will review and provide a replacement or
        refund when appropriate.
      </p>

      <h2>7. Intellectual Property</h2>
      <p>
        All website content — including text, photos, product images, and logos
        — is owned by <strong>Sta. Cruz Kesong Puti</strong> and protected by
        copyright laws. You may not use, copy, or reproduce any content from
        this website without written permission.
      </p>

      <h2>8. Limitation of Liability</h2>
      <p>We are not responsible for:</p>
      <ul>
        <li>
          Allergic reactions or issues caused by improper storage or handling of
          our products.
        </li>
        <li>
          Any indirect or incidental damages from using our website or products.
        </li>
        <li>
          Interruptions or errors that may occur while accessing the website.
        </li>
      </ul>
      <p>
        Products should be consumed at your own discretion. Please read product
        labels carefully and store items as recommended.
      </p>

      <h2>9. Privacy</h2>
      <p>
        We respect your privacy. Any personal information collected during
        orders (such as your name, contact number, or address) will only be used
        for order processing and delivery.
      </p>

      <h2>10. Governing Law</h2>
      <p>
        These Terms are governed by and interpreted under the laws of the
        <strong>Republic of the Philippines</strong>. Any disputes shall be
        settled in the proper courts of <strong>Laguna, Philippines</strong>.
      </p>

      <h2>11. Contact Us</h2>
      <p>
        If you have any questions or concerns about these Terms, please reach
        out to us: <a href="#">Contact Us</a>
      </p>
    </div>
    <!-- TERMS AND CONDITION -->

    <!-- FOOTER -->
    <footer>
      <div class="container footer-container">
        <div class="row">
          <div class="col-md-3 col-sm-12 text-center footer-logo">
            <img src="assets/logo.png" alt="Kesong Puti Logo" />
            <p class="small mt-2">
              Kesong Puti is your go-to online shop for fresh, authentic
              Filipino cottage cheese. We take pride in delivering locally made,
              high-quality products straight to your doorstep, preserving the
              rich tradition of our hometown delicacy.
            </p>
          </div>

          <!-- quick links -->
          <div class="col-md-3 col-sm-12">
            <div class="footer-links">
              <h6 class="footer-title">Quick Links</h6>
              <a href="#">Home</a>
              <a href="#">Products</a>
              <a href="#">About Us</a>
              <a href="#">Feedback</a>
            </div>
            <div class="footer-links mt-5">
              <h6 class="footer-title">Need Assistance?</h6>
              <a href="#">Contact Us</a>
              <a href="#">FAQ</a>
              <a href="#">Privacy Policy</a>
            </div>
          </div>

          <!-- business hours -->
          <div class="col-md-3 col-sm-12">
            <div class="contact-info">
              <h6 class="footer-title">Business Hours</h6>
              <p>Monday: 07:00AM - 05:00PM</p>
              <p>Monday: 07:00AM - 05:00PM</p>
              <p>Monday: 07:00AM - 05:00PM</p>
              <p>Monday: 07:00AM - 05:00PM</p>
              <p>Monday: 07:00AM - 05:00PM</p>
              <p>Monday: 07:00AM - 05:00PM</p>
              <p>Monday: 07:00AM - 05:00PM</p>
            </div>
          </div>

          <!-- contact us form -->
          <div class="col-md-3 col-sm-12 contact-form">
            <h5 class="footer-title">Leave A Message</h5>
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

              <select class="form-control">
                <option value="" disabled selected>Select Branch</option>
                <option value="branch1">Branch 1 - Manila</option>
                <option value="branch2">Branch 2 - Quezon City</option>
                <option value="branch3">Branch 3 - Cebu</option>
                <option value="branch4">Branch 4 - Davao</option>
              </select>

              <textarea
                class="form-control"
                rows="3"
                placeholder="Message"
              ></textarea>
              <button type="submit" class="submit-btn mt-3">Submit</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Bottom -->
      <div class="footer-bottom">
        <p>Kesong Puti © 2025 All Rights Reserved</p>
        <a href="#">Terms and Condition</a>
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

        function updateNavbar() {
          const y = window.scrollY;

          if (y <= 0) {
            navbar.classList.add("navbar-top");
            navbar.classList.remove("navbar-shrink");
          } else {
            navbar.classList.add("navbar-shrink");
            navbar.classList.remove("navbar-top");
          }
        }

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

    <!-- BUTTON UP -->
    <script>
      const backToTop = document.getElementById("backToTop");

      // Show button when scrolled down
      window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
          backToTop.classList.add("show");
        } else {
          backToTop.classList.remove("show");
        }
      });

      // Scroll to top when clicked
      backToTop.addEventListener("click", () => {
        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
      });
    </script>

<?php include('../../includes/floating-button.php'); ?>
<?php include('../../includes/footer.php'); ?>
  </body>
</html>
