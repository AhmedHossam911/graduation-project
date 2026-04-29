// drop down menu variables
const dropDownBtn = document.querySelectorAll(".dropDownBtn")
const dropDown = document.querySelectorAll(".dropDown")
// end drop down menu variables
// calendar variables
const calendarLabel = document.querySelector(".calendar-label")
const calendarSpan = calendarLabel.querySelector("span")
// end calendar variables

// modals variables
const modal = document.querySelectorAll(".modal")
const modalClose = document.querySelectorAll(".modal-close")
const openModal = document.querySelectorAll(".open-modal")
const overlay = document.querySelector(".overlay")
// end modals variables

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