<?php
require '../../connection.php';


// Ensure admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: ../../login.php");
    exit;
}

// Toast message setup
$toast_message = '';
if (isset($_SESSION['toast_message'])) {
    $toast_message = $_SESSION['toast_message'];
    unset($_SESSION['toast_message']);
}

$admin_id = $_SESSION['admin_id'];

// Fetch store name for display
$store_name = '';
$storeQuery = $connection->prepare("SELECT store_name FROM store WHERE owner_id = ? LIMIT 1");
$storeQuery->bind_param("i", $admin_id);
$storeQuery->execute();
$storeQuery->bind_result($store_name);
$storeQuery->fetch();
$storeQuery->close();
if (!$store_name) $store_name = 'Admin';

// -------------------------
// AJAX: Add Category
// -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_category') {
    header('Content-Type: application/json');
    ob_clean();

    $category_name = trim($_POST['category_name'] ?? '');
    if ($category_name === '') {
        echo json_encode(['success' => false, 'message' => 'Category name is required.']);
        exit;
    }

    // Check duplicate (case-insensitive)
    $stmt = $connection->prepare("SELECT category_id FROM categories WHERE LOWER(category_name) = LOWER(?) AND owner_id = ? LIMIT 1");
    $stmt->bind_param('si', $category_name, $admin_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Category already exists.']);
        exit;
    }

    $stmt = $connection->prepare("INSERT INTO categories (category_name, owner_id, owner_type, created_at) VALUES (?, ?, 'admin', NOW())");
    $stmt->bind_param('si', $category_name, $admin_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id, 'name' => $category_name]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }
    exit;
}

// -------------------------
// AJAX: Fetch Categories
// -------------------------
if (isset($_GET['action']) && $_GET['action'] === 'fetch_categories') {
    header('Content-Type: application/json');
    $cats = [];
    $res = $connection->query("SELECT category_id, category_name FROM categories WHERE owner_type='admin' AND owner_id={$admin_id} ORDER BY category_name ASC");
    while ($row = $res->fetch_assoc()) $cats[] = ['id' => $row['category_id'], 'name' => $row['category_name']];
    echo json_encode(['success' => true, 'categories' => $cats]);
    exit;
}

// -------------------------
// AJAX: Fetch Product Details
// -------------------------
if (isset($_GET['action']) && $_GET['action'] === 'fetch_product_details') {
    header('Content-Type: application/json');
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
        exit;
    }

    $gallery = [];
    $stmt = $connection->prepare("SELECT image_path FROM product_gallery WHERE product_id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) $gallery[] = $row;
    $stmt->close();

    $variants = [];
    $stmt2 = $connection->prepare("SELECT size, price, stock_qty, variant_image FROM product_variations WHERE product_id = ?");
    $stmt2->bind_param('i', $id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    while ($row2 = $res2->fetch_assoc()) $variants[] = $row2;
    $stmt2->close();

    echo json_encode(['success' => true, 'gallery' => $gallery, 'variants' => $variants]);
    exit;
}

// -------------------------
// AJAX: Add Product
// -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
    header('Content-Type: application/json');
    ob_clean();

    $name = trim($_POST['product_name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $cat_id = intval($_POST['category_id'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock_qty'] ?? 0);
    $status = $_POST['status'] ?? 'available';

    if (!$name || !$cat_id) {
        echo json_encode(['success' => false, 'message' => 'Product name and category required.']);
        exit;
    }

    // Main image upload
    $main_img_path = '';
    if (!empty($_FILES['product_image']['name'])) {
        $fname = uniqid('prod_') . "_" . basename($_FILES['product_image']['name']);
        $target = __DIR__ . "/../../assets/" . $fname;
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target)) {
            $main_img_path = "assets/" . $fname;
        }
    }

    // Insert product
    $stmt = $connection->prepare(
        "INSERT INTO products (product_name, description, price, stock_qty, product_image, category_id, owner_id, owner_type, date_added, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'admin', NOW(), ?)"
    );
    $stmt->bind_param('ssdisiss', $name, $desc, $price, $stock, $main_img_path, $cat_id, $admin_id, $status);

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        exit;
    }

    $product_id = $stmt->insert_id;

    // Gallery upload
    if (!empty($_FILES['gallery_images']['name'][0])) {
        $targetDir = __DIR__ . '/../../uploads/products/gallery/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
            $gallery_image = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', basename($_FILES['gallery_images']['name'][$key]));
            $targetPath = $targetDir . $gallery_image;
            if (move_uploaded_file($tmp_name, $targetPath)) {
                $img_db_path = "uploads/products/gallery/" . $gallery_image;
                $connection->query("INSERT INTO product_gallery (product_id, image_path) VALUES ($product_id, '$img_db_path')");
            }
        }
    }

    // Variants
    if (!empty($_POST['variant_size']) && is_array($_POST['variant_size'])) {
        $targetDir = __DIR__ . "/../../uploads/products/variants/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        foreach ($_POST['variant_size'] as $i => $size) {
            $size = trim($size);
            if ($size === '') continue;

            $var_price = floatval($_POST['variant_price'][$i] ?? 0);
            $var_stock = intval($_POST['variant_stock'][$i] ?? 0);
            $variant_image_path = '';

            if (isset($_FILES['variant_image']['name'][$i]) && $_FILES['variant_image']['name'][$i] !== '') {
                $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $_FILES['variant_image']['name'][$i]);
                $targetPath = $targetDir . $fileName;

                if (move_uploaded_file($_FILES['variant_image']['tmp_name'][$i], $targetPath)) {
                    $variant_image_path = "uploads/products/variants/" . $fileName;
                }
            }

            $stmt3 = $connection->prepare("INSERT INTO product_variations (product_id, size, price, stock_qty, variant_image) VALUES (?, ?, ?, ?, ?)");
            $stmt3->bind_param("isdis", $product_id, $size, $var_price, $var_stock, $variant_image_path);
            $stmt3->execute();
            $stmt3->close();
        }
    }

    echo json_encode(['success' => true, 'message' => 'Product added successfully.']);
    exit;
}

