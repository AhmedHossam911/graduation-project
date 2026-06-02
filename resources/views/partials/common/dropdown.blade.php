{{--
    Custom Dropdown Component Partial:
    A styled custom select/dropdown input component used throughout the application forms.
--}}
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
    $autoSubmit = $autoSubmit ?? $autoSubmitClear;
    $showConfirm = $showConfirm ?? false;
    $borderColorClass = $errors->has($name) ? 'border-[#D92D20]' : $borderColorClass ?? 'border-[#124375]';

    $selectedLabel = $placeholder;
    if ($selected && isset($options[$selected])) {
        $selectedLabel = $options[$selected];
    } elseif ($selected && in_array($selected, $options)) {
        // Fallback for flat arrays where value is the label
        $selectedLabel = $selected;
    }
@endphp

<div class="border {{ $borderColorClass }} rounded-xl h-[50px] w-full bg-[#F4F7F9] relative custom-dropdown-container flex items-center px-4 gap-2 surface-shadow"
    data-clear-value="{{ $clearValue }}" data-auto-submit="{{ $autoSubmit ? 'true' : 'false' }}"
    data-has-confirm="{{ $showConfirm ? 'true' : 'false' }}">

    @if ($clearable)
        <button type="button"
            class="custom-dropdown-clear shrink-0 flex items-center justify-center w-8 h-8 rounded-lg border border-[#D92D20] bg-white hover:bg-red-50 z-10 transition {{ $selected && (string) $selected !== (string) $clearValue ? '' : 'hidden' }}" title="مسح الاختيار">
            <iconify-icon icon="mingcute:close-fill" width="20" height="20" class="text-[#D92D20]">
            </iconify-icon>
        </button>
    @endif

    @if ($floatingLabel)
        <label
            class="absolute top-[-12px] right-4 {{ $errors->has($name) ? 'text-[#D92D20]' : 'text-[#124375]' }} text-sm font-medium bg-[#F4F7F9] px-1 z-10">
            {{ $label }} @if ($required)
                <span class="text-[#D92D20]">*</span>
            @endif
        </label>
        <div class="flex-1 overflow-hidden">
            <span
                class="custom-dropdown-text cursor-pointer block w-full truncate {{ $selected ? 'text-[#124375] font-bold text-base' : 'text-[#6D6D6D] text-sm font-medium' }}" title="{{ $selectedLabel }}">
                {{ $selectedLabel }}
            </span>
        </div>
    @else
        <div class="flex-1 flex items-center overflow-hidden">
            @if ($label)
                <span
                    class="{{ $errors->has($name) ? 'text-[#D92D20]' : 'text-[#124375]' }} font-medium text-base ml-2 shrink-0">{{ $label }}
                    : </span>
            @endif
            <span
                class="custom-dropdown-text cursor-pointer block flex-1 truncate {{ $selected ? 'text-[#124375] font-bold text-base' : 'text-[#6D6D6D] text-sm font-medium' }}" title="{{ $selectedLabel }}">
                {{ $selectedLabel }}
            </span>
        </div>
    @endif



    <div class="shrink-0 flex items-center pointer-events-none">
        <iconify-icon icon="oui:arrow-down"
            class="custom-dropdown-btn cursor-pointer text-xl text-[#124375]"></iconify-icon>
    </div>

    <!-- Dropdown -->
    <div
        class="custom-dropdown-menu hidden absolute top-[calc(100%+8px)] left-0 w-full bg-[#F4F7F9] py-4 rounded-2xl surface-shadow z-50 ">
        <div class="flex flex-col gap-3 h-auto max-h-[250px] overflow-y-auto px-4 custom-scrollbar">
            @foreach ($options as $value => $optionLabel)
                <label
                    class="flex items-center justify-between gap-4 bg-white rounded-xl border border-gray-300 px-5 py-4 cursor-pointer whitespace-nowrap overflow-hidden">
                    <span class="text-lg font-semibold text-[#124375] truncate" title="{{ $optionLabel }}">{{ $optionLabel }}</span>
                    <input type="radio" name="{{ $name }}" class="peer hidden" value="{{ $value }}"
                        {{ $selected == $value ? 'checked' : '' }}>
                    <span
                        class="shrink-0 inline-block w-5 h-5 rounded-full border-2 border-[#124375] peer-checked:bg-[#124375] peer-checked:shadow-[inset_0_0_0_2px_white]"></span>
                </label>
            @endforeach
            @if ($showConfirm)
                <button type="button"
                    class="custom-dropdown-confirm surface-shadow bg-[#124375] text-white text-base font-semibold py-2 rounded-xl mt-1 hover:bg-[#0e3560] transition-colors w-full">تأكيد</button>
            @endif
        </div>
    </div>
</div>
