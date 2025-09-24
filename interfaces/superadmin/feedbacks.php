  <?php
  $page_title = 'Feedbacks | Kesong Puti';
  require '../../connection.php';
  include ('../../includes/superadmin-dashboard.php');

  $toast_message = ''; // Initialize variable for toast message

 if ($_SESSION['role'] === 'admin') {
    $recipient = "admin_" . $_SESSION['admin_id'];
    $userId = $_SESSION['admin_id'];
    $storeQuery = "SELECT store_name FROM admins WHERE admin_id = $userId LIMIT 1";
} elseif ($_SESSION['role'] === 'superadmin') {
    $recipient = "super_" . $_SESSION['super_id'];
    $userId = $_SESSION['super_id'];
    $storeQuery = "SELECT store_name FROM super_admin WHERE super_id = $userId LIMIT 1";
}

$storeName = "Kesong Puti"; // fallback
if (isset($storeQuery)) {
    $storeResult = mysqli_query($connection, $storeQuery);
    $storeRow = mysqli_fetch_assoc($storeResult);
    if ($storeRow && !empty($storeRow['store_name'])) {
        $storeName = htmlspecialchars($storeRow['store_name']);
    }
}

  // Fetch inbox messages
  $sql = "SELECT * FROM reviews 
          WHERE recipient = '$recipient' 
          ORDER BY created_at DESC";

  $result = mysqli_query($connection, $sql);


  ?>




  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Feedbacks | Kesong Puti</title>

      <!-- BOOTSTRAP ICONS -->
      <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      />

      <link rel="stylesheet" href="../../css/admin.css"/>
<style>


