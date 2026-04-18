const menuBtn = document.getElementById("Nav-menu");
const sideBar = document.querySelector(".SideBar");

menuBtn.addEventListener("click", () => {
    sideBar.classList.toggle("active");
    sideBar.classList.toggle("side-bar");
});
