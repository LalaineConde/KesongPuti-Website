<?php

// Get latest footer settings
$query = "SELECT * FROM footer_settings ORDER BY id DESC LIMIT 1";
$result = mysqli_query($connection, $query);
$footer = mysqli_fetch_assoc($result);

$background_image = !empty($footer['background_image']) 
    ? '../../uploads/footer/' . $footer['background_image']
    : 'leave.png';

$logo = !empty($footer['logo']) ? htmlspecialchars($footer['logo']) : 'logo.png';

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

    <!-- SEPARATOR -->
    <section class="footer-separator"></section>
    <!-- SEPARATOR -->

<!-- FOOTER -->
<footer style="
    padding: 30px 0;
    background-color: #FFF5E1;
    background-image: url('<?php echo $background_image; ?>');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    font-family: var(--primary-font) !important;
">
  <div class="container footer-container">
    <div class="row">
      
      <!-- Column 1: Logo & Social -->
      <div class="col-md-3 col-sm-12 text-center footer-logo">
      <img src="../../uploads/footer/<?php echo $logo; ?>" alt="Kesong Puti Logo" />

        <p class="small mt-2"><?php echo htmlspecialchars($footer['description']); ?></p>

        <!-- <div class="social-icons d-flex justify-content-center gap-2 mt-2">
          <a href="<?php echo htmlspecialchars($footer['facebook_link']); ?>" target="_blank" class="social-circle facebook"><i class="bi bi-facebook"></i></a>
          <a href="<?php echo htmlspecialchars($footer['instagram_link']); ?>" target="_blank" class="social-circle instagram"><i class="bi bi-instagram"></i></a>
        </div> -->
      </div>

      <!-- Column 2: Quick Links & Contact Info -->
      <div class="col-md-3 col-sm-12">
        <div class="footer-links">
          <h6 class="footer-title">Quick Links</h6>
          <?php foreach (array_slice($quickLinks, 0, 4) as $link): ?>
            <a href="<?php echo htmlspecialchars($link['url']); ?>">
              <?php echo htmlspecialchars($link['name']); ?>
            </a>
          <?php endforeach; ?>
        </div>

        <div class="footer-links mt-4">
          <h6 class="footer-title">Need Assistance?</h6>
          <?php foreach (array_slice($quickLinks, 4, 7) as $link): ?>
            <a href="<?php echo htmlspecialchars($link['url']); ?>">
              <?php echo htmlspecialchars($link['name']); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
      
      <!-- Column 3: Business Hours -->
      <div class="col-md-3 col-sm-12">
        <div class="contact-info">
          <h6 class="footer-title">Business Hours</h6>
          <p>Monday: <?= htmlspecialchars($footer['mon_hours']) ?></p>
          <p>Tuesday: <?= htmlspecialchars($footer['tue_hours']) ?></p>
          <p>Wednesday: <?= htmlspecialchars($footer['wed_hours']) ?></p>
          <p>Thursday: <?= htmlspecialchars($footer['thu_hours']) ?></p>
          <p>Friday: <?= htmlspecialchars($footer['fri_hours']) ?></p>
          <p>Saturday: <?= htmlspecialchars($footer['sat_hours']) ?></p>
          <p>Sunday: <?= htmlspecialchars($footer['sun_hours']) ?></p>
        </div>
      </div>

      <!-- Column 4: Contact Form -->
      <div class="col-md-3 col-sm-12 contact-form">
        <h5 class="footer-title">Leave A Message</h5>
            <p class="small">
              We’d love to hear from you! Send us a message—we’ll get back to
              you as soon as we can!
            </p>
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
