<?php

// Get latest footer settings
$query = "SELECT * FROM footer_settings ORDER BY id DESC LIMIT 1";
$result = mysqli_query($connection, $query);
$footer = mysqli_fetch_assoc($result);

// Decode quick links JSON
$quickLinks = json_decode($footer['quick_links'], true);
?>

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
    <link rel="stylesheet" href="../../css/styles.css">
  </head>
<body>

<!-- FOOTER -->
<footer>
  <div class="container footer-container">
    <div class="row">
      
      <!-- Column 1: Logo & Social -->
      <div class="col-md-4 col-sm-12 text-center footer-logo">
        <img src="<?php echo htmlspecialchars($footer['logo']); ?>" alt="Kesong Puti Logo" />
        <p class="small mt-2"><?php echo htmlspecialchars($footer['description']); ?></p>

        <div class="social-icons d-flex justify-content-center gap-2 mt-2">
          <a href="<?php echo htmlspecialchars($footer['facebook_link']); ?>" target="_blank" class="social-circle facebook"><i class="bi bi-facebook"></i></a>
          <a href="<?php echo htmlspecialchars($footer['instagram_link']); ?>" target="_blank" class="social-circle instagram"><i class="bi bi-instagram"></i></a>
        </div>
      </div>

      <!-- Column 2: Quick Links & Contact Info -->
      <div class="col-md-4 col-sm-12">
        <div class="footer-links">
          <h6 class="footer-title">Quick Links</h6>
          <?php foreach ($quickLinks as $link): ?>
            <a href="<?php echo htmlspecialchars($link['url']); ?>"><?php echo htmlspecialchars($link['name']); ?></a>
          <?php endforeach; ?>
        </div>
        <div class="contact-info mt-4">
        <h6 class="footer-title">Contact Information</h6>
            <p>
            <a href="mailto:<?php echo $footer['email']; ?>?subject=Kesong%20Puti%20Customer%20Inquiry&body=Good%20day,%0D%0A%0D%0AI%20would%20like%20to%20inquire%20about..." 
                class="contact-info">
                <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($footer['email']); ?>
            </a>
            </p>
        <p><a href="tel:<?php echo $footer['phone']; ?>"><i class="bi bi-telephone"></i> <?php echo $footer['phone']; ?></a></p>
        <p><a href="https://www.google.com/maps/place/Arlene+Macalinao+Kesong+Puti/@14.2684861,121.3959904,17z/data=!3m1!4b1!4m6!3m5!1s0x3397e3efb384ecc1:0xf04659f5f159bd0c!8m2!3d14.2684861!4d121.3985653!16s%2Fg%2F11j4m2c68r?entry=ttu&g_ep=EgoyMDI1MDkwMy4wIKXMDSoASAFQAw%3D%3D<?php echo urlencode($footer['address']); ?>" target="_blank"><i class="bi bi-geo-alt"></i> <?php echo $footer['address']; ?></a></p>

        <!-- View More link -->
        <a href="contact.php" class="btn btn-link p-0 mt-2">View More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>

      <!-- Column 3: Contact Form -->
      <div class="col-md-4 col-sm-12 contact-form">
        <h5 class="footer-title">Contact Us</h5>
        <p class="small">We’d love to hear from you! Send us a message—we’ll get back to you as soon as we can!</p>

        <form action="save-message.php" method="POST">
          <input type="text" name="name" class="form-control mb-2" placeholder="Name" required />
          <input type="email" name="email" class="form-control mb-2" placeholder="Email" required />
          <input type="text" name="contact" class="form-control mb-2" placeholder="Contact Number" />

          <select name="recipient" class="form-control mb-2" required>
            <option value="">-- Select Store --</option>
            <?php
              $superQuery = "SELECT super_id, username FROM super_admin";
              $superResult = mysqli_query($connection, $superQuery);
              while ($row = mysqli_fetch_assoc($superResult)) {
                  echo '<option value="super_' . $row['super_id'] . '">' . htmlspecialchars($row['username']) . '</option>';
              }

              $adminQuery = "SELECT admin_id, username FROM admins";
              $adminResult = mysqli_query($connection, $adminQuery);
              while ($row = mysqli_fetch_assoc($adminResult)) {
                  echo '<option value="admin_' . $row['admin_id'] . '">' . htmlspecialchars($row['username']) . '</option>';
              }
            ?>
          </select>

          <textarea name="message" class="form-control mb-2" rows="3" placeholder="Message" required></textarea>
          <button type="submit" class="submit-btn mt-2">Submit</button>
        </form>
      </div>

    </div>

    <!-- Bottom -->
    <div class="footer-bottom text-center mt-3">
      <?php echo htmlspecialchars($footer['bottom_text']); ?>
    </div>
  </div>
</footer>
<!-- FOOTER -->

</body>
</html>
