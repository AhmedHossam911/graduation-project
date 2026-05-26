// drop down menu variables
const dropDownBtn = document.querySelectorAll(".dropDownBtn")
const dropDown = document.querySelectorAll(".dropDown")
// end drop down menu variables
// calendar variables
const calendarLabel = document.querySelector(".calendar-label")
const calendarSpan = calendarLabel.querySelector("span")
// end calendar variables
// tabs variables
const tabs = document.querySelectorAll(".tab")
const tabContents = document.querySelectorAll(".tab-content")
// end tabs variables
// modals variables
const openModalBtns = document.querySelectorAll(".open-modal")
const overlay = document.querySelector(".overlay")
// end modals variables
// file variables
const inputsFile = document.querySelectorAll('input[type="file"]')
// end file variables

const dropdownGroups = document.querySelectorAll(".dropdown-group")
const dropDownBtnModal = document.querySelector(".drop-down-btn")
const modalBtns = document.querySelectorAll(".modal-btn")
modalBtns.forEach(btn => {
    btn.addEventListener("click" , () => {
        modalBtns.forEach(b => {
            b.classList.add('defult-btn')
            b.classList.remove('green-btn')
            b.classList.remove('red-btn')
        })
        if(btn.textContent.trim() === "إيراد") {
            btn.classList.remove('defult-btn')
            btn.classList.add('green-btn')
        } else if(btn.textContent.trim() === "مصروف") {
            btn.classList.remove('defult-btn')
            btn.classList.add('red-btn')
        }
        dropDownBtnModal.disabled = false
        dropdownGroups.forEach(group =>{
            if(group.dataset.dropdown === btn.textContent.trim()) {
                group.classList.remove('hidden')
            } else {
                group.classList.add('hidden')
            }
        })
    })
})

// file inputs logic
inputsFile.forEach((input) => {
    input.addEventListener('change' , (e) => {
        const label = input.closest('label')
        const p = label.querySelector('p')
        const icon = label.querySelector('iconify-icon')
        if(input.files.length > 0) {
            p.textContent = 'تم إرفاق المستند'
            icon.setAttribute('icon' , 'material-symbols:cloud-done-rounded')
        }
    })
})
// end file inputs logic

// start tabs logic
tabs.forEach(tab => {
    tab.addEventListener("click", () => {
        const tabName= tab.querySelector(".tab-name").textContent
        tabContents.forEach(tabContent => {
            if (tabContent.dataset.tab === tabName) {
                tabContent.classList.remove("hidden")
            } else {
                tabContent.classList.add("hidden")
            }
        })
    })
})
// end tabs logic


// drop down menu logic
dropDownBtn.forEach((btn, index) => {
    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        dropDown.forEach((d, i) => {
            if (i !== index) d.classList.add("hidden");
        });
        dropDown[index].classList.toggle("hidden");
    });
});

dropDown.forEach((menu, index) => {
    const items = menu.querySelectorAll("button");
    items.forEach(item => {
        item.addEventListener("click", (e) => {
            e.stopPropagation();
            const spans = dropDownBtn[index].querySelectorAll("span");
            if (spans.length > 0) {
                spans[0].textContent = item.textContent;
            }
            menu.classList.add("hidden");
        });
    });
});

document.addEventListener("click", () => {
    dropDown.forEach(menu => {
        menu.classList.add("hidden");
    });
});
// end drop down menu logic

// calendar logic
$(function () {
    $("#datepicker").datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        showButtonPanel: true,
        currentText: "Done",
        closeText: "Cancel",
        onSelect: function () {
            const currentDate = $("#datepicker").datepicker("getDate");
            const day = currentDate.getDate();
            const month = currentDate.getMonth() + 1;
            const year = currentDate.getFullYear();
            calendarSpan.textContent = day + "/" + month + "/" + year;
        }
    });
});
// end calendar logic

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
