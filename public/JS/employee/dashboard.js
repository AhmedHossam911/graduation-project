const menuBtn = document.getElementById("Nav-menu");
const sideBar = document.querySelector(".SideBar");
const notiBtn = document.querySelector(".notification-btn");
const notiBox = document.querySelector(".notifications-box");

// modals variables
const modal = document.querySelectorAll(".modal")
const modalClose = document.querySelectorAll(".modal-close")
const openModal = document.querySelectorAll(".open-modal")
const overlay = document.querySelector(".overlay")
// end modals variables

// file variables
const inputsFile = document.querySelectorAll('input[type="file"]')
// end file variables

// dropDown variables
const dropDownBtn = document.querySelectorAll(".dropDownBtn")
const dropDown = document.querySelectorAll(".dropDown")
// end dropDown variables

notiBtn.addEventListener("click", () => {
    notiBox.classList.toggle("hidden");
});

menuBtn.addEventListener("click", () => {
    sideBar.classList.toggle("active");
    sideBar.classList.toggle("side-bar");
});

// modals logic
openModal.forEach((btn, index) => {
    btn.addEventListener("click", () => {
        modal[index].classList.remove("hidden")
        overlay.classList.remove("hidden")
    })
})
modalClose.forEach((btn, index) => {
    btn.addEventListener("click", () => {
        modal[index].classList.add("hidden")
        overlay.classList.add("hidden")
    })
})
// end modals logic

// file inputs logic
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
// end file inputs logic

// dropDown logic
function updateCheckboxDropdownText(menu, btn) {
    const checked = menu.querySelectorAll('input[type="checkbox"]:checked');
    const spans = btn.querySelectorAll("span");
    if (spans.length > 1) {
        if (checked.length > 0) {
            const selectedValues = Array.from(checked).map(cb => cb.getAttribute('data-month') || cb.value);
            spans[1].textContent = selectedValues.join(" ، ");
        } else {
            spans[1].textContent = "اختر الشهر";
        }
    }
}

dropDownBtn.forEach((btn, index) => {
    const hasCheckboxes = dropDown[index].querySelector('input[type="checkbox"]') !== null;

    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        if (hasCheckboxes && !dropDown[index].classList.contains("hidden")) {
            updateCheckboxDropdownText(dropDown[index], btn);
        }
        dropDown.forEach((d, i) => {
            if (i !== index) d.classList.add("hidden");
        });
        dropDown[index].classList.toggle("hidden");
    });
    if (hasCheckboxes) {
        dropDown[index].addEventListener("click", (e) => {
            e.stopPropagation();
        });
    }
});
 
dropDown.forEach((menu, index) => {
    const hasCheckboxes = menu.querySelector('input[type="checkbox"]') !== null;
    if (!hasCheckboxes) {
        const items = menu.querySelectorAll("button");
        items.forEach(item => {
            item.addEventListener("click", (e) => {
                e.stopPropagation();
                const spans = dropDownBtn[index].querySelectorAll("span");
                if (spans.length > 0) {
                    spans[1].textContent = item.textContent;
                }
                menu.classList.add("hidden");
            });
        });
    }
});

document.addEventListener("click", () => {
    dropDown.forEach((menu, index) => {
        const hasCheckboxes = menu.querySelector('input[type="checkbox"]') !== null;
        if (hasCheckboxes && !menu.classList.contains("hidden")) {
            updateCheckboxDropdownText(menu, dropDownBtn[index]);
        }
        menu.classList.add("hidden");
    });
});
// end dropDown logic

// --- DASHBOARD AJAX LOGIC ---

(() => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    async function searchMember(query, type) {
        if (!query.trim()) return;
        
        try {
            const url = window.appRoutes ? window.appRoutes.searchMember + '?q=' + encodeURIComponent(query) : `/dashboard/search-member?q=${encodeURIComponent(query)}`;
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await res.json();
            
            if (!data.success) {
                alert(data.message);
                return;
            }

            const member = data.member;

            if (type === 'subscription') {
                document.getElementById('sub-member-info').classList.remove('hidden');
                document.getElementById('sub-member-name').textContent = member.full_name;
                document.getElementById('sub-membership-number').textContent = member.membership_number;
                document.getElementById('sub-member-id').value = member.id;

                const subs = data.subscriptions;
                const container = document.getElementById('sub-months-dropdown');
                container.innerHTML = '';
                
                if (subs && subs.length > 0) {
                    document.getElementById('sub-due-date').textContent = subs[0].month_year;
                    document.getElementById('sub-amount').textContent = subs[0].amount + ' ج.م';
                    
                    subs.forEach(sub => {
                        container.innerHTML += `
                            <label class="flex items-center gap-2 cursor-pointer surface-shadow py-1 px-4 rounded-[8px]">
                                <input type="checkbox" class="hidden peer" value="${sub.id}" data-amount="${sub.amount}" data-month="${sub.month_year}">
                                <span class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                    <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                                </span>
                                <span>${sub.month_year}</span>
                            </label>`;
                    });
                    
                    // Add change listener to calculate selected amount
                    container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                        cb.addEventListener('change', () => {
                            let total = 0;
                            const checkedBoxes = container.querySelectorAll('input[type="checkbox"]:checked');
                            if (checkedBoxes.length > 0) {
                                checkedBoxes.forEach(c => {
                                    total += parseFloat(c.getAttribute('data-amount'));
                                });
                            } else {
                                total = 0;
                            }
                            document.getElementById('sub-amount').textContent = total + ' ج.م';
                        });
                    });
                } else {
                    document.getElementById('sub-due-date').textContent = '-';
                    document.getElementById('sub-amount').textContent = '-';
                    container.innerHTML = '<p class="text-sm text-center text-gray-500 py-2">لا يوجد اشتراكات مستحقة</p>';
                }
                
                // Re-bind click events for new dropdown items
                container.addEventListener("click", (e) => e.stopPropagation());

            } else if (type === 'installment') {
                document.getElementById('inst-member-info').classList.remove('hidden');
                document.getElementById('inst-member-name').textContent = member.full_name;
                
                const instMemberIdEl = document.getElementById('inst-member-id');
                if(instMemberIdEl) instMemberIdEl.value = member.id;

                if (data.loan) {
                    document.getElementById('inst-loan-number').textContent = data.loan.id;
                    document.getElementById('inst-loan-remaining').textContent = data.loan.remaining_amount + ' ج.م';
                    document.getElementById('inst-loan-id').value = data.loan.id;
                    
                    const container = document.getElementById('inst-months-dropdown');
                    container.innerHTML = '';
                    
                    if (data.loan.installments && data.loan.installments.length > 0) {
                        data.loan.installments.forEach(inst => {
                            container.innerHTML += `
                                <label class="flex items-center gap-2 cursor-pointer surface-shadow py-1 px-4 rounded-[8px]">
                                    <input type="checkbox" class="hidden peer" value="${inst.id}" data-amount="${inst.amount}" data-month="${inst.month_year}">
                                    <span class="custom-checkbox flex items-center justify-center h-[17px] w-[17px] rounded-sm border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:border-[#124375] text-transparent peer-checked:text-white transition-all duration-200">
                                        <iconify-icon icon="mdi:check-bold" class="text-[14px]"></iconify-icon>
                                    </span>
                                    <span>${inst.month_year}</span>
                                </label>`;
                        });
                        
                        // Add change listener to calculate selected amount
                        container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                            cb.addEventListener('change', () => {
                                let total = 0;
                                container.querySelectorAll('input[type="checkbox"]:checked').forEach(c => {
                                    total += parseFloat(c.getAttribute('data-amount'));
                                });
                                document.getElementById('inst-amount-selected').textContent = total;
                            });
                        });
                    } else {
                        container.innerHTML = '<p class="text-sm text-center text-gray-500 py-2">لا يوجد أقساط مستحقة</p>';
                    }
                    container.addEventListener("click", (e) => e.stopPropagation());
                } else {
                    document.getElementById('inst-loan-number').textContent = '-';
                    document.getElementById('inst-loan-remaining').textContent = '-';
                    document.getElementById('inst-months-dropdown').innerHTML = '<p class="text-sm text-center text-gray-500 py-2">لا يوجد قرض نشط</p>';
                }
            } else if (type === 'claim') {
                document.getElementById('claim-member-info').classList.remove('hidden');
                document.getElementById('claim-member-name').textContent = member.full_name;
                document.getElementById('claim-membership-number').textContent = member.membership_number;
                document.getElementById('claim-national-id').textContent = member.national_id;
                document.getElementById('claim-member-id').value = member.id;
            } else if (type === 'global') {
                const tbody = document.getElementById('search-results-tbody');
                
                let loanNum = '-';
                if (data.loan && data.loan.id) {
                    loanNum = data.loan.id;
                }

                if (tbody) {
                    tbody.innerHTML = `
                        <tr class="text-center">
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">${member.membership_number || '-'}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">${member.full_name}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">${member.national_id}</td>
                            <td class="py-3 border-l border-[#6D6D6D] text-[#021219]">${loanNum}</td>
                            <td class="py-3  text-[#124375]">
                                <a href="${window.appRoutes ? window.appRoutes.memberProfile(member.id) : '/members/' + member.id}" class="inline-block hover:text-[#0e3560]">
                                    <iconify-icon icon="solar:eye-linear" class="text-2xl"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    `;
                }

                // Show dropdown instead of modal
                const searchResultsContainer = document.getElementById('global-search-results');
                if (searchResultsContainer) {
                    searchResultsContainer.classList.remove('hidden');
                }
            }
        } catch (error) {
            console.error('Error fetching member:', error);
            alert('حدث خطأ أثناء البحث عن العضو');
        }
    }

    // Modal 1: Subscription
    const subInput = document.getElementById('sub-search-input');
    const subBtn = document.getElementById('sub-search-btn');
    if (subInput && subBtn) {
        subInput.addEventListener('input', debounce(() => searchMember(subInput.value, 'subscription'), 500));
        subBtn.addEventListener('click', () => searchMember(subInput.value, 'subscription'));
    }

    // Modal 2: Installment
    const instInput = document.getElementById('inst-search-input');
    const instBtn = document.getElementById('inst-search-btn');
    if (instInput && instBtn) {
        instInput.addEventListener('input', debounce(() => searchMember(instInput.value, 'installment'), 500));
        instBtn.addEventListener('click', () => searchMember(instInput.value, 'installment'));
    }

    // Modal 3: Claim
    const claimInput = document.getElementById('claim-search-input');
    const claimBtn = document.getElementById('claim-search-btn');
    if (claimInput && claimBtn) {
        claimInput.addEventListener('input', debounce(() => searchMember(claimInput.value, 'claim'), 500));
        claimBtn.addEventListener('click', () => searchMember(claimInput.value, 'claim'));
    }
    
    // Set Claim Type hidden input when clicked
    document.querySelectorAll('#claim-type').forEach(el => {
        const claimTypesContainer = el.closest('.modal-body').querySelector('.dropDown');
        if (claimTypesContainer) {
            claimTypesContainer.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('click', () => {
                    el.value = btn.getAttribute('data-value');
                });
            });
        }
    });

    // Modal 4: Global Search
    const globalInput = document.getElementById('global-search-input');
    const globalBtn = document.getElementById('global-search-btn');
    const closeGlobalBtn = document.getElementById('close-global-search');
    
    if (closeGlobalBtn) {
        closeGlobalBtn.addEventListener('click', () => {
            const container = document.getElementById('global-search-results');
            if (container) container.classList.add('hidden');
        });
    }

    if (globalInput && globalBtn) {
        globalInput.addEventListener('input', debounce(() => searchMember(globalInput.value, 'global'), 500));
        globalBtn.addEventListener('click', () => searchMember(globalInput.value, 'global'));
        
        // Also allow pressing Enter
        globalInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchMember(globalInput.value, 'global');
            }
        });
    }

    // SUBMIT SUBSCRIPTION
    const subSubmit = document.getElementById('sub-submit-btn');
    if (subSubmit) {
        subSubmit.addEventListener('click', async () => {
            const memberId = document.getElementById('sub-member-id').value;
            const subsCheckboxes = document.getElementById('sub-months-dropdown').querySelectorAll('input[type="checkbox"]:checked');
            const receiptNum = document.getElementById('sub-receipt-number').value;
            const receiptImg = document.getElementById('sub-receipt-image').files[0];
            
            if (!memberId || subsCheckboxes.length === 0 || !receiptNum || !receiptImg) {
                alert('يرجى التأكد من البحث عن العضو واختيار شهر الدفع وإدخال رقم الإيصال وإرفاق صورته.');
                return;
            }

            subSubmit.disabled = true;
            subSubmit.innerHTML = 'جاري التسجيل...';

            // Pay the first selected subscription for now
            // To support multiple, backend would need to loop. But the UI lets them select multiple, so we will send the first one as standard, or we should loop in JS and send requests sequentially if backend doesn't support array.
            // Wait, we can just send multiple requests to the pay endpoint.
            
            let hasError = false;
            for (let cb of subsCheckboxes) {
                const subId = cb.value;
                const formData = new FormData();
                formData.append('receipt_number', receiptNum);
                formData.append('receipt_image', receiptImg);
                formData.append('_token', csrfToken);
                formData.append('source', 'dashboard');
                
                try {
                    const postUrl = window.appRoutes ? window.appRoutes.paySubscription(subId) : `/subscriptions/${subId}/pay`;
                    const res = await fetch(postUrl, {
                        method: 'POST',
                        body: formData
                    });
                    if (!res.ok) throw new Error('Network response was not ok');
                } catch(e) {
                    hasError = true;
                }
            }
            
            if (!hasError) {
                const redirectUrl = window.appRoutes ? window.appRoutes.memberProfile(memberId) + '?tab=subscriptions' : `/members/${memberId}?tab=subscriptions`;
                window.location.href = redirectUrl;
            } else {
                alert('حدث خطأ أثناء تسجيل الدفع.');
                subSubmit.disabled = false;
                subSubmit.innerHTML = 'تسجيل سداد الإشتراك';
            }
        });
    }

    // SUBMIT INSTALLMENT
    const instSubmit = document.getElementById('inst-submit-btn');
    if (instSubmit) {
        instSubmit.addEventListener('click', async () => {
            const loanId = document.getElementById('inst-loan-id').value;
            const instCheckboxes = document.getElementById('inst-months-dropdown').querySelectorAll('input[type="checkbox"]:checked');
            const receiptNum = document.getElementById('inst-receipt-number').value;
            const receiptImg = document.getElementById('inst-receipt-image').files[0];
            
            if (!loanId || instCheckboxes.length === 0 || !receiptNum || !receiptImg) {
                alert('يرجى التأكد من البحث عن العضو واختيار القسط وإدخال رقم الإيصال وإرفاق صورته.');
                return;
            }

            instSubmit.disabled = true;
            instSubmit.innerHTML = 'جاري التسجيل...';

            let hasError = false;
            for (let cb of instCheckboxes) {
                const formData = new FormData();
                formData.append('installment_id', cb.value);
                formData.append('amount', cb.getAttribute('data-amount'));
                formData.append('receipt_number', receiptNum);
                formData.append('receipt_image', receiptImg);
                formData.append('_token', csrfToken);
                formData.append('source', 'dashboard');
                
                try {
                    const postUrl = window.appRoutes ? window.appRoutes.payInstallment(cb.value) : `/loans/installments/${cb.value}/pay`;
                    const res = await fetch(postUrl, {
                        method: 'POST',
                        body: formData
                    });
                    if (!res.ok) throw new Error('Network response was not ok');
                } catch(e) {
                    hasError = true;
                }
            }

            if (!hasError) {
                // Find member id from somewhere? We can just reload the dashboard or use loan API to get member id.
                // Wait, we don't have memberId explicitly in installment modal.
                // Let's add it.
                // It will be easier to just redirect to the loan page if we don't have member ID.
                // But wait, the searchMember returns member info and we can just add `inst-member-id`
                const memberIdEl = document.getElementById('inst-member-id');
                const mid = memberIdEl ? memberIdEl.value : '';
                if(mid) {
                    const redirectUrl = window.appRoutes ? window.appRoutes.memberProfile(mid) + '?tab=loans' : `/members/${mid}?tab=loans`;
                    window.location.href = redirectUrl;
                } else {
                    window.location.reload();
                }
            } else {
                alert('حدث خطأ أثناء تسجيل الدفع.');
                instSubmit.disabled = false;
                instSubmit.innerHTML = 'تسجيل سداد القسط';
            }
        });
    }

    // SUBMIT CLAIM
    const claimSubmit = document.getElementById('claim-submit-btn');
    if (claimSubmit) {
        claimSubmit.addEventListener('click', async () => {
            const memberId = document.getElementById('claim-member-id').value;
            const type = document.getElementById('claim-type').value;
            
            if (!memberId || !type) {
                alert('يرجى التأكد من البحث عن العضو واختيار نوع المطالبة.');
                return;
            }

            claimSubmit.disabled = true;
            claimSubmit.innerHTML = 'جاري التسجيل...';

            const formData = new FormData();
            formData.append('claim_type', type);
            formData.append('_token', csrfToken);
            formData.append('source', 'dashboard');

            try {
                const postUrl = window.appRoutes ? window.appRoutes.createClaim(memberId) : `/members/${memberId}/claim`;
                const res = await fetch(postUrl, {
                    method: 'POST',
                    body: formData
                });
                if (res.ok) {
                    const redirectUrl = window.appRoutes ? window.appRoutes.memberProfile(memberId) + '?tab=claims' : `/members/${memberId}?tab=claims`;
                    window.location.href = redirectUrl;
                } else {
                    throw new Error('Network error');
                }
            } catch(error) {
                console.error(error);
                alert('حدث خطأ أثناء إنشاء المطالبة. تأكد من أن هذا النوع لا يتطلب إرفاق ملفات ضرورية فوراً.');
                claimSubmit.disabled = false;
                claimSubmit.innerHTML = 'تأكيد الأختيار';
            }
        });
    }
})();
