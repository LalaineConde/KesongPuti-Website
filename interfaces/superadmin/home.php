<?php
$page_title = 'Homepage | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

$toast_message = '';

// ---------- HELPER FUNCTIONS ----------
function fetchSettings($connection) {
    $settings = [];
    $result = mysqli_query($connection, "SELECT setting_key, setting_value FROM home_settings");
    while ($row = mysqli_fetch_assoc($result)) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

function fetchHero($connection) {
    $items = [];
    $result = mysqli_query($connection, "SELECT * FROM home_hero ORDER BY position ASC");
    while($row = mysqli_fetch_assoc($result)) $items[$row['position']] = $row;
    return $items;
}

function fetchFeatured($connection) {
    $items = [];
    $result = mysqli_query($connection, "SELECT * FROM home_featured ORDER BY position ASC");
    while($row = mysqli_fetch_assoc($result)) $items[$row['position']] = $row;
    return $items;
}

function fetchReasons($connection) {
    $items = [];
    $result = mysqli_query($connection, "SELECT * FROM home_reasons ORDER BY position ASC");
    while($row = mysqli_fetch_assoc($result)) $items[$row['position']] = $row;
    return $items;
}

function fetchAboutSlider($connection) {
    $items = [];
    $result = mysqli_query($connection, "SELECT * FROM home_about_slider ORDER BY position ASC");
    while($row = mysqli_fetch_assoc($result)) $items[] = $row;
    return $items;
}

// ---------- RESET HOME ----------
if (isset($_POST['reset_home_btn'])) {
    $defaults = [
        'part1' => 'PURE',
        'part2' => 'CHEESE',
        'part3' => 'BLISS',
        'settings' => [
            'home_header_font_color_part1' => '#0D8540',
            'home_header_font_color_part2' => '#F4C40F',
            'home_header_font_color_part3' => '#0D8540',
            'del_pick_font_color_title1' => '#058240',
            'del_pick_font_color_title2' => '#058240',
            'del_pick_font_color_title3' => '#058240',
            'del_pick_font_color_title4' => '#F4C40F',
            'del_pick_image' => '',
            'home_featured_title' => 'The Original & Classics',
            'home_reasons_heading' => 'Why is it Good?',
            'about_heading' => "OUR FAMILY’S LEGACY OF KESONG PUTI"
        ]
    ];

    mysqli_query($connection, "UPDATE page_headers 
        SET part1='{$defaults['part1']}', part2='{$defaults['part2']}', part3='{$defaults['part3']}'
        WHERE page_name='home'");

    foreach ($defaults['settings'] as $key => $value) {
        mysqli_query($connection, "REPLACE INTO home_settings (setting_key, setting_value) VALUES ('$key', '$value')");
    }

    // Hero
    mysqli_query($connection, "TRUNCATE TABLE home_hero");
    mysqli_query($connection, "INSERT INTO home_hero (image_path, subtitle, position) VALUES
        ('uploads/assets/default-pic-1.png', 'SOFT CREAMY AND FRESH', 1),
        ('uploads/assets/default-pic-2.png', 'MORNING CLASSIC BREAKFAST', 2),
        ('uploads/assets/default-pic-3.png', 'FRESH WHITE CHEESE', 3),
        ('uploads/assets/default-pic-4.png', 'SNACKS AND APPETIZERS', 4)");

    // Featured
    mysqli_query($connection, "TRUNCATE TABLE home_featured");
    mysqli_query($connection, "INSERT INTO home_featured (image_path, title, position) VALUES
        ('uploads/assets/default-featured-1.png', 'Kesorbetes', 1),
        ('uploads/assets/default-featured-2.png', 'Kesong Puti', 2)");

    // Delivery/Pickup
    mysqli_query($connection, "UPDATE home_delivery_pickup 
        SET title1='THE', title2='CHOICE', title3='IS YOURS:', title4='GO OR STAY' WHERE id=1");

    // Reasons
    mysqli_query($connection, "TRUNCATE TABLE home_reasons");
    mysqli_query($connection, "INSERT INTO home_reasons (icon, title, subtitle, position) VALUES
        ('fa-solid fa-cow', 'Freshness and Simple Production', 'Kesong puti is a fresh cheese without preservatives, offering a pure, natural flavor.', 1),
        ('fa-solid fa-pills', 'Rich in Nutrients', 'It is a great source of essential nutrients, particularly protein and calcium, from carabao\'s milk.', 2),
        ('fa-solid fa-cheese', 'Cultural and Culinary Importance', 'Kesong puti is a cultural staple in the Philippines, often enjoyed as a classic breakfast food.', 3),
        ('fa-solid fa-utensils', 'Versatility in the Kitchen', 'Its mild, slightly salty flavor makes it a versatile ingredient for both sweet and savory dishes.', 4)");

    // About slider
    mysqli_query($connection, "TRUNCATE TABLE home_about_slider");
    mysqli_query($connection, "INSERT INTO home_about_slider (image_path, position) VALUES
        ('uploads/assets/default-team-1.png', 1),
        ('uploads/assets/default-team-2.png', 2),
        ('uploads/assets/default-team-3.png', 3),
        ('uploads/assets/default-team-4.png', 4),
        ('uploads/assets/default-team-5.png', 5)");

    $toast_message = "Homepage has been fully reset.";
}

// ---------- FETCH DATA ----------
$settings = fetchSettings($connection);

$home_header_part1 = "PURE";
$home_header_part2 = "CHEESE";
$home_header_part3 = "BLISS";

$row = mysqli_fetch_assoc(mysqli_query($connection, "SELECT part1, part2, part3 FROM page_headers WHERE page_name='home' LIMIT 1"));
if($row){
    $home_header_part1 = $row['part1'] ?? $home_header_part1;
    $home_header_part2 = $row['part2'] ?? $home_header_part2;
    $home_header_part3 = $row['part3'] ?? $home_header_part3;
}

$hero_items = fetchHero($connection);
$featured_items = fetchFeatured($connection);
$reasons = fetchReasons($connection);
$about_slider = fetchAboutSlider($connection);
$del_pick = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM home_delivery_pickup LIMIT 1"));

$featured_title = $settings['home_featured_title'] ?? "The Original & Classics";
$reasons_heading = $settings['home_reasons_heading'] ?? "Why is it Good?";
$about_heading = $settings['about_heading'] ?? "OUR FAMILY’S LEGACY OF KESONG PUTI";

// ---------- UPDATE HOME ----------
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_home_btn'])){

    // Header
    $stmt = mysqli_prepare($connection, "UPDATE page_headers SET part1=?, part2=?, part3=? WHERE page_name='home'");
    mysqli_stmt_bind_param($stmt, 'sss', $_POST['part1'], $_POST['part2'], $_POST['part3']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Settings
    if(!empty($_POST['settings'])){
        foreach($_POST['settings'] as $key => $value){
            $stmt = mysqli_prepare($connection, "REPLACE INTO home_settings (setting_key, setting_value) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, 'ss', $key, $value);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // Featured section title
    if(!empty($_POST['featured_title_main'])){
        $stmt = mysqli_prepare($connection, "REPLACE INTO home_settings (setting_key, setting_value) VALUES ('home_featured_title', ?)");
        mysqli_stmt_bind_param($stmt, 's', $_POST['featured_title_main']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Hero Images
    for($i=1;$i<=4;$i++){
        $subtitle = $_POST['hero_subtitle'][$i] ?? '';
        $image_path = $hero_items[$i]['image_path'] ?? '';

        if(!empty($_FILES['hero_image']['name'][$i])){
            $tmp_name = $_FILES['hero_image']['tmp_name'][$i];
            $ext = pathinfo($_FILES['hero_image']['name'][$i], PATHINFO_EXTENSION);
            $name = 'uploads/uploaded/home_hero_'.time().'_'.$i.'.'.$ext;
            move_uploaded_file($tmp_name, '../../'.$name);
            $image_path = $name;
        }

        // UPDATE instead of INSERT
        $stmt = mysqli_prepare($connection, "UPDATE home_hero SET image_path=?, subtitle=? WHERE position=?");
        mysqli_stmt_bind_param($stmt, 'ssi', $image_path, $subtitle, $i);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Featured items
    for($i=1;$i<=2;$i++){
        $title = $_POST['featured_title'][$i] ?? '';
        $image_path = $featured_items[$i]['image_path'] ?? '';

        if(!empty($_FILES['featured_image']['name'][$i])){
            $tmp_name = $_FILES['featured_image']['tmp_name'][$i];
            $ext = pathinfo($_FILES['featured_image']['name'][$i], PATHINFO_EXTENSION);
            $name = 'uploads/uploaded/featured_'.time().'_'.$i.'.'.$ext;
            move_uploaded_file($tmp_name, '../../'.$name);
            $image_path = $name;
        }

        $stmt = mysqli_prepare($connection, "INSERT INTO home_featured (position, image_path, title) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE image_path=VALUES(image_path), title=VALUES(title)");
        mysqli_stmt_bind_param($stmt, 'iss', $i, $image_path, $title);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Delivery/Pickup titles
    $stmt = mysqli_prepare($connection, "UPDATE home_delivery_pickup SET title1=?, title2=?, title3=?, title4=? WHERE id=1");
    mysqli_stmt_bind_param($stmt, 'ssss', $_POST['del_title1'], $_POST['del_title2'], $_POST['del_title3'], $_POST['del_title4']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Delivery/Pickup image
    if(!empty($_FILES['del_pick_image']['name'])){
        $targetDir = "../../uploads/uploaded/";
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time().'_'.basename($_FILES['del_pick_image']['name']);
        $targetFile = $targetDir.$fileName;
        if(move_uploaded_file($_FILES['del_pick_image']['tmp_name'], $targetFile)){
            $dbPath = "uploads/uploaded/".$fileName;
            $stmt = mysqli_prepare($connection, "REPLACE INTO home_settings (setting_key, setting_value) VALUES ('del_pick_image', ?)");
            mysqli_stmt_bind_param($stmt, 's', $dbPath);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // Reasons
    for($i=1;$i<=4;$i++){
        $icon = $_POST['reason_icon'][$i] ?? '';
        $title = $_POST['reason_title'][$i] ?? '';
        $subtitle = $_POST['reason_subtitle'][$i] ?? '';

        $stmt = mysqli_prepare($connection, "INSERT INTO home_reasons (position, icon, title, subtitle) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE icon=VALUES(icon), title=VALUES(title), subtitle=VALUES(subtitle)");
        mysqli_stmt_bind_param($stmt, 'isss', $i, $icon, $title, $subtitle);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Reasons heading
    if(!empty($_POST['reasons_heading'])){
        $stmt = mysqli_prepare($connection, "REPLACE INTO home_settings (setting_key, setting_value) VALUES ('home_reasons_heading', ?)");
        mysqli_stmt_bind_param($stmt, 's', $_POST['reasons_heading']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // About heading
    if(!empty($_POST['about_heading'])){
        $stmt = mysqli_prepare($connection, "REPLACE INTO home_settings (setting_key, setting_value) VALUES ('about_heading', ?)");
        mysqli_stmt_bind_param($stmt, 's', $_POST['about_heading']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // About slider images
    if(!empty($_FILES['about_images']['name'][0])){
        foreach($_FILES['about_images']['tmp_name'] as $key=>$tmp_name){
            if($_FILES['about_images']['error'][$key]===UPLOAD_ERR_OK){
                $ext = pathinfo($_FILES['about_images']['name'][$key], PATHINFO_EXTENSION);
                $filename = 'uploads/uploaded/about_'.time().'_'.$key.'.'.$ext;
                move_uploaded_file($tmp_name, '../../'.$filename);

                $stmt = mysqli_prepare($connection, "INSERT INTO home_about_slider (image_path, position) VALUES (?, ?)");
                $pos = count($about_slider)+1;
                mysqli_stmt_bind_param($stmt, 'si', $filename, $pos);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }
    }

    $toast_message = "Homepage updated successfully!";
    // Refetch
    $settings = fetchSettings($connection);
    $hero_items = fetchHero($connection);
    $featured_items = fetchFeatured($connection);
    $reasons = fetchReasons($connection);
    $about_slider = fetchAboutSlider($connection);
    $del_pick = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM home_delivery_pickup LIMIT 1"));
    $home_header_part1 = $_POST['part1'];
    $home_header_part2 = $_POST['part2'];
    $home_header_part3 = $_POST['part3'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>

<div  id="home-content" class="home-container">
  <h1>Customize Homepage</h1>

  <form method="POST" enctype="multipart/form-data" class="home-form">

    <!-- Homepage Header -->
    <div class="settings-card home">
      <div class="settings-header">Homepage Header</div>
      <div class="settings-body">
        <div class="mb-3">
          <label for="part1" class="form-label">Header Part 1</label>
          <input type="text" class="form-control" id="part1" name="part1" value="<?= htmlspecialchars($home_header_part1) ?>" required>
        </div>
        <div class="mb-3">
          <label for="part2" class="form-label">Header Part 2</label>
          <input type="text" class="form-control" id="part2" name="part2" value="<?= htmlspecialchars($home_header_part2) ?>">
        </div>
        <div class="mb-3">
          <label for="part3" class="form-label">Header Part 3</label>
          <input type="text" class="form-control" id="part3" name="part3" value="<?= htmlspecialchars($home_header_part3) ?>">
        </div>
           </div>

           <div class="font-color-section">

        <h5>Font Colors</h5>
        <div class="settings-row">
    <?php $fontColorFields = [ "home_header_font_color_part1" => "#0D8540", "home_header_font_color_part2" => "#F4C40F", "home_header_font_color_part3" => "#0D8540" ]; 
    foreach ($fontColorFields as $key => $default): $value = htmlspecialchars($settings[$key] ?? $default); 
    $label = ucwords(str_replace("_", " ", $key)); ?> 
    <div class="settings-col"> 
        <label class="form-label"><?= $label ?></label> 
        <div class="color-picker"> 
            <input type="color" id="<?= $key ?>" name="settings[<?= $key ?>]" value="<?= $value ?>"> 
            <span id="<?= $key ?>Value"><?= $value ?></span> 
        </div> 
    </div> <?php endforeach; ?> </div>
      </div>
</div>
    <!-- Hero Section -->
    <div class="settings-card home">
      <div class="settings-header">Hero Section</div>
      <div class="settings-body">
        <?php for($i=1; $i<=4; $i++): 
          $img = $hero_items[$i]['image_path'] ?? '';
          $subtitle = $hero_items[$i]['subtitle'] ?? '';
        ?>
        <div class="mb-3">
          <label class="form-label">Image <?= $i ?></label>
          <input type="file" class="form-control" name="hero_image[<?= $i ?>]">
          <?php if($img): ?>
            <img src="../../<?= htmlspecialchars($img) ?>" alt="Hero <?= $i ?>" width="120" class="mt-2 mb-2" id="hero-preview-<?= $i ?>">
          <?php else: ?>
            <img src="" alt="Preview <?= $i ?>" width="120" class="mt-2 mb-2" id="preview-<?= $i ?>" style="display:none;">
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label class="form-label">Subtitle <?= $i ?></label>
          <input type="text" class="form-control" name="hero_subtitle[<?= $i ?>]" value="<?= htmlspecialchars($subtitle) ?>">
        </div>
        <?php endfor; ?>
      </div>
    </div>

    <!-- Featured Products -->
    <div class="settings-card home">
      <div class="settings-header">Featured Products</div>
      <div class="settings-body">
        <div class="mb-3">
          <label for="featured_title_main" class="form-label">Section Title</label>
          <input type="text" class="form-control" id="featured_title_main" 
                 name="featured_title_main" value="<?= htmlspecialchars($featured_title) ?>">
        </div>

        <?php for($i=1;$i<=2;$i++):
          $img = $featured_items[$i]['image_path'] ?? '';
          $title = $featured_items[$i]['title'] ?? '';
        ?>
        <div class="mb-3">
          <label>Featured Image <?= $i ?></label>
          <input type="file" name="featured_image[<?= $i ?>]" class="form-control">
          <img src="../../<?= htmlspecialchars($img) ?>" id="featured-preview-<?= $i ?>" width="120" class="mt-2 mb-2" <?= $img ? '' : 'style="display:none;"' ?>>
        </div>
        <div class="mb-3">
          <label>Title <?= $i ?></label>
          <input type="text" name="featured_title[<?= $i ?>]" value="<?= htmlspecialchars($title) ?>" class="form-control">
        </div>
        <?php endfor; ?>
      </div>
    </div>

    <!-- Delivery or Pickup -->
    <div class="settings-card home">
      <div class="settings-header">Delivery / Pickup Section</div>
      <div class="settings-body">
        <?php for($i=1;$i<=4;$i++): ?>
        <div class="mb-3">
          <label>Title <?= $i ?></label>
          <input type="text" name="del_title<?= $i ?>" value="<?= htmlspecialchars($del_pick['title'.$i]) ?>" class="form-control">
        </div>
        <?php endfor; ?>

<h5>Font Colors for Delivery/Pickup Titles</h5> 
<div class="settings-row"> 
    <?php $delFontColorFields = [ "del_pick_font_color_title1" => "#058240", "del_pick_font_color_title2" => "#058240", "del_pick_font_color_title3" => "#058240", "del_pick_font_color_title4" => "#F4C40F" ]; 
    foreach ($delFontColorFields as $key => $default): $value = htmlspecialchars($settings[$key] ?? $default); $label = ucwords(str_replace("_", " ", $key)); ?> 
    <div class="settings-col"> 
        <label class="form-label"><?= $label ?></label> 
        <div class="color-picker"> 
            <input type="color" id="<?= $key ?>" name="settings[<?= $key ?>]" value="<?= $value ?>"> 
            <span id="<?= $key ?>Value"><?= $value ?></span> 
        </div> 
    </div> 
    <?php endforeach; ?> 
</div>
        

        <h5>Section Image</h5>
        <input type="file" id="del_pick_image" name="del_pick_image" accept="image/*">
        <div class="mt-2">
          <img id="del-pick-preview"
               src="<?= !empty($settings['del_pick_image']) ? '../../' . htmlspecialchars($settings['del_pick_image']) : '' ?>"
               alt="Preview"
               style="max-width:200px; margin-top:10px; border:1px solid #ccc; padding:5px; <?= empty($settings['del_pick_image']) ? : '' ?>">
        </div>
      </div>
    </div>

    <!-- Reasons Section -->
    <div class="settings-card home">
      <div class="settings-header">Why is it Good?</div>
      <div class="settings-body">
        <div class="mb-3">
          <label for="reasons_heading" class="form-label">Section Heading</label>
          <input type="text" class="form-control" id="reasons_heading" name="reasons_heading" value="<?= htmlspecialchars($reasons_heading) ?>">
        </div>

        <?php for($i=1; $i<=4; $i++): 
          $icon = $reasons[$i]['icon'] ?? 'fa-solid fa-circle';
          $title = $reasons[$i]['title'] ?? '';
          $subtitle = $reasons[$i]['subtitle'] ?? '';
        ?>
        <div class="mb-4 border p-3 rounded">
          <h5>Reason <?= $i ?></h5>
          <div class="mb-2">
            <label class="form-label">Font Awesome Icon Class</label>
            <input type="text" class="form-control" name="reason_icon[<?= $i ?>]" value="<?= htmlspecialchars($icon) ?>" placeholder="e.g. fa-solid fa-cow">
          </div>
          <div class="mb-2">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="reason_title[<?= $i ?>]" value="<?= htmlspecialchars($title) ?>" maxlength="100">
          </div>
          <div class="mb-2">
            <label class="form-label">Subtitle</label>
            <input type="text" class="form-control" name="reason_subtitle[<?= $i ?>]" value="<?= htmlspecialchars($subtitle) ?>" maxlength="255">
          </div>
        </div>
        <?php endfor; ?>
      </div>
    </div>

    <!-- About Section -->
    <div class="settings-card home">
      <div class="settings-header">About Section</div>
      <div class="settings-body">
        <div class="mb-3">
          <label for="about_heading" class="form-label">Heading</label>
          <input type="text" class="form-control" id="about_heading" name="about_heading" value="<?= htmlspecialchars($about_heading) ?>">
        </div>

        <div class="mb-3">
          <label class="form-label">Upload Slider Images</label>
          <input type="file" class="form-control" id="about_images" name="about_images[]" multiple accept="image/*">
        </div>

        <div id="about-preview" class="mt-3" style="display:flex; flex-wrap:wrap; gap:10px;"></div>

        <div class="mt-3">
          <?php foreach ($about_slider as $slide): ?>
          <div style="display:inline-block; position:relative; margin:5px;">
            <img src="../../<?= htmlspecialchars($slide['image_path']) ?>" alt="About Slider" style="max-width:120px; border:1px solid #ccc; padding:3px;">
            <button type="button" class="delete-about-img" data-id="<?= $slide['id'] ?>" style="position:absolute; top:0; right:0; background:red; color:white; border:none; cursor:pointer; font-size:14px; padding:2px 5px;">×</button>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <button type="submit" name="update_home_btn" class="update_home_btn">Update Homepage</button>
    <button type="submit" name="reset_home_btn" class="reset_home_btn">Reset to Defaults</button>
  </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Show toast
var toastMessage = "<?= $toast_message ?>";
if (toastMessage) {
    Swal.fire({
        icon: 'success',
        text: toastMessage,
        confirmButtonColor: '#0D8540'
    });
}

// Live color preview
document.querySelectorAll('input[type="color"]').forEach(input => {
    input.addEventListener('input', function() {
        document.getElementById(this.id + 'Value').textContent = this.value;
    });
});
</script>


<script>
// Live image preview
// Hero Images
document.querySelectorAll('input[name^="hero_image"]').forEach((input, index)=>{
    input.addEventListener('change', function(){
        const file = this.files[0];
        const preview = document.getElementById('hero-preview-'+(index+1));
        if(file){
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.style.display='block'; }
            reader.readAsDataURL(file);
        }
    });
});

// Featured Images
document.querySelectorAll('input[name^="featured_image"]').forEach((input, index)=>{
    input.addEventListener('change', function(){
        const file = this.files[0];
        const preview = document.getElementById('featured-preview-'+(index+1));
        if(file){
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.style.display='block'; }
            reader.readAsDataURL(file);
        }
    });
});


document.getElementById('del_pick_image').addEventListener('change', function(){
    const file = this.files[0];
    const preview = document.getElementById('del-pick-preview');
    if(file){
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

</script>


<script>
// ✅ Preview + remove newly selected images (before saving)
document.getElementById('about_images').addEventListener('change', function(){
    const previewContainer = document.getElementById('about-preview');
    previewContainer.innerHTML = ""; // clear old previews

    Array.from(this.files).forEach((file, index) => {
        if(file){
            const reader = new FileReader();
            reader.onload = e => {
                const wrapper = document.createElement('div');
                wrapper.style.position = "relative";
                wrapper.style.display = "inline-block";
                wrapper.style.margin = "5px";

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = "120px";
                img.style.border = "1px solid #ccc";
                img.style.padding = "3px";

                // delete button (for preview only)
                const delBtn = document.createElement('button');
                delBtn.textContent = "×";
                delBtn.style.position = "absolute";
                delBtn.style.top = "0";
                delBtn.style.right = "0";
                delBtn.style.background = "red";
                delBtn.style.color = "white";
                delBtn.style.border = "none";
                delBtn.style.cursor = "pointer";
                delBtn.style.fontSize = "14px";
                delBtn.style.padding = "2px 5px";

                delBtn.addEventListener('click', () => {
                    wrapper.remove();

                    // remove file from input
                    const dt = new DataTransfer();
                    Array.from(document.getElementById('about_images').files)
                        .forEach((f, i) => { if(i !== index) dt.items.add(f); });
                    document.getElementById('about_images').files = dt.files;
                });

                wrapper.appendChild(img);
                wrapper.appendChild(delBtn);
                previewContainer.appendChild(wrapper);
            }
            reader.readAsDataURL(file);
        }
    });
});

// Delete already-saved images (from DB + server)
document.querySelectorAll('.delete-about-img').forEach(btn => {
    btn.addEventListener('click', function() {
        const imgId = this.getAttribute('data-id');
        const parent = this.closest('div');

        if(confirm("Delete this image?")) {
            fetch('delete-about-image.php', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'id=' + imgId + '&table=home_about_slider'
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
