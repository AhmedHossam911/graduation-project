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
    'clearable' => false,
])

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('css/partials/calendar.css') }}?v={{ time() }}">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
<script src="{{ asset('js/partials/calendar.js') }}?v={{ time() }}"></script>

<div class="flex justify-center w-full">
    <div class="relative w-full">
        @if ($floatingLabel)
            <label class="absolute top-[-12px] right-4 text-[#124375] text-sm font-medium bg-[#F4F7F9] px-1 z-10">
                {{ $label }}
            </label>
        @endif
        <label for="{{ $id }}"
            class="calendar-label surface-shadow border border-[#124375] bg-[#F4F7F9] text-[#124375] h-[50px] px-4 w-full rounded-xl text-base flex justify-start items-center gap-2 cursor-pointer relative"
            data-calendar-label>


            @if ($clearable === true)
                <button type="button"
                    class="shrink-0 flex items-center justify-center w-8 h-8 rounded-lg border border-[#D92D20] bg-white hover:bg-red-50 z-10 clear-calendar-btn {{ $value ? '' : 'hidden' }}"
                    data-calendar-clear title="مسح التاريخ">
                    <iconify-icon icon="mingcute:close-fill" width="20" height="20"
                        class="text-[#D92D20]"></iconify-icon>
                </button>
            @endif
            
            <div class="flex-1 flex items-center overflow-hidden">
                @if ($label && !$floatingLabel)
                    <span class="font-medium text-base ml-2 shrink-0">{{ $label }} :</span>
                @endif
                <span
                    class="truncate {{ $value ? 'text-[#124375] font-bold text-base' : 'text-[#6D6D6D] text-sm font-medium' }}"
                    data-calendar-text title="{{ $value ?: $placeholder }}">{{ $value ?: $placeholder }}</span>
            </div>


            <div class="shrink-0 flex items-center pointer-events-none">
                <iconify-icon icon="lucide:calendar" class="text-xl text-[#124375]"></iconify-icon>
            </div>

            <input type="text" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
                data-calendar-input data-auto-submit="{{ $autoSubmit ? 'true' : 'false' }}" autocomplete="off"
                class="absolute left-0 top-full mt-3 h-0 w-0 opacity-0 pointer-events-none">
        </label>
    </div>
</div>
