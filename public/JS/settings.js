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

document.addEventListener('DOMContentLoaded', function () {
    const membershipFeeModal = document.getElementById('membershipFeeModal');
    const openModalBtns = document.querySelectorAll('.open-modal');
    const closeBtns = document.querySelectorAll('.close-modal');
    const hiddenInput = document.getElementById('membership_join_fee_hidden');

    let joinFeeData = [];

    const renderJoinFeeRows = () => {
        const container = document.getElementById('joinFeeTableBody');
        if(!container) return;

        container.innerHTML = '';

        joinFeeData.forEach((row, index) => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-[#F9FAFB] transition';

            tr.innerHTML = `
                <td class="p-4 border-b border-[#D0D5DD] align-middle">
                    <div class="relative w-full max-w-[220px] mx-auto">
                        <input type="text" class="w-full text-center py-3 px-4 border border-[#D0D5DD] rounded-xl text-lg font-bold bg-[#F4F7F9] text-[#021219] outline-none focus:ring-1 focus:ring-[#124375]" value="${row.years || ''}" data-index="${index}" data-field="years">
                    </div>
                </td>
                <td class="p-4 border-b border-[#D0D5DD] align-middle">
                    <div class="relative w-full max-w-[220px] mx-auto">
                        <input type="text" class="w-full text-center py-3 px-12 border border-[#D0D5DD] rounded-xl text-lg font-bold bg-[#F4F7F9] text-[#021219] outline-none focus:ring-1 focus:ring-[#124375]" value="${row.fee_months || ''}" data-index="${index}" data-field="fee_months">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold pointer-events-none">شهراً</span>
                    </div>
                </td>
                <td class="p-4 border-b border-[#D0D5DD] align-middle text-center">
                    <button type="button" class="bg-[#D92D20] text-white p-2 rounded-lg hover:bg-red-700 transition mx-auto flex items-center justify-center shadow" onclick="deleteJoinFeeRow(${index})">
                        <iconify-icon icon="mdi:trash-can" class="text-xl"></iconify-icon>
                    </button>
                </td>
            `;
            container.appendChild(tr);
        });

        // Add event listeners to new inputs
        container.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                const idx = this.getAttribute('data-index');
                const field = this.getAttribute('data-field');
                joinFeeData[idx][field] = this.value;
                updateHiddenInput();
            });
        });
    };

    const updateHiddenInput = () => {
        if (hiddenInput) {
            hiddenInput.value = JSON.stringify(joinFeeData);
        }
    };

    window.deleteJoinFeeRow = (index) => {
        joinFeeData.splice(index, 1);
        renderJoinFeeRows();
        updateHiddenInput();
    };

    const addRowBtn = document.getElementById('addRowBtn');
    if(addRowBtn) {
        addRowBtn.addEventListener('click', () => {
            joinFeeData.push({ years: '', fee_months: '' });
            renderJoinFeeRows();
            updateHiddenInput();
        });
    }

    // Open Modal
    openModalBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('data-target');
            if(target === 'membershipFeeModal') {
                if(membershipFeeModal) {
                    membershipFeeModal.classList.remove('hidden');
                    // Add a small delay for transition
                    setTimeout(() => {
                        membershipFeeModal.classList.remove('opacity-0');
                    }, 10);
                }

                // Parse hidden input to state
                if (hiddenInput) {
                    try {
                        joinFeeData = JSON.parse(hiddenInput.value);
                        if(!Array.isArray(joinFeeData)) joinFeeData = [];
                    } catch(err) {
                        joinFeeData = [];
                    }
                }
                renderJoinFeeRows();
            }
        });
    });

    // Close Modal
    const closeModal = () => {
        if(membershipFeeModal) {
            membershipFeeModal.classList.add('opacity-0');
            setTimeout(() => {
                membershipFeeModal.classList.add('hidden');
            }, 300); // Matches transition duration
        }
    };

    closeBtns.forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    // Close when clicking outside modal body
    if (membershipFeeModal) {
        membershipFeeModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }
});
