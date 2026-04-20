@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center gap-2 font-arabic" dir="rtl">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-gray-400 cursor-not-allowed text-[16px]">السابق</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-1 text-[#124375] hover:underline text-[16px]">السابق</a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center gap-1.5">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 text-gray-400 bg-white shadow-sm">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="w-10 h-10 flex items-center justify-center rounded-lg bg-[#124375] text-white font-bold shadow-md shadow-[#124375]/20">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:border-[#124375] hover:text-[#124375] transition-all duration-200 shadow-sm hover:shadow-md">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-1 text-[#124375] hover:underline text-[16px]">التالي</a>
        @else
            <span class="px-3 py-1 text-gray-400 cursor-not-allowed text-[16px]">التالي</span>
        @endif
    </nav>
@endif
