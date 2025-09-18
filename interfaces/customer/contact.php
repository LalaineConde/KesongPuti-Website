<?php
$page_title = 'Contact Us | Kesong Puti';
require '../../connection.php';
$current_page = 'contact'; 
$page_subheader = "Leave us a message";
include ('../../includes/customer-dashboard.php');




// Fetch header text
$result = mysqli_query($connection, "SELECT header_text FROM page_headers WHERE page_name='$current_page' LIMIT 1");
$row = mysqli_fetch_assoc($result);
$page_header = $row['header_text'] ?? "WELCOME";

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Kesong Puti</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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

<!-- CONTACT US -->
<section id="contact-us">
  <div class="container contact-container">
    <div class="row g-5">
      <!-- contact form -->
          <div class="col-lg-6">
            <h1>Contact Us</h1>
            <h2 class="mb-4">
          We'd love to hear from you! Send us a message—we’ll get back to
          you as soon as we can!
        </h2>

        <form action="save-message.php" method="POST">
          <!-- name -->
          <div class="row g-3">
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" name="first_name" placeholder="First Name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" class="form-control" name="last_name" placeholder="Last Name" required>
              </div>
            </div>
          </div>

          <!-- email and contact no -->
          <div class="row g-3 mt-1">
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" class="form-control" name="contact" placeholder="Contact Number" required>
              </div>
            </div>
          </div>

          <!-- branch selection -->
          <div class="mt-3">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-shop"></i></span>
              <select class="form-select" name="recipient" required>
                <option value="">Select Store</option>
                <?php
                  require '../../connection.php';

                  // Super Admins
                  $superQuery = "SELECT super_id, username FROM super_admin";
                  $superResult = mysqli_query($connection, $superQuery);
                  while ($row = mysqli_fetch_assoc($superResult)) {
                      echo '<option value="super_' . $row['super_id'] . '">' 
                          . htmlspecialchars($row['username']) . '</option>';
                  }

                  // Admins
                  $adminQuery = "SELECT admin_id, username FROM admins";
                  $adminResult = mysqli_query($connection, $adminQuery);
                  while ($row = mysqli_fetch_assoc($adminResult)) {
                      echo '<option value="admin_' . $row['admin_id'] . '">' 
                          . htmlspecialchars($row['username']) . '</option>';
                  }
                ?>
              </select>
            </div>
          </div>

          <!-- message -->
          <div class="mt-3">
            <textarea class="form-control" name="message" rows="4" placeholder="Message" required></textarea>
          </div>

          <!-- submit button -->
          <button type="submit" class="btn-submit mt-3">Submit</button>
        </form>
      </div>

      <!-- logo -->
      <div class="col-lg-6 d-flex align-items-center justify-content-center">
        <div class="contact-logo text-center">
          <img src="../../assets/logo.png" alt="Kesong Puti Logo" class="img-fluid" />
        </div>
      </div>
    </div>
  </div>
</section>
<!-- CONTACT US -->


    <!-- VINE SEPARATOR 1 -->
    <div class="wave-transition2">
      <svg
        viewBox="0 0 1440 80"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <!-- two floating lines -->
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
        <!-- last wave: bottom filled green (becomes top of green section) -->
        <path
          d="M0,60 C120,80 240,40 360,60 C480,80 600,40 720,60 C840,80 960,40 1080,60 C1200,80 1320,40 1440,60 L1440,80 L0,80 Z"
          fill="#058240"
          stroke="#058240"
          stroke-width="8"
        />
      </svg>
    </div>
    <!-- VINE SEPARATOR 1 -->


<!-- STORE DETAILS -->
<section id="store-details" class="my-5">
  <div class="container py-5 store-details">
    <h2>Store Branches</h2>
    <div id="storeCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
          $contactQuery = "SELECT * FROM store_contacts ORDER BY id DESC";
          $contactResult = mysqli_query($connection, $contactQuery);

          $counter = 0;
          while ($contact = mysqli_fetch_assoc($contactResult)) {
              if ($counter % 3 == 0) {
                  echo '<div class="carousel-item ' . ($counter == 0 ? 'active' : '') . '"><div class="row g-4">';
              }
              ?>
              <div class="col-md-4">
                <div class="store-card">
                  <div class="card-header-bar">
                    <i class="bi bi-shop"></i>
                  </div>
                  <div class="card-body">
                    <h5 class="store-name"><?= htmlspecialchars($contact['store_name']) ?></h5>
                    <p class="store-info">
                      <strong>Location:</strong>
                      <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($contact['address']) ?>" 
                        target="_blank" 
                        class="text-decoration-none">
                        <?= htmlspecialchars($contact['address']) ?>
                      </a>
                    </p>
                    <!-- Email (mailto link) -->
                    <p class="store-info">
                      <strong>Email:</strong>
                      <a href="mailto:<?= htmlspecialchars($contact['email']) ?>?subject=Kesong%20Puti%20Customer%20Inquiry&body=Good%20day,%0D%0A%0D%0AI%20would%20like%20to%20inquire%20about..."
                        class="text-decoration-none">
                        <?= htmlspecialchars($contact['email']) ?>
                      </a>
                    </p>

                    <!-- Phone (tel link) -->
                    <p class="store-info">
                      <strong>Contact:</strong>
                      <a href="tel:<?= htmlspecialchars($contact['phone']) ?>" class="text-decoration-none">
                        <?= htmlspecialchars($contact['phone']) ?>
                      </a>
                    </p>
                    <p class="store-info"><strong>Owner:</strong> <?= htmlspecialchars($contact['owner'] ?? 'N/A') ?></p>
                  </div>
                </div>
              </div>
              <?php
              if ($counter % 3 == 2) {
                  echo '</div></div>';
              }
              $counter++;
          }
          if ($counter % 3 != 0) {
              echo '</div></div>'; // close last row/slide if not full
          }
        ?>
      </div>

      <!-- carousel button -->
      <button class="carousel-control-prev" type="button" data-bs-target="#storeCarousel" data-bs-slide="prev">
        <i class="bi bi-chevron-left"></i>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#storeCarousel" data-bs-slide="next">
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>
  </div>
</section>
<!-- STORE DETAILS -->


    <!-- VINE SEPARATOR 3 -->
    <div class="wave-transition3">
      <svg
        viewBox="0 0 1440 80"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <!-- two floating lines -->
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
        <!-- last wave: bottom filled green (becomes top of green section) -->
        <path
          d="M0,60 C120,80 240,40 360,60 C480,80 600,40 720,60 C840,80 960,40 1080,60 C1200,80 1320,40 1440,60 L1440,80 L0,80 Z"
          fill="#fefaf6"
          stroke="#fefaf6"
          stroke-width="8"
        />
      </svg>
    </div>
    <!-- VINE SEPARATOR 2 -->


    <!-- LOCATION -->
    <section id="location" class="location">
      <h2>Our Location</h2>
<div class="map-container">
  <div class="map-frame">
    <iframe
      src="https://www.google.com/maps/d/embed?mid=1bWvJY2YBH4bZEfWXVG9nKg6rBG02qWk&ehbc=2E312F&noprof=1"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>
</div>
    </section>
    <!-- LOCATION -->




      <?php include('../../includes/footer.php'); ?>
</body>

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".contact-form-page form");

    form.addEventListener("submit", function (e) {
      e.preventDefault(); // prevent immediate submit

      Swal.fire({
        title: "Send Message?",
        text: "Do you want to send this message?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#064420",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, send it!"
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // submit the form after confirmation
        }
      });
    });
  });
</script>
</html>
