function updateClock() {
  const now = new Date();
  const h = String(now.getHours()).padStart(2, "0");
  const m = String(now.getMinutes()).padStart(2, "0");
  document.getElementById("clock").textContent = h + ":" + m;
}
updateClock();
setInterval(updateClock, 1000);

const menuOptions = document.querySelectorAll(".menuOption");

menuOptions.forEach((option) => {
  option.addEventListener("click", function () {
    
    menuOptions.forEach((item) => {
      item.classList.remove("active");
    });

    this.classList.add("active");
  });
});