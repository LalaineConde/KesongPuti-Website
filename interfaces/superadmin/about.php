<?php
$page_title = 'About Us | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

$toast_message = ''; // Initialize toast

// --- DEFAULT CONTENT ---
$default_sections = [
    'beginning' => [
        'quote' => 'It all began in our kitchen, where kesong puti was more than food — it was family, tradition, and togetherness.',
        'content' => "Our story starts in the heart of our home, ...",
    ],
    'tradition' => [
        'quote' => 'A recipe lovingly passed down through generations...',
        'content' => "Our kesong puti is not just cheese...",
    ],
    'business' => [
        'quote' => "From our table to our neighbors’...",
        'content' => "At first, we made kesong puti only for ourselves...",
    ],
    'support_farmers' => [
        'quote' => "Behind every piece of our kesong puti...",
        'content' => "Our business grew hand in hand with the local farming community...",
    ],
    'present' => [
        'quote' => "Though we’ve grown, our heart remains the same...",
        'content' => "Today, our family business has reached more homes...",
    ]
];

$default_section_images_db = [
    'beginning' => ['uploads/assets/default-team-1.png', 'uploads/assets/default-team-2.png'],
    'tradition' => ['uploads/assets/default-team-3.png', 'uploads/assets/default-team-4.png'],
    'business' => ['uploads/assets/default-team-5.png', 'uploads/assets/default-team-6.png'],
    'support_farmers' => ['uploads/assets/default-team-1.png', 'uploads/assets/default-team-2.png'],
    'present' => ['uploads/assets/default-team-3.png', 'uploads/assets/default-team-4.png']
];

$default_team = [
    'title' => 'OUR KESONG PUTI FAMILY',
    'members' => [
        'uploads/assets/default-team-1.png',
        'uploads/assets/default-team-2.png',
        'uploads/assets/default-team-3.png',
        'uploads/assets/default-team-4.png',
        'uploads/assets/default-team-5.png',
        'uploads/assets/default-team-6.png'
    ]
];

$default_cta = [
    'heading' => 'Experience Authentic Filipino Flavor',
    'paragraph' => 'Discover the creamy, wholesome taste of our kesong puti...'
];

// --- FETCH DATA ---
$sections = [];
$result = mysqli_query($connection, "SELECT * FROM about_sections");
while($row = mysqli_fetch_assoc($result)) { $sections[$row['section_name']] = $row; }

$images = [];
$result = mysqli_query($connection, "SELECT * FROM about_images ORDER BY section_name, position ASC");
while($row = mysqli_fetch_assoc($result)) { $images[$row['section_name']][] = $row; }

$teamData = [];
$teamTitle = $default_team['title'];
$result = mysqli_query($connection, "SELECT * FROM about_team ORDER BY position ASC");
if($result){
    while($row = mysqli_fetch_assoc($result)){
        $teamData[$row['position']] = $row['image_path'];
        $teamTitle = $row['title']; // assume title is same for all
    }
}

$ctaData = ['heading' => '', 'paragraph' => ''];
$result = mysqli_query($connection, "SELECT * FROM cta_sections WHERE id=1 LIMIT 1");
if($row = mysqli_fetch_assoc($result)){
    $ctaData['heading'] = $row['heading'];
    $ctaData['paragraph'] = $row['paragraph'];
}

