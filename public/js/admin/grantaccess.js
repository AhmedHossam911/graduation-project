// drop down menu variables
const dropDownBtn = document.querySelectorAll(".dropDownBtn")
const dropDown = document.querySelectorAll(".dropDown")
// end drop down menu variables

// Role based section toggling
function toggleSectionsByRole() {
    const roleInput = document.getElementById('role_name');
    if (!roleInput) return;
    
    const role = roleInput.value;
    const facultiesSection = document.getElementById('faculties-section');
    const permissionsSection = document.getElementById('permissions-section');
    
    const isMember = role === 'member' || role === 'Member' || role === 'عضو';
    
    if (facultiesSection) facultiesSection.classList.toggle('hidden', isMember);
    if (permissionsSection) permissionsSection.classList.toggle('hidden', isMember);
}

// Select all buttons logic
const selectBtn = document.querySelectorAll('.select-btn');

function updateSelectButtonState(btn, originalText) {
    const groupContainer = btn.parentElement.closest('.navy-shadow');
    if (!groupContainer) return;
    const groupItems = groupContainer.querySelectorAll('.item');
    if (groupItems.length === 0) return;
    
    const allChecked = Array.from(groupItems).every(ch => ch.checked);
    if (allChecked) {
        btn.textContent = "إلغاء التحديد";
    } else {
        btn.textContent = originalText;
    }
}

selectBtn.forEach(btn => {
    const originalText = btn.textContent.trim();
    
    // Check initial state
    updateSelectButtonState(btn, originalText);

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
    
    // Add listener to individual checkboxes to update button text
    const groupContainer = btn.parentElement.closest('.navy-shadow');
    if (groupContainer) {
        const groupItems = groupContainer.querySelectorAll('.item');
        groupItems.forEach(ch => {
            ch.addEventListener('change', () => {
                updateSelectButtonState(btn, originalText);
            });
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    toggleSectionsByRole();
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
                const roleInput = document.getElementById('role_name');
                if (roleInput) {
                    roleInput.value = item.getAttribute('data-value') || item.textContent.trim();
                    toggleSectionsByRole();
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