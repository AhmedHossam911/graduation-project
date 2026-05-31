// drop down menu variables
const dropDownBtn = document.querySelectorAll(".dropDownBtn")
const dropDown = document.querySelectorAll(".dropDown")
// end drop down menu variables

// drop down menu logic
dropDownBtn.forEach((btn, index) => {
    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        dropDown.forEach((d, i) => {
            if (i !== index) d.classList.add("hidden");
        });
        dropDown[index].classList.toggle("hidden");
    });
});

dropDown.forEach((menu, index) => {
    const items = menu.querySelectorAll("button");
    items.forEach(item => {
        item.addEventListener("click", (e) => {
            e.stopPropagation();
            const spans = dropDownBtn[index].querySelectorAll("span");
            if (spans.length > 0) {
                spans[0].textContent = item.textContent;
            }
            menu.classList.add("hidden");
        });
    });
});

document.addEventListener("click", () => {
    dropDown.forEach(menu => {
        menu.classList.add("hidden");
    });
});
// end drop down menu logic

// --- Global Search Modal Logic ---
const dashboardForm = document.getElementById('dashboardForm');
const globalSearchInput = document.getElementById('globalSearchInput');
const searchModalContainer = document.getElementById('searchModalContainer');
const searchResultsList = document.getElementById('searchResultsList');
const searchLoading = document.getElementById('searchLoading');
const noSearchResults = document.getElementById('noSearchResults');

if (dashboardForm) {
    dashboardForm.addEventListener('submit', function(e) {
        const query = globalSearchInput ? globalSearchInput.value.trim() : '';
        if (query !== '') {
            e.preventDefault(); // Prevent form submission
            openSearchModal(query);
        }
    });
}

function openSearchModal(query) {
    if (!searchModalContainer) return;
    
    searchModalContainer.classList.remove('hidden');
    searchLoading.classList.remove('hidden');
    searchResultsList.innerHTML = '';
    noSearchResults.classList.add('hidden');

    const searchUrl = globalSearchInput ? globalSearchInput.getAttribute('data-search-url') : '/admin/search';
    const finalUrl = `${searchUrl}?q=${encodeURIComponent(query)}`;

    fetch(finalUrl)
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            searchLoading.classList.add('hidden');
            if (data.length === 0) {
                noSearchResults.classList.remove('hidden');
            } else {
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'bg-white border border-gray-200 rounded-xl p-4 flex flex-col md:flex-row justify-between md:items-center gap-4 hover:shadow-md transition';
                    li.innerHTML = `
                        <div class="flex items-center gap-4">
                            <div class="bg-[#EEF7FF] text-[#124375] p-3 rounded-lg flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="${item.icon}" class="text-2xl"></iconify-icon>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-white bg-[#124375] px-2 py-0.5 rounded mb-1 inline-block">${item.type}</span>
                                <p class="text-[#021219] font-bold text-sm md:text-base">${item.title}</p>
                            </div>
                        </div>
                        <a href="${item.url}" class="bg-[#F4F7F9] text-[#124375] hover:bg-[#124375] hover:text-white border border-[#124375] px-4 py-2 rounded-xl text-sm font-medium transition flex items-center justify-center gap-2 flex-shrink-0">
                            عرض التفاصيل
                            <iconify-icon icon="solar:arrow-left-outline" class="text-lg"></iconify-icon>
                        </a>
                    `;
                    searchResultsList.appendChild(li);
                });
            }
        })
        .catch(err => {
            searchLoading.classList.add('hidden');
            console.error(err);
        });
}

if (searchModalContainer) {
    const searchModalCloseBtns = searchModalContainer.querySelectorAll('.modal-close');
    searchModalCloseBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            searchModalContainer.classList.add('hidden');
        });
    });

    searchModalContainer.addEventListener('click', (e) => {
        if (e.target === searchModalContainer) {
            searchModalContainer.classList.add('hidden');
        }
    });
}

// --- Regular Modals Logic (Transaction Details) ---
const openModalBtns = document.querySelectorAll(".open-modal");
const closeBtns = document.querySelectorAll(".modal-close, .close-btn");
const overlay = document.querySelector(".overlay");

openModalBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
        const modalId = btn.getAttribute("data-modal");
        const modal = document.getElementById(modalId);
        if(modal) {
            modal.classList.remove("hidden");
            if(overlay) overlay.classList.remove("hidden");
        }
    });
});

closeBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
        const modal = btn.closest("[id^='modal-detail-']");
        if(modal) {
            modal.classList.add("hidden");
            if(overlay) overlay.classList.add("hidden");
        }
    });
});

if(overlay) {
    overlay.addEventListener("click", () => {
        document.querySelectorAll("[id^='modal-detail-']").forEach(m => m.classList.add("hidden"));
        overlay.classList.add("hidden");
    });
}