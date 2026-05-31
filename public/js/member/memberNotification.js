const markAsReadBtn = document.querySelector(".mark-as-read");
const notifications = document.querySelectorAll(".noti");

markAsReadBtn.addEventListener("click" , () => {
    notifications.forEach(noti => {
        noti.classList.add("read");
        noti.classList.remove("unread");
    })
}) 