// -------------------------
// Page HTML
// -------------------------
$page_title = 'Products | Kesong Puti';
include ('../../includes/admin-dashboard.php');

// Fetch admin's products
$sql = "SELECT p.*, c.category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.owner_id = ? ORDER BY p.product_id DESC";
$stmt = $connection->prepare($sql);
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$result = $stmt->get_result();

// Categories and stores for filter
$categories = $connection->query("SELECT * FROM categories WHERE owner_type='admin' AND owner_id={$admin_id} ORDER BY category_name ASC");
$storeResult = $connection->query("SELECT store_name FROM store WHERE owner_id={$admin_id}");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products | Kesong Puti</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="../../css/admin.css"/>
</head>
<body>
  
<div class="main-content">
  <div class="box content-shop" id="products-content">
    <h1>Products Management</h1>
    <div class="filter-bar 1">
      <input type="text" id="productSearch" placeholder="Search product by name..."/>
      <h2 id="productCount">Total Products: <span id="totalProducts"><?= mysqli_num_rows($result) ?></span></h2>
    </div>
    <div class="filter-bar 2">
      <select id="categoryFilter">
        <option value="all">All Categories</option>
        <?php while($cat=mysqli_fetch_assoc($categories)){ ?>
          <option value="<?= htmlspecialchars(strtolower($cat['category_name'])) ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
        <?php } ?>
      </select>
      <select id="storeFilter">
        <option value="all">All Stores</option>
        <?php while($store=mysqli_fetch_assoc($storeResult)){ ?>
          <option value="<?= htmlspecialchars(strtolower($store['store_name'])) ?>"><?= htmlspecialchars($store['store_name']) ?></option>
        <?php } ?>
      </select>
      <button id="openAddProduct" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Product</button>
      <button type="button" class="btn btn-secondary" id="openCategoryModal">
      <i class="bi bi-plus-circle"></i> Add Category
    </button>
    </div>
    <div class="product-grid" id="productGrid">
      <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $product_id   = $row['product_id'];
            $product_name = htmlspecialchars($row['product_name'] ?? '');
            $desc         = htmlspecialchars($row['description'] ?? '');
            $img          = !empty($row['product_image']) ? '../../' . $row['product_image'] : '../../assets/default.png';
            $category     = htmlspecialchars($row['category_name'] ?? '');

            // Fetch variant prices for this product
            $variant_prices = [];
            $vstmt = $connection->prepare("SELECT price FROM product_variations WHERE product_id = ?");
            $vstmt->bind_param('i', $product_id);
            $vstmt->execute();
            $vres = $vstmt->get_result();
            while ($v = $vres->fetch_assoc()) {
                $variant_prices[] = (float)$v['price'];
            }
            $vstmt->close();

            // Determine display price 
            if (count($variant_prices) > 1) {
                $min = min($variant_prices);
                $max = max($variant_prices);
                $price_display = "₱" . number_format($min, 2) . " - ₱" . number_format($max, 2);
            } elseif (count($variant_prices) === 1) {
                $price_display = "₱" . number_format($variant_prices[0], 2);
            } else {
                $price_display = "₱" . number_format($row['price'] ?? 0, 2);
            }
        ?>
        <div class="product-card"
          data-name="<?= htmlspecialchars($row['product_name']) ?>"
          data-category="<?= htmlspecialchars(strtolower($row['category_name']??'')) ?>"
          data-store="">
          <img src="../../<?= htmlspecialchars($row['product_image'] ?: 'assets/default.png') ?>" alt="Product Image">
          
          <div class="product-info">
            <h3 class="card-title"><?= htmlspecialchars($row['product_name']) ?></h3>
            <p class="admin-label">Added by: <?= htmlspecialchars($store_name) ?></p>
             <p class="product-price"><?php echo $price_display; ?></p>
            <button type="button" class="view-btn"
              data-id="<?= $row['product_id'] ?>"
              data-name="<?= htmlspecialchars($row['product_name']) ?>"
              data-desc="<?= htmlspecialchars($row['description']) ?>"
              data-price="<?= htmlspecialchars($row['price']) ?>"
              data-stock="<?= htmlspecialchars($row['stock_qty']) ?>"
              data-category="<?= htmlspecialchars($row['category_name']??'') ?>"
              data-image="../../<?= htmlspecialchars($row['product_image'] ?: 'assets/default.png') ?>"
              data-admin="<?= htmlspecialchars($store_name) ?>">View Details</button>
            <button class="delete-btn btn btn-outline-danger btn-sm" data-id="<?= $row['product_id'] ?>" title="Delete Product">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>

