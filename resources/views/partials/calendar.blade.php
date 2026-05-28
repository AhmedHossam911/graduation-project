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
])

@once
    @push('styles')
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="{{ asset('css/partials/calendar.css') }}?v={{ filemtime(public_path('css/partials/calendar.css')) }}">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.min.js"></script>
        <script src="{{ asset('js/partials/calendar.js') }}?v={{ filemtime(public_path('js/partials/calendar.js')) }}"></script>
    @endpush
@endonce

<div class="flex justify-center ">
    <div class="relative min-w-[240px]">
        <label for="{{ $id }}"
            class="calendar-label navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full rounded-xl text-base flex gap-3 justify-center items-center cursor-pointer"
            data-calendar-label>
            {{ $label }} :
            <span class="text-[#021219]" data-calendar-text>{{ $value ?: $placeholder }}</span>
            <span class="flex items-center">
                <iconify-icon icon="lucide:calendar" class="text-xl"></iconify-icon>
            </span>
            <input
                type="text"
                name="{{ $name }}"
                id="{{ $id }}"
                value="{{ $value }}"
                data-calendar-input
                data-auto-submit="{{ $autoSubmit ? 'true' : 'false' }}"
                autocomplete="off"
                class="absolute left-0 top-full mt-3 h-0 w-0 opacity-0 pointer-events-none">
        </label>
    </div>
</div>
