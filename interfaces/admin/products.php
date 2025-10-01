<?php
$page_title = 'Products | Kesong Puti';
require '../../connection.php';
include ('../../includes/admin-dashboard.php'); 

$toast_message = '';

if (isset($_SESSION['toast_message'])) {
    $toast_message = $_SESSION['toast_message'];
    unset($_SESSION['toast_message']);
}

// Fetch products (filter by role)
if ($_SESSION['role'] === 'admin') {
    $owner_id = $_SESSION['admin_id'];
    $sql = "SELECT p.*, 
                   COALESCE(a.username, s.username) AS owner_username
            FROM products p
            LEFT JOIN admins a ON p.owner_id = a.admin_id
            LEFT JOIN super_admin s ON p.owner_id = s.super_id
            WHERE p.owner_id = '$owner_id'";
} else {
    // superadmin can see all
    $sql = "SELECT p.*, 
                   COALESCE(a.username, s.username) AS owner_username
            FROM products p
            LEFT JOIN admins a ON p.owner_id = a.admin_id
            LEFT JOIN super_admin s ON p.owner_id = s.super_id";
}

$result = mysqli_query($connection, $sql);

// ADD Product
if (isset($_POST['save_product'])) {
    $name = mysqli_real_escape_string($connection, $_POST['product_name']);
    $desc = mysqli_real_escape_string($connection, $_POST['description']);
    $category = mysqli_real_escape_string($connection, $_POST['category']);
    $price = $_POST['price'];
    $stock = $_POST['stock_qty'];

    if ($_SESSION['role'] === 'admin') {
        $owner_id = $_SESSION['admin_id'];
    } else {
        $owner_id = $_SESSION['super_id'];
    }

    $target_dir = "../../assets/";
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    $image = "/assets/" . basename($_FILES["product_image"]["name"]);

    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
        $insert = "INSERT INTO products 
                   (product_name, description, price, stock_qty, category, product_image, owner_id)
                   VALUES ('$name', '$desc', '$price', '$stock', '$category', '$image', '$owner_id')";
        mysqli_query($connection, $insert);
    }

    $_SESSION['toast_message'] = "Product added successfully!";
    echo "<script>location.href='products.php';</script>";
    exit();
}

// UPDATE Product
if (isset($_POST['update_product'])) {
    $id = intval($_POST['product_id']);
    $name = mysqli_real_escape_string($connection, $_POST['product_name']);
    $desc = mysqli_real_escape_string($connection, $_POST['description']);
    $category = mysqli_real_escape_string($connection, $_POST['category']);
    $price = $_POST['price'];
    $stock = $_POST['stock_qty'];

    $imageSql = "";
    if (!empty($_FILES["product_image"]["name"])) {
        $target_dir = "../../assets/";
        $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
        $image = "/assets/" . basename($_FILES["product_image"]["name"]);
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $imageSql = ", product_image='$image'";
        }
    }

    if ($_SESSION['role'] === 'admin') {
        $owner_id = $_SESSION['admin_id'];
        $update = "UPDATE products 
                   SET product_name='$name', description='$desc', price='$price', 
                       stock_qty='$stock', category='$category' $imageSql 
                   WHERE product_id='$id' AND owner_id='$owner_id'";
    } else {
        $update = "UPDATE products 
                   SET product_name='$name', description='$desc', price='$price', 
                       stock_qty='$stock', category='$category' $imageSql 
                   WHERE product_id='$id'";
    }

    mysqli_query($connection, $update);

    $_SESSION['toast_message'] = "Product updated successfully!";
    echo "<script>location.href='products.php';</script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products | Kesong Puti</title>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="../../css/admin.css"/>
</head>
<body>
  
      <!-- SHOP TAB -->
      <!-- PRODUCTS -->
 <div class="main-content">

      <div class="box content-shop" id="products-content" >
        <h1>Products Management</h1>

        <div class="filter-bar 1">
          <input
            type="text"
            id="productSearch"
            placeholder="Search product by name..."
          />



          <h2 id="productCount">Total Products: <span id="totalProducts">0</span></h2>

          <!-- Add Product Button -->
          
        </div>

        
