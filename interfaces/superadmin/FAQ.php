<?php
$page_title = 'FAQ | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

$toast_message = ''; // Initialize variable for toast message

// Add FAQ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_faq'])) {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    if (!empty($question) && !empty($answer)) {
        $sql = "INSERT INTO faqs (question, answer) VALUES (?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ss", $question, $answer);
        $stmt->execute();
        $toast_message = "FAQ added successfully!";
    }
}

// Fetch all FAQs
$result = $connection->query("SELECT * FROM faqs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ | Kesong Puti</title>

  <!-- BOOTSTRAP ICONS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>

  <!-- CUSTOM ADMIN CSS -->
  <link rel="stylesheet" href="../../css/admin.css"/>
</head>
<body>

<div class="container-fluid my-5" id="faq-content">
    <!-- Title -->
    <h1><i class="bi bi-question-circle"></i> Manage FAQs</h1>

    <!-- Add FAQ Form -->
    <h2><i class="bi bi-plus-circle"></i> Add New FAQ</h2>
    <form method="POST">
        <input type="text" name="question" placeholder="Enter question..." required>
        <textarea name="answer" rows="4" placeholder="Enter answer..." required></textarea>
            <div class="btn-wrapper">
            <button type="submit" name="add_faq">
                <i class="bi bi-check-circle"></i> Add FAQ
            </button>
            </div>
    </form>

    <!-- FAQs Table -->
    <div class="faq-table-wrapper">
        <table class="faq-table">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['question']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['answer'])) ?></td>
                        <td>
                            <button class="update-btn" 
                                    data-id="<?= $row['id'] ?>" 
                                    data-question="<?= htmlspecialchars($row['question'], ENT_QUOTES) ?>" 
                                    data-answer="<?= htmlspecialchars($row['answer'], ENT_QUOTES) ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <a href="delete-faq.php?id=<?= $row['id'] ?>" 
                            class="delete-btn" 
                            data-id="<?= $row['id'] ?>">
                            <i class="bi bi-trash-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">No FAQs added yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
document.addEventListener("DOMContentLoaded", () => {
    // ✅ Toast message from PHP
    var toastMessage = "<?= $toast_message ?>";
    if (toastMessage) {
        Swal.fire({
            icon: 'success',
            text: toastMessage,
            confirmButtonColor: '#dc3545'
        });
    }

    // ✅ Delete confirmation
    document.querySelectorAll(".delete-btn").forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            let href = this.getAttribute("href");

            Swal.fire({
                title: "Are you sure?",
                text: "This FAQ will be deleted permanently.",
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

    // ✅ Edit FAQ
    document.querySelectorAll(".update-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const id = this.getAttribute("data-id");
            const oldQuestion = this.getAttribute("data-question");
            const oldAnswer = this.getAttribute("data-answer");

            Swal.fire({
                title: "Edit FAQ",
                html: `
                    <input id="swal-question" class="swal2-input" value="${oldQuestion}" placeholder="Enter question">
                    <textarea id="swal-answer" class="swal2-textarea" placeholder="Enter answer">${oldAnswer}</textarea>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: "Save",
                preConfirm: () => {
                    const question = document.getElementById("swal-question").value;
                    const answer = document.getElementById("swal-answer").value;
                    if (!question || !answer) {
                        Swal.showValidationMessage("Both fields are required");
                        return false;
                    }
                    return { question, answer };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("edit-faq.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `id=${id}&question=${encodeURIComponent(result.value.question)}&answer=${encodeURIComponent(result.value.answer)}`
                    })
                    .then(res => res.text())
                    .then(data => {
                        if (data.trim() === "success") {
                            Swal.fire("Updated!", "The FAQ has been updated.", "success")
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

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
