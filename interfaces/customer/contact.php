<?php
$page_title = 'Contact Us | Kesong Puti';
$page_header = "CONTACT US";
require '../../connection.php';
include ('../../includes/customer-dashboard.php');

// Which page is this?
$current_page = 'contact'; // change this per file (products, contact, faq, etc.)

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

  <!-- PAGE HEADER -->
<section class="product-page" style="background-image: url('../../<?= htmlspecialchars($settings['header_image'] ?? 'assets/header.png') ?>');">
  <div class="header-text">
    <h1><?= htmlspecialchars($page_header) ?></h1>
    <div class="breadcrumb">
      <a href="home.php"><span>Home</span></a>
      <p class="separator">-</p>
      <span>Contacts</span>
    </div>
  </div>
</section>
<!-- PAGE HEADER -->


  <div class="contact-page">
  <div class="container">
    <!-- Logo + Circles -->
    <div class="logo">
      <img src="../../assets/ChatGPT_Image_Jun_15__2025__01_52_01_PM-removebg-preview.png" alt="Kesong Puti Logo">
      <div class="circles">
        <div class="circle"></div>
        <div class="circle"></div>
      </div>
    </div>

    


    <!-- Contact Form -->
    <div class="contact-form-page">
      <h2>Contact Us</h2>
      <p>We’d love to hear from you! Send us a message—we’ll get back to you as soon as we can!</p>
      <form  action="save-message.php" method="POST">
        <div class="input-group">
          <i class="bi bi-person"></i>
          <input type="text" name="name" placeholder="Name" required />
        </div>
        <div class="input-group">
          <i class="bi bi-envelope"></i>
          <input type="email" name="email" placeholder="Email" required />
        </div>
        <div class="input-group">
          <i class="bi bi-telephone"></i>
          <input type="text" name="contact" placeholder="Contact Number" required />
        </div>
        <div class="input-group">
          <i class="bi bi-shop"></i>
          <select name="recipient" required>
            <option value="">-- Select Store --</option>
            <?php
                require '../../connection.php';

                // Fetch all super admins
                $superQuery = "SELECT super_id, username FROM super_admin";
                $superResult = mysqli_query($connection, $superQuery);

                while ($row = mysqli_fetch_assoc($superResult)) {
                    echo '<option value="super_' . $row['super_id'] . '">'
                        . htmlspecialchars($row['username']) . '</option>';
                }

                // Fetch all admins
                $adminQuery = "SELECT admin_id, username FROM admins";
                $adminResult = mysqli_query($connection, $adminQuery);

                while ($row = mysqli_fetch_assoc($adminResult)) {
                    echo '<option value="admin_' . $row['admin_id'] . '">'
                        . htmlspecialchars($row['username']) . '</option>';
                }

                
                ?>
          </select>
        </div>
        <div class="input-group textarea">
          <i class="bi bi-chat-dots"></i>
          <textarea name="message" rows="3" placeholder="Message" required></textarea>
        </div>
        <button type="submit" class="submit-btn mt-2">Submit</button>
      </form>
    </div>



     </div>
  </div>

      <!-- Store Contacts Section -->
<div class="store-contacts mt-5">
  <h3>Our Stores</h3>
  <div class="row">
    <?php
      // fetch store contacts from DB
      $contactQuery = "SELECT * FROM store_contacts ORDER BY id DESC";
      $contactResult = mysqli_query($connection, $contactQuery);

      if (mysqli_num_rows($contactResult) > 0) {
        while ($contact = mysqli_fetch_assoc($contactResult)) {
          echo '
          <div class="col-md-4 mb-3">
            <div class="card h-100 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">'.htmlspecialchars($contact['store_name']).'</h5>
                <p class="card-text mb-1">
                  <i class="bi bi-envelope"></i>
                  <a href="mailto:' . htmlspecialchars($contact['email']) . '?subject=Kesong%20Puti%20Customer%20Inquiry&body=Good%20day,%0D%0A%0D%0AI%20would%20like%20to%20inquire%20about..." class="text-decoration-none">
                    ' . htmlspecialchars($contact['email']) . '
                  </a>
                </p>

                <p class="card-text mb-1">
                  <i class="bi bi-phone"></i>
                  <a href="tel:' . htmlspecialchars($contact['phone']) . '" class="text-decoration-none">
                    ' . htmlspecialchars($contact['phone']) . '
                  </a>
                </p>

                <p class="card-text">
                  <i class="bi bi-geo-alt"></i>
                  <a href="https://www.google.com/maps/search/?api=1&query=' . htmlspecialchars(urlencode($contact['address'])) . '" 
                    target="_blank" class="text-decoration-none">
                    ' . htmlspecialchars($contact['address']) . '
                  </a>
                </p>
              </div>
            </div>
          </div>';
        }
      } else {
        echo '<p class="text-muted">No store contacts available yet.</p>';
      }
    ?>
  </div>
</div>



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
