document.addEventListener("DOMContentLoaded", function () {
  const toggleButton = document.getElementById("navbar-toggle");
  const navbarContent = document.getElementById("navbar-default");

  toggleButton.addEventListener("click", function () {
    // Cette ligne alterne la classe 'hidden' à chaque clic
    console.log("tesssst");
    navbarContent.classList.toggle("hidden");
  });
});
