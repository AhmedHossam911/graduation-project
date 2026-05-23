const date = new Date();
const dateElement = document.getElementById('date');
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
if (dateElement) {
    const today = date.toLocaleDateString('ar-EG' , {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
    dateElement.textContent += today;
}

// Handle Inputs
function handleInputs(Inputs) {
    Inputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
            if (e.target.value.length > 1) {
                e.target.value = e.target.value.slice(0, 1);
            }
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

// close button
if (btnClose && successModal) {
    btnClose.addEventListener('click', () => {
        successModal.classList.add('hidden');
    });
}

// Handle Print Button
const printBtn = document.getElementById('print-btn');
const memberIdInput = document.getElementById('member_id');

if (printBtn && memberIdInput && memberIdInput.value) {
    printBtn.addEventListener('click', () => {
        window.print();
        const redirectUrl = `/members/${memberIdInput.value}/upload-signed`;
        let redirected = false;
        const handleRedirect = () => {
            if (!redirected) {
                redirected = true;
                window.location.href = redirectUrl;
            }
        };
        window.addEventListener('afterprint', handleRedirect);
        setTimeout(handleRedirect, 1000); 
    });
}
