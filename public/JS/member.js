const suspensionBtn = document.querySelector('.suspension-btn')
const suspensionModal = document.querySelector('.suspension-modal')
const overlay = document.querySelector('.overlay')
const closeBtns = document.querySelectorAll('.close-btn')
const textarea = document.querySelector('textarea')
const dropDownBtn = document.querySelector(".dropDownBtn");
const dropDown = document.querySelector(".dropDown");
const items = dropDown.querySelectorAll(".button"); 
const requestModal1 = document.querySelector('.request-modal-1')
const submitBtn = document.querySelector('.submit-btn')
const requestModal2 = document.querySelector('.request-modal-2')
const inputsFile = document.querySelectorAll('input[type="file"]')
const params = new URLSearchParams(window.location.search);
const type = params.get("type");

submitBtn.addEventListener('click' , () => {
    requestModal1.classList.add('hidden')
    requestModal2.classList.remove('hidden')
})

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

suspensionBtn.addEventListener('click' , () => {
    suspensionModal.classList.remove('hidden')
    overlay.classList.remove('hidden')
})

closeBtns.forEach((btn) => {
    btn.addEventListener('click' , () => {
        suspensionModal.classList.add('hidden')
        requestModal1.classList.add('hidden')
        requestModal2.classList.add('hidden')
        overlay.classList.add('hidden')
    })
})

dropDownBtn.addEventListener("click", () => {
    dropDown.classList.toggle("hidden");
});


