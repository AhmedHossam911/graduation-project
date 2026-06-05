// Use a single initialization function to ensure DOM is ready
function initLoansModule() {
    console.log('Initializing Loans Module. APP_URL:', window.APP_URL);

    const overlay = document.querySelector(".overlay");
    const openModalBtns = document.querySelectorAll(".open-modal");
    // const closeModalBtns = document.querySelectorAll(".modal-close");

    // Member Search Elements
    const memberSearchBtn = document.getElementById('memberSearchBtn');
    const memberSearchInput = document.getElementById('memberSearchInput');
    const memberSearchResults = document.getElementById('memberSearchResults');

    // Modals
    openModalBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            const targetId = btn.getAttribute('data-target');
            console.log('Opening modal:', targetId);
            if (!targetId) return;

            const targetModal = document.getElementById(targetId);
            if (targetModal) {
                targetModal.classList.remove("hidden");
                if (overlay) overlay.classList.remove("hidden");

                if (targetId === 'paymentModal') {
                    const loanId = btn.getAttribute('data-loan-id');
                    fetchLoanDataForPayment(loanId);
                }
            }
        });
    });

    document.querySelectorAll(".close-btn, .modal-close").forEach((btn) => {
    btn.addEventListener("click", (e) => {
        e.preventDefault();
        const parentModal = btn.closest(".modal");
        if (parentModal) {
            parentModal.classList.add("hidden");
            overlay.classList.add("hidden");
        }
    });
});


    if (overlay) {
        overlay.addEventListener('click', () => {
            document.querySelectorAll('.modal').forEach(m => m.classList.add('hidden'));
            overlay.classList.add('hidden');
        });
    }

    // Search Logic
    if (memberSearchBtn && memberSearchInput && memberSearchResults) {
        memberSearchBtn.addEventListener('click', performMemberSearch);
        memberSearchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                performMemberSearch();
            }
        });
    }

    function performMemberSearch() {
        const query = memberSearchInput.value.trim();
        if (query === '') return;

        memberSearchResults.innerHTML = '<p class="text-center py-2">جاري البحث...</p>';
        memberSearchResults.classList.remove('hidden');

        const searchUrl = `${window.APP_URL}/loans/search-members?q=${encodeURIComponent(query)}`;
        console.log('Searching members at:', searchUrl);

        fetch(searchUrl)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                memberSearchResults.innerHTML = '';
                if (data.length === 0) {
                    memberSearchResults.innerHTML = '<p class="text-center py-2 text-[#124375]">لا يوجد نتائج</p>';
                } else {
                    data.forEach(member => {
                        const div = document.createElement('div');
                        div.className = 'cursor-pointer p-2 hover:bg-[#EEF7FF] rounded text-[#124375] font-medium border-b border-gray-200';
                        div.innerHTML = `${member.full_name} - ${member.membership_number} <br><span class="text-sm text-gray-500">${member.national_id}</span>`;
                        div.addEventListener('click', () => {
                            if (member.has_active_loan) {
                                showFlash('تنبيه', 'هذا العضو لديه قرض نشط أو طلب قيد المراجعة بالفعل.', 'error');
                                return;
                            }
                            if (!member.id) {
                                showFlash('تنبيه', 'هذا العضو ليس لديه عضوية مفعلة.', 'error');
                                return;
                            }
                            const nameEl = document.getElementById('createLoanName');
                            if (nameEl) nameEl.textContent = member.full_name;
                            const numEl = document.getElementById('createLoanMembershipNum');
                            if (numEl) numEl.textContent = member.membership_number;
                            const idEl = document.getElementById('createLoanNationalId');
                            if (idEl) idEl.textContent = member.national_id;
                            document.getElementById('selectedMemberId').value = member.id;
                            memberSearchResults.classList.add('hidden');
                            memberSearchInput.value = member.full_name;
                            checkCreateLoanSubmitBtn();
                        });
                        memberSearchResults.appendChild(div);
                    });
                }
            })
            .catch(err => {
                console.error('Search error:', err);
                memberSearchResults.innerHTML = '<p class="text-center py-2 text-red-500">حدث خطأ أثناء البحث</p>';
            });
    }

    // Dropdowns for Create Loan
    const loanAmountBtn = document.querySelector('.dropDownBtn:has(#loanAmountSpan)');
    const loanAmountMenu = document.getElementById('loanAmountDropdown');
    const loanMonthsBtn = document.querySelector('.dropDownBtn:has(#loanMonthsSpan)');
    const loanMonthsMenu = document.getElementById('loanMonthsDropdown');

    if (loanAmountBtn && loanAmountMenu) {
        loanAmountBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            loanAmountMenu.classList.toggle('hidden');
            if (loanMonthsMenu) loanMonthsMenu.classList.add('hidden');
        });

        loanAmountMenu.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                const val = btn.getAttribute('data-value');
                document.getElementById('loanAmountSpan').textContent = btn.textContent;
                document.getElementById('selectedLoanAmount').value = val;
                loanAmountMenu.classList.add('hidden');
                checkCreateLoanSubmitBtn();
            });
        });
    }

    if (loanMonthsBtn && loanMonthsMenu) {
        loanMonthsBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            loanMonthsMenu.classList.toggle('hidden');
            if (loanAmountMenu) loanAmountMenu.classList.add('hidden');
        });

        loanMonthsMenu.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                const val = btn.getAttribute('data-value');
                document.getElementById('loanMonthsSpan').textContent = btn.textContent;
                document.getElementById('selectedLoanMonths').value = val;
                loanMonthsMenu.classList.add('hidden');
                checkCreateLoanSubmitBtn();
            });
        });
    }

    // Payment Modal Dropdown
    const paymentBtn = document.querySelector('.dropDownBtn:has(+ #paymentInstallmentsDropdown)');
    const paymentMenu = document.getElementById('paymentInstallmentsDropdown');

    if (paymentBtn && paymentMenu) {
        paymentBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            paymentMenu.classList.toggle('hidden');
        });
    }

    const paymentMethodBtn = document.querySelector('.dropDownBtn:has(+ #payment-methods-dropdown)');
    const paymentMethodMenu = document.getElementById('payment-methods-dropdown');
    const paymentMethodInput = document.getElementById('payment-method-input');
    const paymentMethodText = document.getElementById('payment-method-text');

    if (paymentMethodBtn && paymentMethodMenu) {
        paymentMethodBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            paymentMethodMenu.classList.toggle('hidden');
        });

        paymentMethodMenu.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                const val = btn.getAttribute('data-value');
                paymentMethodText.textContent = btn.textContent;
                paymentMethodInput.value = val;
                paymentMethodMenu.classList.add('hidden');
            });
        });
    }

    // File Inputs
    document.querySelectorAll('input[type="file"]').forEach((input) => {
        input.addEventListener('change', () => {
            const label = input.closest('label');
            if (!label) return;
            const p = label.querySelector('p');
            const icon = label.querySelector('iconify-icon');
            if (input.files.length > 0) {
                if (p) p.textContent = 'تم إرفاق المستند';
                if (icon) icon.setAttribute('icon', 'material-symbols:cloud-done-rounded');
            }
        });
    });

    // Close on body click
    document.addEventListener("click", (event) => {
        document.querySelectorAll('.dropDown').forEach(menu => {
            if (!menu.contains(event.target) && !menu.previousElementSibling.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        if (memberSearchResults && !memberSearchResults.contains(event.target) && event.target !== memberSearchInput && !memberSearchBtn.contains(event.target)) {
            memberSearchResults.classList.add('hidden');
        }
    });

    const createLoanForm = document.getElementById('createLoanForm');
    if (createLoanForm) {
        createLoanForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const memberId = document.getElementById('selectedMemberId').value;
            const amount = document.getElementById('selectedLoanAmount').value;
            const months = document.getElementById('selectedLoanMonths').value;
            const submitBtn = document.getElementById('createLoanSubmitBtn');

            if (memberId && amount && months) {
                // Change button state
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = 'جاري التحقق...';
                submitBtn.classList.add('btn-disabled');

                const validateUrl = `${window.APP_URL}/loans/validate-request`;

                fetch(validateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        member_id: memberId,
                        total_amount: amount,
                        months: months
                    })
                })
                .then(res => res.json())
                .then(data => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.classList.remove('btn-disabled');

                    if (data.success) {
                        const baseUrl = `${window.APP_URL}/members/${memberId}`;
                        window.location.href = `${baseUrl}?tab=loans&create_loan_amount=${amount}&create_loan_months=${months}&open_declaration_modal=1&loan_base_amount=${data.base_amount}&loan_interest_amount=${data.interest_amount}&loan_total_amount=${data.total_amount}&loan_installment_amount=${data.installment_amount}`;
                    } else {
                        showFlash('تنبيه', data.message || 'حدث خطأ أثناء التحقق من الشروط.', 'error');
                    }
                })
                .catch(err => {
                    console.error('Validation error:', err);
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.classList.remove('btn-disabled');
                    showFlash('تنبيه', 'حدث خطأ في الاتصال بالخادم.', 'error');
                });
            }
        });
    }
}

