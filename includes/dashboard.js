// Sidebar, tabs, and content logic
document.addEventListener("DOMContentLoaded", function () {
  const btn = document.querySelector("#btn");
  const sidebar = document.querySelector(".sidebar");
  const navTabs = document.querySelectorAll(".nav-tab");
  const subTabs = document.querySelectorAll(".sub-tab");
  const singleTabs = document.querySelectorAll(".single-tab");
  const contentBoxes = document.querySelectorAll(".main-content .box");

  // Toggle sidebar
  if (btn && sidebar) {
    btn.onclick = () => sidebar.classList.toggle("active");
  }

  // Toggle dropdowns
  navTabs.forEach((tab) => {
    const mainLink = tab.querySelector(".main-tab");
    if (mainLink) {
      mainLink.addEventListener("click", (e) => {
        e.preventDefault();
        if (sidebar.classList.contains("active")) {
          tab.classList.toggle("open");
        }
      });
    }
  });

  // Handle sub-tab clicks
  subTabs.forEach((tab) => {
    tab.addEventListener("click", (e) => {
      const targetId = tab.getAttribute("data-content");

      // Hide all boxes
      contentBoxes.forEach((box) => (box.style.display = "none"));

      // Show the one selected
      const targetBox = document.getElementById(targetId);
      if (targetBox) targetBox.style.display = "block";

      // Remove all active states
      subTabs.forEach((el) => el.classList.remove("active-sub"));
      singleTabs.forEach((el) => el.classList.remove("active-tab"));

      // Highlight selected
      tab.classList.add("active-sub");
    });
  });

  // Handle single (no-submenu) tab clicks
  singleTabs.forEach((tab) => {
    tab.addEventListener("click", (e) => {
      const targetId = tab.getAttribute("data-content");

      // Hide all content
      contentBoxes.forEach((box) => (box.style.display = "none"));

      // Show selected content
      const targetBox = document.getElementById(targetId);
      if (targetBox) targetBox.style.display = "block";

      // Remove other highlights
      subTabs.forEach((el) => el.classList.remove("active-sub"));
      singleTabs.forEach((el) => el.classList.remove("active-tab"));

      // Highlight current single tab
      tab.classList.add("active-tab");
    });
  });

  // Show default tab (Dashboard Overview)
  const overviewBox = document.getElementById("overview-content");
  if (overviewBox) overviewBox.style.display = "block";
});

// SweetAlert2 logout confirmation (runs after CSS is loaded)
window.addEventListener("load", function () {
  const logoutBtn = document.getElementById("logout-button");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", function (e) {
      e.preventDefault();

      Swal.fire({
        title: "Logout Confirmation",
        text: "Are you sure you want to logout?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ff6b6b",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, logout",
        cancelButtonText: "Cancel"
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "../../login.php";
        }
      });
    });
  }
});