<!-- Add Product Modal -->
<div id="addProductModal" class="custom-modal">
  <div class="custom-modal-content">
    <form id="productForm" enctype="multipart/form-data" autocomplete="off">
      <input type="hidden" name="action" value="add_product" />
      <div class="custom-modal-header">
        <span class="modal-title">Add Product</span>
        <button type="button" class="btn-close" id="closeProductModal">&times;</button>
      </div>

      <div class="custom-modal-body">
        <label class="form-label">Product Name</label>
        <input type="text" class="form-control" name="product_name" required />

        <label class="form-label">Description</label>
        <textarea class="form-control" name="description" rows="3" placeholder="Enter product description..."></textarea>

        <label class="form-label">Category</label>
        <div class="category-row">
          <select class="form-select" name="category_id" id="categorySelect" required>
            <option value="" disabled selected>-- Select Category --</option>
          </select>
        </div>

        <label class="form-label">Price</label>
        <input type="number" class="form-control" name="price" step="0.01" min="0" id="mainPriceField" />

        <div class="toggle-row">
          <label class="switch">
            <input type="checkbox" id="hasVariants" />
            <span class="slider round"></span>
          </label>
          <span class="switch-label">This product has variants (sizes/colors)</span>
        </div>

        <label class="form-label">Quantity</label>
        <input type="number" class="form-control" name="stock_qty" min="0" id="mainQuantityField" />

        <label class="form-label">Primary Image</label>
        <input type="file" class="form-control" name="product_image" accept="image/*" />
        <div id="primaryPreview" class="mt-2"></div>

        <label class="form-label">Gallery Images</label>
        <input type="file" class="form-control" name="gallery_images[]" accept="image/*" multiple />
        <div id="galleryPreview" class="gallery-preview"></div>

        <!-- Variants Section -->
        <div id="variantsSection" style="display:none;">
          <label class="form-label">Total Stock</label>
          <input type="number" class="form-control" id="totalStock" readonly />

          <label class="form-label">Price Range</label>
          <input type="text" class="form-control" id="priceRange" readonly />

          <label class="form-label">Variants</label>
          <table class="table" id="variantTable">
            <thead>
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
          <button type="button" class="btn btn-secondary btn-sm" id="addVariantBtn">+ Add Variant</button>
        </div>
      </div>

      <div class="custom-modal-footer">
        <button type="submit" class="btn btn-success">Save Product</button>
      </div>
    </form>
  </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="custom-modal">
  <div class="custom-modal-content">
    <form id="categoryForm">
      <div class="custom-modal-header">
        <span class="modal-title">Add Category</span>
        <button type="button" class="btn-close" id="closeCategoryModal">&times;</button>
      </div>
      <div class="custom-modal-body">
        <label class="form-label">Category Name</label>
        <input type="text" id="categoryName" class="form-control" placeholder="Enter category name..." required />
      </div>
      <div class="custom-modal-footer">
        <button type="submit" class="btn btn-success">Save Category</button>
      </div>
    </form>
  </div>
