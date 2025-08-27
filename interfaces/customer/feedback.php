
<?php
$page_title = 'Customer Feedback | Kesong Puti';
require '../../connection.php';
$page_header = "CUSTOMER FEEDBACK";
include ('../../includes/customer-dashboard.php');


$toast_message = ''; // Initialize variable for toast message


// Fetch all superadmins
$superadmins = [];
$superQuery = "SELECT super_id, username FROM super_admin";
$superResult = mysqli_query($connection, $superQuery);
if ($superResult && mysqli_num_rows($superResult) > 0) {
    while ($row = mysqli_fetch_assoc($superResult)) {
        $superadmins['super_' . $row['super_id']] = $row['username'];
    }
}

// Fetch all admins
$admins = [];
$adminQuery = "SELECT admin_id, username FROM admins";
$adminResult = mysqli_query($connection, $adminQuery);
if ($adminResult && mysqli_num_rows($adminResult) > 0) {
    while ($row = mysqli_fetch_assoc($adminResult)) {
        $admins['admin_' . $row['admin_id']] = $row['username'];
    }
}

// Merge into a single array for easy lookup
$recipients = array_merge($superadmins, $admins);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($connection, $_POST['reviewName']);
    $email = mysqli_real_escape_string($connection, $_POST['reviewEmail']);
    $rating = mysqli_real_escape_string($connection, $_POST['reviewRating']);
    $feedback = mysqli_real_escape_string($connection, $_POST['reviewText']);
    $recipient = mysqli_real_escape_string($connection, $_POST['reviewRecipient']);

    $sql = "INSERT INTO reviews (name, email, rating, feedback, recipient) VALUES ('$name', '$email', '$rating', '$feedback', '$recipient')";
    mysqli_query($connection, $sql);
}

// Fetch reviews
$sql = "SELECT *, 
  LENGTH(rating) - LENGTH(REPLACE(rating, '★', '')) AS star_count
  FROM reviews 
  ORDER BY star_count DESC, created_at DESC";
$result = mysqli_query($connection, $sql);
?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kesong Puti - Products</title>

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

    <!-- FEEDBACK -->
<section class="feedback-page">

<div class="message">
  Love our Kesong Puti products? Share your feedback below and help us serve you better!
</div>


<div class="feedback-container">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <div class="feedback-box">
        <h4 class="feedback-name"><?= htmlspecialchars($row['name']) ?></h4>
        <div class="feedback-stars"><?= htmlspecialchars($row['rating']) ?></div>
        <p class="feedback-text"><?= htmlspecialchars($row['feedback']) ?></p>
        <small>Store: 
            <?= isset($recipients[$row['recipient']]) ? htmlspecialchars($recipients[$row['recipient']]) : 'Unknown' ?>
        </small>
      </div>
    <?php } ?>
</div>

  <button class="review-btn" id="openReviewModal">Leave a Review</button>
</section>

<!-- Review Modal -->
<div class="review-modal" id="reviewModal" style="display:none;">
  <div class="review-modal-content">
    <span class="close-modal" id="closeReviewModal">&times;</span>
    <h3>Leave a Review</h3>
    <form method="POST" action="">
    <input type="text" name="reviewName" placeholder="Your Name" required>
    <input type="email" name="reviewEmail" placeholder="Your Email" required>
    <select name="reviewRating" required>
        <option value="" disabled selected>Rate Us</option>
        <option value="★★★★★">⭐⭐⭐⭐⭐</option>
        <option value="★★★★☆">⭐⭐⭐⭐</option>
        <option value="★★★☆☆">⭐⭐⭐</option>
        <option value="★★☆☆☆">⭐⭐</option>
        <option value="★☆☆☆☆">⭐</option>
    </select>
    <select name="reviewRecipient" required>
          <option value="">-- Select Store --</option>
          <?php
          // Fetch superadmins
          $superQuery = "SELECT super_id, username FROM super_admin";
          $superResult = mysqli_query($connection, $superQuery);
          if ($superResult && mysqli_num_rows($superResult) > 0) {
              while ($row = mysqli_fetch_assoc($superResult)) {
                  echo '<option value="super_' . $row['super_id'] . '">'
                       . htmlspecialchars($row['username']) . '</option>';
              }
          }

          // Fetch admins
          $adminQuery = "SELECT admin_id, username FROM admins";
          $adminResult = mysqli_query($connection, $adminQuery);
          if ($adminResult && mysqli_num_rows($adminResult) > 0) {
              while ($row = mysqli_fetch_assoc($adminResult)) {
                  echo '<option value="admin_' . $row['admin_id'] . '">'
                       . htmlspecialchars($row['username']) . '</option>';
              }
          }
          ?>
      </select>
      <textarea name="reviewText" placeholder="Write your feedback..." required></textarea>
      <button type="submit" class="submit-review">Submit</button>
    </form>
  </div>
</div>
    <!-- FEEDBACK -->

    <!-- FUNCTIONS -->
<script>
  // Review Modal Script
  const reviewModal = document.getElementById("reviewModal");
  const openReviewBtn = document.getElementById("openReviewModal");
  const closeReviewBtn = document.getElementById("closeReviewModal");

  openReviewBtn.addEventListener("click", () => {
    reviewModal.style.display = "flex";
  });

  closeReviewBtn.addEventListener("click", () => {
    reviewModal.style.display = "none";
  });

  window.addEventListener("click", (e) => {
    if (e.target === reviewModal) {
      reviewModal.style.display = "none";
    }
  });
</script>
    <!-- FUNCTIONS -->



    <?php include('../../includes/footer.php'); ?>
  </body>
</html>
