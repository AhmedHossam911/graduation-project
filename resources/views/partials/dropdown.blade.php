@php
    $name = $name ?? '';
    $label = $label ?? '';
    $options = $options ?? [];
    $selected = $selected ?? null;
    $placeholder = $placeholder ?? 'أختر';
    $required = $required ?? false;
    $floatingLabel = $floatingLabel ?? false;
    $clearable = $clearable ?? false;
    $clearValue = $clearValue ?? 'all';
    $autoSubmitClear = $autoSubmitClear ?? false;

    $selectedLabel = $placeholder;
    if ($selected && isset($options[$selected])) {
        $selectedLabel = $options[$selected];
    } elseif ($selected && in_array($selected, $options)) {
        // Fallback for flat arrays where value is the label
        $selectedLabel = $selected;
    }
@endphp

<div class="border border-slate-200 rounded-xl py-2 w-full min-w-[200px] bg-white relative custom-dropdown-container flex items-center pr-3 surface-shadow"
    data-clear-value="{{ $clearValue }}" data-auto-submit="{{ $autoSubmitClear ? 'true' : 'false' }}">

    @if ($floatingLabel)
        <label class="absolute top-[-15px] right-5 text-[#124375] text-base font-medium bg-[#F4F7F9] px-1">
            {{ $label }} @if ($required)
                <span class="text-[#D92D20]">*</span>
            @endif
        </label>
        <span
            class="custom-dropdown-text cursor-pointer block px-3 flex-1 {{ $selected ? 'text-[#124375] font-bold text-base' : 'text-[#6D6D6D] text-sm font-medium' }}">
            {{ $selectedLabel }}
        </span>
    @else
        @if ($label)
            <span class="text-[#124375] font-medium text-base ml-1">{{ $label }} : </span>
        @endif
        <span
            class="custom-dropdown-text cursor-pointer block flex-1 {{ $selected ? 'text-[#124375] font-bold text-base' : 'text-[#6D6D6D] text-sm font-medium' }}">
            {{ $selectedLabel }}
        </span>
    @endif

    <div class="absolute left-[15px] flex items-center gap-2 bg-white pl-1">
        @if ($clearable)
            <iconify-icon icon="mdi:close-circle"
                class="custom-dropdown-clear cursor-pointer text-xl text-[#D92D20] hover:text-red-700 transition {{ $selected && (string) $selected !== (string) $clearValue ? '' : 'hidden' }}">
            </iconify-icon>
        @endif
        <iconify-icon icon="oui:arrow-down"
            class="custom-dropdown-btn cursor-pointer text-xl text-[#124375]"></iconify-icon>
    </div>

    <!-- Dropdown -->
    <div
        class="custom-dropdown-menu hidden absolute top-[calc(100%+8px)] right-0 min-w-full w-max bg-[#F4F7F9] py-4 px-4 rounded-2xl surface-shadow z-50">
        <div class="flex flex-col gap-3">
            @foreach ($options as $value => $optionLabel)
                <label
                    class="flex items-center justify-between gap-6 bg-white rounded-xl border border-gray-300 px-5 py-4 cursor-pointer">
                    <span class="text-xl font-semibold text-[#124375]">{{ $optionLabel }}</span>
                    <input type="radio" name="{{ $name }}" class="peer hidden" value="{{ $value }}"
                        {{ $selected == $value ? 'checked' : '' }}>
                    <span
                        class="inline-block w-5 h-5 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                </label>
            @endforeach
            <button type="button"
                class="custom-dropdown-confirm surface-shadow bg-[#124375] text-white text-lg font-semibold py-3 rounded-xl mt-1 hover:bg-[#0e3560] transition-colors w-full">تأكيد</button>
        </div>
    </div>
</div>