</div>

<!-- VIEW PRODUCT DETAILS MODAL -->
<div id="viewProductModal" class="custom-modal">
  <div class="custom-modal-content">
    <div class="custom-modal-header">
      <span class="modal-title" id="viewProductTitle">Product Details</span>
      <button type="button" class="btn-close" id="closeViewProductModal">&times;</button>
    </div>
    <div class="custom-modal-body" id="viewProductBody">
      <div class="product-details-container">
        <img id="viewProductImage" class="main-product-image" src="" alt="Product Image" />

        <div class="details-info">
          <h3><strong><span id="viewProductName"></span></strong></h3>
          <p><strong>Category:</strong> <span id="viewProductCategory"></span></p>
          <p><strong>Description:</strong> <span id="viewProductDesc"></span></p>
          <p><strong>Price:</strong> <span id="viewProductPrice"></span></p>
          <p><strong>Stock:</strong> <span id="viewProductStock"></span></p>
          <p><strong>Added by:</strong> <span id="viewProductAdmin"></span></p>
        </div>
      </div>

      <hr>

      <!-- Gallery -->
      <div id="viewGallery" class="gallery-section">
        <h4>Gallery Images</h4>
        <div id="galleryImages" class="gallery-preview"></div>
      </div>

      <hr>

      <!-- Variants -->
      <div id="variantSection" class="variant-section" style="display:none;">
        <h4>Variants</h4>
        <div class="variant-dropdown-container">
          <label for="variantSelect">Select Variant:</label>
          <select id="variantSelect" class="form-select">
            <option value="">-- Select a variant --</option>
          </select>
        </div>

        <div class="variant-info mt-3">
          <p><strong>Variant Price:</strong> <span id="variantPrice">₱0.00</span></p>
          <p><strong>Available Stock:</strong> <span id="variantStock">0</span></p>
          <div id="variantImagePreview"></div>
        </div>
      </div>
  </div>
</div>

<!-- FUNCTIONS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Basic element refs used by multiple handlers
const productSearch = document.getElementById('productSearch');
const categoryFilter = document.getElementById('categoryFilter');
const storeFilter = document.getElementById('storeFilter');
const productCards = document.querySelectorAll('.product-card');

function filterProducts() {
  const searchValue = productSearch.value.toLowerCase();
  const categoryValue = categoryFilter.value.toLowerCase();
  const storeValue = storeFilter.value.toLowerCase();
  let visibleCount = 0;

  productCards.forEach(card => {
    const name = card.querySelector('.card-title').textContent.toLowerCase();
    const category = card.getAttribute('data-category');
    const store = card.getAttribute('data-store');

    const matchesSearch = name.includes(searchValue);
    const matchesCategory = (categoryValue === 'all' || category === categoryValue);
    const matchesStore = (storeValue === 'all' || store === storeValue);

    if (matchesSearch && matchesCategory && matchesStore) {
      card.style.display = 'block';
      visibleCount++;
    } else {
      card.style.display = 'none';
    }
  });

  const productCountEl = document.getElementById('productCount');
  if (visibleCount > 0) {
    productCountEl.innerHTML = `Total Products: <span id="totalProducts">${visibleCount}</span>`;
  } else {
    productCountEl.textContent = 'No products found';
  }
}

