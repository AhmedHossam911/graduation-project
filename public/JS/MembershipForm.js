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
        if (selectedInput) {
            statusText.textContent = selectedInput.value;
            statusText.classList.remove('text-[#6D6D6D]');
            statusText.classList.add('text-[#124375]', 'font-bold', 'text-base');
            dropdown.classList.add('hidden');
        }
    });
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
