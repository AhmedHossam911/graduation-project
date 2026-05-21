document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab-button');
    const contents = document.querySelectorAll('.tab-content');
    const formTitleText = document.getElementById('form-title-text');
    const formTitleIconContainer = document.getElementById('form-title-icon-container');

    const tabIcons = {
        basic: '<iconify-icon icon="material-symbols:list-alt-check-rounded" width="28" height="28" class="text-[#1e5a97]"></iconify-icon>',
        subscriptions: '<iconify-icon icon="tabler:clipboard-list-filled" width="28" height="28" class="text-[#1e5a97]"></iconify-icon>',
        loans: '<iconify-icon icon="fluent:money-16-filled" width="28" height="28" class="text-[#1e5a97]"></iconify-icon>',
        claims: '<iconify-icon icon="ph:user-list-fill" width="28" height="28" class="text-[#1e5a97]"></iconify-icon>'
    };

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // Remove active styling from all tabs
            tabs.forEach(t => {
                t.classList.remove('surface-shadow', 'bg-[#f7f9fc]',
                    'text-[#124375]');
                t.classList.add('text-[#6D6D6D]', 'hover:bg-white',
                    'hover:text-[#124375]');
                const label = t.querySelector('.tab-label');
                if (label) {
                    label.classList.remove('font-semibold');
                    label.classList.add('font-medium');
                }
            });

            // Add active styling to clicked tab
            this.classList.add('surface-shadow', 'bg-[#f7f9fc]', 'text-[#124375]');
            this.classList.remove('text-[#6D6D6D]', 'hover:bg-white',
                'hover:text-[#124375]');
            const activeLabel = this.querySelector('.tab-label');
            if (activeLabel) {
                activeLabel.classList.remove('font-medium');
                activeLabel.classList.add('font-semibold');
            }

            // Get target tab name
            const targetTab = this.getAttribute('data-tab');
            const targetTitle = this.getAttribute('data-title');

            // Update form title and icon
            if (formTitleText) formTitleText.textContent = targetTitle;
            if (formTitleIconContainer && tabIcons[targetTab]) {
                formTitleIconContainer.innerHTML = tabIcons[targetTab];
            }

            // Hide all contents
            contents.forEach(content => {
                content.classList.add('hidden');
            });

            // Show target content
            const targetContent = document.getElementById(`tab-${targetTab}-content`);
            if (targetContent) targetContent.classList.remove('hidden');
        });
    });
});

function submitResetForm() {
    if (confirm(
        'هل أنت متأكد من رغبتك في استعادة قيم اللائحة الأساسية الافتراضية؟ سيؤدي هذا إلى الكتابة فوق جميع الإعدادات الحالية.'
    )) {
        document.getElementById('reset-form').submit();
    }
}
