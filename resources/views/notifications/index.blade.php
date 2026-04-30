@extends('layouts.app')
@section('title', 'الإشعارات')
@section('content')
    <div class="heading flex justify-center py-7">
        <h1 class="text-xl font-semibold text-[#124375]">
            الإشعارات
        </h1>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="mx-7 mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-center text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="tabs flex justify-between px-7 flex-wrap gap-3">
        {{-- Filter Tabs --}}
        <div class="flex gap-3">
            <a href="{{ route('notifications.index', array_merge(request()->except('filter', 'page'), ['filter' => 'all'])) }}"
                class="py-2 px-7 rounded-xl text-base surface-shadow {{ $filter === 'all' ? 'bg-[#124375] text-white' : 'bg-[#F4F7F9] text-[#124375]' }}">
                الكل <span>({{ $totalCount }})</span>
            </a>
            <a href="{{ route('notifications.index', array_merge(request()->except('filter', 'page'), ['filter' => 'unread'])) }}"
                class="py-2 px-7 rounded-xl text-base surface-shadow {{ $filter === 'unread' ? 'bg-[#124375] text-white' : 'bg-[#F4F7F9] text-[#124375]' }}">
                غير مقروء <span>({{ $unreadCount }})</span>
            </a>
            <a href="{{ route('notifications.index', array_merge(request()->except('filter', 'page'), ['filter' => 'read'])) }}"
                class="py-2 px-7 rounded-xl text-base surface-shadow {{ $filter === 'read' ? 'bg-[#124375] text-white' : 'bg-[#F4F7F9] text-[#124375]' }}">
                مقروء <span>({{ $readCount }})</span>
            </a>
        </div>

        {{-- Period & Sort Dropdowns --}}
        <div class="flex gap-3">
            <div class="relative">
                <button type="button"
                    class="dropDownBtn bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base surface-shadow flex gap-3 items-center">
                    الفترة :
                    <span class="text-[#021219]">
                        @switch($period)
                            @case('today')
                                اليوم
                            @break

                            @case('week')
                                هذا الأسبوع
                            @break

                            @case('month')
                                هذا الشهر
                            @break

                            @case('last30')
                                أخر 30 يوم
                            @break

                            @default
                                كل الفترات
                        @endswitch
                    </span>
                    <iconify-icon icon="fe:arrow-down" class="text-xl"></iconify-icon>
                </button>
                <div
                    class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl surface-shadow max-w-fit">
                    <a href="{{ route('notifications.index', array_merge(request()->except('period', 'page'), ['period' => 'today'])) }}"
                        class="surface-shadow py-2 px-5 rounded-xl text-base text-center hover:bg-[#EEF7FF]">اليوم</a>
                    <a href="{{ route('notifications.index', array_merge(request()->except('period', 'page'), ['period' => 'week'])) }}"
                        class="surface-shadow py-2 px-5 rounded-xl text-base text-center hover:bg-[#EEF7FF]">هذا الأسبوع</a>
                    <a href="{{ route('notifications.index', array_merge(request()->except('period', 'page'), ['period' => 'month'])) }}"
                        class="surface-shadow py-2 px-5 rounded-xl text-base text-center hover:bg-[#EEF7FF]">هذا الشهر</a>
                    <a href="{{ route('notifications.index', array_merge(request()->except('period', 'page'), ['period' => 'last30'])) }}"
                        class="surface-shadow py-2 px-5 rounded-xl text-base text-center hover:bg-[#EEF7FF]">أخر 30 يوم</a>
                    <a href="{{ route('notifications.index', array_merge(request()->except('period', 'page'), ['period' => 'all'])) }}"
                        class="surface-shadow py-2 px-5 rounded-xl text-base text-center hover:bg-[#EEF7FF]">كل الفترات</a>
                </div>
            </div>
            <div class="relative">
                <button type="button"
                    class="dropDownBtn bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base surface-shadow flex gap-3 items-center">
                    الترتيب :
                    <span class="text-[#021219]">{{ $sort === 'oldest' ? 'الأقدم أولاً' : 'الأحدث أولاً' }}</span>
                    <iconify-icon icon="fe:arrow-down" class="text-xl"></iconify-icon>
                </button>
                <div
                    class="dropDown hidden absolute z-50 bg-[#F4F7F9] left-0 top-full mt-3 flex flex-col gap-3 px-5 py-4 rounded-xl surface-shadow max-w-fit">
                    <a href="{{ route('notifications.index', array_merge(request()->except('sort', 'page'), ['sort' => 'newest'])) }}"
                        class="surface-shadow py-2 px-5 rounded-xl text-base text-center hover:bg-[#EEF7FF]">الأحدث
                        اولاً</a>
                    <a href="{{ route('notifications.index', array_merge(request()->except('sort', 'page'), ['sort' => 'oldest'])) }}"
                        class="surface-shadow py-2 px-5 rounded-xl text-base text-center hover:bg-[#EEF7FF]">الأقدم
                        اولاً</a>
                </div>
            </div>
        </div>

        {{-- Mark All As Read --}}
        <div class="flex gap-3">
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center bg-[#F4F7F9] text-[#124375] py-2 px-7 rounded-xl text-base surface-shadow hover:bg-[#EEF7FF] transition-colors">
                    تحديد الكل كمقروء
                    <iconify-icon icon="material-symbols:check" class="text-2xl mr-1"></iconify-icon>
                </button>
            </form>
        </div>
    </div>

    <hr class="m-5 border border-[#A8A8A8]">

    {{-- Notifications List --}}
    <div class="notifications px-7 space-y-5">
        @forelse ($notifications as $notification)
            <div
                class="notification flex justify-between items-center {{ is_null($notification->read_at) ? 'bg-[#EAF5FF]' : 'bg-[#F4F7F9]' }} rounded-xl px-7 py-4 surface-shadow">
                <div class="flex-1 flex flex-wrap gap-x-8 gap-y-2 items-center">
                    {{-- Unread dot indicator --}}
                    @if (is_null($notification->read_at))
                        <span class="w-3 h-3 bg-[#124375] rounded-full inline-block flex-shrink-0"></span>
                    @endif
                    <p class="text-[#124375] font-semibold">{{ $notification->title }}</p>
                    <p class="text-[#021219]">{{ $notification->message }}</p>
                    <p class="text-[#6D6D6D] text-sm">التاريخ : <span
                            class="text-[#021219]">{{ $notification->created_at->format('Y-m-d') }}</span></p>
                    <p class="text-[#6D6D6D] text-sm">الوقت : <span
                            class="text-[#021219]">{{ $notification->created_at->format('h:i A') }}</span></p>
                </div>
                <div class="flex items-center gap-2 mr-4 flex-shrink-0">
                    @if (is_null($notification->read_at))
                        <form action="{{ route('notifications.read', $notification) }}" method="POST">
                            @csrf
                            <button type="submit" title="تحديد كمقروء"
                                class="text-[#124375] hover:text-[#0e3560] transition-colors p-1">
                                <iconify-icon icon="material-symbols:check-circle-outline" class="text-2xl"></iconify-icon>
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST"
                        onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="حذف"
                            class="text-[#D92D20] hover:text-red-700 transition-colors p-1">
                            <iconify-icon icon="mdi:delete-outline" class="text-2xl"></iconify-icon>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <iconify-icon icon="mdi:bell-off-outline" class="text-6xl text-[#A8A8A8] mb-4"></iconify-icon>
                <p class="text-[#6D6D6D] text-lg">لا توجد إشعارات</p>
            </div>
        @endforelse
    </div>

    @push('scripts')
        <script>
            // Toggle dropdowns
            document.querySelectorAll('.dropDownBtn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const dropdown = this.nextElementSibling;
                    // Close all other dropdowns first
                    document.querySelectorAll('.dropDown').forEach(d => {
                        if (d !== dropdown) d.classList.add('hidden');
                    });
                    dropdown.classList.toggle('hidden');
                });
            });
            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.dropDown').forEach(d => d.classList.add('hidden'));
            });
        </script>
    @endpush
@endsection
@section('pagination')
    @if ($notifications->hasPages())
        <div class="sticky bottom-0 bg-[#F4F7FE] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
            {{ $notifications->links() }}
        </div>
    @endif
@endsection
