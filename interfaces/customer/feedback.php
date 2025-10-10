<?php
$page_title = 'Customer Feedback | Kesong Puti';
require '../../connection.php';
$current_page = 'feedback';
$isHomePage = ($current_page === 'home'); // check if this is the home page
include ('../../includes/customer-dashboard.php');

$toast_message = '';

// Fetch reviews
$reviewQuery = "
SELECT r.rating, r.feedback, r.media, r.created_at,
       r.name AS fullname, r.email
FROM reviews r
ORDER BY r.created_at DESC
";
$reviewResult = mysqli_query($connection, $reviewQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Kesong Puti - Customer Feedback</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>

<section class="feedback-page container mt-4">
  <div class="message mb-3">
    Love our Kesong Puti products? Share your feedback below!
  </div>

  <h4>Customer Reviews</h4>
  <div class="feedback-container mt-4">
    <?php if ($reviewResult && mysqli_num_rows($reviewResult) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($reviewResult)): ?>
        <div class="feedback-box border p-3 mb-3 rounded">
          <h5><?= htmlspecialchars($row['fullname'] ?? 'Anonymous') ?></h5>
          <small class="text-muted"><?= htmlspecialchars($row['email'] ?? '') ?></small>
          <div class="feedback-stars mb-2">
            <?php for ($i=1; $i<=5; $i++): ?>
              <span style="color:<?= ($i <= $row['rating']) ? 'gold' : '#ccc' ?>">&#9733;</span>
            <?php endfor; ?>
          </div>
          <?php if (!empty($row['feedback'])): ?>
            <p class="mt-2"><?= nl2br(htmlspecialchars($row['feedback'])) ?></p>
          <?php endif; ?>

          <?php
          $mediaFiles = [];
          if (!empty($row['media'])) {
              $decoded = json_decode($row['media'], true);
              $mediaFiles = is_array($decoded) ? $decoded : [$row['media']];
          }
          $maxPreview = 3;
          ?>

<?php if (!empty($mediaFiles)): ?>
<div class="media-row mt-2">
  <?php foreach ($mediaFiles as $i => $file): 
      if ($i >= $maxPreview) break; // only show up to $maxPreview
      $ext = pathinfo($file, PATHINFO_EXTENSION);
      $type = preg_match('/(jpg|jpeg|png|gif)/i', $ext) ? 'image' : 'video';
  ?>
    <div class="media-thumb" 
         data-files='<?= htmlspecialchars(json_encode($mediaFiles)) ?>'
         data-index="<?= $i ?>">
      <?php if ($type==='image'): ?>
        <img src="../../<?= htmlspecialchars($file) ?>">
      <?php else: ?>
        <video src="../../<?= htmlspecialchars($file) ?>"></video>
      <?php endif; ?>

      <?php if ($i === $maxPreview - 1 && count($mediaFiles) > $maxPreview): ?>
        <div class="overlay">+<?= count($mediaFiles) - $maxPreview + 1 ?></div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>


          <small class="text-muted">Posted on <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></small>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No reviews yet.</p>
    <?php endif; ?>
  </div>

  <a href="review_form.php" class="review-btn">Leave a Review</a>
</section>

<!-- Media Carousel Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark position-relative">
      <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-index-3" 
              data-bs-dismiss="modal" aria-label="Close" style="z-index: 1055; font-size: 1.5rem;"></button>

      <div class="modal-body p-0 position-relative">
        <div id="mediaCarousel" class="carousel slide">
          <div class="carousel-inner" id="carouselInner"></div>
          <button class="carousel-control-prev" type="button" data-bs-target="#mediaCarousel" data-bs-slide="prev">
            <i class="bi bi-chevron-left text-black fs-1"></i>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#mediaCarousel" data-bs-slide="next">
            <i class="bi bi-chevron-right text-black fs-1"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Open media in carousel
document.querySelectorAll('.media-thumb').forEach(el => {
    el.addEventListener('click', function() {
        const files = JSON.parse(this.dataset.files);
        const index = parseInt(this.dataset.index);
        openMediaCarousel(files, index);
    });
});

function openMediaCarousel(mediaFiles, startIndex = 0) {
    const carouselInner = document.getElementById('carouselInner');
    carouselInner.innerHTML = '';
    mediaFiles.forEach((file, i) => {
        const ext = file.split('.').pop().toLowerCase();
        const activeClass = i === startIndex ? 'active' : '';
        let item = '';
        if (['jpg','jpeg','png','gif'].includes(ext)) {
            item = `<div class="carousel-item ${activeClass}">
                        <img src="../../${file}" style="width:100%; max-height:80vh; object-fit:contain;">
                    </div>`;
        } else if (['mp4','webm','ogg'].includes(ext)) {
            item = `<div class="carousel-item ${activeClass}">
                        <video controls style="width:100%; max-height:80vh; object-fit:contain;">
                            <source src="../../${file}" type="video/mp4">
                        </video>
                    </div>`;
        }
        carouselInner.insertAdjacentHTML('beforeend', item);
    });
    const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
    modal.show();
}
</script>
<?php include('../../includes/floating-button.php'); ?>
<?php include('../../includes/footer.php'); ?>
</body>
</html>
