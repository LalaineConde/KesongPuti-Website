<?php
$page_title = 'Products | Kesong Puti';
require '../../connection.php';
include ('../../includes/superadmin-dashboard.php');

$toast_message = ''; // Initialize toast


if (isset($_POST['update_branding']) && isset($_POST['branding']) && is_array($_POST['branding'])) {
    foreach($_POST['branding'] as $id => $data){
        $icon_class = mysqli_real_escape_string($connection, $data['icon_class']);
        $icon_color = mysqli_real_escape_string($connection, $data['icon_color']);
        $heading = mysqli_real_escape_string($connection, $data['heading']);
        $paragraph = mysqli_real_escape_string($connection, $data['paragraph']);

        mysqli_query($connection, "
            UPDATE branding_sections_products 
            SET icon_class='$icon_class', 
                icon_color='$icon_color', 
                heading='$heading', 
                paragraph='$paragraph' 
            WHERE id=$id
        ");
    }
    $toast_message = "Branding section updated!";
}

if (isset($_POST['reset_branding'])) {
    // Define default values (adjust these as needed)
    $defaults = [
        1 => ['icon_class' => 'fa-solid fa-heart', 'icon_color' => '#0D8540', 'heading' => 'Authentic Filipino Tradition', 'paragraph' => 'Taste a Piece of Filipino Heritage. Our kesong puti is crafted with a time-honored recipe passed down through generations'],
        2 => ['icon_class' => 'fa-solid fa-leaf', 'icon_color' => '#0D8540', 'heading' => 'Freshness from Local Farms', 'paragraph' => 'Farm-Fresh Goodness. We use carabao’s milk sourced daily from local farmers to ensure maximum freshness and flavor.'],
        3 => ['icon_class' => 'fa-solid fa-cheese', 'icon_color' => '#0D8540', 'heading' => 'Simple, Pure Ingredients', 'paragraph' => 'Pure and Simple. Absolutely Delicious. Made only with fresh carabao’s milk, salt, and rennet, our cheese has no preservatives—just natural flavor.'],
    ];

    foreach ($defaults as $id => $data) {
        $icon_class = mysqli_real_escape_string($connection, $data['icon_class']);
        $icon_color = mysqli_real_escape_string($connection, $data['icon_color']);
        $heading = mysqli_real_escape_string($connection, $data['heading']);
        $paragraph = mysqli_real_escape_string($connection, $data['paragraph']);

        mysqli_query($connection, "
            UPDATE branding_sections_products 
            SET icon_class='$icon_class',
                icon_color='$icon_color',
                heading='$heading',
                paragraph='$paragraph'
            WHERE id=$id
        ");
    }

    $toast_message = "Branding reset to default!";
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Products | Kesong Puti</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<link rel="stylesheet" href="../../css/admin.css"/>
</head>
<body>
<div class="products-cms-container" id="products-cms-content">
<h1>Products Page</h1>

<form method="POST" class="products-cms-form">
    
        <div class="settings-card products-cms">
            <div class="products-header">Products Branding</div>
            <div class="section-content">
    
    <?php 
    $result = mysqli_query($connection, "SELECT * FROM branding_sections_products ORDER BY position ASC");
    while($row = mysqli_fetch_assoc($result)): ?>
      <div class="col-lg-4 mb-3 border p-3">
        <h4>Branding Block <?= $row['position'] ?></h4>
        
        <label>Icon Class</label>
        <input type="text" name="branding[<?= $row['id'] ?>][icon_class]" 
               value="<?= htmlspecialchars($row['icon_class']) ?>" class="form-control">

<div class="color-picker-group">
  <label>Icon Color</label>
  <input type="color" 
         name="branding[<?= $row['id'] ?>][icon_color]" 
         value="<?= htmlspecialchars($row['icon_color']) ?>" 
         class="form-control-color">
</div>
        <label>Heading</label>
        <input type="text" name="branding[<?= $row['id'] ?>][heading]" 
               value="<?= htmlspecialchars($row['heading']) ?>" class="form-control">

        <label>Paragraph</label>
        <textarea name="branding[<?= $row['id'] ?>][paragraph]" 
                  class="form-control" rows="3"><?= htmlspecialchars($row['paragraph']) ?></textarea>
      </div>
    <?php endwhile; ?>
  </div>
  </div>
  <button type="submit" name="update_branding" class="update_products_btn">Update Branding</button>
<button type="submit" name="reset_branding" class="reset_products_btn">Reset to Default</button>
</form>


</div>
</body>
</html>