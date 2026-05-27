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
    btn.addEventListener("click", () => {
        modalBtns.forEach(b => {
            b.classList.add('defult-btn')
            b.classList.remove('green-btn')
            b.classList.remove('red-btn')
        })
        if (btn.textContent.trim() === "إيراد") {
            btn.classList.remove('defult-btn')
            btn.classList.add('green-btn')
        } else if (btn.textContent.trim() === "مصروف") {
            btn.classList.remove('defult-btn')
            btn.classList.add('red-btn')
        }
        
        const typeInput = document.getElementById('create-type-input');
        if (typeInput && btn.dataset.type) {
            typeInput.value = btn.dataset.type;
            // Clear category when type changes
            const categoryInput = document.getElementById('create-category-input');
            if (categoryInput) categoryInput.value = '';
            
            // Reset category dropdown text
            const categoryDropdownBtn = document.querySelector('.drop-down-btn');
            const categorySpan = categoryDropdownBtn.querySelector('span');
            if (categorySpan) categorySpan.textContent = 'اختر';
        }

        dropDownBtnModal.disabled = false
        dropdownGroups.forEach(group => {
            if (group.dataset.dropdown === btn.textContent.trim()) {
                group.classList.remove('hidden')
            } else {
                group.classList.add('hidden')
            }
        })
    })
})

// file inputs logic
inputsFile.forEach((input) => {
    input.addEventListener('change', (e) => {
        const label = input.closest('label')
        const p = label.querySelector('p')
        const icon = label.querySelector('iconify-icon')
        if (input.files.length > 0) {
            p.textContent = 'تم إرفاق المستند'
            icon.setAttribute('icon', 'material-symbols:cloud-done-rounded')
        }
    })
})
// end file inputs logic

// start tabs logic
tabs.forEach(tab => {
    tab.addEventListener("click", () => {
        const tabName = tab.querySelector(".tab-name").textContent
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
            
            const inputId = item.getAttribute("data-input");
            const inputValue = item.getAttribute("data-value");
            if (inputId && inputValue) {
                const hiddenInput = document.getElementById(inputId);
                if (hiddenInput) {
                    hiddenInput.value = inputValue;
                }
            } else if (item.hasAttribute("data-value")) {
                const hiddenInput = dropDownBtn[index].parentElement.querySelector(".filter-hidden");
                if (hiddenInput) {
                    hiddenInput.value = item.getAttribute("data-value");
                }
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

// Form validation
const createForm = document.querySelector("#modal1 form");
if (createForm) {
    createForm.addEventListener("submit", (e) => {
        const type = document.getElementById("create-type-input").value;
        const category = document.getElementById("create-category-input").value;
        const method = document.getElementById("create-method-input").value;
        const description = document.querySelector("textarea[name='description']").value;
        const attachment = document.querySelector("input[name='attachment']").files.length;
        
        if (!type) {
            e.preventDefault();
            showFlash('تنبيه', "الرجاء اختيار إيراد أو مصروف.", 'error');
            return;
        }
        if (!category) {
            e.preventDefault();
            showFlash('تنبيه', "الرجاء اختيار بند الحركة.", 'error');
            return;
        }
        if (!method) {
            e.preventDefault();
            showFlash('تنبيه', "الرجاء اختيار طريقة الدفع.", 'error');
            return;
        }
        if (!description.trim()) {
            e.preventDefault();
            showFlash('تنبيه', "الرجاء إدخال بيان الحركة.", 'error');
            return;
        }
        if (attachment === 0) {
            e.preventDefault();
            showFlash('تنبيه', "الرجاء إرفاق صورة الفاتورة أو الإيصال.", 'error');
            return;
        }
    });
}
