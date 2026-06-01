const menuBtn = document.getElementById("Nav-menu");
const sideBar = document.querySelector(".SideBar");
const notiBtn = document.querySelector(".notification-btn");
const notiBox = document.querySelector(".notifications-box");

notiBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    notiBox.classList.toggle("hidden");
});

document.addEventListener("click", () => {
    notiBox.classList.add("hidden");
});

if (menuBtn && sideBar) {
    menuBtn.addEventListener("click", () => {
        if (window.innerWidth <= 768) {
            sideBar.classList.toggle("mobile-open");
            const overlay = document.getElementById("sidebar-overlay");
            if (overlay) {
                overlay.classList.toggle("hidden");
            }
        } else {
            sideBar.classList.toggle("active");
            sideBar.classList.toggle("side-bar");
        }
    });
}

// Overlay click to close sidebar on mobile
const sidebarOverlay = document.getElementById("sidebar-overlay");
if (sidebarOverlay && sideBar) {
    sidebarOverlay.addEventListener("click", () => {
        sideBar.classList.remove("mobile-open");
        sidebarOverlay.classList.add("hidden");
    });
}

// Dismiss flash messages
document.querySelectorAll('.btn-close').forEach(button => {
    button.addEventListener('click', function() {
        const modal = this.closest('.absolute');
        if (modal) {
            modal.style.display = 'none';
        }
    });
});

// Generic DropDown Component Logic
document.querySelectorAll('.custom-dropdown-container').forEach(container => {
    const btn = container.querySelector('.custom-dropdown-btn');
    const menu = container.querySelector('.custom-dropdown-menu');
    const textElement = container.querySelector('.custom-dropdown-text');
    const confirmBtn = container.querySelector('.custom-dropdown-confirm');
    const clearBtn = container.querySelector('.custom-dropdown-clear');
    const clearValue = container.getAttribute('data-clear-value') || 'all';
    const autoSubmit = container.getAttribute('data-auto-submit') === 'true';
    const hasConfirmBtn = container.getAttribute('data-has-confirm') === 'true';

    const toggleMenu = (e) => {
        e.stopPropagation();
        // Hide other dropdowns
        document.querySelectorAll('.custom-dropdown-menu').forEach(otherMenu => {
            if (otherMenu !== menu) otherMenu.classList.add('hidden');
        });
        menu.classList.toggle('hidden');
    };

    container.addEventListener('click', (e) => {
        // Prevent toggling if clicked inside the open menu
        if (e.target.closest('.custom-dropdown-menu')) return;
        // Prevent toggling if clicked the clear button
        if (e.target.closest('.custom-dropdown-clear')) return;

        toggleMenu(e);
    });

    if (confirmBtn) {
        confirmBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const selectedInput = menu.querySelector('input[type="radio"]:checked');
            if (selectedInput) {
                const labelText = selectedInput.previousElementSibling.textContent;
                textElement.textContent = labelText;
                textElement.classList.remove('text-[#6D6D6D]', 'text-sm');
                textElement.classList.add('text-[#124375]', 'font-bold', 'text-base');
                menu.classList.add('hidden');

                // Manage clear button visibility
                if (clearBtn) {
                    if (selectedInput.value !== clearValue) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }
                }
            }
        });
    }

    const radioInputs = menu.querySelectorAll('input[type="radio"]');
    radioInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            if (!hasConfirmBtn) {
                e.stopPropagation();
                const labelText = input.previousElementSibling.textContent;
                textElement.textContent = labelText;
                textElement.classList.remove('text-[#6D6D6D]', 'text-sm');
                textElement.classList.add('text-[#124375]', 'font-bold', 'text-base');
                menu.classList.add('hidden');

                // Manage clear button visibility
                if (clearBtn) {
                    if (input.value !== clearValue) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }
                }

                if (autoSubmit) {
                    const form = container.closest('form');
                    if (form) form.submit();
                }
            }
        });
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const defaultInput = menu.querySelector(`input[type="radio"][value="${clearValue}"]`);
            if (defaultInput) {
                defaultInput.checked = true;
                const labelText = defaultInput.previousElementSibling.textContent;
                textElement.textContent = labelText;
                textElement.classList.remove('text-[#124375]', 'font-bold');
                textElement.classList.add('text-[#6D6D6D]', 'text-sm');
                clearBtn.classList.add('hidden');

                if (autoSubmit) {
                    const form = container.closest('form');
                    if (form) form.submit();
                }
            }
        });
    }
});

// Close dropdowns when clicking outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('.custom-dropdown-container')) {
        document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});
