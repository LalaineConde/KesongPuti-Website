<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <!-- FLOATING BUTTON -->
    <button id="backToTop">
      <i class="fas fa-chevron-up"></i>
    </button>
    <!-- FLOATING BUTTON -->


        <!-- BUTTON UP -->
    <script>
      const backToTop = document.getElementById("backToTop");

      // Show button when scrolled down
      window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
          backToTop.classList.add("show");
        } else {
          backToTop.classList.remove("show");
        }
      });

      // Scroll to top when clicked
      backToTop.addEventListener("click", () => {
        window.scrollTo({
          top: 0,
          behavior: "smooth",
        });
      });
    </script>

</body>
</html>