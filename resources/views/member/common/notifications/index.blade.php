@extends('layouts.member')

@section('title', 'الاشعارات')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/member/memberNotification.css') }}">
    <section class="px-12 py-7">
        <div class="flex justify-between items-center bg-[#F4F7F9] navy-shadow rounded-[16px] py-5 px-4">
            <div class="flex items-center gap-2">
                <iconify-icon icon="ion:notifications"
                    class="text-3xl text-[#124375] bg-[#EAF5FF] rounded-[12px] py-2 px-3"></iconify-icon>
                <h1 class="text-[28px] text-[#124375] font-semibold">الإشعارات</h1>
            </div>
            <div>
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="mark-as-read text-[#124375] underline text-[20px] font-semibold">
                        تحديد الكل كمقروء
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="px-12 py-7 space-y-5">
        @forelse($notifications as $notification)
        <div class="noti flex justify-between bg-[#F4F7F9] {{ is_null($notification->read_at) ? 'unread' : 'read' }} rounded-[16px] py-5 px-4">
            <div class="flex gap-2">
                <iconify-icon icon="material-symbols:notifications-active" class="text-2xl {{ is_null($notification->read_at) ? 'text-[#019168]' : 'text-[#124375]' }} mt-1"></iconify-icon>
                <div class="flex flex-col gap-2">
                    <h2 class="text-[#021219] text-[18px] font-medium">{{ $notification->title }}</h2>
                    <p class="text-[#6D6D6DCC] text-[14px] font-medium">{{ $notification->message }}</p>
                </div>
            </div>
            <div class="flex items-center text-[#6D6D6DCC] gap-1">
                <iconify-icon icon="tabler:clock-filled" class="text-xl mt-1"></iconify-icon>
                <span>{{ $notification->created_at->diffForHumans() }}</span>
            </div>
        </div>
        @empty
        <div class="text-center text-[#6D6D6DCC] py-10">
            <p class="text-[20px]">لا توجد إشعارات حالياً</p>
        </div>
        @endforelse

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </section>

    <script src="{{ asset('JS/member/memberNotification.js') }}"></script>
@endsection
