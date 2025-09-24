<?php
$page_title = 'FAQ | Kesong Puti';
require '../../connection.php';
$current_page = 'FAQ'; 
$isHomePage = ($current_page === 'home'); // check if this is the home page
$page_subheader = "Let's get you an Answer";
include ('../../includes/customer-dashboard.php');

// Fetch header text
$result = mysqli_query($connection, "SELECT header_text FROM page_headers WHERE page_name='$current_page' LIMIT 1");
$row = mysqli_fetch_assoc($result);
$page_header = $row['header_text'] ?? "WELCOME";

$faqs = $connection->query("SELECT * FROM faqs ORDER BY id ASC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ | Kesong Puti</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

   <!-- BOOTSTRAP -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
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
    <link rel="stylesheet" href="../../css/styles.css" />
  </head>

  <body style="background-color: var(--beige)">



    <!-- FAQ -->
    <section id="faq">
      <div class="container faq-container">
      <h1 class="text-center">Frequently Asked Questions</h1>
        <h4 class="mb-4 text-center">How can we help?</h4>

          <div class="accordion" id="faqAccordion">
            <?php if ($faqs->num_rows > 0): ?>
              <?php while($faq = $faqs->fetch_assoc()): ?>
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#faq<?= $faq['id'] ?>">
                      <?= htmlspecialchars($faq['question']) ?>
                    </button>
                  </h2>
                  <div id="faq<?= $faq['id'] ?>" class="accordion-collapse collapse">
                    <div class="accordion-body">
                      <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <div class="alert alert-info text-center" role="alert">
                No FAQs available at the moment. Please check back later.
              </div>
            <?php endif; ?>

        </div>
      </div>
    </section>
    <!-- FAQ -->


    <!-- VINE SEPARATOR -->
    <div class="wave-transition">
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
    <!-- VINE SEPARATOR -->


<!-- CONTACT US -->
<section id="inquiry">
  <div class="container inquiry-container">
    <div class="row g-5">
      <!-- contact form -->
          <div class="col-lg-6">
            <h1 class="mb-1">Still Need Help?</h1>
            <h2 class="mb-4">
              We'd love to hear from you. Reach out, and we'll respond as
              quickly as possible
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



    <!-- BOOTSTRAP JS -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
      crossorigin="anonymous"
    ></script>


    </script>

        <?php include('../../includes/footer.php'); ?>
  </body>
</html>