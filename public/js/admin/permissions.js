const tabs = document.querySelectorAll(".tab-item")
const tabContents = document.querySelectorAll(".tab-content")
// modals variables
const openModalBtns = document.querySelectorAll(".open-modal")
const overlay = document.querySelector(".overlay")
// end modals variables


tabs.forEach((tab , index) => {
    tab.addEventListener("click" , () => {
        tabs.forEach(t => {
            const span = t.querySelectorAll('span')
            if (span[1]) span[1].classList.add('hidden')
            const activeClass = t.getAttribute('data-active');
            t.classList.remove(activeClass);
        })
        const span = tab.querySelectorAll('span')
        if (span[1]) span[1].classList.remove('hidden')
        const currentActiveClass = tab.getAttribute('data-active')
        tab.classList.add(currentActiveClass)
        const tabName = tab.querySelector('button').textContent.split('(')[0].trim()
        tabContents.forEach(content  => {
            if(content.dataset.tab === tabName){
                content.classList.remove("hidden")
            }else{
                content.classList.add("hidden")
            }
        })
    })
})

// modals logic
openModalBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.preventDefault();
        const modalId = btn.getAttribute("data-modal");
        const targetModal = document.getElementById(modalId);
        if (targetModal) {
            targetModal.classList.remove("hidden");
            overlay.classList.remove("hidden");
        }
    });
});

document.querySelectorAll(".close-btn, .modal-close").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.preventDefault();
        const parentModal = btn.closest("[id^='modal']");
        if (parentModal) {
            parentModal.classList.add("hidden");
            overlay.classList.add("hidden");
        }
    });
});

overlay.addEventListener("click", () => {
    document.querySelectorAll("[id^='modal']").forEach((modal) => {
        modal.classList.add("hidden");
    });
    overlay.classList.add("hidden");
});
// end modals logic