// Run on load
document.addEventListener('DOMContentLoaded', () => {
  filterProducts();
  productSearch.addEventListener('input', filterProducts);
  categoryFilter.addEventListener('change', filterProducts);
  storeFilter.addEventListener('change', filterProducts);
});

// Modal open/close for Add Product
const addProductModal = document.getElementById('addProductModal');
const openAddProductBtn = document.getElementById('openAddProduct');
const closeProductModalBtn = document.getElementById('closeProductModal');
openAddProductBtn && (openAddProductBtn.onclick = () => { addProductModal.classList.add('show'); fetchCategories(); resetModal(); });
closeProductModalBtn && (closeProductModalBtn.onclick = () => { addProductModal.classList.remove('show'); });
window.addEventListener('click', (e) => { if (e.target === addProductModal) addProductModal.classList.remove('show'); });

// Fetch categories into the product form select
async function fetchCategories(selectedId = null){
  try {
    const res = await fetch('products.php?action=fetch_categories');
    const data = await res.json();
    const sel = document.getElementById('categorySelect');
    sel.innerHTML = '<option value="" disabled selected>-- Select Category --</option>';
    data.categories.forEach(cat => {
      const opt = document.createElement('option');
      opt.value = cat.id;
      opt.textContent = cat.name;
      if (selectedId && cat.id == selectedId) opt.selected = true;
      sel.appendChild(opt);
    });
  } catch (err) {
    console.error('Failed to load categories', err);
  }
}

// Category modal handlers
const categoryModal = document.getElementById('addCategoryModal');
const openCategoryBtn = document.getElementById('openCategoryModal');
const closeCategoryBtn = document.getElementById('closeCategoryModal');
const categoryForm = document.getElementById('categoryForm');
openCategoryBtn && openCategoryBtn.addEventListener('click', () => { categoryModal.classList.add('show'); document.body.style.overflow = 'hidden'; });
closeCategoryBtn && closeCategoryBtn.addEventListener('click', () => { categoryModal.classList.remove('show'); document.body.style.overflow = ''; });
window.addEventListener('click', (e) => { if (e.target === categoryModal) { categoryModal.classList.remove('show'); document.body.style.overflow = ''; } });

categoryForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  const name = document.getElementById('categoryName').value.trim();
  if (!name) { Swal.fire({ icon: 'warning', title: 'Missing Field', text: 'Please enter a category name.' }); return; }
  const fd = new FormData();
  fd.append('action', 'add_category');
  fd.append('category_name', name);
  try {
    const res = await fetch('products.php', { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      await fetchCategories(data.id);
      Swal.fire({ icon: 'success', title: 'Category Added', text: 'Category added successfully!' });
      categoryForm.reset();
      categoryModal.classList.remove('show');
      document.body.style.overflow = '';
    } else {
      Swal.fire({ icon: 'error', title: 'Error', text: data.message });
    }
  } catch (err) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'An error occurred.' });
  }
});

// Variants logic
const variantToggle = document.getElementById('hasVariants');
const variantsSection = document.getElementById('variantsSection');
const mainPriceField = document.getElementById('mainPriceField');
const mainQuantityField = document.getElementById('mainQuantityField');
variantToggle && (variantToggle.onchange = ()=>{
  if(variantToggle.checked){
    variantsSection.style.display = 'block';
    mainPriceField.style.display = 'none';
    mainQuantityField.style.display = 'none';
  }else{
    variantsSection.style.display = 'none';
    mainPriceField.style.display = '';
    mainQuantityField.style.display = '';
  }
});

let variantTableBody = document.getElementById('variantTable').querySelector('tbody');
const addVariantBtn = document.getElementById('addVariantBtn');
addVariantBtn && (addVariantBtn.onclick = () => {
  let row = document.createElement('tr');
  let idx = variantTableBody.rows.length;
  row.innerHTML = `
    <td><input type="text" name="variant_size[]" class="form-control" required></td>
    <td><input type="number" name="variant_price[]" class="form-control variantPrice" step="0.01" min="0" value="0" oninput="updatePriceRange()" required></td>
    <td><input type="number" name="variant_stock[]" class="form-control variantStock" min="0" value="0" oninput="updateTotalStock()" required></td>
    <td><input type="file" name="variant_image[]" accept="image/*" class="form-control" onchange="previewVariant(event,${idx})"></td>
    <td><img id="variantPreview${idx}" class="image-preview"></td>
    <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();updateTotalStock();updatePriceRange();">Delete</button></td>
  `;
  variantTableBody.appendChild(row);
});

