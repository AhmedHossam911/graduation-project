const notiBtn = document.querySelector(".notification-btn");
const notiBox = document.querySelector(".notifications-box");

notiBtn.addEventListener("click", () => {
    notiBox.classList.toggle("hidden");
}); 