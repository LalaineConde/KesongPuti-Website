<?php
$page_title = 'Payment Method | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');


// Identify recipient
if ($_SESSION['role'] === 'superadmin') {
    $recipient = 'super_' . $_SESSION['super_id'];
} else {
    $recipient = 'admin_' . $_SESSION['admin_id'];
}

// CREATE
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['edit_method_id']) && !isset($_POST['delete_method_id'])) {
    $method_name    = $_POST['method_name'] ?? '';
    $method_type    = strtolower(trim($_POST['method_type'] ?? 'e-wallet')); // normalize
    $account_name   = $_POST['account_name'] ?? null;
    $account_number = $_POST['account_number'] ?? null;
    $status         = $_POST['status'] ?? 'published';

    $qr_code = null;
    if (!empty($_FILES['qr_code']['name'])) {
        $targetDir = "../../uploads/payment_qr/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $filename = time() . "_" . basename($_FILES['qr_code']['name']);
        $qr_code  = $filename;
        move_uploaded_file($_FILES['qr_code']['tmp_name'], $targetDir . $filename);
    }

    $sql = "INSERT INTO payment_methods (method_name, method_type, account_name, account_number, qr_code, status, recipient) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssssss", $method_name, $method_type, $account_name, $account_number, $qr_code, $status, $recipient);
    $stmt->execute();
    $stmt->close();
}

// DELETE
if (isset($_POST['delete_method_id'])) {
    $delete_id = intval($_POST['delete_method_id']);
    $deleteSql = "DELETE FROM payment_methods WHERE method_id = ? AND recipient = ?";
    $stmt = $connection->prepare($deleteSql);
    $stmt->bind_param("is", $delete_id, $recipient);
    $stmt->execute();
    $stmt->close();
}

// UPDATE
if (isset($_POST['edit_method_id'])) {
    $edit_id        = intval($_POST['edit_method_id']);
    $method_name    = $_POST['edit_method_name'];
    $method_type    = strtolower(trim($_POST['edit_method_type'])); 
    $account_name   = $_POST['edit_account_name'];
    $account_number = $_POST['edit_account_number'];
    $status         = $_POST['edit_status'];
    $qr_code        = $_POST['existing_qr_code'];

    if (!empty($_FILES['edit_qr_code']['name'])) {
        $targetDir = "../../uploads/payment_qr/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $filename = time() . "_" . basename($_FILES['edit_qr_code']['name']);
        $qr_code  = $filename;
        move_uploaded_file($_FILES['edit_qr_code']['tmp_name'], $targetDir . $filename);
    }

    $updateSql = "UPDATE payment_methods 
                  SET method_name=?, method_type=?, account_name=?, account_number=?, qr_code=?, status=?, updated_at=NOW() 
                  WHERE method_id=? AND recipient=?";
    $stmt = $connection->prepare($updateSql);
    $stmt->bind_param("ssssssis", $method_name, $method_type, $account_name, $account_number, $qr_code, $status, $edit_id, $recipient);
    $stmt->execute();
    $stmt->close();
}

// FETCH
$listSql = "SELECT * FROM payment_methods WHERE recipient = ? ORDER BY created_at DESC";
$stmt = $connection->prepare($listSql);
$stmt->bind_param("s", $recipient);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Method | Kesong Puti</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="../../css/admin.css"/>
</head>
<body>

<div class="main-content">
  <div class="box" id="payment-content">
    <h1>Payment Methods</h1>

    <!-- ADD FORM -->
    <form id="paymentForm" class="payment-form" method="POST" enctype="multipart/form-data" action="payment.php">
      <div class="form-group">
        <label for="methodName">Method Name</label>
        <select id="methodName" name="method_name" required>
          <option value="e-wallet">E-Wallet</option>
          <option value="bank">Bank</option>
          <option value="cash">Cash</option>
        </select>
      </div>

      <div class="form-group">
        <label for="methodType">Method Type</label>
        <input type="text" id="methodType" name="method_type" placeholder="e.g., GCash, PayMaya, BDO, BPI" required>
      </div>

      <div class="form-group">
        <label for="accountName">Account Name</label>
        <input type="text" id="accountName" name="account_name">
      </div>

      <div class="form-group">
        <label for="accountNumber">Account Number</label>
        <input type="text" id="accountNumber" name="account_number">
      </div>

      <div class="form-group">
        <label for="qrCode">QR Code</label>
        <input type="file" id="qrCode" name="qr_code" accept="image/*">
        <img id="qrPreviewImage" src="" alt="QR Preview" style="max-width:100px;display:none;">
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status">
          <option value="published">Published</option>
          <option value="unpublished">Unpublished</option>
        </select>
      </div>

      <button type="submit" class="save-btn">Save Payment Method</button>
    </form>

    <!-- TABLE -->
    <div class="payment-table-wrapper">
      <table class="payment-table">
        <thead>
          <tr>
            <th>Method</th>
            <th>Type</th>
            <th>Account Name</th>
            <th>Account Number</th>
            <th>QR Code</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="paymentTableBody">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['method_name']) ?></td>
              <td><?= htmlspecialchars(ucfirst($row['method_type'])) ?></td>
              <td><?= htmlspecialchars($row['account_name']) ?></td>
              <td><?= htmlspecialchars($row['account_number']) ?></td>
              <td>
                <?php if ($row['qr_code']): ?>
                  <img src="../../uploads/payment_qr/<?= htmlspecialchars($row['qr_code']) ?>" width="60" alt="QR">
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </td>
              <td><span class="status <?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
              <td>
                <button class="update-btn"
                  data-id="<?= $row['method_id'] ?>"
                  data-method="<?= htmlspecialchars($row['method_name']) ?>"
                  data-type="<?= htmlspecialchars($row['method_type']) ?>"
                  data-account="<?= htmlspecialchars($row['account_name']) ?>"
                  data-number="<?= htmlspecialchars($row['account_number']) ?>"
                  data-status="<?= htmlspecialchars($row['status']) ?>"
                  data-qr="<?= htmlspecialchars($row['qr_code']) ?>">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <button class="delete-btn" data-id="<?= $row['method_id'] ?>">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7">No payment methods found.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// QR Preview