<div class="filter-bar 2">
          <select id="categoryFilter">
            <option value="all">All Categories</option>
            <option value="cheese">Cheese</option>
            <option value="ice-cream">Ice Cream</option>
          </select>

        <button id="openAddProduct" class="btn-add">
            <i class="bi bi-plus-circle"></i> Add Product
          </button>
</div>    

     <!-- PRODUCTS -->
      <div class="product-grid" id="productGrid">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <div class="product-card" 
            data-name="<?= htmlspecialchars($row['product_name']) ?>" 
            data-category="<?= htmlspecialchars(strtolower($row['category'])) ?>">
            <img src="../../<?= htmlspecialchars($row['product_image'] ?: 'assets/default.png') ?>" alt="Product Image">
            <div class="product-info">
            <h3 class="card-title"><?= htmlspecialchars($row['product_name']) ?></h3>
            <p>₱<?= number_format($row['price'], 2) ?></p>
            
            <button 
              type="button" 
              class="view-btn" 
              data-id="<?= $row['product_id'] ?>" 
              data-name="<?= htmlspecialchars($row['product_name']) ?>" 
              data-desc="<?= htmlspecialchars($row['description']) ?>" 
              data-price="<?= htmlspecialchars($row['price']) ?>" 
              data-stock="<?= htmlspecialchars($row['stock_qty']) ?>" 
              data-category="<?= htmlspecialchars($row['category']) ?>" 
              data-image="../../<?= htmlspecialchars($row['product_image'] ?: 'assets/default.png') ?>">
              View Details
            </button>

               <!-- Delete Product -->
              <form method="POST" action="delete-products.php" class="delete-form" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['product_id'] ?>">
                <button type="button" class="delete-btn btn-delete">
                  <i class="bi bi-trash-fill"></i>
                </button>
            </form>
            </div>
          </div>
        <?php } ?>
      </div>

    <!-- Add/Edit Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="productForm" method="POST" enctype="multipart/form-data" action="products.php">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Hidden ID -->
          <input type="hidden" name="product_id" id="product_id">

          <!-- Product Name -->
          <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" class="form-control" name="product_name" id="product_name" required>
          </div>

          <!-- Product Description -->
          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
          </div>

          <!-- Product Category -->
          <div class="mb-3">
            <label class="form-label">Category</label>
            <select class="form-select" name="category" id="category" required>
              <option value="" selected disabled>-- Select Category --</option>
              <option value="cheese">Cheese</option>
              <option value="ice-cream">Ice Cream</option>
              <option value="other">Other</option>
            </select>
          </div>

          <!-- Price -->
          <div class="mb-3" id="mainPriceField">
            <label class="form-label">Price</label>
            <input type="number" class="form-control" name="price" id="price" step="0.01" min="0">
          </div>

          <!-- Has Variants -->
          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="hasVariants" onchange="toggleVariants()">
            <label class="form-check-label" for="hasVariants">This product has variants</label>
          </div>

          <!-- Quantity -->
          <div class="mb-3" id="mainQuantityField">
            <label class="form-label">Quantity</label>
            <input type="number" class="form-control" name="stock_qty" id="stock_qty" min="0">
          </div>

          <!-- Total Stock & Price Range -->
          <div class="mb-3 d-none" id="totalStockField">
            <label class="form-label">Total Stock</label>
            <input type="number" class="form-control" id="totalStock" readonly>
          </div>
          <div class="mb-3 d-none" id="priceRangeField">
            <label class="form-label">Price Range</label>
            <input type="text" class="form-control" id="priceRange" readonly>
          </div>

          <!-- Primary Image -->
          <div class="mb-3">
            <label class="form-label">Primary Image</label>
            <input type="file" class="form-control" name="product_image" id="product_image" accept="image/*" onchange="previewPrimary(event)">
            <div id="primaryPreview" class="mt-2"></div>
          </div>

          <!-- Gallery Images -->
          <div class="mb-3">
            <label class="form-label">Gallery Images</label>
            <input type="file" class="form-control" accept="image/*" multiple onchange="previewGallery(event)">
            <div id="galleryPreview" class="gallery-preview"></div>
          </div>

          <!-- Variants -->
          <div class="mb-3 d-none" id="variantSection">
            <label class="form-label">Variants</label>
            <table class="table table-sm table-bordered align-middle" id="variantTable">
              <thead class="table-light">
                <tr>
                  <th>Variant</th>
                  <th>Price</th>
                  <th>Stock</th>
                  <th>Image</th>
                  <th>Preview</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addVariant()">+ Add Variant</button>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" name="save_product" id="submitBtn" class="btn btn-success">Save Product</button>
        </div>
      </form>
    </div>
  </div>
