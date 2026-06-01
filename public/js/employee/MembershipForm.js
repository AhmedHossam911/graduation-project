const date = new Date();
const dateElement = document.getElementById('date');
const dropdownBtn = document.getElementById('dropdown-btn');
const dropdown = document.querySelector('.dropdown');
const phoneInputs = document.querySelectorAll(".phone-input");
const landlineInputs = document.querySelectorAll(".landline-input");
const idInputs = document.querySelectorAll(".id-input");
const numberInputs = document.querySelectorAll(".number-input");
const btnClose = document.querySelector('.btn-close');
const successModal = document.querySelector('.success-modal');
const inputFile = document.querySelectorAll('.input-file');
const fileName = document.querySelectorAll('.file-name');
const fileIcon = document.querySelectorAll('.file-icon');


inputFile.forEach((input, index) => {
    input.addEventListener('change', (e) => {
        fileName[index].textContent = e.target.files[0].name;
        fileIcon[index].classList.add('hidden');
    });
});


// Dynamic date
const today = date.toLocaleDateString('ar-EG', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
});
if (dateElement) {
    dateElement.textContent += today;
}
// Dynamic date

// DropDown
if (dropdownBtn && dropdown) {
    dropdownBtn.addEventListener('click', () => {
        dropdown.classList.toggle('hidden');
    });
}
// DropDown
const statusText = document.getElementById('status-text');
const confirmStatusBtn = document.getElementById('confirm-status-btn');

if (confirmStatusBtn && statusText) {
    confirmStatusBtn.addEventListener('click', () => {
        const selectedInput = document.querySelector('input[name="gender"]:checked');
        const hiddenInput = document.getElementById('marital_status_hidden');
        if (selectedInput) {
            statusText.textContent = selectedInput.value;
            statusText.classList.remove('text-[#6D6D6D]');
            statusText.classList.add('text-[#124375]', 'font-bold', 'text-base');
            if (hiddenInput) {
                hiddenInput.value = selectedInput.value;
            }
            dropdown.classList.add('hidden');
        }
    });

    // Pre-select marital status radio if a value is already set (edit mode)
    const hiddenInput = document.getElementById('marital_status_hidden');
    if (hiddenInput && hiddenInput.value) {
        const radios = document.querySelectorAll('input[name="gender"]');
        radios.forEach(radio => {
            if (radio.value === hiddenInput.value) {
                radio.checked = true;
            }
        });
        statusText.classList.remove('text-[#6D6D6D]');
        statusText.classList.add('text-[#124375]', 'font-bold', 'text-base');
    }
}

// Handle Inputs
function handleInputs(Inputs) {
    if (!Inputs || Inputs.length === 0) return;
    Inputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
            if (e.target.value.length === 1 && Inputs[index + 1]) {
                Inputs[index + 1].focus();
            }
        });
        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && e.target.value.length === 0 && Inputs[index - 1]) {
                Inputs[index - 1].focus();
            }
        });
    });
}
handleInputs(phoneInputs);
handleInputs(landlineInputs);
handleInputs(idInputs);
handleInputs(numberInputs);
// Handle Inputs

// close button
if (btnClose && successModal) {
    btnClose.addEventListener('click', () => {
        successModal.classList.add('hidden');
    });
}
// close button

// Handle Print Button
const printBtn = document.getElementById('print-btn');
const memberIdInput = document.getElementById('member_id');

if (printBtn && memberIdInput && memberIdInput.value) {
    printBtn.addEventListener('click', () => {
        // trigger print
        window.print();

        const redirectUrl = `/members/${memberIdInput.value}/upload-signed`;
        let redirected = false;

        const handleRedirect = () => {
            if (!redirected) {
                redirected = true;
                window.location.href = redirectUrl;
            }
        };

        // standard event
        window.addEventListener('afterprint', handleRedirect);

        // fallback (setTimeout runs after print dialog unblocks in most browsers)
        setTimeout(handleRedirect, 1000);
    });
}

// Calculate Retirement Date
const birthDay = document.getElementById('birth_day');
const birthMonth = document.getElementById('birth_month');
const birthYear = document.getElementById('birth_year');
const retDay = document.getElementById('retirement_day');
const retMonth = document.getElementById('retirement_month');
const retYear = document.getElementById('retirement_year');

if (birthDay && birthMonth && birthYear && retDay && retMonth && retYear) {
    const toEnglishDigits = (str) => {
        const arabicNumbers = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        return str.replace(/[٠-٩]/g, (w) => arabicNumbers.indexOf(w));
    };

    const calculateRetirement = () => {
        const d = toEnglishDigits(birthDay.value.trim());
        const m = toEnglishDigits(birthMonth.value.trim());
        let y = toEnglishDigits(birthYear.value.trim());

        if (d && m && y && (y.length === 4 || y.length === 2)) {
            let yearNum = parseInt(y, 10);
            let dayNum = parseInt(d, 10);
            let monthNum = parseInt(m, 10);
            if (!isNaN(yearNum) && !isNaN(dayNum) && !isNaN(monthNum)) {
                if (y.length === 2) {
                    yearNum = yearNum > 20 ? 1900 + yearNum : 2000 + yearNum;
                    y = yearNum.toString();
                }
                const rAge = typeof SYSTEM_RETIREMENT_AGE !== 'undefined' ? SYSTEM_RETIREMENT_AGE : 60;
                retDay.value = dayNum;
                retMonth.value = monthNum;
                retYear.value = yearNum + rAge;
            } else {
                retDay.value = '';
                retMonth.value = '';
                retYear.value = '';
            }
        } else {
            retDay.value = '';
            retMonth.value = '';
            retYear.value = '';
        }
    };

    birthDay.addEventListener('input', calculateRetirement);
    birthMonth.addEventListener('input', calculateRetirement);
    birthYear.addEventListener('input', calculateRetirement);

    // Run on initial load in case values are prepopulated by old()
    calculateRetirement();
}
