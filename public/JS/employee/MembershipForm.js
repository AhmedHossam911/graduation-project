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
const today = date.toLocaleDateString('ar-EG' , {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
});
dateElement.textContent += today;
// Dynamic date

// DropDown
dropdownBtn.addEventListener('click', () => {
    dropdown.classList.toggle('hidden');
});
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
function handleInputs (Inputs) {
    Inputs.forEach((input, index) => {
    input.addEventListener("input", (e) => {
        if (e.target.value.length === 1) {
            Inputs[index - 1].focus();
        }
    });
    input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && e.target.value.length === 0) {
            Inputs[index + 1].focus();
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
btnClose.addEventListener('click', () => {
    successModal.classList.add('hidden');
});
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