</div>

      
      
      <!-- View Product Modal -->
      <div class="product-modal" id="viewProductModal" data-id="">
        <div class="modal-content">
          <span class="close-modal-view">&times;</span>
          <h2>Product Details</h2>
          <p><strong>Name:</strong> <span id="view_product_name"></span></p>
          <p><strong>Description:</strong> <span id="view_description"></span></p>
          <p><strong>Price:</strong> <span id="view_price"></span></p>
          <p><strong>Stock:</strong> <span id="view_stock_qty"></span></p>
          <p><strong>Category:</strong> <span id="view_category"></span></p>
          <img id="view_image" src="" alt="Product Image" />

          <button id="editFromViewBtn">Edit</button>
        </div>
      </div>


  <!-- FUNCTIONS -->

    <!--Search and Filter-->

      <script>
      document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("productSearch");
        const categoryFilter = document.getElementById("categoryFilter");
        const productCards = document.querySelectorAll(".product-card");
        const productCountEl = document.getElementById("productCount");
        const totalProductsEl = document.getElementById("totalProducts");

        function filterProducts() {
          const searchValue = searchInput.value.toLowerCase();
          const categoryValue = categoryFilter.value.toLowerCase();

          let visibleCount = 0;

          productCards.forEach(card => {
            const name = card.querySelector(".card-title").textContent.toLowerCase();
            const category = card.getAttribute("data-category");

            const matchesSearch = name.includes(searchValue);
            const matchesCategory = (categoryValue === "all" || category === categoryValue);

            if (matchesSearch && matchesCategory) {
              card.style.display = "block";
              visibleCount++;
            } else {
              card.style.display = "none";
            }
          });

          // Update the counter or show "No products found"
          if (visibleCount > 0) {
            productCountEl.innerHTML = `Total Products: <span id="totalProducts">${visibleCount}</span>`;
          } else {
            productCountEl.textContent = "No products found";
          }
        }

        // Run filter initially (so it counts all products on load)
        filterProducts();

        // Run filter when typing or selecting
        searchInput.addEventListener("input", filterProducts);
        categoryFilter.addEventListener("change", filterProducts);
      });

    </script>


    <!-- Add Product Modal -->

      <script>
        // Add/Edit Product Modal
        const modal = document.getElementById("productModal");  
        const openBtn = document.getElementById("openAddProduct");
        const closeBtn = document.querySelector(".close-modal");

        openBtn.onclick = () => { 
          // reset modal for adding
          document.getElementById('modalTitle').innerText = "Add Product";
          document.getElementById('submitBtn').name = "save_product";
          document.getElementById('submitBtn').innerText = "Save";

          // clear form fields
          document.getElementById('product_id').value = "";
          document.getElementById('product_name').value = "";
          document.getElementById('description').value = "";
          document.getElementById('price').value = "";
          document.getElementById('stock_qty').value = "";
          document.getElementById('category').value = "";
          document.getElementById('product_image').value = "";
          document.getElementById('previewImage').style.display = "none";

          modal.style.display = "flex"; 
        };

        closeBtn.onclick = () => { modal.style.display = "none"; };
        window.onclick = (e) => { if (e.target === modal) modal.style.display = "none"; };

      </script>

       <!-- SweetAlert2 Library -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <script>
          var toastMessage = "<?php echo $toast_message; ?>";
          if (toastMessage) {
              Swal.fire({
                  icon: 'info',
                  text: toastMessage,
                  confirmButtonColor: '#ff6b6b'
              });
          }
      </script> 
        
        <script>
          document.querySelectorAll('.delete-btn').forEach(btn => {
              btn.addEventListener('click', function() {
                  let form = this.closest('form');
                  Swal.fire({
                      title: 'Are you sure?',
                      text: "This product will be permanently deleted.",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#6c757d',
                      confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          // Add hidden field to trigger PHP delete
                          let input = document.createElement("input");
                          input.type = "hidden";
                          input.name = "delete_product";
                          input.value = "1";
                          form.appendChild(input);
                          form.submit();
                      }
                  });
              });
          });
        </script>

     <!-- View Details Product Modal -->       
      <script>
        // View Modal
        const viewModal = document.getElementById("viewProductModal");
        const closeViewBtn = document.querySelector(".close-modal-view");

        document.querySelectorAll(".view-btn").forEach(btn => {
          btn.addEventListener("click", function() {
            // Fill content
            viewModal.dataset.id = this.dataset.id;
            document.getElementById("view_product_name").textContent = this.dataset.name;
            document.getElementById("view_description").textContent = this.dataset.desc;
            document.getElementById("view_price").textContent = "₱" + parseFloat(this.dataset.price).toFixed(2);
            document.getElementById("view_stock_qty").textContent = this.dataset.stock;
            document.getElementById("view_category").textContent = this.dataset.category;
            document.getElementById("view_image").src = this.dataset.image;

            viewModal.style.display = "flex";
          });
        });

        closeViewBtn.onclick = () => { viewModal.style.display = "none"; };
        window.onclick = (e) => { if (e.target === viewModal) viewModal.style.display = "none"; };

      </script>

     <!-- Edit Product Modal -->
    <script>

      // Edit button inside View Modal
      document.getElementById('editFromViewBtn').addEventListener('click', function() {
        // Close the view modal
        document.getElementById('viewProductModal').style.display = "none";

        // Switch modal title and button
        document.getElementById('modalTitle').innerText = "Edit Product";
        document.getElementById('submitBtn').name = "update_product"; 
        document.getElementById('submitBtn').innerText = "Update Product";

        // Fill the fields with View modal values
        document.getElementById('product_id').value = document.getElementById('viewProductModal').dataset.id;
        document.getElementById('product_name').value = document.getElementById('view_product_name').innerText;
        document.getElementById('description').value = document.getElementById('view_description').innerText;
        document.getElementById('price').value = document.getElementById('view_price').innerText.replace('₱','');
        document.getElementById('stock_qty').value = document.getElementById('view_stock_qty').innerText;
        
        // Get category text from View Modal
        let categoryText = document.getElementById('view_category').innerText.trim().toLowerCase();

        // Loop through select options and match
        let categorySelect = document.getElementById('category');
        for (let i = 0; i < categorySelect.options.length; i++) {
          if (categorySelect.options[i].text.toLowerCase() === categoryText) {
            categorySelect.selectedIndex = i;
            break;
          }
        }


        let imgSrc = document.getElementById('view_image').src;
        if (imgSrc) {
          document.getElementById('previewImage').src = imgSrc;
          document.getElementById('previewImage').style.display = "block";
        }

        // Show Add/Edit modal
        document.getElementById('productModal').style.display = "flex";
      });

      
      </script>

    </body>
    </html>


