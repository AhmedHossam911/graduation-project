// drop down menu variables
const dropDownBtn = document.querySelectorAll(".dropDownBtn")
const dropDown = document.querySelectorAll(".dropDown")
// end drop down menu variables

// Select all buttons logic
const selectBtn = document.querySelectorAll('.select-btn');

selectBtn.forEach(btn => {
    const originalText = btn.textContent.trim();

    btn.addEventListener('click' , () => {
        const isSelected = btn.textContent.trim() === "إلغاء التحديد";

        const groupContainer = btn.parentElement.closest('.navy-shadow');
        if (!groupContainer) return;
        
        const groupItems = groupContainer.querySelectorAll('.item');

        groupItems.forEach(ch => {
            ch.checked = !isSelected;
        });

        if (isSelected) {
            btn.textContent = originalText; 
        } else {
            btn.textContent = "إلغاء التحديد";
        }
    });
});

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