// Generic Calendar Input Logic
// Supports multiple calendar instances on a single page
$(function () {
    document.querySelectorAll('.calendar-container').forEach(function (container) {
        const input = container.querySelector('.calendar-input');
        const display = container.querySelector('.calendar-display');
        const autoSubmit = container.dataset.autoSubmit === 'true';

        if (!input || !display) return;

        $(input).datepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            showButtonPanel: true,
            currentText: "اليوم",
            closeText: "إغلاق",
            onSelect: function () {
                const currentDate = $(input).datepicker("getDate");
                const day = currentDate.getDate();
                const month = currentDate.getMonth() + 1;
                const year = currentDate.getFullYear();
                const formatted = day + "/" + month + "/" + year;

                display.textContent = formatted;
                input.value = formatted;

                // Auto-submit parent form if configured
                if (autoSubmit) {
                    const form = container.closest('form');
                    if (form) form.submit();
                }
            }
        });
    });
});