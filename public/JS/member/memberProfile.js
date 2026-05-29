const editBtn = document.querySelector('.edit-btn');
const saveBtn = document.querySelector('.save-btn');
const inputs = document.querySelectorAll('.input');

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

saveBtn.addEventListener('click', () => {
    isEditMode = false;
    inputs.forEach(input => {
        input.disabled = true;
    });
    saveBtn.classList.add('hidden');
    editBtn.innerHTML = '<iconify-icon icon="ic:round-edit" class="text-2xl"></iconify-icon> تعديل البيانات';
});
