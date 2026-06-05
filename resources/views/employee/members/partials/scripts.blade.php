{{-- 
    Member Scripts Partial:
    Includes all JavaScript logic needed for the member profile view,
    handling modal interactions, tab switching, form validation, and dynamic UI updates.
--}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const requestLoanBtn = document.getElementById('request-loan-btn');
            const loanRequestForm = document.getElementById('loan-request-form');
            const loansContentContainer = document.getElementById('loans-content-container');
            const loansActionButtons = document.getElementById('loans-action-buttons');
            const closeLoanRequestBtn = document.querySelector('.close-loan-request-modal');

            if (requestLoanBtn && loanRequestForm) {
                requestLoanBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    loanRequestForm.classList.remove('hidden');
                    if (loansContentContainer) loansContentContainer.classList.add('hidden');
                    if (loansActionButtons) loansActionButtons.classList.add('hidden');
                });
            }

            if (closeLoanRequestBtn && loanRequestForm) {
                closeLoanRequestBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    loanRequestForm.classList.add('hidden');
                    if (loansContentContainer) loansContentContainer.classList.remove('hidden');
                    if (loansActionButtons) loansActionButtons.classList.remove('hidden');
                });
            }

            const tabs = document.querySelectorAll('.tabs button');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    if (loanRequestForm) {
                        loanRequestForm.classList.add('hidden');
                    }
                });
            });

            // Handle custom dropdowns for loan form
            document.querySelectorAll('.loan-amount-option').forEach(option => {
                option.addEventListener('click', function() {
                    const selectedAmount = document.getElementById('selected_total_amount');
                    if (selectedAmount) selectedAmount.value = this.dataset.value;

                    const amountBtn = document.getElementById('amount_dropdown_btn');
                    const amountError = document.getElementById('amount_error_msg');
                    if (amountBtn) {
                        amountBtn.classList.remove('border', 'border-[#D92D20]', 'text-[#D92D20]');
                        amountBtn.classList.add('text-[#124375]');
                    }
                    if (amountError) amountError.classList.add('hidden');
                });
            });
            document.querySelectorAll('.loan-months-option').forEach(option => {
                option.addEventListener('click', function() {
                    const selectedMonths = document.getElementById('selected_months');
                    if (selectedMonths) selectedMonths.value = this.dataset.value;

                    const monthsBtn = document.getElementById('months_dropdown_btn');
                    const monthsError = document.getElementById('months_error_msg');
                    if (monthsBtn) {
                        monthsBtn.classList.remove('border', 'border-[#D92D20]', 'text-[#D92D20]');
                        monthsBtn.classList.add('text-[#124375]');
                    }
                    if (monthsError) monthsError.classList.add('hidden');
                });
            });

            const proceedBtn = document.getElementById('proceed-to-declaration-btn');
            if (proceedBtn) {
                proceedBtn.addEventListener('click', function(e) {
                    const totalAmount = document.getElementById('selected_total_amount');
                    const months = document.getElementById('selected_months');
                    let isValid = true;

                    const amountBtn = document.getElementById('amount_dropdown_btn');
                    const amountError = document.getElementById('amount_error_msg');
                    if (!totalAmount || !totalAmount.value) {
                        if (amountBtn) {
                            amountBtn.classList.add('border', 'border-[#D92D20]', 'text-[#D92D20]');
                            amountBtn.classList.remove('text-[#124375]');
                        }
                        if (amountError) amountError.classList.remove('hidden');
                        isValid = false;
                    }

                    const monthsBtn = document.getElementById('months_dropdown_btn');
                    const monthsError = document.getElementById('months_error_msg');
                    if (!months || !months.value) {
                        if (monthsBtn) {
                            monthsBtn.classList.add('border', 'border-[#D92D20]', 'text-[#D92D20]');
                            monthsBtn.classList.remove('text-[#124375]');
                        }
                        if (monthsError) monthsError.classList.remove('hidden');
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        return false;
                    }

                    // Calculate and populate the summary
                    const amountVal = parseFloat(totalAmount.value);
                    const monthsVal = parseInt(months.value);

                    const interestRate =
                        {{ \App\Models\System\SystemSetting::get('loan_interest_rate', 8) }};
                    const years = monthsVal / 12;
                    const interestAmount = (interestRate / 100) * amountVal * years;
                    const totalWithInterest = amountVal + interestAmount;
                    const installmentAmount = totalWithInterest / monthsVal;

                    document.getElementById('summary_base_amount').textContent = amountVal.toLocaleString(
                        undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    document.getElementById('summary_interest_amount').textContent = interestAmount
                        .toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    document.getElementById('summary_total_amount').textContent = totalWithInterest
                        .toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    document.getElementById('summary_installment_amount').textContent = installmentAmount
                        .toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    document.getElementById('summary_months').textContent = monthsVal;

                    // Set the href for the print button
                    const printBtn = document.getElementById('print-declaration-btn');
                    if (printBtn) {
                        const baseUrl = "{{ route('print.new_loan_declaration', $member->id) }}";
                        printBtn.href =
                            `${baseUrl}?amount=${amountVal}&months=${monthsVal}&interest=${interestAmount}&total=${totalWithInterest}&installment=${installmentAmount}`;
                    }

                    // Open the modal and overlay manually
                    const modal2 = document.getElementById('modal2');
                    const overlay = document.querySelector('.overlay');
                    if (modal2) modal2.classList.remove('hidden');
                    if (overlay) overlay.classList.remove('hidden');
                });
            }

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('installments-table');
            if (!table) return;
            const tbody = table.querySelector('tbody');
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const rowsPerPage = 12;

            if (rows.length > rowsPerPage) {
                const totalPages = Math.ceil(rows.length / rowsPerPage);

                function displayPage(page) {
                    rows.forEach((row, index) => {
                        if (index >= (page - 1) * rowsPerPage && index < page * rowsPerPage) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                }

                displayPage(1);

                const paginationContainer = document.createElement('div');
                paginationContainer.className = 'flex justify-center items-center gap-2 mt-6 pb-6';

                for (let i = 1; i <= totalPages; i++) {
                    const btn = document.createElement('button');
                    btn.innerText = i;
                    btn.className =
                        'w-10 h-10 flex items-center justify-center rounded-lg border font-medium transition-colors ' +
                        (i === 1 ? 'bg-[#124375] text-white border-[#124375]' :
                            'bg-white text-[#124375] border-[#124375] hover:bg-gray-50');

                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        displayPage(i);

                        Array.from(paginationContainer.children).forEach(child => {
                            child.className =
                                'w-10 h-10 flex items-center justify-center rounded-lg border font-medium transition-colors bg-white text-[#124375] border-[#124375] hover:bg-gray-50';
                        });
                        this.className =
                            'w-10 h-10 flex items-center justify-center rounded-lg border font-medium transition-colors bg-[#124375] text-white border-[#124375]';
                    });

                    paginationContainer.appendChild(btn);
                }

                const tableWrapper = table.closest('.rounded-\\[14px\\]');
                if (tableWrapper) {
                    tableWrapper.parentNode.insertBefore(paginationContainer, tableWrapper.nextSibling);
                }
            }
        });
    </script>
    <script src="{{ asset('js/employee/member.js') }}?v={{ time() }}"></script>
    @if (session('receipt_data'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let receiptData = {!! session('receipt_data') !!};
                Swal.fire({
                    html: `
                    <div class="receipt-container p-6 text-center" style="background-color: #F4F7F9; border-radius: 12px; font-family: 'Tajawal', sans-serif; direction: rtl;">
                        <h2 class="text-2xl font-bold text-[#124375] mb-4">تم تسجيل العضوية بنجاح</h2>
                        <div class="mb-6 flex flex-col items-center justify-center gap-2">
                            <iconify-icon icon="line-md:confirm-circle" class="text-6xl text-[#067647]"></iconify-icon>
                            <p class="text-lg text-[#021219]">رقم العضوية: <span class="font-bold">${receiptData.membership_number}</span></p>
                        </div>
                        <button onclick="window.open('{{ route('print.new_membership_receipt', $member->id) }}', '_blank')" class="w-full bg-[#124375] text-white py-3 rounded-xl font-bold text-lg flex justify-center items-center gap-2 hover:bg-[#0e3560] transition-colors">
                            <iconify-icon icon="material-symbols:print-rounded" class="text-2xl"></iconify-icon> طباعة إيصال الاشتراك
                        </button>
                    </div>
                `,
                    showConfirmButton: false,
                    width: '800px',
                    background: '#F4F7F9',
                    customClass: {
                        popup: 'rounded-2xl border border-[#124375]'
                    }
                });
            });
        </script>
    @endif

    <script>
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                const container = this.closest('.relative');
                const hiddenInput = container.querySelector('.payment-method-input');
                if (hiddenInput) hiddenInput.value = this.dataset.value;

                const btn = container.querySelector('.payment-method-btn');
                const errorMsg = container.querySelector('.payment_error_msg');
                if (btn) {
                    btn.classList.remove('border-[#D92D20]', 'text-[#D92D20]');
                    btn.classList.add('border', 'border-[#124375]', 'text-[#124375]');
                }
                if (errorMsg) errorMsg.classList.add('hidden');
            });
        });

        const paymentForms = document.querySelectorAll(
            'form[action*="installments"], form[action*="subscriptions"], form[action*="early-repayment"]');
        paymentForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                let hasError = false;
                const hiddenInput = this.querySelector('.payment-method-input');
                if (hiddenInput && !hiddenInput.value) {
                    hasError = true;
                    const container = hiddenInput.closest('.relative');
                    if (container) {
                        const btn = container.querySelector('.payment-method-btn');
                        if (btn) {
                            btn.classList.remove('border-[#124375]', 'text-[#124375]');
                            btn.classList.add('border', 'border-[#D92D20]', 'text-[#D92D20]');
                        }
                        const errorMsg = container.querySelector('.payment_error_msg');
                        if (errorMsg) {
                            errorMsg.classList.remove('hidden');
                        }
                    }
                }
                
                const receiptInput = this.querySelector('input[type="file"][name="receipt_image"]');
                if (receiptInput && !receiptInput.files.length) {
                    hasError = true;
                    const container = receiptInput.closest('.border.rounded-2xl') || receiptInput.closest('.border');
                    if (container) {
                        container.classList.remove('border-[#124375]');
                        container.classList.add('border-[#D92D20]');
                    }
                    const labelP = receiptInput.closest('label').querySelector('p');
                    if (labelP) {
                        labelP.textContent = 'يجب إرفاق صورة إيصال السداد';
                        labelP.classList.remove('text-[#6D6D6D]', 'text-[#124375]');
                        labelP.classList.add('text-[#D92D20]');
                    }
                }

                if (hasError) {
                    e.preventDefault();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('open_declaration_modal') === '1') {
                const safeParse = (val) => {
                    if (!val) return 0;
                    const parsed = parseFloat(String(val).replace(/,/g, ''));
                    return isNaN(parsed) ? 0 : parsed;
                };

                const totalAmount = urlParams.get('create_loan_amount');
                const months = urlParams.get('create_loan_months');
                const safeMonths = parseInt(months) || 0;

                let baseAmount = safeParse(urlParams.get('loan_base_amount'));
                let interestAmount = safeParse(urlParams.get('loan_interest_amount'));
                let totalWithInterest = safeParse(urlParams.get('loan_total_amount'));
                let installmentAmount = safeParse(urlParams.get('loan_installment_amount'));

                // Fallback to calculation if URL params are missing
                if (!baseAmount && totalAmount) {
                    baseAmount = safeParse(totalAmount);
                    const interestRate = {{ \App\Models\System\SystemSetting::get('loan_interest_rate', 8) }};
                    const years = safeMonths / 12;
                    interestAmount = (interestRate / 100) * baseAmount * years;
                    totalWithInterest = baseAmount + interestAmount;
                    installmentAmount = safeMonths > 0 ? totalWithInterest / safeMonths : 0;
                }

                document.getElementById('selected_total_amount').value = totalAmount || baseAmount;
                document.getElementById('selected_months').value = months || safeMonths;

                // Populate summary
                document.getElementById('summary_base_amount').textContent = baseAmount.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                document.getElementById('summary_interest_amount').textContent = interestAmount.toLocaleString(
                    undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                document.getElementById('summary_total_amount').textContent = totalWithInterest.toLocaleString(
                    undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                document.getElementById('summary_installment_amount').textContent = installmentAmount
                    .toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                document.getElementById('summary_months').textContent = safeMonths;

                // Set the href for the print button
                const printBtn = document.getElementById('print-declaration-btn');
                if (printBtn) {
                    const baseUrl = "{{ route('print.new_loan_declaration', $member->id) }}";
                    printBtn.href =
                        `${baseUrl}?amount=${baseAmount}&months=${safeMonths}&interest=${interestAmount}&total=${totalWithInterest}&installment=${installmentAmount}`;
                }

                // Open Modal 2
                const modal2 = document.getElementById('modal2');
                const overlay = document.querySelector('.overlay');
                if (modal2) {
                    modal2.classList.remove('hidden');
                }
                if (overlay) {
                    overlay.classList.remove('hidden');
                }
            }
        });
    </script>
