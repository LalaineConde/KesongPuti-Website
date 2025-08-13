<?php
$page_title = 'Feedbacks | Kesong Puti';
require '../../connection.php';
include ('../../includes/admin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message


// Close the connection
mysqli_close($connection);


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
          <select id="ratingFilter">
            <option value="all">All Ratings</option>
            <option value="5">⭐⭐⭐⭐⭐</option>
            <option value="4">⭐⭐⭐⭐</option>
            <option value="3">⭐⭐⭐</option>
            <option value="2">⭐⭐</option>
            <option value="1">⭐</option>
          </select>
        </div>

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
            <tr>
              <td>Juan Dela Cruz</td>
              <td>juan@example.com</td>
              <td>⭐⭐⭐⭐⭐</td>
              <td class="feedback-message">
                <span class="short-msg">The product quality is amazing!</span>
                <button
                  class="view-btn view-more"
                  data-message="The product quality is amazing! Will definitely order again. Fast delivery and well-packaged."
                  title="View More"
                >
                  <i class="bi bi-eye-fill"></i>
                </button>
              </td>
              <td>2025-08-05</td>
              <td>
                <button class="delete-btn">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
            <tr>
              <td>Maria Santos</td>
              <td>maria.s@example.com</td>
              <td>⭐⭐⭐</td>
              <td class="feedback-message">
                <span class="short-msg">It was okay.</span>
                <button
                  class="view-btn view-more"
                  data-message="It was okay, but packaging could be better. I hope to see improvements next time."
                  title="View More"
                >
                  <i class="bi bi-eye-fill"></i>
                </button>
              </td>
              <td>2025-08-03</td>
              <td>
                <button class="delete-btn">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>

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
      const ratingFilter = document.getElementById("ratingFilter");
      const feedbackRows = document.querySelectorAll("#feedbackTableBody tr");

      const modal = document.getElementById("feedbackModal");
      const fullMessageText = document.getElementById("fullMessageText");
      const closeModalBtn = document.querySelector(".close-modal");

      function filterFeedback() {
        const searchTerm = feedbackSearch.value.toLowerCase();
        const selectedRating = ratingFilter.value;

        feedbackRows.forEach((row) => {
          const name = row.children[0].textContent.toLowerCase();
          const email = row.children[1].textContent.toLowerCase();
          const message = row.children[3].textContent.toLowerCase();
          const rating = row.children[2].textContent.length;

          const matchesSearch =
            name.includes(searchTerm) ||
            email.includes(searchTerm) ||
            message.includes(searchTerm);
          const matchesRating =
            selectedRating === "all" || rating == selectedRating;

          row.style.display = matchesSearch && matchesRating ? "" : "none";
        });
      }

      feedbackSearch.addEventListener("input", filterFeedback);
      ratingFilter.addEventListener("change", filterFeedback);

      // View More modal
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

<!-- FUNCTIONS -->
</body>
</html>