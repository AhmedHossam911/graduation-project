const notiBtn = document.querySelector(".notification-btn");
const notiBox = document.querySelector(".notifications-box");

notiBtn.addEventListener("click", () => {
    notiBox.classList.toggle("hidden");
});

document.addEventListener("click", () => {
    notiBox.classList.add("hidden");
});
