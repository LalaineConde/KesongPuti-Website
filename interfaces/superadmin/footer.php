<?php
$page_title = 'Footer | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

$toast_message = '';

$footer_query = mysqli_query($connection, "SELECT * FROM footer_settings LIMIT 1");
$footer = mysqli_fetch_assoc($footer_query);


// UPDATE FOOTER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_footer'])) {
    $description = $_POST['description'];
    $bottom_text = $_POST['bottom_text'];

    // Handle file upload (background)
if (!empty($_FILES['background_image']['name'])) {
    $background = time() . '_' . $_FILES['background_image']['name'];
    $target = "../../uploads/footer/" . basename($background);
    move_uploaded_file($_FILES['background_image']['tmp_name'], $target);

    $background_sql = ", background_image='$background'";
} else {
    $background_sql = "";
}



    // Handle file upload (logo)
    if (!empty($_FILES['logo']['name'])) {
        $logo = $_FILES['logo']['name'];
        $target = "../../uploads/footer/" . basename($logo);
        move_uploaded_file($_FILES['logo']['tmp_name'], $target);

        $logo_sql = ", logo='$logo'";
    } else {
        $logo_sql = "";
    }

mysqli_query($connection, "UPDATE footer_settings 
                           SET description='$description', bottom_text='$bottom_text' $logo_sql $background_sql
                           WHERE id=" . $footer['id']);



    $toast_message = "Footer updated successfully!";
}

// ADD CONTACT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_contact'])) {
    $store_name = $_POST['store_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    mysqli_query($connection, "INSERT INTO store_contacts (store_name,email,phone,address) 
                               VALUES ('$store_name','$email','$phone','$address')");
    $toast_message = "Contact added successfully!";
}

// EDIT CONTACT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_contact'])) {
    $id = $_POST['id'];
    $store_name = $_POST['store_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    mysqli_query($connection, "UPDATE store_contacts 
                               SET store_name='$store_name', email='$email', phone='$phone', address='$address' 
                               WHERE id=$id");
    $toast_message = "Contact updated successfully!";
}

// DELETE CONTACT
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($connection, "DELETE FROM store_contacts WHERE id=$id");
    $toast_message = "Contact deleted successfully!";
}

// Fetch contacts (all)
$contacts_query = mysqli_query($connection, "SELECT * FROM store_contacts ORDER BY id DESC");
$contacts = mysqli_fetch_all($contacts_query, MYSQLI_ASSOC);

mysqli_close($connection);
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Footer | Kesong Puti</title>
     <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

    <link rel="stylesheet" href="../../css/admin.css"/>

</head>
</head>
<body>
<div class="box" id="footer-content">
  <h1>Customize Footer</h1>

  <!-- Update Footer Settings -->
  <form method="post" enctype="multipart/form-data" id="footer-content">
    <label>Footer Background:</label>
    <input type="file" name="background_image"><br>
    <?php if (!empty($footer['background_image'])): ?>
      <img src="../../uploads/footer/<?php echo $footer['background_image']; ?>" width="200"><br>
    <?php endif; ?>

    <label>Logo:</label>
    <input type="file" name="logo"><br>
    <?php if (!empty($footer['logo'])): ?>
      <img src="../../uploads/footer/<?php echo htmlspecialchars($footer['logo']); ?>" width="100"><br>
    <?php endif; ?>

    <label>Description:</label>
    <textarea name="description" rows="3"><?php echo $footer['description']; ?></textarea><br>

    <label>Footer Note:</label>
    <input type="text" name="bottom_text" value="<?php echo $footer['bottom_text']; ?>"><br>

    <div class="btn-wrapper">
        <button type="submit" name="update_footer">
            <i class="bi bi-check-circle"></i> Update Footer
        </button>
    </div>
  </form>

  

  <!-- Add Contact -->
  <form method="post" id="footer-content">  
    <h3>Add Store Contact</h3>
    <input type="text" name="store_name" placeholder="Store Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="phone" placeholder="Phone" required><br>
    <textarea name="address" placeholder="Address" required></textarea><br>
    <div class="btn-wrapper">
        <button type="submit" name="add_contact">
            <i class="bi bi-check-circle"></i> Add Contact
        </button>
    </div>
  </form>



<!-- Contacts Table -->
<h3>Contact List</h3>
<div class="footer-table-wrapper">
  <table class="footer-table">
    <thead>
      <tr>
        <th>Store Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($contacts)): ?>
        <?php foreach ($contacts as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['store_name']) ?></td>
            <td><?= htmlspecialchars($c['email']) ?></td>
            <td><?= htmlspecialchars($c['phone']) ?></td>
            <td><?= htmlspecialchars($c['address']) ?></td>
            <td>
              <!-- Edit -->
            <button class="update-btn" 
                    data-id="<?= $c['id'] ?>" 
                    data-store="<?= htmlspecialchars($c['store_name'], ENT_QUOTES) ?>" 
                    data-email="<?= htmlspecialchars($c['email'], ENT_QUOTES) ?>" 
                    data-phone="<?= htmlspecialchars($c['phone'], ENT_QUOTES) ?>" 
                    data-address="<?= htmlspecialchars($c['address'], ENT_QUOTES) ?>">
                <i class="bi bi-pencil-square"></i>
            </button>

              <!-- Delete -->
              <button class="delete-btn" href="?delete=<?= $c['id'] ?>" >              
                    <i class="bi bi-trash-fill"></i>
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center text-muted">No contacts available</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Edit Contact Modal -->
<div id="editModal" style="display:none;">
  <h3>Edit Contact</h3>
  <form method="post">
    <input type="hidden" name="id" id="edit_id">
    <input type="text" name="store_name" id="edit_store_name" required><br>
    <input type="email" name="email" id="edit_email" required><br>
    <input type="text" name="phone" id="edit_phone" required><br>
    <textarea name="address" id="edit_address" required></textarea><br>
    <button type="submit" name="edit_contact">Update</button>
    <button type="button" onclick="closeEditModal()">Cancel</button>
  </form>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // ✅ Toast after Add
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
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
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
            const oldEmail = this.getAttribute("data-email");
            const oldPhone = this.getAttribute("data-phone");
            const oldAddress = this.getAttribute("data-address");

            Swal.fire({
                title: "Edit Contact",
                html: `
                    <input id="swal-store" class="swal2-input" value="${oldStore}" placeholder="Store Name">
                    <input id="swal-email" class="swal2-input" value="${oldEmail}" placeholder="Email">
                    <input id="swal-phone" class="swal2-input" value="${oldPhone}" placeholder="Phone">
                    <textarea id="swal-address" class="swal2-textarea" placeholder="Address">${oldAddress}</textarea>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: "Save",
                preConfirm: () => {
                    const store = document.getElementById("swal-store").value;
                    const email = document.getElementById("swal-email").value;
                    const phone = document.getElementById("swal-phone").value;
                    const address = document.getElementById("swal-address").value;

                    if (!store || !email || !phone || !address) {
                        Swal.showValidationMessage("All fields are required");
                        return false;
                    }
                    return { store, email, phone, address };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("edit-contact.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `id=${id}&store_name=${encodeURIComponent(result.value.store)}&email=${encodeURIComponent(result.value.email)}&phone=${encodeURIComponent(result.value.phone)}&address=${encodeURIComponent(result.value.address)}`
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