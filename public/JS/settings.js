document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab-button');
    const contents = document.querySelectorAll('.tab-content');
    const formTitleText = document.getElementById('form-title-text');
    const formTitleIconContainer = document.getElementById('form-title-icon-container');

    const tabIcons = {
        basic: '<div class="w-5 h-7 left-[5.34px] top-[2.67px] absolute bg-[#1e5a97]"></div>',
        subscriptions: '<div class="w-[26.67px] h-[26.67px] left-[4px] top-[4px] absolute bg-[#1e5a97]"></div>',
        loans: '<div class="w-[26.67px] h-[18.67px] left-[2px] top-[6px] absolute bg-[#1e5a97]"></div>',
        claims: '<div class="w-full h-full px-[1.33px] py-[5.33px] flex justify-center items-center gap-[10.67px] overflow-hidden"><div class="flex-1 h-[18.67px] bg-[#1e5a97]"></div></div>'


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
