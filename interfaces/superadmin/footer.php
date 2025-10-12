<?php
$page_title = 'Footer | Kesong Puti';
require '../../connection.php';

$updated = false;

if (isset($_GET['updated']) && $_GET['updated'] == 1) {
    $updated = true;
}

$footer_query = mysqli_query($connection, "SELECT * FROM footer_settings LIMIT 1");
$footer = mysqli_fetch_assoc($footer_query);

// UPDATE FOOTER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_footer'])) {
    $description = $_POST['description'];
    $bottom_text = $_POST['bottom_text'];

    // business hours
    $days = ["mon", "tue", "wed", "thu", "fri", "sat", "sun"];
    $hours_sql = [];

    foreach ($days as $day) {
        $open = !empty($_POST[$day . "_open"]) ? date("h:iA", strtotime($_POST[$day . "_open"])) : "07:00AM";
        $close = !empty($_POST[$day . "_close"]) ? date("h:iA", strtotime($_POST[$day . "_close"])) : "05:00PM";
        $hours_sql[$day] = "$open - $close";
    }

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
        SET description='$description', bottom_text='$bottom_text',
            mon_hours='{$hours_sql['mon']}', tue_hours='{$hours_sql['tue']}', wed_hours='{$hours_sql['wed']}',
            thu_hours='{$hours_sql['thu']}', fri_hours='{$hours_sql['fri']}',
            sat_hours='{$hours_sql['sat']}', sun_hours='{$hours_sql['sun']}'
            $logo_sql $background_sql
        WHERE id=" . $footer['id']);

    // redirect with flag
    header("Location: footer.php?updated=1");
    exit();
}

include ('../../includes/superadmin-dashboard.php');
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Footer | Kesong Puti</title>

  <!-- BOOTSTRAP ICONS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="../../css/admin.css"/>

  <!-- SWEETALERT2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

<div class="footer-cms-content">
  <h1>Customize Footer</h1>
  <div class="footer-cms-container">

    <form method="post" enctype="multipart/form-data">
      <label>Footer Background:</label>
      <input type="file" name="background_image">
      <?php if (!empty($footer['background_image'])): ?>
        <img src="../../uploads/footer/<?= htmlspecialchars($footer['background_image']) ?>" width="200">
      <?php endif; ?>

      <label>Logo:</label>
      <input type="file" name="logo">
      <?php if (!empty($footer['logo'])): ?>
        <img src="../../uploads/footer/<?= htmlspecialchars($footer['logo']) ?>" width="100">
      <?php endif; ?>

      <label>Description:</label>
      <textarea name="description" rows="3"><?= htmlspecialchars($footer['description']) ?></textarea>

      <?php
      function split_hours($hours, $default_open = "07:00AM", $default_close = "05:00PM") {
          if (!empty($hours) && strpos($hours, '-') !== false) {
              list($open, $close) = explode(" - ", $hours);
              return [
                  "open" => date("H:i", strtotime(trim($open))),
                  "close" => date("H:i", strtotime(trim($close)))
              ];
          }
          return [
              "open" => date("H:i", strtotime($default_open)),
              "close" => date("H:i", strtotime($default_close))
          ];
      }

      $days = [
          "mon" => "Monday",
          "tue" => "Tuesday",
          "wed" => "Wednesday",
          "thu" => "Thursday",
          "fri" => "Friday",
          "sat" => "Saturday",
          "sun" => "Sunday"
      ];

      foreach ($days as $short => $label):
          $time = split_hours($footer[$short . "_hours"]);
      ?>
      <label><?= $label ?>:</label>
      <div class="day-hours">
        <input type="time" name="<?= $short ?>_open" value="<?= $time['open'] ?>" required>
        <span>to</span>
        <input type="time" name="<?= $short ?>_close" value="<?= $time['close'] ?>" required>
      </div>
      <?php endforeach; ?>

      <label>Footer Note:</label>
      <input type="text" name="bottom_text" value="<?= htmlspecialchars($footer['bottom_text']) ?>">
  </div>

  <div class="footer-btns">
    <button type="submit" name="update_footer">Update Footer</button>
  </div>
  </form>
</div>

<!-- SweetAlert Trigger -->
<?php if ($updated): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Success!',
  text: 'Footer updated successfully!',
  confirmButtonColor: '#064420'
});
</script>
<?php endif; ?>

</body>
</html>
