@extends('layouts.app')
{{-- 
    419 Error View:
    Displayed when a CSRF token expires or the page session expires.
--}}

@section('title', 'انتهت صلاحية الصفحة')

@section('content')
<div class="p-6 h-full flex flex-col justify-center items-center">
    <div class="bg-white rounded-2xl shadow-sm border border-[#124375] p-10 max-w-lg w-full text-center">
        <div class="flex justify-center mb-6">
            <div class="w-24 h-24 bg-[#FEF3F2] rounded-full flex items-center justify-center">
                <iconify-icon icon="mdi:clock-alert-outline" class="text-6xl text-[#D92D20]"></iconify-icon>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-[#124375] mb-3">انتهت صلاحية الصفحة</h1>
        <p class="text-[#6D6D6D] text-lg mb-8 leading-relaxed">
            عذراً، لقد انتهت صلاحية جلستك أو الصفحة التي تحاول الوصول إليها بسبب عدم النشاط. يرجى تحديث الصفحة والمحاولة مرة أخرى.
        </p>
        
        <div class="flex justify-center gap-4">
            <button onclick="window.location.reload()" class="inline-flex items-center gap-2 bg-[#124375] text-white font-medium px-8 py-3 rounded-xl hover:bg-[#0a2a4a] transition-colors shadow-md">
                <iconify-icon icon="mdi:refresh" class="text-xl"></iconify-icon>
                تحديث الصفحة
            </button>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 font-medium px-8 py-3 rounded-xl hover:bg-gray-200 transition-colors shadow-sm border border-gray-300">
                الرئيسية
            </a>
        </div>
    </div>
</div>
@endsection
