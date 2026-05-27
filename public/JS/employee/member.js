// tabs variables
const tabs = document.querySelectorAll('.tabs button')
const tabContents = document.querySelectorAll('.tab-content')
// end tabs variables
// modals variables
const openModalBtns = document.querySelectorAll(".open-modal")
const overlay = document.querySelector(".overlay")
// end modals variables
// drop down variables
const dropDownBtn = document.querySelectorAll(".dropDownBtn");
const dropDown = document.querySelectorAll(".dropDown");
// end drop down variables
// file variables
const inputsFile = document.querySelectorAll('input[type="file"]')
// end file variables


// tabs logic
const currentPath = window.location.pathname;
const urlParams = new URLSearchParams(window.location.search);
const urlTab = urlParams.get('tab');

const tabNameMap = {
    'subscriptions': 'الاشتراكات',
    'loans': 'قروض',
    'claims': 'مطالبات',
    'documents': 'مرفقات العضو',
    'personal': 'المعلومات الشخصية'
};

const mappedTabName = tabNameMap[urlTab] || urlTab || sessionStorage.getItem('activeTab_' + currentPath);
let initialTabName = mappedTabName;

function activateTabByName(tabName) {
    let tabFound = false;
    tabs.forEach(tab => {
        if (tab.textContent.replace(/\s+/g, ' ').trim() === tabName) {
            tabFound = true;
            tab.click();
        }
    });
    return tabFound;
}

tabs.forEach(tab => {
    tab.addEventListener('click', (e) => {
        if (e.isTrusted === true) {
            // Only prevent default for actual user clicks, not programmatic ones
            // though programatic ones are ok to preventDefault too.
        }
        e.preventDefault();
        tabs.forEach(t => {
            t.classList.remove('active-tab');
            t.classList.add('tab');
        });
        tab.classList.remove('tab');
        tab.classList.add('active-tab');
        const tabName = tab.textContent.replace(/\s+/g, ' ').trim();
        
        // Save to session storage
        sessionStorage.setItem('activeTab_' + currentPath, tabName);
        
        // Update URL safely without reloading
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', newUrl);

        tabContents.forEach(content => {
            if (content.dataset.tab === tabName) {
                content.classList.remove('hidden');
            } else {
                content.classList.add('hidden');
            }
        });
    });
});

// Initialize on page load
if (initialTabName) {
    // Wait a tick for DOM
    setTimeout(() => activateTabByName(initialTabName), 50);
}
// tabs logic  

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

// Auto-open declaration modal if query param exists
if (urlParams.get('open_declaration_modal') === '1') {
    const modal2 = document.getElementById('modal2');
    if (modal2 && overlay) {
        // Wait a small amount for DOM to settle
        setTimeout(() => {
            modal2.classList.remove('hidden');
            overlay.classList.remove('hidden');

            // Set hidden inputs in loanStoreForm
            const loanStoreForm = document.getElementById('loanStoreForm');
            if (loanStoreForm) {
                const totalAmountInput = document.getElementById('selected_total_amount');
                const monthsInput = document.getElementById('selected_months');
                if (totalAmountInput) totalAmountInput.value = urlParams.get('create_loan_amount');
                if (monthsInput) monthsInput.value = urlParams.get('create_loan_months');
            }

            // Summary population is handled in show.blade.php to allow formatting
            // and correct print button url assignment.
            
            // update URL to remove the params so refresh doesn't reopen
            const newUrl = new URL(window.location);
            newUrl.searchParams.delete('open_declaration_modal');
            newUrl.searchParams.delete('loan_base_amount');
            newUrl.searchParams.delete('loan_interest_amount');
            newUrl.searchParams.delete('loan_total_amount');
            newUrl.searchParams.delete('loan_installment_amount');
            window.history.replaceState({}, '', newUrl);
        }, 100);
    }
}

// file logic
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
// end file logic

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
    const items = menu.querySelectorAll("a");
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



