<?php
$page_title = 'Contact | Kesong Puti';
require '../../connection.php';

$toast_message = '';

// ADD CONTACT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_contact'])) {
    $store_name = $_POST['store_name'];
    $owner = $_POST['owner'] ?? 'N/A';
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $facebook = $_POST['facebook'];
    $twitter = $_POST['twitter'];
    $instagram = $_POST['instagram'];
    
    mysqli_query($connection, "INSERT INTO store_contacts 
        (store_name, owner, email, phone, address, facebook, twitter, instagram) 
        VALUES 
        ('$store_name','$owner','$email','$phone','$address','$facebook','$twitter','$instagram')");
    $toast_message = "Contact added successfully!";
}

// EDIT CONTACT (via fetch)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_contact'])) {
    $id = intval($_POST['id']);
    $store_name = $_POST['store_name'];
    $owner = $_POST['owner'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $facebook = $_POST['facebook'];
    $twitter = $_POST['twitter'];
    $instagram = $_POST['instagram'];

    $query = "UPDATE store_contacts 
              SET store_name='$store_name',
                  owner='$owner',
                  email='$email',
                  phone='$phone',
                  address='$address',
                  facebook='$facebook',
                  twitter='$twitter',
                  instagram='$instagram'
              WHERE id=$id";

    if (mysqli_query($connection, $query)) {
        echo "success"; // ✅ return success for JS
        exit;
    } else {
        echo "DB Error: " . mysqli_error($connection);
        exit;
    }
}

// DELETE CONTACT
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($connection, "DELETE FROM store_contacts WHERE id=$id");
    $toast_message = "Contact deleted successfully!";
}

// Fetch contacts
$contacts_query = mysqli_query($connection, "SELECT * FROM store_contacts ORDER BY id DESC");
$contacts = mysqli_fetch_all($contacts_query, MYSQLI_ASSOC);

include ('../../includes/superadmin-dashboard.php');
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact | Kesong Puti</title>

  <!-- BOOTSTRAP ICONS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="../../css/admin.css"/>
