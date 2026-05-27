@props(['title' => 'مستند طباعة', 'reference' => ''])

@php
    $systemName = \App\Models\System\SystemSetting::get('system_name');
    $extractor = auth()->user()->name ?? 'مستخرج البيان';
    $date = now()->timezone('Africa/Cairo')->format('Y/m/d');
    $time = now()->timezone('Africa/Cairo')->format('h:i A');
@endphp

<div class="print-container w-full">

    <!-- Print Header (Hidden on screen, visible on print) -->
    <header class="hidden print:flex flex-col border-b-2 border-gray-800 pb-5 mb-6 justify-between items-start">
        <div class="flex justify-between w-full">
            <!-- Right side: Logo & Uni Info -->
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center border-2 border-[#124375] shadow-sm shrink-0 overflow-hidden">
                    <img src="{{ asset('IMGs/Hu Logo 1.png') }}" class="w-14 h-14 object-contain" alt="Logo">
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 leading-tight">جامعة العاصمة (حلوان سابقا)</h1>
                    <h2 class="text-sm font-semibold text-gray-700 mt-1">{{ $systemName }}</h2>
                </div>
            </div>

            <!-- Center: Document Title -->
            <div class="text-center pt-2 flex-grow">
                <h2 class="text-2xl font-extrabold text-gray-900 underline decoration-2 decoration-gray-400 underline-offset-8">{{ $title }}</h2>
                @if($reference)
                <p class="text-sm text-gray-500 mt-3">رقم المرجع: <span dir="ltr">{{ $reference }}</span></p>
                @endif
            </div>

            <!-- Left side: Print Metadata -->
            <div class="text-right text-sm text-gray-800 bg-gray-50 p-4 rounded-lg border border-gray-200 min-w-[220px]">
                <div class="flex justify-between mb-2">
                    <span class="font-bold text-gray-600">تاريخ الطباعة:</span>
                    <span>{{ $date }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-bold text-gray-600">وقت الطباعة:</span>
                    <span>{{ $time }}</span>
                </div>
                <div class="border-t border-gray-200 my-2"></div>
                <div class="flex flex-col">
                    <span class="font-bold text-gray-600 mb-1">اسم مستخرج البيان:</span>
                    <span class="font-semibold text-[#124375] truncate">{{ $extractor }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Print Content -->
    <main class="w-full">
        {{ $slot }}
    </main>

    <!-- Print Footer -->
    <div class="hidden print:flex flex-col mt-20 justify-around items-end text-center pt-10 w-full" style="page-break-inside: avoid;">
        <div class="flex justify-between w-full px-10">
            <div class="w-48">
                <p class="font-bold text-gray-800 mb-10">مستخرج البيان</p>
                <p class="text-gray-400 border-t-2 border-gray-400 border-dashed pt-2">التوقيع</p>
            </div>

            <div class="relative">
                <div class="w-28 h-28 border-4 border-blue-100 text-[#124375] rounded-full flex flex-col items-center justify-center mx-auto absolute -top-16 left-1/2 transform -translate-x-1/2 -rotate-12 opacity-80">
                    <span class="text-xs font-bold">صندوق الزمالة</span>
                    <span class="text-xs">خاتم الإعتماد</span>
                </div>
            </div>

            <div class="w-48">
                <p class="font-bold text-gray-800 mb-10">المدير المالي / المشرف</p>
                <p class="text-gray-400 border-t-2 border-gray-400 border-dashed pt-2">التوقيع والإعتماد</p>
            </div>
        </div>

        <footer class="mt-12 pt-4 border-t-2 border-gray-800 flex justify-between items-center text-sm text-gray-600 font-medium w-full">
            <p>{{ $systemName }}</p>
            <p dir="ltr" class="text-gray-500">تمت الطباعة آلياً من النظام</p>
        </footer>
    </div>
</div>
