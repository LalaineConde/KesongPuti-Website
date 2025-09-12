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

    <!-- BOOTSTRAP ICONS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
    />

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
    <div class="col-md-6">
      <div class="settings-card">
        <div class="settings-header">General Settings</div>
        <div class="settings-body">
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Primary Color</label>
              <input type="color" class="form-control form-control-color" 
                     name="settings[primary_color]" 
                     value="<?= htmlspecialchars($settings['primary_color'] ?? '#4e6f47') ?>">
            </div>
            
            <div class="mb-3">
              <label class="form-label">Secondary Color</label>
              <input type="color" class="form-control form-control-color" 
                     name="settings[secondary_color]" 
                     value="<?= htmlspecialchars($settings['secondary_color'] ?? '#f4c400') ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Font Family</label>
              <input type="text" class="form-control" 
                     name="settings[font_family]" 
                     value="<?= htmlspecialchars($settings['font_family'] ?? 'Fredoka') ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Site Title</label>
              <input type="text" class="form-control" 
                     name="settings[site_title]" 
                     value="<?= htmlspecialchars($settings['site_title'] ?? 'Kesong Puti Store') ?>">
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