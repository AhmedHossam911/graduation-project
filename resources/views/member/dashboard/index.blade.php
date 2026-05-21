@extends('layouts.app')

@section('title', 'لوحة تحكم العضو')

@section('content')
<main class="flex-1 py-5 px-3">
    <div class="flex flex-col gap-2">
        <h2 class="text-[#021219] text-xl font-semibold"> مرحباً ، <span>{{ Auth::user()->name ?? 'عضو' }}</span></h2>
        <p class="text-[#6D6D6D] text-base font-normal">
            نظام إدارة الصندوق – لوحة تحكم العضو
        </p>
    </div>

    <div class="py-10 text-center text-[#124375] font-semibold text-2xl">
        قيد الإنشاء - Member Dashboard Placeholder
    </div>
</main>
@endsection
