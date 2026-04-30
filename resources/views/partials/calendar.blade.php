@php
    $name = $name ?? 'date';
    $label = $label ?? 'التاريخ';
    $value = $value ?? request($name);
    $placeholder = $placeholder ?? 'يوم/شهر/سنة';
    $autoSubmit = $autoSubmit ?? false;
    $uniqueId = 'calendar_' . $name . '_' . uniqid();
@endphp

<div class="relative min-w-[240px] calendar-container" data-auto-submit="{{ $autoSubmit ? 'true' : 'false' }}">
    <label for="{{ $uniqueId }}"
        class="calendar-trigger navy-shadow bg-[#F4F7F9] text-[#124375] py-2.5 w-full rounded-xl text-base flex gap-3 justify-center items-center cursor-pointer">
        {{ $label }} :
        <span class="calendar-display text-[#021219]">{{ $value ?: $placeholder }}</span>
        <span class="flex items-center">
            <iconify-icon icon="lucide:calendar" class="text-xl"></iconify-icon>
        </span>
        <input type="text" name="{{ $name }}" id="{{ $uniqueId }}" value="{{ $value }}"
            class="calendar-input absolute left-0 top-full mt-3 opacity-0 w-0 h-0 pointer-events-none">
    </label>
</div>