document.getElementById("qrCode").addEventListener("change", function () {
  const file = this.files[0], preview = document.getElementById("qrPreviewImage");
  if (file) {
    const reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; preview.style.display = "block"; };
    reader.readAsDataURL(file);
  }
});

// Delete
document.querySelectorAll(".delete-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const id = btn.dataset.id;
    Swal.fire({
      title: "Are you sure?",
      text: "This payment method will be permanently deleted.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#ff6b6b",
      cancelButtonColor: "#6c757d",
      confirmButtonText: "Yes, delete it!"
    }).then(result => {
      if (result.isConfirmed) {
        const form = document.createElement("form");
        form.method = "POST";
        form.innerHTML = `<input type="hidden" name="delete_method_id" value="${id}">`;
        document.body.appendChild(form);
        form.submit();
      }
    });
  });
});

// Edit
document.querySelectorAll(".update-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    const {id, method, type, account, number, status, qr} = btn.dataset;

    Swal.fire({
      title: "Edit Payment Method",
      html: `
        <form id="editForm" enctype="multipart/form-data" style="text-align:left;">
          <input type="hidden" name="edit_method_id" value="${id}">

          <div style="margin-bottom:12px;">
            <label style="font-weight:bold;">Method Name</label>
            <input class="swal2-input" style="width:95%;" name="edit_method_name" value="${method}" required>
          </div>

          <div style="margin-bottom:12px;">
            <label style="font-weight:bold;">Method Type</label>
            <select class="swal2-select" name="edit_method_type" style="
              width:95%;
              padding:0.625em;
              border:1px solid #d9d9d9;
              border-radius:0.25em;
              font-size:1.1em;
              color:#545454;
            ">
              <option value="e-wallet" ${type==="e-wallet"?"selected":""}>E-Wallet</option>
              <option value="bank" ${type==="bank"?"selected":""}>Bank</option>
              <option value="cash" ${type==="cash"?"selected":""}>Cash on Delivery</option>
            </select>
          </div>

          <div style="margin-bottom:12px;">
            <label style="font-weight:bold;">Account Name</label>
            <input class="swal2-input" style="width:95%;" name="edit_account_name" value="${account}">
          </div>

          <div style="margin-bottom:12px;">
            <label style="font-weight:bold;">Account Number</label>
            <input class="swal2-input" style="width:95%;" name="edit_account_number" value="${number}">
          </div>

          <div style="margin-bottom:12px;text-align:center;">
            ${qr 
              ? `<img src='../../uploads/payment_qr/${qr}' style='max-width:120px;margin:10px auto;display:block;border:1px solid #ddd;border-radius:8px;padding:4px;background:#fff;'>`
              : "<p style='color:#777;'>No QR uploaded</p>"}
            <input type="hidden" name="existing_qr_code" value="${qr}">
          </div>

          <div style="margin-bottom:12px;">
            <label style="font-weight:bold;">Replace QR Code</label>
            <input type="file" class="swal2-input" style="width:95%;" name="edit_qr_code" accept="image/*">
          </div>
        </form>
      `,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: "Save Changes",
      cancelButtonText: "Cancel",
      confirmButtonColor: "#ff6b6b",
      width: "600px",
      preConfirm: () => {
        return new Promise(resolve => {
          const form = document.getElementById("editForm");
          const fd = new FormData(form);

          fetch("payment.php", {
            method: "POST",
            body: fd
          }).then(() => resolve());
        });
      }
    }).then(result => {
      if (result.isConfirmed) {
        Swal.fire({
          icon: "success",
          text: "Payment method updated!",
          confirmButtonColor: "#ff6b6b"
        }).then(() => location.reload());
      }
    });
  });
});
</script>
</body>
</html>
