// tabs variables
const tabs = document.querySelectorAll('.tabs button')
const tabContents = document.querySelectorAll('.tab-content')
// end tabs variables

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