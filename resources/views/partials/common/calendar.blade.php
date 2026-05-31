{{--
    Calendar Component Partial:
    A reusable jQuery UI datepicker component for consistent date selection across forms.
--}}
@props([
    'name' => 'date',
    'id' => 'datepicker',
    'label' => 'التاريخ',
    'value' => request('date'),
    'placeholder' => 'يوم/شهر/سنة',
    'autoSubmit' => false,
    'floatingLabel' => false,
])

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
<link rel="stylesheet"
    href="{{ asset('css/partials/calendar.css') }}?v={{ time() }}">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
<script src="{{ asset('js/partials/calendar.js') }}?v={{ time() }}"></script>

<div class="flex justify-center ">
    <div class="relative min-w-[240px] w-full">
        @if ($floatingLabel)
            <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9] px-1 z-10">
                {{ $label }}
            </label>
        @endif
        <label for="{{ $id }}"
            class="calendar-label navy-shadow border border-[#124375] bg-[#F4F7F9] text-[#124375] py-2.5 w-full rounded-xl text-base flex gap-3 justify-center items-center cursor-pointer relative"
            data-calendar-label>
            <button type="button"
                class="absolute right-[10px] flex items-center justify-center w-8 h-8 rounded-lg border border-[#D92D20] bg-white hover:bg-red-50 z-10 clear-calendar-btn {{ $value ? '' : 'hidden' }}"
                data-calendar-clear title="مسح التاريخ">
                <iconify-icon icon="mingcute:close-fill" width="20" height="20"
                    class="text-[#D92D20]"></iconify-icon>
            </button>
            @if ($label && !$floatingLabel)
                {{ $label }} :
            @endif
            <span class="text-[#021219]" data-calendar-text>{{ $value ?: $placeholder }}</span>
            <span class="flex items-center">
                <iconify-icon icon="lucide:calendar" class="text-xl"></iconify-icon>
            </span>
            <input type="text" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
                data-calendar-input data-auto-submit="{{ $autoSubmit ? 'true' : 'false' }}" autocomplete="off"
                class="absolute left-0 top-full mt-3 h-0 w-0 opacity-0 pointer-events-none">
        </label>
    </div>
</div>