function checkCreateLoanSubmitBtn() {
    const memberId = document.getElementById('selectedMemberId').value;
    const amount = document.getElementById('selectedLoanAmount').value;
    const months = document.getElementById('selectedLoanMonths').value;
    const btn = document.getElementById('createLoanSubmitBtn');

    if (memberId && amount && months) {
        btn.classList.remove('btn-disabled');
    } else {
        btn.classList.add('btn-disabled');
    }
}

function fetchLoanDataForPayment(loanId) {
    if (!loanId) return;

    const nameSpan = document.getElementById('paymentMemberName');
    const numSpan = document.getElementById('paymentMembershipNum');
    const idSpan = document.getElementById('paymentNationalId');
    const dropdown = document.getElementById('paymentInstallmentsDropdown');

    if (nameSpan) nameSpan.textContent = 'جاري التحميل...';
    if (dropdown) dropdown.innerHTML = '<p class="text-center py-2">جاري التحميل...</p>';

    const fetchUrl = `${window.APP_URL}/loans/${loanId}/data`;
    console.log('Fetching loan data from:', fetchUrl);

    fetch(fetchUrl)
        .then(res => {
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        })
        .then(data => {
            if (nameSpan) nameSpan.textContent = data.member_name;
            if (numSpan) numSpan.textContent = data.membership_number;
            if (idSpan) idSpan.textContent = data.national_id;

            const form = document.getElementById('paymentForm');
            if (form) form.action = `${window.APP_URL}/loans/${loanId}/payment`;

            if (dropdown) {
                dropdown.innerHTML = '';
                if (data.unpaid_installments.length === 0) {
                    dropdown.innerHTML = '<p class="text-center py-2 text-[#124375]">لا يوجد أقساط مستحقة</p>';
                } else {
                    data.unpaid_installments.forEach(inst => {
                        const label = document.createElement('label');
                        label.className = 'flex items-center gap-2 cursor-pointer navy-shadow py-1 px-4 rounded-[8px]';
                        label.innerHTML = `
                            <input type="checkbox" class="hidden peer payment-checkbox" value="${inst.id}" data-amount="${inst.amount}">
                            <span class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                            </span>
                            <span>${inst.month_year} (${inst.amount} ج.م)</span>
                        `;
                        dropdown.appendChild(label);
                    });
                }

                // Bind checkbox events
                dropdown.querySelectorAll('.payment-checkbox').forEach(cb => {
                    cb.addEventListener('change', () => {
                        calculatePaymentTotal();
                        updatePaymentBtnText();
                    });
                });
            }

            document.getElementById('paymentTotalAmount').textContent = '0';
            document.getElementById('paymentHiddenInputs').innerHTML = '';

            const paymentBtn = document.querySelector('.dropDownBtn:has(+ #paymentInstallmentsDropdown)');
            if (paymentBtn) {
                const spans = paymentBtn.querySelectorAll('span');
                if (spans.length > 1) spans[1].textContent = "اختر الشهر";
            }
        })
        .catch(err => {
            console.error('Fetch loan data error:', err);
            if (nameSpan) nameSpan.textContent = 'خطأ في التحميل';
            if (dropdown) dropdown.innerHTML = '<p class="text-center py-2 text-red-500">فشل تحميل البيانات</p>';
        });
}

