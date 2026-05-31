@extends('layouts.member')

@section('title', 'الاشعارات')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/member/memberNotification.css') }}">
    <section class="px-4 md:px-12 py-4 md:py-7">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 bg-[#F4F7F9] navy-shadow rounded-[16px] py-5 px-4">
            <div class="flex items-center gap-2">
                <iconify-icon icon="ion:notifications"
                    class="text-2xl md:text-3xl text-[#124375] bg-[#EAF5FF] rounded-[12px] py-2 px-3"></iconify-icon>
                <h1 class="text-[22px] md:text-[28px] text-[#124375] font-semibold">الإشعارات</h1>
            </div>
            <div>
                <form action="{{ route('member.notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="mark-as-read text-[#124375] underline text-[16px] md:text-[20px] font-semibold">
                        تحديد الكل كمقروء
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="px-4 md:px-12 py-4 md:py-7 space-y-4 md:space-y-5">
        @forelse($notifications as $notification)
        <div class="noti flex flex-col md:flex-row justify-between gap-3 md:gap-0 bg-[#F4F7F9] {{ is_null($notification->read_at) ? 'unread' : 'read' }} rounded-[16px] py-4 md:py-5 px-4">
            <div class="flex gap-2">
                <iconify-icon icon="material-symbols:notifications-active" class="text-xl md:text-2xl {{ is_null($notification->read_at) ? 'text-[#019168]' : 'text-[#124375]' }} mt-1"></iconify-icon>
                <div class="flex flex-col gap-1 md:gap-2">
                    <h2 class="text-[#021219] text-[16px] md:text-[18px] font-medium">{{ $notification->title }}</h2>
                    <p class="text-[#6D6D6DCC] text-[13px] md:text-[14px] font-medium">{{ $notification->message }}</p>
                </div>
            </div>
            <div class="flex items-center text-[#6D6D6DCC] gap-1 self-end md:self-center text-xs md:text-sm">
                <iconify-icon icon="tabler:clock-filled" class="text-lg md:text-xl mt-1"></iconify-icon>
                <span>{{ $notification->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <div class="text-center text-[#6D6D6DCC] py-10">
            <p class="text-[18px] md:text-[20px]">لا توجد إشعارات حالياً</p>
        </div>
        @endforelse

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </section>

    <script src="{{ asset('js/member/memberNotification.js') }}"></script>
@endsection
