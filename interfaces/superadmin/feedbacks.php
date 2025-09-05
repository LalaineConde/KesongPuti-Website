  <?php
  $page_title = 'Feedbacks | Kesong Puti';
  require '../../connection.php';
  include ('../../includes/superadmin-dashboard.php');

  $toast_message = ''; // Initialize variable for toast message

  if ($_SESSION['role'] === 'admin') {
      $recipient = "admin_" . $_SESSION['admin_id'];
  } elseif ($_SESSION['role'] === 'superadmin') {
      $recipient = "super_" . $_SESSION['super_id'];
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
                      $to = htmlspecialchars($row['email']);
                      $subject = rawurlencode('Response to your message');
                      
                      // Use admin name from DB (recipient column stores "admin_1" or "super_2")
                      $recipient = $row['recipient']; 
                      $adminName = "Our Team"; // fallback
                      
                      if (strpos($recipient, 'admin_') === 0) {
                          $adminId = str_replace('admin_', '', $recipient);
                          $query = mysqli_query($connection, "SELECT username FROM admins WHERE admin_id='$adminId'");
                          if ($query && $adminRow = mysqli_fetch_assoc($query)) {
                              $adminName = $adminRow['username'];

                          }
                      } elseif (strpos($recipient, 'super_') === 0) {
                          $superId = str_replace('super_', '', $recipient);
                          $query = mysqli_query($connection, "SELECT username FROM super_admin WHERE super_id='$superId'");
                          if ($query && $superRow = mysqli_fetch_assoc($query)) {
                              $adminName = $superRow['username'];

                          }
                      }

                      $body = rawurlencode(
                        "Hi {$row['name']},\r\n\r\n".
                        "Thank you for your feedback.\r\n\r\n".
                        "Your Message:\r\n\"{$row['feedback']}\"\r\n\r\n".
                        "[Insert your response here]\r\n\r\n".
                        "Best regards,\r\n".
                        "$adminName\r\n".
                        "[Your Contact Information]"
                      );
                    ?>
                    <tr>
                      <td><?php echo htmlspecialchars($row['name']); ?></td>
                      <td>
                        <a href="mailto:<?= $to ?>?subject=<?= $subject ?>&body=<?= $body ?>" target="_blank">
                          <?= $to ?>
                        </a>
                      </td>
                      <td><?php echo htmlspecialchars($row['rating']); ?></td>
                      <td class="contact-message"><span class="short-msg"><?php echo htmlspecialchars($row['feedback']); ?></span></td>
                      <td><?php echo date("M j, Y", strtotime($row['created_at'])); ?></td>
                      <td>
                      <button
                        class="view-btn view-more"
                        data-message="<?php echo htmlspecialchars($row['feedback']); ?>"

                        title="View More"
                      >
                        <i class="bi bi-eye-fill"></i>
                      </button>
                      <button class="delete-btn" data-id="<?php echo $row['review_id']; ?>">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" style="text-align:center;">No messages found</td>
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
  // 👁️ View More modal
  const modal = document.getElementById("feedbackModal");
  const fullMessageText = document.getElementById("fullMessageText");
  const closeModalBtn = document.querySelector(".close-modal");

  document.querySelectorAll(".view-more").forEach((button) => {
    button.addEventListener("click", () => {
      fullMessageText.textContent = button.getAttribute("data-message");
      modal.style.display = "flex";
    });
  });

  closeModalBtn.onclick = () => (modal.style.display = "none");
  window.onclick = (e) => {
    if (e.target === modal) modal.style.display = "none";
  };
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