document.addEventListener('DOMContentLoaded', () => {
    // modals variables
    const openModalBtns = document.querySelectorAll('.open-modal');
    const overlay = document.querySelector('.overlay');
    // end modals variables

    // file variables
    const inputsFile = document.querySelectorAll('input[type="file"]');
    // end file variables

    // modals logic
    openModalBtns.forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const modalId = btn.getAttribute('data-modal');
            const targetModal = document.getElementById(modalId);
            if (targetModal) {
                targetModal.classList.remove('hidden');
                overlay.classList.remove('hidden');
            }
        });
    });

    document.querySelectorAll('.close-btn, .modal-close').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const parentModal = btn.closest("[id^='modal']");
            if (parentModal) {
                parentModal.classList.add('hidden');
                overlay.classList.add('hidden');
            }
        });
    });

    overlay.addEventListener('click', () => {
        document.querySelectorAll("[id^='modal']").forEach((modal) => {
            modal.classList.add('hidden');
        });
        overlay.classList.add('hidden');
    });
    // end modals logic

    // file logic
    inputsFile.forEach((input) => {
        input.addEventListener('change', () => {
            const label = input.closest('label');
            if (!label) {
                return;
            }

            const p = label.querySelector('p');
            const icon = label.querySelector('iconify-icon');
            const form = input.closest('form');
            const submitBtn = form ? form.querySelector('button[type="submit"]') : document.querySelector('#submit-doc-btn');

            if (input.files.length > 0) {
                if (p) {
                    p.textContent = input.files[0].name;
                }
                if (icon) {
                    icon.setAttribute('icon', 'material-symbols:cloud-done-rounded');
                }

                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-disabled', 'opacity-50', 'cursor-not-allowed');
                }
            } else {
                if (p) {
                    p.textContent = 'اضغط لإرفاق مستند';
                }
                if (icon) {
                    icon.setAttribute('icon', 'mingcute:upload-3-fill');
                }

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('btn-disabled', 'opacity-50', 'cursor-not-allowed');
                }
            }
        });
    });
    // end file logic
});
