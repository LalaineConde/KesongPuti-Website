
<?php
$page_title = 'Customer Feedback | Kesong Puti';
require '../../connection.php';
$current_page = 'feedback';
include ('../../includes/customer-dashboard.php');


$toast_message = ''; // Initialize variable for toast message



// Fetch header text
$result = mysqli_query($connection, "SELECT header_text FROM page_headers WHERE page_name='$current_page' LIMIT 1");
$row = mysqli_fetch_assoc($result);
$page_header = $row['header_text'] ?? "WELCOME";

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
        <small class="feedback-store">Store: 
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

    <div class="checkbox-row">
      <!-- Anonymous Checkbox -->
      <label class="anonymous-label">
        <input type="checkbox" id="anonymousCheckbox" name="anonymous" class="anonymous-checkbox"> Submit Anonymously
      </label>
    </div>
    <input type="text" name="reviewName" placeholder="Your Name" required>
    <input type="email" name="reviewEmail" placeholder="Your Email" required>
    <!-- Star Rating -->
    <div class="star-rating">
      <span class="star-rating-label">Rating:</span>
      <input type="hidden" name="reviewRating" id="reviewRating" required>
      <span class="star" data-value="1">★</span>
      <span class="star" data-value="2">★</span>
      <span class="star" data-value="3">★</span>
      <span class="star" data-value="4">★</span>
      <span class="star" data-value="5">★</span>
    </div>
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

// Star Rating Logic
const stars = document.querySelectorAll(".star-rating .star");
const reviewRatingInput = document.getElementById("reviewRating");

stars.forEach(star => {
  star.addEventListener("mouseover", () => {
    const val = star.dataset.value;
    stars.forEach(s => s.classList.toggle("hover", s.dataset.value <= val));
  });

  star.addEventListener("mouseout", () => {
    stars.forEach(s => s.classList.remove("hover"));
  });

  star.addEventListener("click", () => {
    const val = star.dataset.value;
    reviewRatingInput.value = "★".repeat(val) + "☆".repeat(5 - val);
    stars.forEach(s => s.classList.toggle("selected", s.dataset.value <= val));
  });
});


// Anonymous Checkbox Logic
const anonymousCheckbox = document.getElementById("anonymousCheckbox");
const reviewNameInput = document.querySelector("input[name='reviewName']");

anonymousCheckbox.addEventListener("change", () => {
  if (anonymousCheckbox.checked) {
    reviewNameInput.value = "Anonymous";
    reviewNameInput.disabled = true;
  } else {
    reviewNameInput.value = "";
    reviewNameInput.disabled = false;
  }
});
</script>
    <!-- FUNCTIONS -->



    <?php include('../../includes/footer.php'); ?>
  </body>
</html>
