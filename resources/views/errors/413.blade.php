@extends('layouts.app')
{{-- 
    413 Error View:
    Displayed when a payload/file is too large to be processed by the server.
--}}

@section('title', 'حجم الملف كبير جداً')

@section('content')
<div class="p-6 h-full flex flex-col justify-center items-center">
    <div class="bg-white rounded-2xl shadow-sm border border-[#124375] p-10 max-w-lg w-full text-center">
        <div class="flex justify-center mb-6">
            <div class="w-24 h-24 bg-[#FEF3F2] rounded-full flex items-center justify-center">
                <iconify-icon icon="mdi:file-cancel-outline" class="text-6xl text-[#D92D20]"></iconify-icon>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-[#124375] mb-3">حجم البيانات كبير جداً</h1>
        <p class="text-[#6D6D6D] text-lg mb-8 leading-relaxed">
            عذراً، الملف أو البيانات التي تحاول رفعها تتجاوز الحد الأقصى المسموح به. يرجى محاولة رفع ملف بحجم أصغر.
        </p>
        
        <div class="flex justify-center">
            <button onclick="window.history.back()" class="inline-flex items-center gap-2 bg-[#124375] text-white font-medium px-8 py-3 rounded-xl hover:bg-[#0a2a4a] transition-colors shadow-md">
                <iconify-icon icon="mdi:arrow-right" class="text-xl"></iconify-icon>
                العودة للصفحة السابقة
            </button>
        </div>
    </div>
</div>
@endsection
