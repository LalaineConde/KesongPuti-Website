<?php
$page_title = 'FAQ | Kesong Puti';
$page_header = "FAQ";
require '../../connection.php';
include ('../../includes/customer-dashboard.php');


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
      <div class="faq-box mb-5">
        <div class="p-5">
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
      </div>
    </section>
    <!-- FAQ -->



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