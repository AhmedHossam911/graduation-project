const editBtn = document.querySelector('.edit-btn');
const saveBtn = document.querySelector('.save-btn');
const inputs = document.querySelectorAll('.input');
const showBtn = document.querySelectorAll(".show-btn")
const input = document.querySelectorAll(".input-field")

let isEditMode = false;
editBtn.addEventListener('click', () => {
    isEditMode = !isEditMode;

    if (isEditMode) {
        inputs.forEach(input => {
            input.disabled = false;
        });
        saveBtn.classList.remove('hidden');
        editBtn.innerHTML = '<iconify-icon icon="zondicons:close-solid" class="text-2xl"></iconify-icon> إلغاء التعديل';
    } else {
        inputs.forEach(input => {
            input.disabled = true;
        });
        saveBtn.classList.add('hidden');
        editBtn.innerHTML = '<iconify-icon icon="ic:round-edit" class="text-2xl"></iconify-icon> تعديل البيانات';
    }
});

showBtn.forEach((btn , index) => {
    btn.addEventListener("click" , () => {
        input[index].type === "password" ? input[index].type = "text" : input[index].type = "password"
    })
})