</head>
<body>
<div class="box" id="contact-content">
  <h1>Contacts</h1>

  <!-- Add Contact -->
  <form method="post" id="contact-content">  
    <h3>Add Store Contact</h3>
    <input type="text" name="store_name" placeholder="Store Name" required><br>
    <input type="text" name="owner" placeholder="Owner/Username"><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="phone" placeholder="Phone" required><br>
    <textarea name="address" placeholder="Address" required></textarea><br>
    <input type="url" name="facebook" placeholder="Facebook Link"><br>
    <input type="url" name="twitter" placeholder="Twitter Link"><br>
    <input type="url" name="instagram" placeholder="Instagram Link"><br>
    <div class="btn-wrapper">
        <button type="submit" name="add_contact">
            <i class="bi bi-check-circle"></i> Add Contact
        </button>
    </div>
  </form>

  <!-- Contacts Table -->
  <h3>Contact List</h3>
  <div class="contact-table-wrapper">
    <table class="contact-table">
      <thead>
        <tr>
          <th>Store Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Social Media</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($contacts)): ?>
          <?php foreach ($contacts as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['store_name']) ?></td>
              <td><?= htmlspecialchars($c['owner']) ?></td>
              <td><?= htmlspecialchars($c['email']) ?></td>
              <td><?= htmlspecialchars($c['phone']) ?></td>
              <td><?= htmlspecialchars($c['address']) ?></td>
              <td>
                <div class="social-icons">
                  <?php if (!empty($c['facebook'])): ?>
                    <a href="<?= htmlspecialchars($c['facebook']) ?>" target="_blank">
                      <i class="bi bi-facebook"></i>
                    </a>
                  <?php endif; ?>
                  <?php if (!empty($c['twitter'])): ?>
                    <a href="<?= htmlspecialchars($c['twitter']) ?>" target="_blank">
                      <i class="bi bi-twitter-x"></i>
                    </a>
                  <?php endif; ?>
                  <?php if (!empty($c['instagram'])): ?>
                    <a href="<?= htmlspecialchars($c['instagram']) ?>" target="_blank">
                      <i class="bi bi-instagram"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </td>
              <td>
                <!-- Edit -->
                <button class="update-btn" 
                        data-id="<?= $c['id'] ?>" 
                        data-store="<?= htmlspecialchars($c['store_name'], ENT_QUOTES) ?>" 
                        data-owner="<?= htmlspecialchars($c['owner'], ENT_QUOTES) ?>" 
                        data-email="<?= htmlspecialchars($c['email'], ENT_QUOTES) ?>" 
                        data-phone="<?= htmlspecialchars($c['phone'], ENT_QUOTES) ?>" 
                        data-address="<?= htmlspecialchars($c['address'], ENT_QUOTES) ?>"
                        data-facebook="<?= htmlspecialchars($c['facebook'], ENT_QUOTES) ?>"
                        data-twitter="<?= htmlspecialchars($c['twitter'], ENT_QUOTES) ?>"
                        data-instagram="<?= htmlspecialchars($c['instagram'], ENT_QUOTES) ?>">
                    <i class="bi bi-pencil-square"></i>
                </button>

                <!-- Delete -->
                <a class="delete-btn" href="?delete=<?= $c['id'] ?>">              
                    <i class="bi bi-trash-fill"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center text-muted">No contacts available</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // ✅ Toast after Add/Delete
    var toastMessage = "<?= $toast_message ?>";
    if (toastMessage) {
        Swal.fire({
            icon: 'success',
            text: toastMessage,
            confirmButtonColor: '#28a745'
        });
    }

    // ✅ Delete confirmation
    document.querySelectorAll(".delete-btn").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            let href = this.getAttribute("href");

            Swal.fire({
                title: "Are you sure?",
                text: "This contact will be deleted permanently.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });

    // ✅ Edit Contact
    document.querySelectorAll(".update-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.getAttribute("data-id");
            const oldStore = this.getAttribute("data-store");
            const oldOwner = this.getAttribute("data-owner");
            const oldEmail = this.getAttribute("data-email");
            const oldPhone = this.getAttribute("data-phone");
            const oldAddress = this.getAttribute("data-address");
            const oldFacebook = this.getAttribute("data-facebook");
            const oldTwitter = this.getAttribute("data-twitter");
            const oldInstagram = this.getAttribute("data-instagram");

            Swal.fire({
                title: "Edit Contact",
                html: `
                    <input id="swal-store" class="swal2-input" value="${oldStore}" placeholder="Store Name">
                    <input id="swal-owner" class="swal2-input" value="${oldOwner}" placeholder="Owner">
                    <input id="swal-email" class="swal2-input" value="${oldEmail}" placeholder="Email">
                    <input id="swal-phone" class="swal2-input" value="${oldPhone}" placeholder="Phone">
                    <textarea id="swal-address" class="swal2-textarea" placeholder="Address">${oldAddress}</textarea>
                    <input id="swal-facebook" class="swal2-input" value="${oldFacebook}" placeholder="Facebook Link">
                    <input id="swal-twitter" class="swal2-input" value="${oldTwitter}" placeholder="Twitter Link">
                    <input id="swal-instagram" class="swal2-input" value="${oldInstagram}" placeholder="Instagram Link">
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: "Save",
                preConfirm: () => {
                    const store = document.getElementById("swal-store").value;
                    const owner = document.getElementById("swal-owner").value;
                    const email = document.getElementById("swal-email").value;
                    const phone = document.getElementById("swal-phone").value;
                    const address = document.getElementById("swal-address").value;
                    const facebook = document.getElementById("swal-facebook").value;
                    const twitter = document.getElementById("swal-twitter").value;
                    const instagram = document.getElementById("swal-instagram").value;

                    if (!store || !owner || !email || !phone || !address) {
                        Swal.showValidationMessage("Store, Owner, Email, Phone, and Address are required");
                        return false;
                    }
                    return { store, owner, email, phone, address, facebook, twitter, instagram };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("", { // same PHP file
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `id=${id}&store_name=${encodeURIComponent(result.value.store)}&owner=${encodeURIComponent(result.value.owner)}&email=${encodeURIComponent(result.value.email)}&phone=${encodeURIComponent(result.value.phone)}&address=${encodeURIComponent(result.value.address)}&facebook=${encodeURIComponent(result.value.facebook)}&twitter=${encodeURIComponent(result.value.twitter)}&instagram=${encodeURIComponent(result.value.instagram)}&edit_contact=1`
                    })
                    .then(res => res.text())
                    .then(data => {
                        if (data.trim() === "success") {
                            Swal.fire("Updated!", "The contact has been updated.", "success")
                                .then(() => location.reload());
                        } else {
                            Swal.fire("Error!", data, "error");
                        }
                    });
                }
            });
        });
    });
});
</script>
</body>
</html>
