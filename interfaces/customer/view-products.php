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

// Fetch product
$product = null;
$store_contact = null;
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
  $id = (int) $_GET['id'];
$stmt = mysqli_prepare($connection, "SELECT p.*, COALESCE(st.store_name, 'Superadmin') AS recipient, st.store_id AS store_id FROM products p LEFT JOIN store st ON p.owner_id = st.owner_id OR p.owner_id = st.super_id WHERE p.product_id = ? LIMIT 1");
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $product = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    // Fetch store contact information if product exists
    if ($product) {
      $store_name = $product['recipient'];
      $contact_stmt = mysqli_prepare($connection, "SELECT * FROM store_contacts WHERE store_name = ? LIMIT 1");
      if ($contact_stmt) {
        mysqli_stmt_bind_param($contact_stmt, 's', $store_name);
        mysqli_stmt_execute($contact_stmt);
        $contact_result = mysqli_stmt_get_result($contact_stmt);
        $store_contact = mysqli_fetch_assoc($contact_result);
        mysqli_stmt_close($contact_stmt);
      }
    }
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
            src="<?= $product ? '../../' . htmlspecialchars($product['product_image']) : '../../assets/banana-leaf.png' ?>"
            alt="Product Image"
            class="img-fluid rounded-4 shadow product-image"
            id="mainImage"
          />

          <div class="row mt-3">
            <div class="col-3">
              <img
                src="<?= $product ? '../../' . htmlspecialchars($product['product_image']) : '../../assets/banana-leaf.png' ?>"
                class="thumb-image"
                onclick="changeImage(this)"
              />
            </div>

            <div class="col-3">
              <img
                src="<?= $product ? '../../' . htmlspecialchars($product['product_image']) : '../../assets/banana-leaf.png' ?>"
                class="thumb-image"
                onclick="changeImage(this)"
              />
            </div>

            <div class="col-3">
              <img
                src="<?= $product ? '../../' . htmlspecialchars($product['product_image']) : '../../assets/banana-leaf.png' ?>"
                class="thumb-image"
                onclick="changeImage(this)"
              />
            </div>

            <div class="col-3">
              <img
                src="<?= $product ? '../../' . htmlspecialchars($product['product_image']) : '../../assets/banana-leaf.png' ?>"
                class="thumb-image"
                onclick="changeImage(this)"
              />
            </div>
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
            <span class="product-price">₱<?= $product ? number_format((float)$product['price'], 2) : '0.00' ?></span>
            <?php if ($product && !empty($product['old_price'])) { ?>
            <span class="old-price">₱<?= number_format((float)$product['old_price'], 2) ?></span>
            <?php } ?>
          </div>

          <!-- Quantity & Variation -->
          <div class="d-flex mb-3">
            <div class="qty-box">
              <button class="qty-btn" onclick="changeQty(-1)">−</button>
              <input
                type="text"
                id="quantity"
                class="qty-input"
                value="1"
                readonly
              />
              <button class="qty-btn" onclick="changeQty(1)">+</button>
            </div>

            <select class="variation form-select w-auto shadow-none">
              <option selected>Variation Size</option>
              <option value="1">Small</option>
              <option value="2">Medium</option>
              <option value="3">Large</option>
            </select>
          </div>

          <!-- Add to Cart Button -->
           <div class="d-flex flex-column gap-3 mt-4">
          <button 
            class="btn-add add-to-cart"
            data-id="<?= $product ? $product['product_id'] : '' ?>"
            data-name="<?= $product ? htmlspecialchars($product['product_name']) : '' ?>"
            data-price="<?= $product ? htmlspecialchars($product['price']) : '' ?>"
            data-image="<?= $product ? '../../' . htmlspecialchars($product['product_image']) : '' ?>"
            data-store="<?= $product ? htmlspecialchars($product['recipient']) : '' ?>"
            data-store-id="<?= $product ? htmlspecialchars($product['store_id'] ?? '') : '' ?>"
            data-qty-target="#quantity"
          ><i class="bi bi-bag-plus"></i> Add To Cart</button>
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
                      <h5><strong>Product Information</strong></h5>
                      <p><strong>Name:</strong> <?= htmlspecialchars($product['product_name']) ?></p>
                      <p><strong>Description:</strong> <?= htmlspecialchars($product['description']) ?></p>
                      <p><strong>Price:</strong> ₱<?= number_format((float)$product['price'], 2) ?></p>
                      <p><strong>Stock Quantity:</strong> <?= $product['stock_qty'] ?> pieces</p>
                      <p><strong>Status:</strong> 
                        <span class="badge <?= $product['status'] === 'available' ? 'bg-success' : 'bg-danger' ?>">
                          <?= ucfirst($product['status']) ?>
                        </span>
                      </p>
                      <p><strong>Store:</strong> <?= htmlspecialchars($product['recipient']) ?></p>
                      <p><strong>Date Added:</strong> <?= date('F j, Y', strtotime($product['date_added'])) ?></p>
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

      // Buy Now function
      function buyNow() {
        <?php if ($product): ?>
          const productId = <?= $product['product_id'] ?>;
          const quantity = document.getElementById('quantity').value;
          const productName = '<?= addslashes($product['product_name']) ?>';
          const price = <?= $product['price'] ?>;
          const image = '<?= addslashes($product['product_image']) ?>';
          const store = '<?= addslashes($product['recipient']) ?>';
          const storeId = '<?= $product['store_id'] ?? '' ?>';
          
          // Add item to cart first
          const cartItem = {
            id: productId,
            name: productName,
            price: price,
            image: '../../' + image,
            store: store,
            storeId: storeId,
            quantity: parseInt(quantity)
          };
          
          // Add to localStorage cart
          let cart = JSON.parse(localStorage.getItem('cart') || '[]');
          const existingItemIndex = cart.findIndex(item => item.id === productId && item.storeId === storeId);
          
          if (existingItemIndex > -1) {
            cart[existingItemIndex].quantity += parseInt(quantity);
          } else {
            cart.push(cartItem);
          }
          
          localStorage.setItem('cart', JSON.stringify(cart));
          
          // Redirect to checkout page
          window.location.href = 'checkout.php';
        <?php else: ?>
          alert('Product not available');
        <?php endif; ?>
      }
    </script>


        <?php include('../../includes/floating-button.php'); ?>
    <?php include('../../includes/footer.php'); ?>
  </body>
</html>
