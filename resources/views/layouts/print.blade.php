<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'الطباعة')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap');
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: white;
            color: #021219;
        }
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="setTimeout(function() { window.print(); }, 800)">
    <div class="p-8 max-w-4xl mx-auto">
        <div class="no-print mb-6 flex justify-center gap-4">
            <button onclick="window.print()" class="bg-[#124375] text-white px-8 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="material-symbols:print" class="text-2xl"></iconify-icon>
                طباعة المستند
            </button>
            <button onclick="window.close()" class="bg-gray-200 text-gray-800 px-8 py-3 rounded-xl font-bold hover:bg-gray-300 transition-colors">
                إغلاق
            </button>
        </div>
        
        <x-print-layout :title="$title ?? 'مستند طباعة'" :reference="$reference ?? ''">
            @yield('content')
        </x-print-layout>
    </div>
</body>
</html>