window.previewVariant = function(event, idx) {
  const file = event.target.files[0];
  const preview = document.getElementById('variantPreview'+idx);
  preview.src = file ? URL.createObjectURL(file) : '';
};
window.updateTotalStock = function() {
  const stocks = document.querySelectorAll('.variantStock');
  let total = 0;
  stocks.forEach(input => total += parseInt(input.value || 0, 10));
  document.getElementById('totalStock').value = total;
};
window.updatePriceRange = function() {
  const prices = document.querySelectorAll('.variantPrice');
  let values = [];
  prices.forEach(input => {
    let val = parseFloat(input.value || 0);
    if (!isNaN(val)) values.push(val);
  });
  if (values.length === 0) { document.getElementById('priceRange').value = ""; return; }
  const min = Math.min(...values);
  const max = Math.max(...values);
  document.getElementById('priceRange').value = (min === max) ? `₱${min.toFixed(2)}` : `₱${min.toFixed(2)} – ₱${max.toFixed(2)}`;
};

// Image previews and gallery selection
const primaryInput = document.querySelector('input[name="product_image"]');
const primaryPreview = document.getElementById('primaryPreview');
primaryInput && (primaryInput.onchange = function(e){
  let file = this.files[0];
  primaryPreview.innerHTML = '';
  if(file){
    let img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.classList.add('image-preview');
    primaryPreview.appendChild(img);
  }
});

const galleryInput = document.querySelector('input[name="gallery_images[]"]');
const galleryPreview = document.getElementById('galleryPreview');
let selectedImages = [];

if (galleryInput) {
  galleryInput.addEventListener('change', function () {
    const newFiles = Array.from(this.files);
    selectedImages = [...selectedImages, ...newFiles];
    renderGallery();
  });
}

function renderGallery() {
  galleryPreview.innerHTML = '';
  selectedImages.forEach((file, index) => {
    const div = document.createElement('div');
    div.classList.add('gallery-item');
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.classList.add('image-preview');
    const removeBtn = document.createElement('button');
    removeBtn.innerHTML = '×';
    removeBtn.classList.add('remove-btn');
    removeBtn.onclick = () => { selectedImages.splice(index, 1); renderGallery(); };
    div.appendChild(img);
    div.appendChild(removeBtn);
    galleryPreview.appendChild(div);
  });
  const dataTransfer = new DataTransfer();
  selectedImages.forEach(file => dataTransfer.items.add(file));
  galleryInput.files = dataTransfer.files;
}

function resetModal(){
  document.getElementById('productForm').reset();
  variantTableBody.innerHTML = '';
  primaryPreview.innerHTML = '';
  galleryPreview.innerHTML = '';
  selectedImages = [];
  variantsSection.style.display = 'none';
  mainPriceField.style.display = '';
  mainQuantityField.style.display = '';
}


// Submit product form (POST to same file - products.php)
const productForm = document.getElementById('productForm');
productForm && productForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(productForm);

  try {
    const res = await fetch('products.php', { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      Swal.fire({ icon: 'success', title: 'Success!', text: data.message, timer: 1500, showConfirmButton: false }).then(()=> location.reload());
    } else {
      Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Something went wrong.' });
    }
  } catch (err) {
    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to submit product. Please try again.' });
  }
});

