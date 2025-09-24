<?php
$page_title = 'Home | Kesong Puti';
require '../../connection.php';
$current_page = 'home'; // or dynamically determine page
$isHomePage = ($current_page === 'home'); // check if this is the home page

include ('../../includes/customer-dashboard.php');

// Fetch header text
$result = mysqli_query($connection, "SELECT header_text FROM page_headers WHERE page_name='$current_page' LIMIT 1");
$row = mysqli_fetch_assoc($result);

// Fetch hero images & subtitles
$hero_items = [];
$result = mysqli_query($connection, "SELECT * FROM home_hero ORDER BY position ASC");
while($row = mysqli_fetch_assoc($result)){
    $hero_items[] = $row;
}


// --- Fetch Featured Products ---


$settings = [];
$result = mysqli_query($connection, "SELECT setting_key, setting_value FROM home_settings");
while ($row = mysqli_fetch_assoc($result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$featured_title = $settings['home_featured_title'] ?? "The Original & Classics";
$reasons_heading = $settings['home_reasons_heading'] ?? "Why is it Good?";

$featured_items = [];
$result = mysqli_query($connection, "SELECT * FROM home_featured ORDER BY position ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $featured_items[$row['position']] = $row;
}

$reasons = [];
$result = mysqli_query($connection, "SELECT * FROM home_reasons ORDER BY position ASC");
while($row = mysqli_fetch_assoc($result)){
    $reasons[$row['position']] = $row;
}

// Delivery/Pickup Section
$del_pick = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM home_delivery_pickup LIMIT 1"));

$about_heading = $settings['about_heading'] ?? "OUR FAMILY’S LEGACY OF KESONG PUTI";

$about_slider = [];
$result = mysqli_query($connection, "SELECT * FROM home_about_slider ORDER BY position ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $about_slider[] = $row;
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home | Kesong Puti</title>

    <!-- BOOTSTRAP -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr"
      crossorigin="anonymous"
    />

    <!-- ICONS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />

    <!-- CSS -->
    <link rel="stylesheet" href="../../css/styles.css" />
  </head>

  <body>




    <!-- HERO PAGE -->
<section class="home-hero mb-5">
  <div class="container-fluid">
    <div class="images-container">
      <?php foreach($hero_items as $item): ?>
      <div class="img-home">
        <img src="../../<?= htmlspecialchars($item['image_path']) ?>" alt="Hero Image" />
        <i class="fa-solid fa-heart"></i>
        <div class="card-text">
          <?= nl2br(htmlspecialchars($item['subtitle'])) ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
    <!-- HERO PAGE -->

    <!-- VINE SEPARATOR 1 -->
    <div class="wave-transition1">
      <svg
        viewBox="0 0 1440 80"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M0,20 C120,40 240,0 360,20 C480,40 600,0 720,20 C840,40 960,0 1080,20 C1200,40 1320,0 1440,20"
          fill="none"
          stroke="#058240"
          stroke-width="8"
        />
        <path
          d="M0,40 C120,60 240,20 360,40 C480,60 600,20 720,40 C840,60 960,20 1080,40 C1200,60 1320,20 1440,40"
          fill="none"
          stroke="#058240"
          stroke-width="8"
        />
        <path
          d="M0,60 C120,80 240,40 360,60 C480,80 600,40 720,60 C840,80 960,40 1080,60 C1200,80 1320,40 1440,60 L1440,80 L0,80 Z"
          fill="#058240"
          stroke="#058240"
          stroke-width="8"
        />
      </svg>
    </div>
    <!-- VINE SEPARATOR 1 -->

    <!-- FEATURED -->
<section class="featured-products">
  <div class="container text-center">
    <h1 class="mb-4"><?= htmlspecialchars($featured_title) ?></h1>

    <div class="row card-container justify-content-center">
      <?php foreach ($featured_items as $item): ?>
        <div class="col-md-5 mb-4">
          <div class="card-custom">
            <img src="../../<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" />
            <div class="card-text"><?= strtoupper(htmlspecialchars($item['title'])) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

   <a href="products.php" class="view-more-btn mt-4">View More</a>

  </div>
</section>
    <!-- FEATURED -->

    <!-- VINE SEPARATOR 2 -->
    <div class="wave-transition2">
      <svg
        viewBox="0 0 1440 80"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M0,20 C120,40 240,0 360,20 C480,40 600,0 720,20 C840,40 960,0 1080,20 C1200,40 1320,0 1440,20"
          fill="none"
          stroke="#fefaf6"
          stroke-width="8"
        />
        <path
          d="M0,40 C120,60 240,20 360,40 C480,60 600,20 720,40 C840,60 960,20 1080,40 C1200,60 1320,20 1440,40"
          fill="none"
          stroke="#fefaf6"
          stroke-width="8"
        />
        <path
          d="M0,60 C120,80 240,40 360,60 C480,80 600,40 720,60 C840,80 960,40 1080,60 C1200,80 1320,40 1440,60 L1440,80 L0,80 Z"
          fill="#fefaf6"
          stroke="#fefaf6"
          stroke-width="8"
        />
      </svg>
    </div>
    <!-- VINE SEPARATOR 2 -->

    <!-- DELIVERY OR PICKUP -->
    <section id="del-pick">
      <div class="del-pick">
        <div class="del-pick-text">
            <h1>
                <span style="color: <?= htmlspecialchars($settings['del_pick_font_color_title1'] ?? '#058240') ?>">
                    <?= htmlspecialchars($del_pick['title1']) ?>
                </span><br>
                <span style="color: <?= htmlspecialchars($settings['del_pick_font_color_title2'] ?? '#058240') ?>">
                    <?= htmlspecialchars($del_pick['title2']) ?>
                </span><br>
                <span style="color: <?= htmlspecialchars($settings['del_pick_font_color_title3'] ?? '#058240') ?>">
                    <?= htmlspecialchars($del_pick['title3']) ?>
                </span><br>
                <span style="color: <?= htmlspecialchars($settings['del_pick_font_color_title4'] ?? '#F4C40F') ?>">
                    <?= htmlspecialchars($del_pick['title4']) ?>
                </span>
            </h1>
          <div class="text-button mt-4">
            <a href="products.php" class="shop-all-btn">Shop All</a>
            <!-- <p>Available for Deliver or Pickup</p> -->
          </div>
        </div>

        <div class="image-container">
            <?php if (!empty($settings['del_pick_image'])): ?>
                <img src="../../<?= htmlspecialchars($settings['del_pick_image']) ?>" alt="Delivery/Pickup">
            <?php else: ?>
                <img src="../../uploads/assets/delivery-section-default.png" alt="Delivery/Pickup">
            <?php endif; ?>
        </div>
      </div>
    </section>
    <!-- DELIVERY OR PICKUP -->

    <!-- VINE SEPARATOR 1 -->
    <div class="wave-transition1">
      <svg
        viewBox="0 0 1440 80"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M0,20 C120,40 240,0 360,20 C480,40 600,0 720,20 C840,40 960,0 1080,20 C1200,40 1320,0 1440,20"
          fill="none"
          stroke="#058240"
          stroke-width="8"
        />
        <path
          d="M0,40 C120,60 240,20 360,40 C480,60 600,20 720,40 C840,60 960,20 1080,40 C1200,60 1320,20 1440,40"
          fill="none"
          stroke="#058240"
          stroke-width="8"
        />
        <path
          d="M0,60 C120,80 240,40 360,60 C480,80 600,40 720,60 C840,80 960,40 1080,60 C1200,80 1320,40 1440,60 L1440,80 L0,80 Z"
          fill="#058240"
          stroke="#058240"
          stroke-width="8"
        />
      </svg>
    </div>
    <!-- VINE SEPARATOR 1 -->

    <!-- ABOUT US -->
<section id="reasons">
  <div class="container text-center">
    <h1 class="mb-5"><?= htmlspecialchars($reasons_heading) ?></h1>
    <div class="row">
      <?php foreach ($reasons as $reason): ?>
        <div class="col-lg-3 perks">
          <i class="<?= htmlspecialchars($reason['icon']) ?>"></i>
          <h3><?= htmlspecialchars($reason['title']) ?></h3>
          <p><?= htmlspecialchars($reason['subtitle']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
    <!-- ABOUT US -->

    <!-- VINE SEPARATOR 2 -->
    <div class="wave-transition2">
      <svg
        viewBox="0 0 1440 80"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M0,20 C120,40 240,0 360,20 C480,40 600,0 720,20 C840,40 960,0 1080,20 C1200,40 1320,0 1440,20"
          fill="none"
          stroke="#fefaf6"
          stroke-width="8"
        />
        <path
          d="M0,40 C120,60 240,20 360,40 C480,60 600,20 720,40 C840,60 960,20 1080,40 C1200,60 1320,20 1440,40"
          fill="none"
          stroke="#fefaf6"
          stroke-width="8"
        />
        <path
          d="M0,60 C120,80 240,40 360,60 C480,80 600,40 720,60 C840,80 960,40 1080,60 C1200,80 1320,40 1440,60 L1440,80 L0,80 Z"
          fill="#fefaf6"
          stroke="#fefaf6"
          stroke-width="8"
        />
      </svg>
    </div>
    <!-- VINE SEPARATOR 2 -->

    <!-- LEGACY -->
    <section id="about">
      <div class="about-us">
        <h1><?= htmlspecialchars($about_heading) ?></h1>
        <a href="about.php" class="learn-more-btn">Learn More</a>

        <!-- slider images -->
        <div class="slider mt-5">
          <div class="slider-track">
    <?php foreach ($about_slider as $slide): ?>
        <div class="slide">
            <img src="../../<?= htmlspecialchars($slide['image_path']) ?>" alt="About Image">
        </div>
    <?php endforeach; ?>
</div>
        </div>

        <!-- arrow -->
        <div class="slider-controls">
          <button class="arrow" id="prev">
            <i class="fa-solid fa-arrow-left"></i>
          </button>
          <button class="arrow" id="next">
            <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>
      </div>
    </section>
    <!-- LEGACY -->



    <!-- FLOATING BUTTON -->
    <button id="backToTop">
      <i class="fas fa-chevron-up"></i>
    </button>
    <!-- FLOATING BUTTON -->



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

    <!-- CAROUSEL -->
    <script>
      const track = document.querySelector(".slider-track");
      const slides = document.querySelectorAll(".slide");
      const prevBtn = document.getElementById("prev");
      const nextBtn = document.getElementById("next");

      let index = 0;

      function updateSlide() {
        const slideWidth = slides[0].offsetWidth + 20; // width + margin
        track.style.transform = `translateX(-${index * slideWidth}px)`;
      }

      nextBtn.addEventListener("click", () => {
        index = (index + 1) % slides.length; // loop forward
        updateSlide();
      });

      prevBtn.addEventListener("click", () => {
        index = (index - 1 + slides.length) % slides.length; // loop backward
        updateSlide();
      });

      window.addEventListener("resize", updateSlide);
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


<?php include('../../includes/footer.php'); ?>
  </body>
</html>