</style>
  </head>
  <body>

  <!-- FEEDBACK -->

  <div class="main-content">
        <div class="box" id="feedback-content">
          <h1>Feedbacks</h1>

          <div class="feedback-filter-bar">
            <input
              type="text"
              id="feedbackSearch"
              placeholder="Search by name, email, or message..."
            />
  <div class="star-filter">
    <span class="filter-star active" data-value="all">All</span>
    <span class="filter-star" data-value="1">★</span>
    <span class="filter-star" data-value="2">★</span>
    <span class="filter-star" data-value="3">★</span>
    <span class="filter-star" data-value="4">★</span>
    <span class="filter-star" data-value="5">★</span>
  </div>
          </div>
        <div class="feedback-table-wrapper">
          <table class="feedback-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Rating</th>
                <th>Message</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
              <tbody id="feedbackTableBody">
              <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <?php
                    // Convert numeric rating to stars for display
                  $rating = (int)$row['rating']; // ensure it's an integer
              $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                  ?>
                  <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
              <td>
                  <?php 
                      $cleanName = htmlspecialchars($row['name']);
                      $email = htmlspecialchars($row['email']);
                      $subject = "Response to Your Feedback";

                      $body = "Dear $cleanName,%0D%0A%0D%0A" .
                              "Thank you for your feedback regarding our services. We have reviewed your message and would like to provide the following response:%0D%0A%0D%0A" .
                              "[Your Response Here]%0D%0A%0D%0A" .
                              "Best regards,%0D%0A" .
                              "$storeName";
                  ?>
                  <a href="mailto:<?= $email ?>?subject=<?= rawurlencode($subject) ?>&body=<?= $body ?>">
                      <?= $email ?>
                  </a>
              </td>

                    <td class="rating-stars"><?= $stars ?></td>
                    <td class="contact-message">
                      <span class="short-msg">
                        <?= htmlspecialchars(strlen($row['feedback']) > 50 ? substr($row['feedback'],0,50).'...' : $row['feedback']) ?>
                      </span>
                    </td>
                    <td><?= date("M j, Y, g:i a", strtotime($row['created_at'])) ?></td>
                    <td>
                      <button
                        class="view-btn view-more"
                        data-message="<?= htmlspecialchars($row['feedback']) ?>"
                        data-media='<?= htmlspecialchars($row['media']) ?>'
                        title="View Full Message"
                      >
                        <i class="bi bi-eye-fill"></i>
                      </button>
                      <button class="delete-btn" data-id="<?= $row['review_id'] ?>">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" style="text-align:center;">No feedbacks found</td>
                </tr>
              <?php endif; ?>
              </tbody>
                        </table>
                      </div>

                        <!-- MODAL for full message -->
              <div class="feedback-modal" id="feedbackModal">
                  <div class="modal-content">
                      <span class="close-modal">&times;</span>
                      <h2>Full Message</h2>
                      <p id="fullMessageText"></p>
                      <div class="modal-gallery">
                          <button class="prev-btn">&#10094;</button>
                          <img id="modalImage" src="" alt="Media Preview" style="max-width:400px;">
                          <button class="next-btn">&#10095;</button>
                      </div>
                  </div>
              </div>
                      </div>
                </div>
        <!-- FEEDBACK -->
    
  <!-- FUNCTIONS -->

  <script>
        const contactSearch = document.getElementById("contactSearch");
        const contactRows = document.querySelectorAll("#contactTableBody tr");

        contactSearch.addEventListener("input", () => {
          const term = contactSearch.value.toLowerCase();

          contactRows.forEach((row) => {
            const name = row.children[0].textContent.toLowerCase();
            const email = row.children[1].textContent.toLowerCase();
            row.style.display =
              name.includes(term) || email.includes(term) ? "" : "none";
          });
        });
      </script>

  <script>
  const feedbackSearch = document.getElementById("feedbackSearch");
  const feedbackRows = document.querySelectorAll("#feedbackTableBody tr");
  const stars = document.querySelectorAll(".filter-star");

  let selectedRating = "all";

  // ✅ Convert "★★★★★" to 5, "★★★☆☆" to 3, etc.
  function getStarCount(starString) {
    return (starString.match(/★/g) || []).length;
  }

  // Filter function
  function filterFeedback() {
    const searchTerm = feedbackSearch.value.toLowerCase();

    feedbackRows.forEach((row) => {
      const name = row.children[0].textContent.toLowerCase();
      const email = row.children[1].textContent.toLowerCase();
      const message = row.children[3].textContent.toLowerCase();
      const ratingText = row.children[2].textContent.trim();
      const rowRating = getStarCount(ratingText);

      const matchesSearch =
        name.includes(searchTerm) ||
        email.includes(searchTerm) ||
        message.includes(searchTerm);

      const matchesRating =
        selectedRating === "all" || rowRating == selectedRating;

      row.style.display = matchesSearch && matchesRating ? "" : "none";
    });
  }

  // Search input event
  feedbackSearch.addEventListener("input", filterFeedback);

  // ⭐ Star click + hover
  stars.forEach((star, index) => {
    // Click → select rating
    star.addEventListener("click", () => {
      stars.forEach(s => s.classList.remove("active"));

      if (star.dataset.value === "all") {
        selectedRating = "all";
        star.classList.add("active");
      } else {
        selectedRating = parseInt(star.dataset.value);
        for (let i = 1; i <= index; i++) stars[i].classList.add("active"); // start from 1 because 0 = All
      }

      filterFeedback();
    });

    // Hover effect only for numbered stars
    if (star.dataset.value !== "all") {
      star.addEventListener("mouseover", () => {
        stars.forEach(s => s.classList.remove("hover"));
        for (let i = 1; i <= index; i++) stars[i].classList.add("hover");
      });

      star.addEventListener("mouseout", () => {
        stars.forEach(s => s.classList.remove("hover"));
      });
    }
  });
  </script>

  <script>
      // 👁️ Modal with Prev/Next for images
      const modal = document.getElementById("feedbackModal");
      const fullMessageText = document.getElementById("fullMessageText");
      const modalImage = document.getElementById("modalImage");
      const closeModalBtn = document.querySelector(".close-modal");
      const prevBtn = document.querySelector(".prev-btn");
      const nextBtn = document.querySelector(".next-btn");

      let mediaFiles = [];
      let currentIndex = 0;

      document.querySelectorAll(".view-more").forEach(button => {
          button.addEventListener("click", () => {
              // Full message
              fullMessageText.textContent = button.getAttribute("data-message");

              // Get media
              const mediaData = button.getAttribute("data-media");
              mediaFiles = [];
              currentIndex = 0;

              if(mediaData){
                  try {
                      mediaFiles = JSON.parse(mediaData);
                      if(!Array.isArray(mediaFiles)) mediaFiles = [mediaData];
                  } catch {
                      mediaFiles = [mediaData];
                  }
              }

              // Show first image/video
              showMedia(currentIndex);

              modal.style.display = "flex";
          });
      });

      closeModalBtn.onclick = () => modal.style.display = "none";
      window.onclick = e => { if(e.target === modal) modal.style.display = "none"; };

      function showMedia(index) {
          if(mediaFiles.length === 0) {
              modalImage.style.display = "none";
              return;
          }

          let file = mediaFiles[index];
          const ext = file.split('.').pop().toLowerCase();

          if(['jpg','jpeg','png','gif'].includes(ext)){
              modalImage.src = '../../'+file;
              modalImage.style.display = "block";
          } else {
              // For video, replace image with video element
              const videoEl = document.createElement('video');
              videoEl.src = '../../'+file;
              videoEl.controls = true;
              videoEl.style.maxWidth = '400px';
              modalImage.replaceWith(videoEl);
              modalImage = videoEl; // Update reference
          }
      }

      prevBtn.addEventListener('click', () => {
          if(mediaFiles.length === 0) return;
          currentIndex = (currentIndex - 1 + mediaFiles.length) % mediaFiles.length;
          showMedia(currentIndex);
      });

      nextBtn.addEventListener('click', () => {
          if(mediaFiles.length === 0) return;
          currentIndex = (currentIndex + 1) % mediaFiles.length;
          showMedia(currentIndex);
      });
  </script>
      
  <!-- FUNCTIONS -->
  <!-- SweetAlert2 Library -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      
      <script>
      var toastMessage = "<?php echo isset($toast_message) ? $toast_message : ''; ?>";
      if (toastMessage) {
          Swal.fire({
              icon: 'info', // can be 'success', 'error', 'warning', 'info', 'question'
              text: toastMessage,
              confirmButtonColor: '#ff6b6b'
          });
      }
      </script>  
      
      
  
      <script>
        document.addEventListener("DOMContentLoaded", function () {
          const deleteButtons = document.querySelectorAll(".delete-btn");

          deleteButtons.forEach((btn) => {
            btn.addEventListener("click", function () {
              const messageId = this.getAttribute("data-id");

              Swal.fire({
                title: "Are you sure?",
                text: "This message will be deleted permanently!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ff6b6b",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!",
              }).then((result) => {
                if (result.isConfirmed) {
                  fetch("delete-feedback.php", {
                    method: "POST",
                    headers: {
                      "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: "id=" + messageId,
                  })
                    .then((response) => response.text())
                    .then((data) => {
                      data = data.trim(); // 🔑 remove spaces/newlines
                      if (data === "success") {
                        Swal.fire("Deleted!", "The message has been removed.", "success")
                          .then(() => location.reload());
                      } else {
                        Swal.fire("Error!", data, "error"); // show actual error
                      }
                    });
                }
              });
            });
          });
        });
  </script>

  <!-- FUNCTIONS -->
  </body>
  </html>