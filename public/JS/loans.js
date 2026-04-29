// dropDown variables
const dropDownBtn = document.querySelectorAll(".dropDownBtn")
const dropDown = document.querySelectorAll(".dropDown")
// end dropDown variables

// modals variables
const modal = document.querySelectorAll(".modal")
const modalClose = document.querySelectorAll(".modal-close")
const openModal = document.querySelectorAll(".open-modal")
const overlay = document.querySelector(".overlay")
// end modals variables

// calendar variables
const calendarLabel = document.querySelector(".calendar-label")
const span = calendarLabel.querySelector("span")
// end calendar variables

// file variables
const inputsFile = document.querySelectorAll('input[type="file"]')
// end file variables

// modals logic
openModal.forEach((btn, index) => {
    btn.addEventListener("click", () => {
        modal[index].classList.remove("hidden")
        overlay.classList.remove("hidden")
    })
})
modalClose.forEach((btn, index) => {
    btn.addEventListener("click", () => {
        modal[index].classList.add("hidden")
        overlay.classList.add("hidden")
    })
})
// end modals logic

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

// dropDown logic
function updateCheckboxDropdownText(menu, btn) {
    const checked = menu.querySelectorAll('input[type="checkbox"]:checked');
    const spans = btn.querySelectorAll("span");
    if (spans.length > 1) {
        if (checked.length > 0) {
            const selectedValues = Array.from(checked).map(cb => cb.value);
            spans[1].textContent = selectedValues.join(" ، ");
        } else {
            spans[1].textContent = "اختر الشهر";
        }
    }
}

dropDownBtn.forEach((btn, index) => {
    const hasCheckboxes = dropDown[index].querySelector('input[type="checkbox"]') !== null;

    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        if (hasCheckboxes && !dropDown[index].classList.contains("hidden")) {
            updateCheckboxDropdownText(dropDown[index], btn);
        }
        dropDown.forEach((d, i) => {
            if (i !== index) d.classList.add("hidden");
        });
        dropDown[index].classList.toggle("hidden");
    });
    if (hasCheckboxes) {
        dropDown[index].addEventListener("click", (e) => {
            e.stopPropagation();
        });
    }
});
 
dropDown.forEach((menu, index) => {
    const hasCheckboxes = menu.querySelector('input[type="checkbox"]') !== null;
    if (!hasCheckboxes) {
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
    }
});

document.addEventListener("click", () => {
    dropDown.forEach((menu, index) => {
        const hasCheckboxes = menu.querySelector('input[type="checkbox"]') !== null;
        if (hasCheckboxes && !menu.classList.contains("hidden")) {
            updateCheckboxDropdownText(menu, dropDownBtn[index]);
        }
        menu.classList.add("hidden");
    });
});
// end dropDown logic

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
            span.textContent = day + "/" + month + "/" + year;
        }
    });
});
// end calendar logic