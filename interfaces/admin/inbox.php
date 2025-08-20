<?php
$page_title = 'Inbox | Kesong Puti';
require '../../connection.php';
include ('../../includes/admin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message

if ($_SESSION['role'] === 'admin') {
    $recipient = "admin_" . $_SESSION['admin_id'];
} elseif ($_SESSION['role'] === 'superadmin') {
    $recipient = "super_" . $_SESSION['super_id'];
}

// Fetch inbox messages
$sql = "SELECT * FROM inbox_messages 
        WHERE recipient = '$recipient' 
        ORDER BY created_at DESC";

$result = mysqli_query($connection, $sql);

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox | Kesong Puti</title>

    <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../css/admin.css"/>

</head>
<body>
  <div class="main-content">

      <!-- INBOX -->
      <div class="box" id="inbox-content">
        <h1>Inbox Messages</h1>

        <div class="contact-table-container">
          <input
            type="text"
            id="contactSearch"
            placeholder="Search by name or email..."
          />

          <table class="contact-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact No.</th>
                <th>Message</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="contactTableBody">
              <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['contact']); ?></td>
                    <td class="contact-message"><span class="short-msg"><?php echo htmlspecialchars($row['message']); ?></span></td>
                    <td><?php echo date("M j, Y", strtotime($row['created_at'])); ?></td>
                    <td>
                    <button
                      class="view-btn view-more"
                      data-message="<?php echo htmlspecialchars($row['message']); ?>"
                      data-name="<?php echo htmlspecialchars($row['name']); ?>"
                      title="View More"
                    >
                      <i class="bi bi-eye-fill"></i>
                    </button>
                    <button class="delete-btn" data-id="<?php echo $row['inbox_id']; ?>">
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
              <div class="inbox-modal" id="inboxModal">
                <div class="modal-content">
                  <span class="close-modal">&times;</span>
                  <h2 id="modalName"></h2>
                  <p id="fullMessageText"></p>
                </div>
              </div>
      </div>
</div>      
      <!-- INBOX -->
      
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
      const modal = document.getElementById("inboxModal");
      const modalName = document.getElementById("modalName");
      const fullMessageText = document.getElementById("fullMessageText");
      const closeModalBtn = document.querySelector(".close-modal");

      // Attach click to all "View More" buttons
      document.querySelectorAll(".view-more").forEach((button) => {
        button.addEventListener("click", () => {
          modalName.textContent = button.getAttribute("data-name"); // sender's name
          fullMessageText.textContent = button.getAttribute("data-message"); // full message
          modal.style.display = "flex"; // show modal
        });
      });

      // Close when clicking X
      closeModalBtn.onclick = () => {
        modal.style.display = "none";
      };

      // Close when clicking outside modal
      window.onclick = (e) => {
        if (e.target === modal) {
          modal.style.display = "none";
        }
      };
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
                fetch("delete-message.php", {
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