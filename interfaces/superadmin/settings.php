<?php
$page_title = 'Settings | Kesong Puti';
require '../../connection.php';

/* ========== SAVE GENERAL SETTINGS ========== */
if (isset($_POST['save_general'])) {
    foreach ($_POST['settings'] as $key => $value) {
        $key = mysqli_real_escape_string($connection, $key);
        $value = mysqli_real_escape_string($connection, $value);

        mysqli_query($connection,
            "REPLACE INTO site_settings (setting_key, setting_value)
             VALUES ('$key', '$value')")
            or die("Error: " . mysqli_error($connection));
    }
    header("Location: settings.php?success=general");
    exit;
}

/* ========== SAVE PAGE HEADERS ========== */
if (isset($_POST['save_headers'])) {
    foreach ($_POST['headers'] as $page => $text) {
        $page = mysqli_real_escape_string($connection, $page);
        $text = mysqli_real_escape_string($connection, $text);

        mysqli_query($connection,
            "INSERT INTO page_headers (page_name, header_text)
             VALUES ('$page', '$text')
             ON DUPLICATE KEY UPDATE header_text='$text'");
    }
    header("Location: settings.php?success=headers");
    exit;
}

/* ========== FETCH SETTINGS ========== */
$settings = [];
$query = mysqli_query($connection, "SELECT * FROM site_settings");
while ($row = mysqli_fetch_assoc($query)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

/* ========== FETCH HEADERS ========== */
$headers = [];
$query = mysqli_query($connection, "SELECT * FROM page_headers");
while ($row = mysqli_fetch_assoc($query)) {
    $headers[$row['page_name']] = $row['header_text'];
}

include ('../../includes/superadmin-dashboard.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings | Kesong Puti</title>

  <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- BOOTSTRAP ICONS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="../../css/admin.css"/>
</head>
<body class="p-4 bg-light">
<div class="settings-container">
  <h2 class="mb-4">Admin Settings</h2>

  <!-- Alerts -->
  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
      <?= $_GET['success'] === 'general' ? 'General settings updated!' : 'Page headers updated!' ?>
    </div>
  <?php endif; ?>

  <div class="row">
    <!-- General Settings -->
<!-- General Settings -->
<div class="col-md-6">
  <div class="settings-card">
    <div class="settings-header">General Settings</div>
    <div class="settings-body">
      <form method="POST">
        <!-- Primary Font Family -->
        <div class="mb-3">
          <label class="form-label">Primary Font</label>
          <select class="form-select" name="settings[primary_font]">
            <?php 
            $fonts = [
              'Arial', 
              'Verdana', 
              'Tahoma', 
              'Georgia', 
              'Times New Roman', 
              'Courier New', 
              'Trebuchet MS',
              'Lilita One',   
              'Fredoka'       
            ];
            foreach ($fonts as $font): ?>
              <option value="<?= $font ?>" 
                <?= ($settings['primary_font'] ?? '') === $font ? 'selected' : '' ?>>
                <?= $font ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

<!-- Page Header Font Family -->
<div class="mb-3">
  <label class="form-label">Page Header Font</label>
  <select class="form-select" name="settings[page_header_font]">
    <?php foreach ($fonts as $font): ?>
      <option value="<?= $font ?>" 
        <?= ($settings['page_header_font'] ?? '') === $font ? 'selected' : '' ?>>
        <?= $font ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>

        <!-- NavBar Color -->
        <div class="mb-3">
          <label class="form-label">NavBar Color</label>
          <input type="color" class="form-control form-control-color" 
                 name="settings[navbar_color]" 
                 value="<?= htmlspecialchars($settings['navbar_color'] ?? '#000000') ?>">
        </div>
        <!-- Subtitle Font Color -->
        <div class="mb-3">
          <label class="form-label">Subtitle Font Color</label>
          <input type="color" class="form-control form-control-color" 
                name="settings[subtitle_font_color]" 
                value="<?= htmlspecialchars($settings['subtitle_font_color'] ?? '#333333') ?>">
        </div>

        <!-- Price Color -->
        <div class="mb-3">
          <label class="form-label">Price Color</label>
          <input type="color" class="form-control form-control-color" 
                 name="settings[price_color]" 
                 value="<?= htmlspecialchars($settings['price_color'] ?? '#000000') ?>">
        </div>

        <!-- Description Color -->
        <div class="mb-3">
          <label class="form-label">Description Color</label>
          <input type="color" class="form-control form-control-color" 
                 name="settings[description_color]" 
                 value="<?= htmlspecialchars($settings['description_color'] ?? '#000000') ?>">
        </div>

        <!-- Button 1 Color -->
        <div class="mb-3">
          <label class="form-label">Button 1 Color</label>
          <input type="color" class="form-control form-control-color" 
                 name="settings[button1_color]" 
                 value="<?= htmlspecialchars($settings['button1_color'] ?? '#000000') ?>">
        </div>

        <!-- Button 2 Color -->
        <div class="mb-3">
          <label class="form-label">Button 2 Color</label>
          <input type="color" class="form-control form-control-color" 
                 name="settings[button2_color]" 
                 value="<?= htmlspecialchars($settings['button2_color'] ?? '#000000') ?>">
        </div>


                <!-- FAQ Button Background Color -->
        <div class="mb-3">
          <label class="form-label">FAQ Button Background Color</label>
          <input type="color" class="form-control form-control-color" 
                name="settings[faq_button_bg]" 
                value="<?= htmlspecialchars($settings['faq_button_bg'] ?? '#000000') ?>">
        </div>

        <!-- FAQ Answer Background Color -->
        <div class="mb-3">
          <label class="form-label">FAQ Answer Background Color</label>
          <input type="color" class="form-control form-control-color" 
                name="settings[faq_answer_bg]" 
                value="<?= htmlspecialchars($settings['faq_answer_bg'] ?? '#ffffff') ?>">
        </div>

        <!-- Product Page Number Background Color -->
        <div class="mb-3">
          <label class="form-label">Product Page Number Background Color</label>
          <input type="color" class="form-control form-control-color" 
                name="settings[product_page_number_bg]" 
                value="<?= htmlspecialchars($settings['product_page_number_bg'] ?? '#000000') ?>">
        </div>

        <button type="submit" name="save_general" class="settings-btn">Save General Settings</button>
      </form>
    </div>
  </div>
</div>


    <!-- Page Headers -->
    <div class="col-md-6">
      <div class="settings-card">
        <div class="settings-header">Page Headers</div>
        <div class="settings-body">
          <form method="POST">
            <?php
            $pages = ['home','products','faq','contact','feedback'];
            foreach ($pages as $page): ?>
              <div class="mb-3">
                <label class="form-label"><?= ucfirst($page) ?> Page Header</label>
                <input type="text" class="form-control"
                       name="headers[<?= $page ?>]"
                       value="<?= htmlspecialchars($headers[$page] ?? '') ?>">
              </div>
            <?php endforeach; ?>
            <button type="submit" name="save_headers" class="settings-btn">Save Page Headers</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
