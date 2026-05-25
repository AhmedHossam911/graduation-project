(function () {
    function updateLabel(input, dateText) {
        const label = input.closest('[data-calendar-label]');
        const text = label ? label.querySelector('[data-calendar-text]') : null;

        if (text) {
            text.textContent = dateText || text.dataset.placeholder || 'يوم/شهر/سنة';
        }
    }

    function initDatepickers() {
        if (typeof window.jQuery === 'undefined' || typeof window.jQuery.fn.datepicker === 'undefined') {
            return;
        }

        const $ = window.jQuery;

        $('[data-calendar-input]').each(function () {
            const input = this;
            const $input = $(input);
            const label = input.closest('[data-calendar-label]');

            if ($input.data('hasDatepicker')) {
                return;
            }

            $input.datepicker({
                dateFormat: 'dd/mm/yy',
                showOtherMonths: true,
                selectOtherMonths: true,
                showButtonPanel: true,
                prevText: '\u2039',
                nextText: '\u203A',
                currentText: 'اليوم',
                closeText: 'إلغاء',
                onSelect: function (dateText) {
                    updateLabel(input, dateText);

                    if (input.dataset.autoSubmit === 'true' && input.form) {
                        input.form.submit();
                    }
                }
            });

            if (label) {
                label.addEventListener('click', function (event) {
                    event.preventDefault();
                    $input.datepicker('show');
                });
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDatepickers);
    } else {
        initDatepickers();
    }
})();