function calculatePaymentTotal() {
    const checkboxes = document.querySelectorAll('.payment-checkbox:checked');
    let total = 0;
    const hiddenInputsContainer = document.getElementById('paymentHiddenInputs');
    if (hiddenInputsContainer) hiddenInputsContainer.innerHTML = '';

    checkboxes.forEach(cb => {
        total += parseFloat(cb.getAttribute('data-amount'));
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'installment_ids[]';
        input.value = cb.value;
        if (hiddenInputsContainer) hiddenInputsContainer.appendChild(input);
    });

    const amountSpan = document.getElementById('paymentTotalAmount');
    if (amountSpan) amountSpan.textContent = total.toLocaleString();

    const submitBtn = document.querySelector('#paymentForm .submit-btn');
    if (submitBtn) {
        if (checkboxes.length > 0) submitBtn.classList.remove('btn-disabled');
        else submitBtn.classList.add('btn-disabled');
    }
}

function updatePaymentBtnText() {
    const paymentBtn = document.querySelector('.dropDownBtn:has(+ #paymentInstallmentsDropdown)');
    if (!paymentBtn) return;
    const paymentMenu = document.getElementById('paymentInstallmentsDropdown');
    const checked = paymentMenu.querySelectorAll('input[type="checkbox"]:checked');
    const spans = paymentBtn.querySelectorAll('span');

    if (spans.length > 1) {
        if (checked.length > 0) {
            const selectedValues = Array.from(checked).map(cb => cb.parentElement.querySelector('span:last-child').textContent.split(' (')[0]);
            spans[1].textContent = selectedValues.join(" ، ");
        } else {
            spans[1].textContent = "اختر الشهر";
        }
    }
}

// Initialize on DOMContentLoaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLoansModule);
} else {
    initLoansModule();
}