// --- HANDLE FORM SUBMISSION ---
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // UPDATE SECTIONS
    if(isset($_POST['update_about_btn'])){
        // Sections
        foreach($_POST['sections'] as $section => $data){
            $quote = $data['quote'];
            $content = $data['content'];
            $stmt = mysqli_prepare($connection, "INSERT INTO about_sections (section_name, quote, content) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quote=VALUES(quote), content=VALUES(content)");
            mysqli_stmt_bind_param($stmt, 'sss', $section, $quote, $content);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Images
foreach($_FILES['images']['tmp_name'] as $section => $files){
    // Get current max position for this section
    $result = mysqli_query($connection, "SELECT MAX(position) AS max_pos FROM about_images WHERE section_name='$section'");
    $row = mysqli_fetch_assoc($result);
    $maxPos = $row['max_pos'] ?? 0;

    foreach($files as $i => $tmp_name){
        if($_FILES['images']['error'][$section][$i] === 0){
            $ext = pathinfo($_FILES['images']['name'][$section][$i], PATHINFO_EXTENSION);
            $filename = "uploads/uploaded/about_{$section}_" . time() . rand(1000,9999) . "_{$i}.{$ext}";
            move_uploaded_file($tmp_name, "../../".$filename);

            $pos = ++$maxPos; // increment for each new image
            $stmt = mysqli_prepare(
                $connection,
                "INSERT INTO about_images (section_name, image_path, position) VALUES (?, ?, ?)"
            );
            mysqli_stmt_bind_param($stmt, 'ssi', $section, $filename, $pos);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

        // Team Section
        $title = $_POST['team_title'];
        for($i=1; $i<=6; $i++){
            $img_path = $teamData[$i] ?? '';
            if(isset($_FILES['team_images']['tmp_name'][$i]) && $_FILES['team_images']['error'][$i] === 0){
                $ext = pathinfo($_FILES['team_images']['name'][$i], PATHINFO_EXTENSION);
                $filename = "uploads/uploaded/team_" . time() . rand(1000,9999) . "_{$i}.{$ext}";
                move_uploaded_file($_FILES['team_images']['tmp_name'][$i], "../../".$filename);
                $img_path = $filename;
            }

            $stmt = mysqli_prepare(
                $connection,
                "INSERT INTO about_team (position, title, image_path) VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE title=VALUES(title), image_path=VALUES(image_path)"
            );
            mysqli_stmt_bind_param($stmt, 'iss', $i, $title, $img_path);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // CTA
        $cta_heading = mysqli_real_escape_string($connection, $_POST['cta_heading']);
        $cta_paragraph = mysqli_real_escape_string($connection, $_POST['cta_paragraph']);
        $stmt = mysqli_prepare($connection, "INSERT INTO cta_sections (id, heading, paragraph) VALUES (1, ?, ?) ON DUPLICATE KEY UPDATE heading=VALUES(heading), paragraph=VALUES(paragraph)");
        mysqli_stmt_bind_param($stmt, 'ss', $cta_heading, $cta_paragraph);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $toast_message = "About, Team & CTA sections updated successfully!";
    }


$section_names = ['beginning','tradition','business','support_farmers','present'];
    // RESET TO DEFAULT
if(isset($_POST['reset_about_btn'])){
    // DELETE UPLOADED SECTION IMAGES
    $result = mysqli_query($connection, "SELECT image_path FROM about_images WHERE image_path LIKE 'uploads/uploaded/%'");
    while($row = mysqli_fetch_assoc($result)){
        $file = "../../".$row['image_path'];
        if(file_exists($file)) unlink($file); // delete file
    }

    // DELETE UPLOADED TEAM IMAGES
    $result = mysqli_query($connection, "SELECT image_path FROM about_team WHERE image_path LIKE 'uploads/uploaded/%'");
    while($row = mysqli_fetch_assoc($result)){
        $file = "../../".$row['image_path'];
        if(file_exists($file)) unlink($file); // delete file
    }

    // RESET ALL SECTION IMAGES TO DEFAULT
    foreach($section_names as $section){
        // Delete ALL images for this section
        mysqli_query($connection, "DELETE FROM about_images WHERE section_name='$section'");

        // Re-insert defaults
        foreach($default_section_images_db[$section] as $pos => $imgPath){
            $position = $pos + 1;
            mysqli_query($connection, "INSERT INTO about_images (section_name, image_path, position) VALUES ('$section', '$imgPath', $position)");
        }
    }
        // Team
        for($i=0; $i<6; $i++){
            $position = $i + 1;
            $title = mysqli_real_escape_string($connection, $default_team['title']);
            $img = mysqli_real_escape_string($connection, $default_team['members'][$i]);
            mysqli_query($connection, "INSERT INTO about_team (position, title, image_path) VALUES ($position, '$title', '$img') ON DUPLICATE KEY UPDATE title=VALUES(title), image_path=VALUES(image_path)");
        }

        // CTA
        $heading = mysqli_real_escape_string($connection, $default_cta['heading']);
        $paragraph = mysqli_real_escape_string($connection, $default_cta['paragraph']);
        mysqli_query($connection, "INSERT INTO cta_sections (id, heading, paragraph) VALUES (1, '$heading', '$paragraph') ON DUPLICATE KEY UPDATE heading=VALUES(heading), paragraph=VALUES(paragraph)");

        $toast_message = "Sections reset to default values!";
    }

    // Reload data
    $sections = [];
    $result = mysqli_query($connection, "SELECT * FROM about_sections");
    while($row = mysqli_fetch_assoc($result)) { $sections[$row['section_name']] = $row; }

    $images = [];
    $result = mysqli_query($connection, "SELECT * FROM about_images ORDER BY section_name, position ASC");
    while($row = mysqli_fetch_assoc($result)) { $images[$row['section_name']][] = $row; }

    $teamData = [];
    $teamTitle = $default_team['title'];
    $result = mysqli_query($connection, "SELECT * FROM about_team ORDER BY position ASC");
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $teamData[$row['position']] = $row['image_path'];
            $teamTitle = $row['title'];
        }
    }

    $ctaData = ['heading' => '', 'paragraph' => ''];
    $result = mysqli_query($connection, "SELECT * FROM cta_sections WHERE id=1 LIMIT 1");
    if($row = mysqli_fetch_assoc($result)){
        $ctaData['heading'] = $row['heading'];
        $ctaData['paragraph'] = $row['paragraph'];
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us | Kesong Puti</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="../../css/admin.css"/>
</head>
<body>
<div class="about-container" id="about-content">
    <h1>About Us</h1>
    <form method="POST" enctype="multipart/form-data"  class="about-form">
        <div class="settings-card about">
            <div class="settings-header">About Header</div>
            <div class="section-content">
            <?php 
            $section_names = ['beginning','tradition','business','support_farmers','present'];
            foreach($section_names as $name):
                $quote = $sections[$name]['quote'] ?? '';
                $content = $sections[$name]['content'] ?? '';
                $sectionImages = $images[$name] ?? [];
            ?>
            <div class="section-card mb-4 p-3 border rounded">
                <h4 class="mb-2"><?= ucfirst($name) ?> Section</h4>
                <div class="mb-2">
                    <label>Quote</label>
                    <input type="text" name="sections[<?= $name ?>][quote]" value="<?= htmlspecialchars($quote) ?>" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Content</label>
                    <textarea name="sections[<?= $name ?>][content]" class="form-control" rows="5"><?= htmlspecialchars($content) ?></textarea>
                </div>
                <div class="mb-2">
                    <label>Images</label>
                    <input type="file" name="images[<?= $name ?>][]" multiple class="form-control upload-preview" data-section="<?= $name ?>">
                </div>
                <div class="mt-2 d-flex flex-wrap gap-2 preview-container" id="preview-<?= $name ?>">
                    <?php foreach($sectionImages as $img): ?>
                        <div style="position:relative; display:inline-block;">
                            <img src="../../<?= htmlspecialchars($img['image_path']) ?>" style="max-width:100px; border:1px solid #ccc; padding:2px; display:block;">
                            <?php if(!empty($img['id'])): ?>
                                <button type="button" 
                                        class="delete-about-img" 
                                        data-id="<?= $img['id'] ?>" 
                                        style="position:absolute;top:5px;right:5px;background:red;color:white;border:none;border-radius:50%;width:20px;height:20px;font-size:14px;line-height:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;">×</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- TEAM -->
            <div class="section-card mb-4 p-3 border rounded">
                <h4 class="mb-2">Team Section</h4>
                <div class="mb-2">
                    <label>Team Title</label>
                    <input type="text" name="team_title" value="<?= htmlspecialchars($teamTitle) ?>" class="form-control">
                </div>
                <div class="row">
                    <?php for($i=1; $i<=6; $i++): $img = $teamData[$i] ?? ''; ?>
                        <div class="col-lg-4 mb-3 team-section">
                            <label>Member <?= $i ?> Image</label>
                            <div class="team-preview-container" id="team-preview-<?= $i ?>">
                                <?php if($img): ?>
                                    <img src="../../<?= $img ?>" style="max-width:100px; border:1px solid #ccc; padding:2px; display:flex;">
                                <?php endif; ?>
                            </div>
                            <input type="file" name="team_images[<?= $i ?>]" class="form-control team-upload" data-member="<?= $i ?>">
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="mb-3">
                <label>CTA Heading</label>
                <input type="text" name="cta_heading" value="<?= htmlspecialchars($ctaData['heading']) ?>" class="form-control">
            </div>
            <div class="mb-3">
                <label>CTA Paragraph</label>
                <textarea name="cta_paragraph" class="form-control" rows="4"><?= htmlspecialchars($ctaData['paragraph']) ?></textarea>
            </div>


        </div>
        </div>
            <button type="submit" name="update_about_btn" class="update_about_btn">Update Sections</button>
            <button type="submit" name="reset_about_btn" class="reset_about_btn">Reset to Default</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.upload-preview').forEach(input => {
    const section = input.dataset.section;
    const previewContainer = document.getElementById('preview-' + section);

    input.addEventListener('change', function() {
        previewContainer.querySelectorAll('.new-preview').forEach(el => el.remove());
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                img.style.border = '1px solid #ccc';
                img.style.padding = '2px';
                img.classList.add('new-preview');
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });
});

document.querySelectorAll('.team-upload').forEach(input => {
    const member = input.dataset.member;
    const previewContainer = document.getElementById('team-preview-' + member);

    input.addEventListener('change', function() {
        const file = this.files[0]; // only 1 image per team member
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                // Remove existing img inside container
                previewContainer.innerHTML = '';
                // Create new preview
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                img.style.border = '1px solid #ccc';
                img.style.padding = '2px';
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        }
    });
});

var toastMessage = "<?php echo isset($toast_message) ? $toast_message : ''; ?>";
if (toastMessage) {
    Swal.fire({icon:'info', text:toastMessage, confirmButtonColor:'#ff6b6b'});
}

document.querySelectorAll('.delete-about-img').forEach(btn => {
    btn.addEventListener('click', function() {
        const imgId = this.getAttribute('data-id');
        const parent = this.closest('div');

        if(confirm("Delete this image?")) {
            fetch('delete-about-image.php', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'id=' + imgId + '&table=about_images'
            })
            .then(res => res.text())
            .then(data => {
                if(data.trim() === 'success') parent.remove();
                else alert("Failed to delete image.");
            });
        }
    });
});
</script>
</body>
</html>
