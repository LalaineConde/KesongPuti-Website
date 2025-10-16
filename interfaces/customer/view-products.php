<?php
$page_title = 'Customer Products | Kesong Puti';
require '../../connection.php';
$current_page = 'products';
$isHomePage = ($current_page === 'home'); // check if this is the home page
$page_header = "PRODUCTS";
include ('../../includes/customer-dashboard.php');

// Page meta
$page_title = 'Product Details | Kesong Puti';
$page_header = 'Product Details';
$page_breadcrumb_html = '<span>Home</span>  -  <span>Product Details</span>';
$current_page = 'products';

// Fetch product, gallery, and variations
$product = null;
$gallery = [];
$variations = [];

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
  $id = (int) $_GET['id'];

  // Fetch product
  $stmt = mysqli_prepare($connection, "
    SELECT p.*, COALESCE(st.store_name, 'Superadmin') AS recipient, st.store_id
    FROM products p
    LEFT JOIN store st ON p.owner_id = st.owner_id OR p.owner_id = st.super_id
    WHERE p.product_id = ? LIMIT 1
  ");
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    // Fetch gallery images
    $gal_stmt = mysqli_prepare($connection, "
      SELECT * FROM product_gallery WHERE product_id = ? ORDER BY gallery_id ASC
    ");
    mysqli_stmt_bind_param($gal_stmt, 'i', $id);
    mysqli_stmt_execute($gal_stmt);
    $gal_result = mysqli_stmt_get_result($gal_stmt);
    while ($row = mysqli_fetch_assoc($gal_result)) {
      $gallery[] = $row;
    }
    mysqli_stmt_close($gal_stmt);

    // Fetch variations
    $var_stmt = mysqli_prepare($connection, "
      SELECT variation_id, size, price, stock_qty, variant_image
      FROM product_variations
      WHERE product_id = ?
      ORDER BY variation_id ASC
    ");

    mysqli_stmt_bind_param($var_stmt, 'i', $id);
    mysqli_stmt_execute($var_stmt);
    $var_result = mysqli_stmt_get_result($var_stmt);
    while ($row = mysqli_fetch_assoc($var_result)) {
      $variations[] = $row;
    }
    mysqli_stmt_close($var_stmt);
  }
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kesong Puti - Products</title>

    <!-- BOOTSTRAP -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr"
      crossorigin="anonymous"
    >

    <!-- ICONS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    >

    <!-- CSS -->
    <link rel="stylesheet" href="../../css/styles.css" >
    
  
</head>

  <body>


    <!-- PRODUCT DETAILS -->
    <div class="container py-5">
      <div class="row g-4 align-items-start">
        <!-- Product Image -->
        <div class="col-md-6">
        <img
          src="../../<?= htmlspecialchars($product['product_image']) ?>"
          alt="<?= htmlspecialchars($product['product_name']) ?>"
          class="img-fluid rounded-4 shadow product-image"
          id="mainImage"
          data-default="../../<?= htmlspecialchars($product['product_image']) ?>"
        />



          <div class="row mt-3">
            <?php foreach ($gallery as $g): ?>
            <div class="col-3">
              <img
                src="../../<?= htmlspecialchars($g['image_path']) ?>"
                class="thumb-image gallery-thumb"
                data-type="gallery"
                data-src="../../<?= htmlspecialchars($g['image_path']) ?>"
              />
            </div>
              <?php endforeach; ?>
              <?php foreach ($variations as $v): ?>
                <?php if (!empty($v['variant_image'])): ?>
                  <div class="col-3">
                    <img
                      src="../../<?= htmlspecialchars($v['variant_image']) ?>"
                      class="thumb-image variant-thumb"
                      data-type="variant"
                      data-src="../../<?= htmlspecialchars($v['variant_image']) ?>"
                    />
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </div>

      

        <!-- Product Details -->
        <div class="col-md-6">
          <h1 class="product-title"><?= $product ? htmlspecialchars($product['product_name']) : 'Product' ?></h1>
          <p class="text-muted">
            <?= $product ? htmlspecialchars($product['description']) : 'Product description will appear here.' ?>
          </p>

          <!-- Rating -->
          <div class="rating d-flex align-items-center mb-4">
            <div class="stars me-2">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star text-warning"></i>
            </div>
            <span class="fw-semibold text-muted">(200 reviews)</span>
          </div>


          <div class="branch mb-2">
            <span><?= $product ? htmlspecialchars($product['recipient']) : 'Store' ?></span>
          </div>

          <div class="d-flex align-items-center mb-3">
            <p><strong><span class="product-price" id="productPrice">
              ₱<?= $variations ? number_format($variations[0]['price'], 2) : number_format($product['price'], 2) ?></strong>
            </span><p>
          </div>
          <p id="stockInfo" class="text-muted mb-3">
          <?= $variations ? "Stock: " . htmlspecialchars($variations[0]['stock_qty']) : "Stock: " . htmlspecialchars($product['stock_qty'] ?? 0) ?>
        </p>

          <!-- Quantity & Variation -->
          <div class="d-flex mb-3 align-items-center gap-3">
            <div class="qty-box">
              <button class="qty-btn" onclick="changeQty(-1)">−</button>
              <input type="text" id="quantity" class="qty-input" value="1" readonly />
              <button class="qty-btn" onclick="changeQty(1)">+</button>
            </div>

            <select id="variationSelect" class="variation form-select w-auto shadow-none">
              <option disabled selected>Select Size</option>
              <?php foreach ($variations as $index => $var): ?>
                <option value="<?= $index ?>">
                  <?= htmlspecialchars($var['size']) ?>
                </option>
              <?php endforeach; ?>
            </select>

          </div>


           <!-- Add to Cart Button -->
          <div class="d-flex flex-column gap-3 mt-4">
            <button 
              class="btn-add add-to-cart"
              data-id="<?= $product['product_id'] ?>"
              data-product_id="<?= $product['product_id'] ?>"
              data-name="<?= htmlspecialchars($product['product_name']) ?>"
              data-price="<?= $variations ? $variations[0]['price'] : $product['price'] ?>"
              data-store="<?= htmlspecialchars($product['recipient']) ?>"
              data-image="<?= $gallery ? '../../' . htmlspecialchars($gallery[0]['image_path']) : '../../' . htmlspecialchars($product['product_image']) ?>"
              data-qty-target="#quantity"
              data-variation-select="#variationSelect"
            >
              <i class="bi bi-bag-plus"></i> Add To Cart
            </button>


            <button class="btn-buy" onclick="buyNow()">Buy Now</button>
          </div>
        </div>
      </div>
      

      <!-- More Details -->
      <section id="details" class="mt-3">
        <div class="row about-content">
          <!-- Tabs -->
          <div class="col-md-6 col-sm-12">
            <div class="tab-container">
              <div class="tab-box">
                <button class="tab-btn active">Product Details</button>
                <button class="tab-btn">Contact Us</button>
                <button class="tab-btn">Reviews</button>
                <div class="line"></div>
              </div>

              <div class="content-box">
                <div class="content active">
                  <?php if ($product): ?>
                    <div class="product-details-content">
                      <p> <?= htmlspecialchars($product['description']) ?></p>
                    </div>
                  <?php else: ?>
                    <p>Product information not available.</p>
                  <?php endif; ?>
                </div>

                <div class="content">
                  <?php if ($store_contact): ?>
                    <div class="contact-details-content">
                      <h5><strong>Store Contact Information</strong></h5>
                      <div class="contact-info">
                        <p><strong>Store Name:</strong> <?= htmlspecialchars($store_contact['store_name']) ?></p>
                        <?php if (!empty($store_contact['email'])): ?>
                          <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($store_contact['email']) ?>"><?= htmlspecialchars($store_contact['email']) ?></a></p>
                        <?php endif; ?>
                        <?php if (!empty($store_contact['phone'])): ?>
                          <p><strong>Phone:</strong> <a href="tel:<?= htmlspecialchars($store_contact['phone']) ?>"><?= htmlspecialchars($store_contact['phone']) ?></a></p>
                        <?php endif; ?>
                        <?php if (!empty($store_contact['address'])): ?>
                          <p><strong>Address:</strong> <?= htmlspecialchars($store_contact['address']) ?></p>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="contact-details-content">
                      <h5><strong>Store Contact Information</strong></h5>
                      <p>Contact information for this store is not available at the moment.</p>
                      <?php if ($product): ?>
                        <p><strong>Store:</strong> <?= htmlspecialchars($product['recipient']) ?></p>
                      <?php endif; ?>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="content">
                  <p>
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                    Nam aliquam magnam exercitationem dicta voluptatum.
                    Inventore ad dolores, at sunt placeat voluptatem neque
                    possimus? Nihil laudantium provident vero veritatis debitis
                    libero! 789
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Related Items -->
          <div class="col-md-6 col-sm-12">
            <h2>Related Products</h2>
            <div class="related-items">
              <?php
              if ($product) {
                // Fetch related products from the same store, exclude the current product
                $related_stmt = mysqli_prepare(
                  $connection,
                  "SELECT p.*, 
                          COALESCE(st.store_name, 'Superadmin') AS recipient 
                  FROM products p
                  LEFT JOIN store st 
                    ON p.owner_id = st.owner_id OR p.owner_id = st.super_id
                  WHERE p.product_id != ? 
                    AND (p.owner_id = ? OR p.owner_id IN (
                          SELECT super_id FROM store WHERE owner_id = ?)
                    )
                  LIMIT 3"
                );

                if ($related_stmt) {
                  mysqli_stmt_bind_param($related_stmt, 'iii', $product['product_id'], $product['owner_id'], $product['owner_id']);
                  mysqli_stmt_execute($related_stmt);
                  $related_result = mysqli_stmt_get_result($related_stmt);

                  if (mysqli_num_rows($related_result) > 0) {
                    while ($related = mysqli_fetch_assoc($related_result)) { ?>
                      <a href="product-details.php?id=<?= $related['product_id'] ?>" class="related-card">
                        <img src="../../<?= htmlspecialchars($related['product_image']) ?>" alt="<?= htmlspecialchars($related['product_name']) ?>" />
                        <div class="related-info">
                          <h5><?= htmlspecialchars($related['product_name']) ?></h5>
                          <p><?= htmlspecialchars(substr($related['description'], 0, 50)) ?>...</p>
                          <span class="price">₱<?= number_format((float)$related['price'], 2) ?></span>
                        </div>
                      </a>
                    <?php }
                  } else {
                    echo "<p>No related products found.</p>";
                  }
                  mysqli_stmt_close($related_stmt);
                }
              } else {
                echo "<p>No product details available to show related items.</p>";
              }
              ?>
            </div>
          </div>
        </div>
        </div>
      </section>
    

    <!-- PRODUCT DETAILS -->



    <!-- BOOTSTRAP JS -->
    <script
      src="hhttps://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
      crossorigin="anonymous"
    ></script>

    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CHANGE IMAGE -->

    <script>
      function changeImage(element) {
        let mainImage = document.getElementById("mainImage");
        mainImage.style.opacity = "0";
        setTimeout(() => {
          mainImage.src = element.src;
          mainImage.style.opacity = "1";
        }, 300);
      }
    </script>

    <!-- ADD QUANTITY -->
    <script>
      function changeQty(value) {
        let qty = document.getElementById("quantity");
        let current = parseInt(qty.value);
        if (current + value >= 1) {
          qty.value = current + value;
        }
      }
    </script>

    <!-- DETAILS -->
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        
        const tabs = document.querySelectorAll(".tab-btn");
        const all_content = document.querySelectorAll(".content");

        tabs.forEach((tab, index) => {
          tab.addEventListener("click", (e) => {
            tabs.forEach((tab) => tab.classList.remove("active"));
            tab.classList.add("active");

            var line = document.querySelector(".line");
            if (line) {
              line.style.width = e.target.offsetWidth + "px";
              line.style.left = e.target.offsetLeft + "px";
            }

            all_content.forEach((content) =>
              content.classList.remove("active")
            );
            all_content[index].classList.add("active");
          });
        });
        // Add-to-cart handled globally in customer-dashboard include
      });


