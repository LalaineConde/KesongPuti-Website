<?php
$page_title = 'About Us | Kesong Puti';
require '../../connection.php';
$current_page = 'About Us'; // or dynamically determine page
$isHomePage = ($current_page === 'home'); // check if this is the home page
$page_subheader = "How It All Started";

include ('../../includes/customer-dashboard.php');

// Fetch header text
$result = mysqli_query($connection, "SELECT header_text FROM page_headers WHERE page_name='$current_page' LIMIT 1");
$row = mysqli_fetch_assoc($result);

// Fetch all sections
$sections = [];
$secQuery = mysqli_query($connection, "SELECT * FROM about_sections");
while($row = mysqli_fetch_assoc($secQuery)) {
    $sections[$row['section_name']] = $row;
}

// Fetch images for each section
$images = [];
$imgQuery = mysqli_query($connection, "SELECT * FROM about_images ORDER BY section_name, position ASC");
while($row = mysqli_fetch_assoc($imgQuery)) {
    $images[$row['section_name']][] = $row['image_path'];
}


// Fetch existing team data
$teamData = [];
$teamTitle = "OUR KESONG PUTI FAMILY"; // default
$result = mysqli_query($connection, "SELECT * FROM about_team ORDER BY position ASC");
if($result){
    while($row = mysqli_fetch_assoc($result)){
        $teamData[$row['position']] = $row['image_path'];
        $teamTitle = $row['title']; // assume all rows have same title
    }
}


$ctaData = ['heading' => '', 'paragraph' => ''];
$result = mysqli_query($connection, "SELECT * FROM cta_sections WHERE id=1 LIMIT 1");
if($row = mysqli_fetch_assoc($result)){
    $ctaData['heading'] = $row['heading'];
    $ctaData['paragraph'] = $row['paragraph'];
}
?>


?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home | Kesong Puti</title>

    <!-- BOOTSTRAP -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr"
      crossorigin="anonymous"
    />

    <!-- ICONS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />

    <!-- CSS -->
    <link rel="stylesheet" href="/css/styles.css" />
  </head>

  <body>




    <!-- STORY -->
<section id="story">
  <div class="container story-container">
    <?php 
    $section_order = ['beginning','tradition','business','support_farmers','present'];
    foreach($section_order as $sec): 
        $secData = $sections[$sec] ?? ['quote'=>'','content'=>''];
        $secImages = !empty($images[$sec]) ? $images[$sec] : ['uploads/assets/default-'.$sec.'-1.png']; 
        $isRight = in_array($sec, ['beginning','business','present']); // align images
    ?>
    <div class="<?= $sec ?> mt-5">
      <!-- quotes -->
      <div class="quotes">
        <i class="fa-solid fa-quote-left"></i>
        <h1><?= htmlspecialchars($secData['quote']) ?></h1>
        <i class="fa-solid fa-quote-right"></i>
      </div>

      <!-- contents -->
      <div class="row mt-4">
        <!-- text -->
        <div class="col-lg-7 <?= $isRight ? '' : 'order-lg-2' ?>">
          <p><?= nl2br(htmlspecialchars($secData['content'])) ?></p>
        </div>

        <!-- images -->
        <div class="col-lg-5 other-images <?= $isRight ? '' : 'order-lg-1' ?>">
          <div class="image-slider <?= $isRight ? 'right-image' : 'left-image' ?>">
            <?php foreach($secImages as $i => $img): ?>
              <img src="../../<?= htmlspecialchars($img) ?>" alt="" class="<?= $i === 0 ? 'active' : '' ?>" />
            <?php endforeach; ?>
          </div>
          <!-- arrows -->
          <div class="slider-controls">
            <button class="prev"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="next"><i class="fa-solid fa-chevron-right"></i></button>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

    <!-- STORY -->

    <!-- VINE SEPARATOR 1 -->
    <div class="teams">
      <div class="wave-transition1">
        <svg
          viewBox="0 0 1440 80"
          preserveAspectRatio="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M0,20 C120,40 240,0 360,20 C480,40 600,0 720,20 C840,40 960,0 1080,20 C1200,40 1320,0 1440,20"
            fill="none"
            stroke="#058240"
            stroke-width="8"
          />
          <path
            d="M0,40 C120,60 240,20 360,40 C480,60 600,20 720,40 C840,60 960,20 1080,40 C1200,60 1320,20 1440,40"
            fill="none"
            stroke="#058240"
            stroke-width="8"
          />
          <path
            d="M0,60 C120,80 240,40 360,60 C480,80 600,40 720,60 C840,80 960,40 1080,60 C1200,80 1320,40 1440,60 L1440,80 L0,80 Z"
            fill="none"
            stroke="none"
            stroke-width="8"
          />
        </svg>
      </div>
    </div>

    <!-- TEAMS -->
