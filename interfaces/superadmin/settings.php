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

    // ✅ Handle Page Header Cover Upload
    if (!empty($_FILES['page_header_cover']['name'])) {
        $targetDir = "../../uploads/header/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . "header.png"; // overwrite same file

        if (file_exists($targetFile)) {
            unlink($targetFile);
        }

        move_uploaded_file($_FILES['page_header_cover']['tmp_name'], $targetFile);
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

/* ========== DEFINE HEADER PATHS ========== */
$defaultHeader = "../../assets/header.png";
$uploadedHeader = "../../uploads/header/header.png";

while ($row = mysqli_fetch_assoc($query)) {
    $headers[$row['page_name']] = $row['header_text'];
}

/* ========== RESET TO DEFAULT SETTINGS ========== */
if (isset($_POST['reset_defaults'])) {
    $defaultSettings = [
        // PAGE BACKGROUNDS
        "page_bg" => "#FEFAF6",
        "headers_bg" => "#FBF1D7",
        "footer_bg" => "#FBF1D7",
        "wave_background" => "#058240",

        // FONTS
        "page_header_font" => "Lilita One",
        "primary_font" => "Poppins",

        // FONT COLORS
        "heading_font_color" => "#0D8540",
        "second_heading_font_color" => "#FFFFFF",
        "body_font_color" => "#000000",
        "navbar_hover_color" => "#87B86B",
        "price_color" => "#F4C40F",


        // BUTTONS
        "checkout_button_color" => "#F4C40F",
        "checkout_button_hover" => "#0D8540",
        "button1_color" => "#0D8540",
        "button1_font_color" => "#0D8540",
        "button1_hover" => "#FFFFFF",
        "button1_hover_font" => "#0D8540",
        "button2_color" => "#FFFFFF",
        "button2_font_color" => "#FFFFFF",
        "button2_hover" => "#0D8540",
        "button2_hover_font" => "#FFFFFF",
        "button3_color" => "#F4C40F",
        "button3_font_color" => "#000000",

        // PRODUCTS
        "product_font_color" => "#000000",
        "icon_color" => "#0D8540",
        "page_number_active" => "#0D8540",
        "page_number_hover" => "#F4C40F",

        // FAQ
        "faq_button_bg" => "#0D8540",
        "faq_question_font_color" => "#FFFFFF",
        "faq_answer_bg" => "#87B86B",
        "faq_answer_font_color" => "#000000",

    ];

    foreach ($defaultSettings as $key => $value) {
        $key = mysqli_real_escape_string($connection, $key);
        $value = mysqli_real_escape_string($connection, $value);
        mysqli_query($connection,
            "REPLACE INTO site_settings (setting_key, setting_value)
             VALUES ('$key', '$value')")
            or die("Error: " . mysqli_error($connection));
    }

    // Default Page Headers
    $defaultHeaders = [
        "home" => "WELCOME",
        "products" => "OUR PRODUCTS",
        "FAQ" => "GOT QUESTIONS?",
        "contact" => "GET IN TOUCH",
        "feedback" => "CUSTOMER FEEDBACK"
    ];

    foreach ($defaultHeaders as $page => $text) {
        $page = mysqli_real_escape_string($connection, $page);
        $text = mysqli_real_escape_string($connection, $text);
        mysqli_query($connection,
            "INSERT INTO page_headers (page_name, header_text)
             VALUES ('$page', '$text')
             ON DUPLICATE KEY UPDATE header_text='$text'");
    }

    // Reset Page Header Cover
    $uploadedHeader = "../../uploads/header/header.png";
    if (file_exists($uploadedHeader)) {
        unlink($uploadedHeader);
    }

    header("Location: settings.php?success=reset");
    exit;
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
<link href="https://fonts.googleapis.com/css2?family=Lilita+One&family=Fredoka:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- BOOTSTRAP ICONS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="../../css/admin.css"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="p-4 bg-light">
<div class="settings-container">
  <h2 class="mb-4">General Settings</h2>

  <?php if (isset($_GET['success'])): ?>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      Swal.fire({
        icon: "success",
        title: "Success",
        text: "<?= $_GET['success'] === 'general' ? 'General settings updated!' : 'Page headers updated!' ?>",
        confirmButtonColor: "#0D8540"
      });
    });
  </script>
<?php endif; ?>


    <!-- Page Headers -->
    <div class="col-md-6">
      <div class="settings-card general">
        <div class="settings-header">Page Header</div>
        <div class="settings-body">
          <form method="POST">
            <?php
            $pages = ['home','products','FAQ','contact','feedback'];
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

  <div class="row">

<!-- General Settings -->
<div class="col-md-6">
<div class="settings-card general">
  <div class="settings-header">General</div>
  <div class="settings-body">

    <form method="POST" enctype="multipart/form-data">



<!-- ================= Page Header Cover ================= -->
<h5>Page Header Cover</h5>
<div class="settings-row">
  <div class="settings-col">
    <label class="form-label">Upload Page Header Cover</label>
    <input type="file" name="page_header_cover" id="pageHeaderInput" class="form-control" accept="image/*">

    <!-- Preview Div -->
    <div id="pageHeaderPreview" 
         style="background-image: url('<?= file_exists($uploadedHeader) ? $uploadedHeader : ''?>');
                height:150px; background-size:cover; 
                border:1px solid #ccc; border-radius:8px; margin-top:10px;">
    </div>
  </div>
</div>




   <!-- ================= Fonts ================= -->
    <h5>Fonts</h5>
    <div class="settings-row">
      <div class="settings-col">
        <label class="form-label">Primary Font</label>
        <select class="form-select" name="settings[primary_font]">
          <?php 
          $fonts = ['Lilita One','Poppins','Fredoka','Arial','Verdana','Tahoma','Georgia','Times New Roman','Courier New','Trebuchet MS'];
          foreach ($fonts as $font): ?>
            <option value="<?= $font ?>" <?= ($settings['primary_font'] ?? '') === $font ? 'selected' : '' ?>><?= $font ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="settings-col">
        <label class="form-label">Page Header Font</label>
        <select class="form-select" name="settings[page_header_font]">
          <?php foreach ($fonts as $font): ?>
            <option value="<?= $font ?>" <?= ($settings['page_header_font'] ?? '') === $font ? 'selected' : '' ?>><?= $font ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>


        <!-- ================= Backgrounds ================= -->
    <h5>Backgrounds</h5>
    <div class="settings-row">
      <?php 
      $bgFields = [
        "page_bg" => "#FEFAF6",
        "headers_bg" => "#FBF1D7",
        "footer_bg" => "#FBF1D7",
        "wave_background" => "#058240"
      ];
      foreach ($bgFields as $key => $default): 
        $label = ucwords(str_replace("_", " ", $key));
        $value = htmlspecialchars($settings[$key] ?? $default);
      ?>
        <div class="settings-col">
          <label class="form-label"><?= $label ?></label>
          <div class="color-picker">
            <input type="color" id="<?= $key ?>" name="settings[<?= $key ?>]" value="<?= $value ?>">
            <span id="<?= $key ?>Value"><?= $value ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- ================= Font Colors ================= -->
    <h5>Font Colors</h5>
    <div class="settings-row">
      <?php 
      $fontColorFields = [
        "heading_font_color" => "#0D8540",
        "second_heading_font_color" => "#FFFFFF",
        "body_font_color" => "#000000",
        "navbar_hover_color" => "#87B86B",
        "price_color" => "#F4C40F"
      ];
      foreach ($fontColorFields as $key => $default): 
        $label = ucwords(str_replace("_", " ", $key));
        $value = htmlspecialchars($settings[$key] ?? $default);
      ?>
        <div class="settings-col">
          <label class="form-label"><?= $label ?></label>
          <div class="color-picker">
            <input type="color" id="<?= $key ?>" name="settings[<?= $key ?>]" value="<?= $value ?>">
            <span id="<?= $key ?>Value"><?= $value ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

      <!-- ================= Buttons ================= -->
    <h5>Buttons</h5>
    <div class="settings-row">
      <?php 
      $buttonFields = [
        "checkout_button_color" => "#F4C40F",
        "checkout_button_hover" => "#0D8540",
        "button1_color" => "#0D8540",
        "button1_font_color" => "#0D8540",
        "button1_hover" => "#FFFFFF",
        "button1_hover_font" => "#0D8540",
        "button2_color" => "#FFFFFF",
        "button2_font_color" => "#FFFFFF",
        "button2_hover" => "#0D8540",
        "button2_hover_font" => "#FFFFFF",
        "button3_color" => "#F4C40F",
        "button3_font_color" => "#000000"
      ];
      foreach ($buttonFields as $key => $default): 
        $label = ucwords(str_replace("_", " ", $key));
        $value = htmlspecialchars($settings[$key] ?? $default);
      ?>
        <div class="settings-col">
          <label class="form-label"><?= $label ?></label>
          <div class="color-picker">
            <input type="color" id="<?= $key ?>" name="settings[<?= $key ?>]" value="<?= $value ?>">
            <span id="<?= $key ?>Value"><?= $value ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- ================= Products ================= -->
    <h5>Products</h5>
    <div class="settings-row">
      <?php 
      $productFields = [
        "product_font_color" => "#000000",
        "icon_color" => "#0D8540",
        "page_number_active" => "#0D8540",
        "page_number_hover" => "#F4C40F"
      ];
      foreach ($productFields as $key => $default): 
        $label = ucwords(str_replace("_", " ", $key));
        $value = htmlspecialchars($settings[$key] ?? $default);
      ?>
        <div class="settings-col">
          <label class="form-label"><?= $label ?></label>
          <div class="color-picker">
            <input type="color" id="<?= $key ?>" name="settings[<?= $key ?>]" value="<?= $value ?>">
            <span id="<?= $key ?>Value"><?= $value ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

<!-- ================= FAQ Styles ================= -->
<h5>FAQ Styles</h5>
<div class="settings-row">
  <?php 
  $faqFields = [
    "faq_button_bg" => "#0D8540",
    "faq_question_font_color" => "#FFFFFF",
    "faq_answer_bg" => "#87B86B",
    "faq_answer_font_color" => "#000000"
  ];
  foreach ($faqFields as $key => $default): 
    $label = ucwords(str_replace("_", " ", $key));
    $value = htmlspecialchars($settings[$key] ?? $default);
  ?>
    <div class="settings-col">
      <label class="form-label"><?= $label ?></label>
      <div class="color-picker">
        <input type="color" id="<?= $key ?>" name="settings[<?= $key ?>]" value="<?= $value ?>">
        <span id="<?= $key ?>Value"><?= $value ?></span>
      </div>
    </div>
  <?php endforeach; ?>
</div>


    <!-- Submit -->
    <button type="submit" name="save_general" class="settings-btn">Save General Settings</button>
  </div>
</div>







      <!-- Reset All Settings -->
<form method="post">
  <button type="submit" name="reset_defaults" class="reset-btn">
    Reset All to Default
  </button>
</form>
</div>
</body>
  </div>





<script>
document.querySelectorAll(".color-picker input[type=color]").forEach(function(picker) {
  const valueSpan = document.getElementById(picker.id + "Value");
  picker.addEventListener("input", function() {
    valueSpan.textContent = this.value.toUpperCase();
  });
});
</script>

<script>
document.getElementById("pageHeaderInput").addEventListener("change", function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("pageHeaderPreview").style.backgroundImage = "url('" + e.target.result + "')";
        };
        reader.readAsDataURL(file);
    }
});
</script>
</html>