// Variations
document.addEventListener("DOMContentLoaded", function () {

  // 1. Prepare variations from PHP
  const variations = <?= json_encode($variations) ?> || [];
  const mainImage = document.getElementById("mainImage");
  const priceTag = document.getElementById("productPrice");
  const stockInfo = document.getElementById("stockInfo");
  const variationSelect = document.getElementById("variationSelect");
  const qtyInput = document.getElementById("quantity");
  const defaultImage = mainImage.dataset.default || mainImage.src;

  // 2. Change main image when a variation is selected
  if (variationSelect) {
    variationSelect.addEventListener("change", () => {
      const idx = parseInt(variationSelect.value);
      if (isNaN(idx)) return;
      const selected = variations[idx];
      if (!selected) return;

      mainImage.style.opacity = "0";
      setTimeout(() => {
        mainImage.src = selected.variant_image ? "../../" + selected.variant_image : defaultImage;
        mainImage.style.opacity = "1";
      }, 200);

      // Update price and stock
      priceTag.textContent = "₱" + parseFloat(selected.price).toFixed(2);
      stockInfo.textContent = "Stock: " + selected.stock_qty;
    });
  }

  // 3. Thumbnail click
  document.querySelectorAll(".thumb-image").forEach(img => {
    img.addEventListener("click", () => {
      const src = img.dataset.src;
      mainImage.style.opacity = "0";
      setTimeout(() => {
        mainImage.src = src;
        mainImage.style.opacity = "1";
      }, 200);
    });
  });

  // 4. Quantity buttons
  window.changeQty = function (val) {
    let current = parseInt(qtyInput.value) || 1;
    if (current + val >= 1) qtyInput.value = current + val;
  };

  // 5. Add to Cart
  const addToCartBtn = document.querySelector(".add-to-cart");
  if (addToCartBtn) {
    addToCartBtn.addEventListener("click", () => {
      const productId = parseInt(addToCartBtn.dataset.id);
      const productName = addToCartBtn.dataset.name;
      const storeName = addToCartBtn.dataset.store;
      const quantity = parseInt(qtyInput.value) || 1;

      // Determine selected variation
      let selectedVariation = null;
      if (variationSelect && variationSelect.value !== "" && !isNaN(variationSelect.value)) {
        selectedVariation = variations[parseInt(variationSelect.value)];
      }

      const item = {
        id: productId,
        name: productName,
        store: storeName,
        image: selectedVariation?.variant_image ? "../../" + selectedVariation.variant_image : addToCartBtn.dataset.image,
        quantity: quantity,
        price: selectedVariation ? parseFloat(selectedVariation.price) : parseFloat(addToCartBtn.dataset.price),
        size: selectedVariation ? selectedVariation.size : null
      };

      // Get existing cart
      let cart = JSON.parse(localStorage.getItem("cart") || "[]");

      // Merge if same product + variation exists
      const existIdx = cart.findIndex(c => c.id === item.id && c.size === item.size);
      if (existIdx > -1) {
        cart[existIdx].quantity += item.quantity;
      } else {
        cart.push(item);
      }

      // Save to localStorage
      localStorage.setItem("cart", JSON.stringify(cart));

      // Success alert
      Swal.fire({
        icon: "success",
        title: "Added to cart!",
        text: `${item.name}${item.size ? " (" + item.size + ")" : ""} x${item.quantity} has been added to your cart.`,
        timer: 1500,
        showConfirmButton: false
      });
    });
  }

  // 6. Buy Now
  window.buyNow = function () {
    addToCartBtn.click(); // Add to cart first
    window.location.href = "checkout.php";
  };

});

</script>


    </script>


        <?php include('../../includes/floating-button.php'); ?>
    <?php include('../../includes/footer.php'); ?>
  </body>
</html>
