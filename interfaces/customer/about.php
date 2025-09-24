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
        <!-- beginning -->
        <div class="beginning">
          <!-- quotes -->
          <div class="quotes">
            <i class="fa-solid fa-quote-left"></i>
            <h1>
              It all began in our kitchen, where kesong puti was more than food
              — it was family, tradition, and togetherness.
            </h1>
            <i class="fa-solid fa-quote-right"></i>
          </div>

          <!-- contents -->
          <div class="row mt-4">
            <!-- text -->
            <div class="col-lg-7">
              <p>
                Our story starts in the heart of our home, where the aroma of
                freshly made kesong puti would fill the air every morning. Long
                before this became a business, kesong puti was part of our
                family’s daily life — soft, creamy, and always served with warm
                pan de sal and a hot cup of coffee.
                <br /><br />
                It wasn’t just food; it was tradition. Each batch of cheese
                brought us together around the breakfast table, a reminder of
                simpler times when meals were about love and connection. For us,
                kesong puti was more than just nourishment — it was a way of
                keeping our family’s bond strong, generation after generation.
              </p>
            </div>

            <!-- images -->
            <div class="col-lg-5 other-images">
              <div class="image-slider right-image">
                <img src="../../assets/team1.png" alt="" class="active" />
                <img src="../../assets/team2.png" alt="" />
                <img src="../../assets/team3.png" alt="" />
                <img src="../../assets/team4.png" alt="" />
              </div>
              <!-- arrows -->
              <div class="slider-controls">
                <button class="prev">
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="next">
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- tradition -->
        <div class="tradition mt-5">
          <!-- quotes -->
          <div class="quotes">
            <i class="fa-solid fa-quote-left"></i>
            <h1>
              A recipe lovingly passed down through generations, carrying with
              it the flavors of home and the values of our family.
            </h1>
            <i class="fa-solid fa-quote-right"></i>
          </div>

          <!-- contents -->
          <div class="row mt-4">
            <!-- images -->
            <div class="col-lg-5 other-images">
              <div class="image-slider left-image">
                <img src="../../assets/team1.png" alt="" class="active" />
                <img src="../../assets/team2.png" alt="" />
                <img src="../../assets/team3.png" alt="" />
                <img src="../../assets/team4.png" alt="" />
              </div>
              <!-- controls OUTSIDE the image -->
              <div class="slider-controls">
                <button class="prev">
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="next">
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
              </div>
            </div>

            <!-- text -->
            <div class="col-lg-7">
              <p>
                Our kesong puti is not just cheese — it’s a family treasure. The
                recipe has been passed down from our grandparents, who learned
                the art of making it the traditional way: heating fresh
                carabao’s milk, curdling it naturally, and carefully shaping it
                by hand. The final touch was always wrapping it in banana
                leaves, a symbol of authenticity and respect for our Filipino
                roots.
                <br /><br />
                Over time, this recipe became more than just instructions — it
                became a symbol of our heritage. Every member of the family had
                a role to play, whether it was preparing the milk, stirring the
                curds, or helping pack the cheese. Through these small yet
                meaningful moments, we learned not only how to make kesong puti,
                but also how to value patience, craftsmanship, and love for
                tradition.
              </p>
            </div>
          </div>
        </div>

        <!-- business -->
        <div class="business mt-5">
          <!-- quotes -->
          <div class="quotes">
            <i class="fa-solid fa-quote-left"></i>
            <h1>
              From our table to our neighbors’, our kesong puti grew from a
              family favorite into a tradition we wanted to share with everyone.
            </h1>
            <i class="fa-solid fa-quote-right"></i>
          </div>

          <!-- contents -->
          <div class="row mt-4">
            <!-- text -->
            <div class="col-lg-7">
              <p>
                At first, we made kesong puti only for ourselves. But as
                neighbors, friends, and even relatives from far away tasted it,
                they began to request more. What started as small gifts shared
                during gatherings and fiestas soon turned into regular orders.
                <br /><br />
                Encouraged by the joy on people’s faces, our family decided to
                take the leap — to transform our homemade recipe into a small
                business. We carried with us a promise: no shortcuts, no
                compromises. Just the same freshness, authenticity, and love
                that began in our home.
              </p>
            </div>

            <!-- images -->
            <div class="col-lg-5 other-images">
              <div class="image-slider right-image">
                <img src="../../assets/team1.png" alt="" class="active" />
                <img src="../../assets/team2.png" alt="" />
                <img src="../../assets/team3.png" alt="" />
                <img src="../../assets/team4.png" alt="" />
              </div>
              <!-- controls OUTSIDE the image -->
              <div class="slider-controls">
                <button class="prev">
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="next">
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- supporting farmers -->
        <div class="support-farmers mt-5">
          <!-- quotes -->
          <div class="quotes">
            <i class="fa-solid fa-quote-left"></i>
            <h1>
              Behind every piece of our kesong puti are hardworking farmers and
              a community built on trust and tradition.
            </h1>
            <i class="fa-solid fa-quote-right"></i>
          </div>

          <!-- contents -->
          <div class="row mt-4">
            <!-- images -->
            <div class="col-lg-5 other-images">
              <div class="image-slider left-image">
                <img src="../../assets/team1.png" alt="" class="active" />
                <img src="../../assets/team2.png" alt="" />
                <img src="../../assets/team3.png" alt="" />
                <img src="../../assets/team4.png" alt="" />
              </div>
              <!-- controls OUTSIDE the image -->
              <div class="slider-controls">
                <button class="prev">
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="next">
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
              </div>
            </div>

            <!-- text -->
            <div class="col-lg-7">
              <p>
                Our business grew hand in hand with the local farming community.
                By sourcing directly from small-scale dairy farmers, we not only
                ensure the freshness of our kesong puti but also support the
                livelihoods of hardworking families like ours.
                <br /><br />
                This partnership is more than business — it’s family helping
                family. It reflects our belief that when farmers thrive,
                traditions live on, and when traditions are preserved,
                communities grow stronger together.
              </p>
            </div>
          </div>
        </div>

        <!-- present -->
        <div class="present mt-5">
          <!-- quotes -->
          <div class="quotes">
            <i class="fa-solid fa-quote-left"></i>
            <h1>
              Though we’ve grown, our heart remains the same — to make kesong
              puti with love, tradition, and the taste of home.
            </h1>
            <i class="fa-solid fa-quote-right"></i>
          </div>

          <!-- contents -->
          <div class="row mt-4">
            <!-- text -->
            <div class="col-lg-7">
              <p>
                Today, our family business has reached more homes and more
                hearts, but our values remain unchanged. Every piece of kesong
                puti we make still carries the same authenticity, warmth, and
                care that started it all.
                <br /><br />
                With each bite, we hope you experience not just cheese, but a
                story — of tradition, of community, and of the love that binds
                families together across generations.
              </p>
            </div>

            <!-- images -->
            <div class="col-lg-5 other-images">
              <div class="image-slider right-image">
                <img src="../../assets/team1.png" alt="" class="active" />
                <img src="../../assets/team2.png" alt="" />
                <img src="../../assets/team3.png" alt="" />
                <img src="../../assets/team4.png" alt="" />
              </div>
              <!-- controls OUTSIDE the image -->
              <div class="slider-controls">
                <button class="prev">
                  <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="next">
                  <i class="fa-solid fa-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
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
        <h1 class="mb-5">OUR KESONG PUTI FAMILY</h1>
        <div class="row cards-container">
          <div class="col-lg-4 member-image">
            <img src="" alt="" />
          </div>
          <div class="col-lg-4 member-image">
            <img src="" alt="" />
          </div>
          <div class="col-lg-4 member-image">
            <img src="" alt="" />
          </div>
          <div class="col-lg-4 member-image">
            <img src="" alt="" />
          </div>
          <div class="col-lg-4 member-image">
            <img src="" alt="" />
          </div>
          <div class="col-lg-4 member-image">
            <img src="" alt="" />
          </div>
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
      <h1>Experience Authentic Filipino Flavor</h1>
      <p>
        Discover the creamy, wholesome taste of our kesong puti — a true
        Filipino delicacy made with love and tradition. Browse our shop today
        and bring home a piece of heritage that will delight every table.
      </p>
      <button class="shop-now-btn mt-4">Shop Now</button>
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


<?php include('../../includes/footer.php'); ?>
  </body>
</html>
