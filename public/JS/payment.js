const closeBtns = document.querySelectorAll('.close-btn')
const overlay = document.querySelector('.overlay')
const modal = document.querySelector('.modal')
const approvalBtn = document.querySelector('.approval-btn')
const inputsFile = document.querySelectorAll('input[type="file"]')

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

approvalBtn.addEventListener('click' , () => {
    modal.classList.remove('hidden')
    overlay.classList.remove('hidden')
})

closeBtns.forEach((btn) => {
    btn.addEventListener('click' , () => {
        modal.classList.add('hidden')
        overlay.classList.add('hidden')
    })
})