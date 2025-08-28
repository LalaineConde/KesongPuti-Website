<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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

    <!-- CSS -->
    <link rel="stylesheet" href="../../css/styles.css" >
  </head>
<body>
    

<!-- FOOTER -->
<footer>
  <div class="container footer-container">
    <div class="row">
      <!-- Logo & Social -->
      <div class="col-md-4 col-sm-12 text-center footer-logo">
        <img src="../../assets/logo.png" alt="Kesong Puti Logo" />
        <p class="small mt-2">
          Kesong Puti is your go-to online shop for fresh, authentic Filipino cottage cheese. 
          We take pride in delivering locally made, high-quality products straight to your 
          doorstep, preserving the rich tradition of our hometown delicacy.
        </p>
        <div class="social-icons d-flex justify-content-center gap-2 mt-2">
          <a href="https://www.facebook.com/AlohaKesorbetes" target="_blank" class="social-circle facebook">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="https://www.instagram.com/arlene_macalinao_kesongputi/" target="_blank" class="social-circle instagram">
            <i class="bi bi-instagram"></i>
          </a>
        </div>
      </div>

      <!-- Links & Contact Info -->
      <div class="col-md-4 col-sm-12">
        <div class="footer-links">
          <h6 class="footer-title">Quick Links</h6>
          <a href="#">Home</a>
          <a href="#">Products</a>
          <a href="#">About Us</a>
          <a href="#">FAQ</a>
          <a href="#">Contact Us</a>
          <a href="#">Feedback</a>
          <a href="#">Order Now</a>
        </div>
        <div class="contact-info mt-4">
          <h6 class="footer-title mt-3">Contact Information</h6>
          <p><a href="https://mail.google.com/mail/u/0/#inbox?compose=GTvVlcRzDCttwRNWCwlNpgGbQKpxBZrgCtRFCGdGjMFchRBStFpxmsqqzbwsmphHhxRPrWMJnsGjv" target="_blank" class="contact-info"><i class="bi bi-envelope"></i> hernandezshy00@gmail.com </a></p>
          <p><a href="tel:+639997159226"><i class="bi bi-telephone"></i> +63 999 715 9226 </a></p>
          <p><a href="https://maps.app.goo.gl/XhDrJM3vM9fk9WPg9" target="_blank">
              <i class="bi bi-geo-alt"></i> 4883 Sitio 3 Brgy. Bagumbayan, Santa Cruz, Philippines, 4009
            </a>
          </p>
        </div>
      </div>

<!-- Contact Form -->
<div class="col-md-4 contact-form">
  <h5 class="footer-title">Contact Us</h5>
  <p class="small">
    We’d love to hear from you! Send us a message—we’ll get back to
    you as soon as we can!
  </p>

  <form action="save-message.php" method="POST">
    <!-- Name -->
    <input 
      type="text" 
      name="name" 
      class="form-control mb-2" 
      placeholder="Name" 
      required 
    />

    <!-- Email -->
    <input 
      type="email" 
      name="email" 
      class="form-control mb-2" 
      placeholder="Email" 
      required 
    />

    <!-- Contact Number -->
    <input 
      type="text" 
      name="contact" 
      class="form-control mb-2" 
      placeholder="Contact Number" 
    />

    <!-- Recipient Dropdown -->
    <select name="recipient" class="form-control mb-2" required>
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

    <!-- Message -->
    <textarea 
      name="message" 
      class="form-control mb-2" 
      rows="3" 
      placeholder="Message" 
      required
    ></textarea>

    <!-- Submit Button -->
    <button type="submit" class="submit-btn mt-2">Submit</button>
  </form>
</div>


    <!-- Bottom -->
    <div class="footer-bottom text-center mt-3">
      Kesong Puti © 2025 All Rights Reserved
    </div>
  </div>
</footer>
<!-- FOOTER -->


<!-- FUNCTIONS -->

<!-- FUNCTIONS -->
</body>
</html>