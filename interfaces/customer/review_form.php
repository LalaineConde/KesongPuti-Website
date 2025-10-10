<?php
$page_title = 'Leave a Review | Kesong Puti';
require '../../connection.php';
$current_page = 'feedback';
$isHomePage = ($current_page === 'home'); // check if this is the home page

// Identify recipient
// if ($_SESSION['role'] === 'superadmin') {
//     $recipient = 'super_' . $_SESSION['super_id'];
// } else {
//     $recipient = 'admin_' . $_SESSION['admin_id'];
// }


$toast_message = '';
$order_id = intval($_GET['order_id'] ?? 0);
$orderInfo = null;
$alreadyReviewed = false;

// Handle order reference input if no reference_number in URL
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reference'])) {
    $reference_number = mysqli_real_escape_string($connection, $_POST['reference_number']);
    header("Location: review_form.php?reference_number=$reference_number");
    exit;
}


// Fetch order + customer info
$reference_number = $_GET['reference_number'] ?? '';
if (!empty($reference_number)) {
    $orderQuery = mysqli_query($connection, "
        SELECT o.o_id, o.reference_number, o.owner_id, c.fullname, c.email,
               CASE 
                   WHEN sa.super_id IS NOT NULL THEN CONCAT('super_', sa.super_id)
                   WHEN a.admin_id IS NOT NULL THEN CONCAT('admin_', a.admin_id)
               END AS recipient
        FROM orders o
        INNER JOIN customers c ON o.c_id = c.c_id
        LEFT JOIN super_admin sa ON o.owner_id = sa.super_id
        LEFT JOIN admins a ON o.owner_id = a.admin_id
        WHERE o.reference_number = '$reference_number'
        LIMIT 1
    ");

    $orderInfo = mysqli_fetch_assoc($orderQuery);

    if (!$orderInfo) {
        echo "<script>alert('Invalid order or customer not found.'); window.location='review_form.php';</script>";
        exit;
    }
    $recipient = $orderInfo['recipient'];

// Check if the order is already reviewed
$reviewCheckQuery = mysqli_query($connection, "
    SELECT COUNT(*) as review_count
    FROM reviews
    WHERE order_item_id IN (
        SELECT order_item_id
        FROM order_items
        WHERE o_id = {$orderInfo['o_id']}
    )
");

    $reviewCheck = mysqli_fetch_assoc($reviewCheckQuery);
    $alreadyReviewed = $reviewCheck['review_count'] > 0;

    // Fetch order items only if not already reviewed
    $orderItems = [];
    if (!$alreadyReviewed) {
        $itemsQuery = mysqli_query($connection, "
            SELECT oi.order_item_id, p.product_name, p.variation_size, oi.quantity
            FROM order_items oi
            INNER JOIN products p ON oi.product_id = p.product_id
            WHERE oi.o_id = {$orderInfo['o_id']}
        ");
        while ($item = mysqli_fetch_assoc($itemsQuery)) {
            $orderItems[] = $item;
        }
    }
}


// Step 3: Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_feedback'])) {
    $ratings = $_POST['rating'];
    $comments = $_POST['comment'];
    $order_item_ids = $_POST['order_item_id'];

    foreach ($order_item_ids as $i => $item_id) {
        $rating = intval($ratings[$i]);
        $comment = mysqli_real_escape_string($connection, $comments[$i]);
        $uploadedFiles = [];

        // Handle multiple uploads per product
        if (isset($_FILES['media']['name'][$i])) {
            foreach ($_FILES['media']['name'][$i] as $j => $filename) {
                if ($_FILES['media']['error'][$i][$j] === UPLOAD_ERR_OK) {
                    $upload_dir = "../../uploads/reviews/";
                    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                    $tmp_name = $_FILES['media']['tmp_name'][$i][$j];
                    $file_name = time() . "_{$i}_{$j}_" . basename($filename);
                    $file_path = $upload_dir . $file_name;

                    if (move_uploaded_file($tmp_name, $file_path)) {
                        $uploadedFiles[] = "uploads/reviews/" . $file_name;
                    }
                }
            }
        }

        $media_json = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;

$sql = "INSERT INTO reviews (name, email, rating, feedback, recipient, media, order_item_id, created_at)
        VALUES ('{$orderInfo['fullname']}', '{$orderInfo['email']}', '$rating', '$comment', '{$recipient}', " .
        ($media_json ? "'".mysqli_real_escape_string($connection, $media_json)."'" : "NULL") .
        ", '$item_id', NOW())";



        mysqli_query($connection, $sql);
    }
  $toast_message = "Reviews submitted successfully!";
}


include('../../includes/customer-dashboard.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Leave a Review</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.star-rating { font-size: 1.8rem; color: #ccc; cursor: pointer; }
.star-rating span { transition: color 0.2s; }
.star-rating span.active, .star-rating span:hover, .star-rating span:hover ~ span { color: gold; }
</style>
</head>
<body>
<div class="feedback-wrapper" >

    <?php if (!$orderInfo): ?>
        <!-- Step 1: Enter Order Reference -->
        <div class="card p-4">
            <h4>Enter Order Reference</h4>
            <form method="post">
                <div class="mb-3">
                    <input type="text" name="reference_number" class="form-control" placeholder="Enter your Reference Number" required>

                </div>
                <button type="submit" name="submit_reference" class="btn-review btn-primary">Go to Review Form</button>
            </form>
        </div>

    <?php elseif ($alreadyReviewed): ?>
        <!-- Order Already Reviewed -->
        <div class="card p-4">
            <h4>Order Already Reviewed</h4>
            <p>You have already submitted a review for this order. Thank you!</p>
            <a href="feedback.php" class="btn-back btn-primary">Back to Feedback</a>
        </div>

    <?php else: ?>
        
        <!-- Step 2: Leave Review -->
        <div class="p-4">
            
            <?php if ($toast_message): ?>
                <div class="alert alert-success"><?= htmlspecialchars($toast_message) ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="card p-4">

            <h4>Leave a Review</h4>
                <div class="mb-3">
                    <strong>Customer:</strong> <?= htmlspecialchars($orderInfo['fullname']) ?><br>
                    <strong>Email:</strong> <?= htmlspecialchars($orderInfo['email']) ?>
                </div>
              
            <!-- Ordered Products -->
            <?php if (!empty($orderItems)): ?>
            <div class="mb-3">
                <h5>Products in this Order:</h5>
                <ul class="list-group mb-3">
                    <?php foreach ($orderItems as $index => $item): ?>
<div class="card p-3 mb-3">
    <h5><?= htmlspecialchars($item['product_name']) ?></h5>
    <?php if (!empty($item['variation_size'])): ?>
        - Size: <?= htmlspecialchars($item['variation_size']) ?>
    <?php endif; ?>
    <input type="hidden" name="order_item_id[]" value="<?= intval($item['order_item_id']) ?>">

    <div class="mb-2 mt-2">
        <label>Rating:</label>
        <div class="star-rating" data-index="<?= $index ?>">
            <span data-value="1">&#9733;</span>
            <span data-value="2">&#9733;</span>
            <span data-value="3">&#9733;</span>
            <span data-value="4">&#9733;</span>
            <span data-value="5">&#9733;</span>
        </div>
        <input type="hidden" name="rating[]" id="rating-<?= $index ?>" required>
    </div>

    <div class="mb-2">
        <label>Comment:</label>
        <textarea name="comment[]" class="form-control" rows="2" required></textarea>
    </div>

<div class="mb-3">
    <label class="form-label mt-3">Upload Photos/Videos</label>
    <div id="drop-area" class="upload-area" data-index="<?= $index ?>" style="cursor:pointer; border:1px dashed #ccc; padding:10px; min-height:60px;">
        Click or Drag & Drop to Upload (Max 3)
        <div class="previews" style="display:flex; gap:5px; margin-top:5px;"></div>
    </div>
    <input type="file" name="media[<?= $index ?>][]" class="fileElem d-none" data-index="<?= $index ?>" accept="image/*,video/*" multiple>
</div>
<?php endforeach; ?>

                </ul>
            </div>
            <?php endif; ?>


   

                <button type="submit" name="submit_feedback" class="btn-submit mt-3">Submit Review</button>
            </form>
        </div>
    <?php endif; ?>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Star rating per product
    const starContainers = document.querySelectorAll(".star-rating");

    starContainers.forEach(container => {
        const index = container.dataset.index;
        const stars = container.querySelectorAll("span");
        const hiddenInput = document.getElementById(`rating-${index}`);

        stars.forEach(star => {
            star.addEventListener("click", () => {
                hiddenInput.value = star.dataset.value;
                // Reset all stars
                stars.forEach(s => s.style.color = "#ccc");
                // Highlight selected stars
                for (let i = 0; i < star.dataset.value; i++) {
                    stars[i].style.color = "gold";
                }
            });
        });
    });

    // Drag & drop per product with preview and +N overlay
    const dropAreas = document.querySelectorAll(".upload-area");

    dropAreas.forEach(area => {
        const index = area.dataset.index;
        const fileInput = document.querySelector(`.fileElem[data-index='${index}']`);

        // Create preview container
        const previewContainer = document.createElement('div');
        previewContainer.classList.add('preview-container', 'd-flex', 'gap-2', 'mt-2');
        area.parentNode.insertBefore(previewContainer, area.nextSibling);

        const updatePreview = (files) => {
            previewContainer.innerHTML = ''; // clear previous preview
            const maxPreview = 3;

            Array.from(files).forEach((file, i) => {
                if (i < maxPreview) {
                    const thumb = document.createElement('div');
                    thumb.classList.add('thumb');
                    thumb.style.width = '60px';
                    thumb.style.height = '60px';
                    thumb.style.position = 'relative';
                    thumb.style.overflow = 'hidden';
                    thumb.style.cursor = 'pointer';
                    thumb.style.border = '1px solid #ccc';
                    thumb.style.borderRadius = '5px';

                    if (file.type.startsWith('image')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';
                        img.addEventListener('click', () => window.open(img.src));
                        thumb.appendChild(img);
                    } else if (file.type.startsWith('video')) {
                        const vid = document.createElement('video');
                        vid.src = URL.createObjectURL(file);
                        vid.style.width = '100%';
                        vid.style.height = '100%';
                        vid.style.objectFit = 'cover';
                        vid.addEventListener('click', () => window.open(vid.src));
                        thumb.appendChild(vid);
                    }

                    // Overlay for extra files
                      if (i === maxPreview - 1 && files.length > maxPreview) {
                          const overlay = document.createElement('div');
                          overlay.style.position = 'absolute';
                          overlay.style.top = 0;
                          overlay.style.left = 0;
                          overlay.style.width = '100%';
                          overlay.style.height = '100%';
                          overlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
                          overlay.style.color = '#fff';
                          overlay.style.display = 'flex';
                          overlay.style.alignItems = 'center';
                          overlay.style.justifyContent = 'center';
                          overlay.style.fontSize = '1rem';
                          overlay.style.pointerEvents = 'none'; // <-- this allows clicks to pass through
                          overlay.textContent = `+${files.length - maxPreview}`;
                          thumb.appendChild(overlay);
                      }

                    previewContainer.appendChild(thumb);
                }
            });
        };

        area.addEventListener('click', () => fileInput.click());

        area.addEventListener('dragover', (e) => {
            e.preventDefault();
            area.classList.add('bg-light');
        });

        area.addEventListener('dragleave', () => area.classList.remove('bg-light'));

        area.addEventListener('drop', (e) => {
            e.preventDefault();
            area.classList.remove('bg-light');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                updatePreview(fileInput.files);
            }
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) {
                updatePreview(fileInput.files);
            }
        });
    });
});

</script>

<!-- SweetAlert2 Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Toast message from PHP
    var toastMessage = "<?= $toast_message ?>";
    if (toastMessage) {
        Swal.fire({
            icon: 'success',
            text: toastMessage,
            confirmButtonColor: '#28a745'
        }).then((result) => {
            // Redirect to feedback.php after clicking OK
            if (result.isConfirmed) {
                window.location.href = 'feedback.php';
            }
        });
    }
});
</script>
<?php include('../../includes/floating-button.php'); ?>
<?php include('../../includes/footer.php'); ?>
</body>
</html>
