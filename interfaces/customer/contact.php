<?php
$page_title = 'Contact Us | Kesong Puti';
$page_header = "CONTACT US";
include ('../../includes/customer-dashboard.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us | Kesong Puti</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      font-family: Arial, sans-serif;

      margin: 0;
      padding: 0;

      justify-content: center;
      align-items: center;
      background-color: #fdfbe9;
    }

    .container {     
      display: flex;
      justify-content: center;
      gap: 80px;
      margin: 50px auto;
    }

    .logo {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      position: relative;
    }

    .logo img {
      width: 590px;
    }

    .circles {
      display: flex;
      gap: 20px;
      position: absolute;
      bottom: 5px;   
      left: 50%;
      transform: translateX(-50%);
    }

    .circle {
      width: 60px;
      height: 60px;
      background-color: #002d13; 
      border-radius: 50%;
    }

    .contact-form-page {
      text-align: center;
      max-width: 420px;
      width: 100%;
    }

    .contact-form-page h2 {
      font-size: 2.4rem;
      font-weight: bold;
      color: #000;
      margin-bottom: 10px;
    }

    .contact-form-page p {
      font-size: 15px;
      color: #064420;
      margin-bottom: 25px;
    }

    .contact-form-page form {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    .input-group {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-group i {
      position: absolute;
      left: 12px;
      font-size: 16px;
      color: #444;
      top: 50%;
      transform: translateY(-50%);
    }

    .input-group input,
    .input-group textarea {
      width: 100%;
      padding: 10px 12px 10px 40px;
      border: 2px solid #000;
      border-radius: 6px;
      font-size: 15px;
      outline: none;
    }

    .input-group input {
      height: 45px;
    }

    .input-group textarea {
      resize: none;
      height: 100px;
      padding-top: 12px;
    }

    
    .input-group.textarea i {
      top: 15px; 
      transform: none;
    }
    
    .input-group select {
      width: 100%;
      height: 45px;
      padding: 10px 12px 10px 40px;
      border: 2px solid #000;
      border-radius: 6px;
      font-size: 15px;
      outline: none;
      background-color: #fff;
      color: #000;
      appearance: none; 
      cursor: pointer;
   }

   .input-group::after {
      content: "\f078"; 
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      position: absolute;
      right: 12px;
      pointer-events: none;
      color: #444;
      font-size: 12px;
    }


   
    .contact-form-page button {
      background-color: #f4c400;
      color: #000;
      font-weight: bold;
      border: 2px solid #000;
      border-radius: 25px;
      padding: 10px;
      font-size: 16px;
      cursor: pointer;
      width: 200px;
      margin: 15px auto 0 auto;
      transition: 0.3s;
      box-shadow: 2px 3px 6px rgba(0,0,0,0.3);
    }

    .contact-form-page button:hover {
      background-color: #e0b200;
    }
  </style>
</head>
<body>
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
          <i class="fa-solid fa-user"></i>
          <input type="text" name="name" placeholder="Name" required />
        </div>
        <div class="input-group">
          <i class="fa-solid fa-envelope"></i>
          <input type="email" name="email" placeholder="Email" required />
        </div>
        <div class="input-group">
          <i class="fa-solid fa-phone"></i>
          <input type="text" name="contact" placeholder="Contact Number" required />
        </div>
        <div class="input-group">
          <i class="fa-solid fa-user-circle"></i>
          <select name="recipient" required>
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
        </div>
        <div class="input-group textarea">
          <i class="fa-solid fa-comment"></i>
          <textarea name="message" rows="3" placeholder="Message" required></textarea>
        </div>
        <button type="submit" class="submit-btn mt-2">Submit</button>
      </form>
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
    const form = document.querySelector(".contact-form form");

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