<section id="teams">
  <div class="teams-container">
    <h1 class="mb-5"><?= htmlspecialchars($teamTitle) ?></h1>
    <div class="row cards-container">
      <?php 
        // Loop through team members (max 6)
        for($i = 1; $i <= 6; $i++): 
            $imgSrc = isset($teamData[$i]) 
          ? '../../' . $teamData[$i]  // prepend ../../ to match your folder structure
          : '../../uploads/assets/default-member.png';
      ?>
      <div class="col-lg-4 member-image mb-3">
        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="Team Member <?= $i ?>" class="img-fluid rounded">
      </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
    <!-- TEAMS -->

    <!-- VINE SEPARATOR 1 -->

    <!-- VINE SEPARATOR 1 -->
    <div class="wave-transition1">
      <svg
        viewBox="0 0 1440 80"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M0,20 C120,40 240,0 360,20 C480,40 600,0 720,20 C840,40 960,0 1080,20 C1200,40 1320,0 1440,20"
          fill="none"
          stroke="#058240"
          stroke-width="8"
        />
        <path
          d="M0,40 C120,60 240,20 360,40 C480,60 600,20 720,40 C840,60 960,20 1080,40 C1200,60 1320,20 1440,40"
          fill="none"
          stroke="#058240"
          stroke-width="8"
        />
        <path
          d="M0,60 C120,80 240,40 360,60 C480,80 600,40 720,60 C840,80 960,40 1080,60 C1200,80 1320,40 1440,60 L1440,80 L0,80 Z"
          fill="#058240"
          stroke="#058240"
          stroke-width="8"
        />
      </svg>
    </div>
    <!-- VINE SEPARATOR 1 -->

    <!-- CTA -->
    <section id="cta-buy">
      <h1><?= htmlspecialchars($ctaData['heading']) ?></h1>
        <p><?= htmlspecialchars($ctaData['paragraph']) ?></p>
      <a href="products.php" class="shop-now-btn mt-4">Shop Now </a>
    </section>
    <!-- CTA -->

    <!-- FLOATING BUTTON -->
    <button id="backToTop">
      <i class="fas fa-chevron-up"></i>
    </button>
    <!-- FLOATING BUTTON -->

    <!-- SEPARATOR -->
    <section class="footer-separator"></section>
    <!-- SEPARATOR -->



    <!-- BOOTSTRAP JS -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
      crossorigin="anonymous"
    ></script>

    <!-- SCROLL NAVBAR -->
    <script>
      (function () {
        const navbar = document.getElementById("mainNavbar");

        function updateNavbar() {
          const y = window.scrollY;

          if (y <= 0) {
            navbar.classList.add("navbar-top");
            navbar.classList.remove("navbar-shrink");
          } else {
            navbar.classList.add("navbar-shrink");
            navbar.classList.remove("navbar-top");
          }
        }

        window.addEventListener("scroll", updateNavbar, { passive: true });
        window.addEventListener("load", updateNavbar);
        document.addEventListener("DOMContentLoaded", updateNavbar);
      })();
    </script>

    <!-- CART SIDEBAR -->
    <script>
      // Cart sidebar functionality
      const cartBtn = document.getElementById("cartBtn");
      const cartSidebar = document.getElementById("cartSidebar");
      const closeCart = document.getElementById("closeCart");
      const overlay = document.getElementById("overlay");

      cartBtn.addEventListener("click", () => {
        cartSidebar.classList.add("active");
        overlay.classList.add("active");
      });

      closeCart.addEventListener("click", () => {
        cartSidebar.classList.remove("active");
        overlay.classList.remove("active");
      });

      overlay.addEventListener("click", () => {
        cartSidebar.classList.remove("active");
        overlay.classList.remove("active");
      });

      // Cart items functionality
      function updateCartTotal() {
        let total = 0;
        document.querySelectorAll(".cart-item").forEach((item) => {
          const checkbox = item.querySelector(".cart-check");
          const priceEl = item.querySelector(".item-price");
          const qty = parseInt(item.querySelector(".qty").textContent);
          const pricePerItem =
            parseFloat(priceEl.getAttribute("data-price")) / qty;

          if (checkbox.checked) {
            total += pricePerItem * qty;
          }
        });
        document.getElementById("cartTotal").textContent = `₱${total}`;
      }

      // Quantity controls
      document.addEventListener("click", function (e) {
        if (e.target.classList.contains("plus")) {
          let qtyEl = e.target.previousElementSibling;
          let qty = parseInt(qtyEl.textContent);
          qty++;
          qtyEl.textContent = qty;

          let priceEl = e.target
            .closest(".cart-item")
            .querySelector(".item-price");
          let pricePerItem =
            parseFloat(priceEl.getAttribute("data-price")) / (qty - 1);
          let newPrice = qty * pricePerItem;
          priceEl.setAttribute("data-price", newPrice);
          priceEl.textContent = `₱${newPrice}`;
          updateCartTotal();
        }

        if (e.target.classList.contains("minus")) {
          let qtyEl = e.target.nextElementSibling;
          let qty = parseInt(qtyEl.textContent);
          if (qty > 1) {
            qty--;
            qtyEl.textContent = qty;

            let priceEl = e.target
              .closest(".cart-item")
              .querySelector(".item-price");
            let pricePerItem =
              parseFloat(priceEl.getAttribute("data-price")) / (qty + 1);
            let newPrice = qty * pricePerItem;
            priceEl.setAttribute("data-price", newPrice);
            priceEl.textContent = `₱${newPrice}`;
          }
          updateCartTotal();
        }

        if (e.target.closest(".btn-delete")) {
          e.target.closest(".cart-item").remove();
          updateCartTotal();
        }
      });

      // Checkbox updates
      document.addEventListener("change", function (e) {
        if (e.target.classList.contains("cart-check")) {
          updateCartTotal();
        }

        if (e.target.id === "selectAll") {
          let checked = e.target.checked;
          document.querySelectorAll(".cart-check").forEach((cb) => {
            cb.checked = checked;
          });
          updateCartTotal();
        }
      });

      updateCartTotal();
    </script>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".other-images").forEach((section) => {
          const slider = section.querySelector(".image-slider");
          const images = slider.querySelectorAll("img");
          let currentIndex = 0;

          const showImage = (index) => {
            images.forEach((img) => img.classList.remove("active"));
            images[index].classList.add("active");
          };

          section.querySelector(".next").addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % images.length;
            showImage(currentIndex);
          });

          section.querySelector(".prev").addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            showImage(currentIndex);
          });
        });
      });
    </script>

<?php include('../../includes/floating-button.php'); ?>
<?php include('../../includes/footer.php'); ?>
  </body>
</html>
