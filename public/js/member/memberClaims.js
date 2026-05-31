// tabs variables
const tabs = document.querySelectorAll('.tabs button')
const tabContents = document.querySelectorAll('.tab-content')
// end tabs variables
// file variables
const inputsFile = document.querySelectorAll('input[type="file"]')
// end file variables

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

// tabs logic
tabs.forEach(tab => {
    tab.addEventListener('click', (e) => {
        e.preventDefault();
        tabs.forEach(t => {
            t.classList.remove('active-tab');
            t.classList.add('tab');
        });
        tab.classList.remove('tab');
        tab.classList.add('active-tab');
        const tabName = tab.textContent.trim();
        tabContents.forEach(content => {
            if (content.dataset.tab === tabName) {
                content.classList.remove('hidden');
            } else {
                content.classList.add('hidden');
            }
        });
    });
});
// tabs logic 