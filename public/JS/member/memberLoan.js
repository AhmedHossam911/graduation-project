// drop down menu variables
const dropDownBtn = document.querySelectorAll(".dropDownBtn")
const dropDown = document.querySelectorAll(".dropDown")
// end drop down menu variables

// Form variables
const amountInput = document.getElementById("total_amount_input");
const monthsInput = document.getElementById("months_input");
const checkbox = document.querySelector("input[name='digital_declaration_checkbox']");
const submitBtn = document.getElementById("submit_btn");

const summaryAmount = document.getElementById("summary_amount");
const summaryInterest = document.getElementById("summary_interest");
const summaryTotal = document.getElementById("summary_total");
const summaryInstallment = document.getElementById("summary_installment");

function updateSummary() {
    const amount = parseFloat(amountInput.value) || 0;
    const months = parseInt(monthsInput.value) || 0;

    if (amount > 0 && months > 0) {
        const interestRate = 0.08;
        const interest = (amount * interestRate) * (months / 12);
        const total = amount + interest;
        const installment = total / months;

        summaryAmount.textContent = amount.toLocaleString('en-US');
        summaryInterest.textContent = interest.toLocaleString('en-US');
        summaryTotal.textContent = total.toLocaleString('en-US');
        summaryInstallment.textContent = Math.ceil(installment).toLocaleString('en-US');
    } else {
        summaryAmount.textContent = "0";
        summaryInterest.textContent = "0";
        summaryTotal.textContent = "0";
        summaryInstallment.textContent = "0";
    }

    validateForm();
}

function validateForm() {
    if (amountInput.value && monthsInput.value && checkbox.checked) {
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        submitBtn.removeAttribute('disabled');
    } else {
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        submitBtn.setAttribute('disabled', 'true');
    }
}

checkbox.addEventListener("change", validateForm);

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
            }
            
            // Set hidden inputs
            if (item.classList.contains("amount-option")) {
                amountInput.value = item.getAttribute("data-value");
            } else if (item.classList.contains("months-option")) {
                monthsInput.value = item.getAttribute("data-value");
            }
            
            updateSummary();
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

// Initial state
validateForm();