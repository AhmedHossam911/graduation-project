    @if (session('success'))
        <div class="absolute success-modal top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
            <div class="py-9 px-5 relative bg-[#F0FFF6] max-w-3xl mx-auto">
                <iconify-icon icon="iconamoon:close-fill"
                    class="btn-close cursor-pointer absolute top-0 right-0 text-2xl p-1 text-[#019168]"></iconify-icon>
                <p class="text-[#019168] #019168 font-semibold">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="absolute top-1/2 -translate-y-1/2 left-44 no-print">
            <div
                class="flex items-center gap-3 py-3 rounded-xl px-5 bg-[#FFF0EF] border border-[#D92D20] surface-shadow">
                <iconify-icon icon="gridicons:cross-circle" class="text-[#D92D20] text-xl"></iconify-icon>
                @foreach ($errors->all() as $error)
                    <p class="text-[#D92D20] font-medium text-base">
                        {{ $error }}
                    </p>
                @endforeach
            </div>
        </div>
    @endif