// Delete product (keeps external delete endpoint)
document.querySelectorAll('.delete-btn').forEach(btn => {
  btn.addEventListener('click', function() {
    const id = this.dataset.id;
    const productCard = this.closest('.product-card');
    Swal.fire({ title: 'Are you sure?', text: "This product will be permanently deleted.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete it!', cancelButtonText: 'Cancel' }).then((result) => {
      if (result.isConfirmed) {
        fetch('delete-products.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'id=' + encodeURIComponent(id) })
        .then(response => response.ok ? response.text() : Promise.reject('Error'))
        .then(() => { Swal.fire({ icon: 'success', title: 'Deleted!', text: 'The product has been deleted.', timer: 1500, showConfirmButton: false }).then(()=> location.reload()); productCard.remove(); })
        .catch(() => Swal.fire({ icon: 'error', title: 'Oops...', text: 'An error occurred while deleting the product.' }));
      }
    });
  });
});

// View product details - fetches from this file
const viewModal = document.getElementById('viewProductModal');
const closeViewModal = document.getElementById('closeViewProductModal');
closeViewModal && (closeViewModal.onclick = () => viewModal.classList.remove('show'));
window.addEventListener('click', (e) => { if (e.target === viewModal) viewModal.classList.remove('show'); });

document.querySelectorAll('.view-btn').forEach(btn => {
  btn.addEventListener('click', async () => {
    const productId = btn.dataset.id;
    const productName = btn.dataset.name;
    const productDesc = btn.dataset.desc;
    const productPrice = btn.dataset.price;
    const productStock = btn.dataset.stock;
    const productCategory = btn.dataset.category;
    const productImage = btn.dataset.image;
    const productAdmin = btn.dataset.admin;

    document.getElementById('viewProductName').textContent = productName;
    document.getElementById('viewProductDesc').textContent = productDesc || 'No description.';
    document.getElementById('viewProductPrice').textContent = '₱' + parseFloat(productPrice).toFixed(2);
    document.getElementById('viewProductStock').textContent = productStock;
    document.getElementById('viewProductCategory').textContent = 'Category: ' + (productCategory || 'Uncategorized');
    document.getElementById('viewProductAdmin').textContent = productAdmin;
    document.getElementById('viewProductImage').src = productImage;

    const variantSelect = document.getElementById('variantSelect');
    const variantPrice = document.getElementById('variantPrice');
    const variantStock = document.getElementById('variantStock');
    const variantImagePreview = document.getElementById('variantImagePreview');
    variantSelect.innerHTML = '<option value="">-- Select a variant --</option>';
    variantPrice.textContent = '₱0.00';
    variantStock.textContent = '0';
    variantImagePreview.innerHTML = '';

    try {
      const res = await fetch(`products.php?action=fetch_product_details&id=${productId}`);
      const data = await res.json();

      const galleryContainer = document.getElementById('galleryImages');
      galleryContainer.innerHTML = '';
      if (data.gallery && data.gallery.length > 0) {
        data.gallery.forEach(img => {
          const im = document.createElement('img');
          im.src = '../../' + img.image_path;
          im.classList.add('gallery-thumb');
          im.style.width = '80px';
          im.style.height = '80px';
          im.style.objectFit = 'cover';
          im.style.margin = '3px';
          galleryContainer.appendChild(im);
        });
      } else {
        galleryContainer.innerHTML = '<p>No gallery images.</p>';
      }

      if (data.variants && data.variants.length > 0) {
        data.variants.forEach((v, idx) => {
          const opt = document.createElement('option');
          opt.value = idx;
          opt.dataset.price = v.price;
          opt.dataset.stock = v.stock_qty;
          opt.dataset.image = v.variant_image;
          opt.textContent = v.size;
          variantSelect.appendChild(opt);
        });
        document.getElementById('variantSection').style.display = 'block';
      } else {
        document.getElementById('variantSection').style.display = 'none';
      }

    } catch (err) {
      console.error('Error loading details:', err);
    }

    variantSelect.onchange = () => {
      const selected = variantSelect.options[variantSelect.selectedIndex];
      if (selected && selected.value !== '') {
        const price = parseFloat(selected.dataset.price).toFixed(2);
        const stock = selected.dataset.stock;
        const image = selected.dataset.image;
        variantPrice.textContent = '₱' + price;
        variantStock.textContent = stock;
        variantImagePreview.innerHTML = image ? `<img src="../../${image}" style="width:80px;height:80px;object-fit:cover;">` : '';
      } else {
        variantPrice.textContent = '₱0.00';
        variantStock.textContent = '0';
        variantImagePreview.innerHTML = '';
      }
    };

    viewModal.classList.add('show');
  });
});
</script>
<!-- FUNCTIONS -->

</body>
</html>