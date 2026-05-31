const sidebarPages = document.querySelectorAll(".page")
sidebarPages.forEach(page => {
    page.addEventListener("click", () => {
        sidebarPages.forEach(page => {
            page.classList.remove("active")
        })
        page.classList.add("active")
    })
})