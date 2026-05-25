@extends('layouts.pages')
@section('title', 'المستندات')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/employee/documents.css') }}">
    <!-- start header -->
    <div class="py-4 px-12 flex items-center justify-between">
        <div class="space-y-2">
            <h1 class="text-[#124375] text-[28px] font-semibold flex items-center gap-2">
                <a href="{{ route('members.show', $member->id) }}" class="hover:text-[#0e3560] transition-colors">
                    {{ $member->full_name }}
                </a>
                <span>/ المستندات</span>
            </h1>
            <p class="text-[#124375] text-[18px] font-medium">تعرض هذه الصفحة جميع المستندات والملفات الخاصة بالعضو .</p>
        </div>
        <div>
            <button data-modal="modal-add-doc"
                class="open-modal flex text-[16px] font-medium items-center gap-2 bg-[#124375] navy-shadow py-3 px-20 text-white rounded-[12px] hover:bg-[#0e3560] transition-colors">
                <iconify-icon icon="ic:baseline-plus" class="text-2xl mt-1"></iconify-icon>
                إرفاق مستند
            </button>
        </div>
    </div>
    <!-- end header -->

    <div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60]"></div>

    <section class="px-12 py-5">
        @if(session('success'))
            <div class="mb-4 bg-[#ECFDF3] text-[#067647] border border-[#067647] p-4 rounded-xl flex items-center gap-3">
                <iconify-icon icon="healthicons:yes" class="text-2xl"></iconify-icon>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 bg-[#FFEAE880] text-[#D92D20] border border-[#D92D20] p-4 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-[14px] overflow-hidden border border-[#D1D5DB] bg-white">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#EEF7FF] border-b border-[#D1D5DB]">
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">نوع المستند</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">أسم المستند</th>
                        <th class="py-4 border-l border-[#D1D5DB] font-medium text-[#021219]">تاريخ الرفع</th>
                        <th class="py-4 font-medium text-[#021219]">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($member->attachments as $attachment)
                        @php
                            $ext = strtolower(pathinfo($attachment->file_path, PATHINFO_EXTENSION));
                            $isPdf = $ext === 'pdf';
                        @endphp
                        <tr class="text-center border-b border-[#D1D5DB] even:bg-[#EFEFEF]">
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                <div class="flex items-center gap-2 justify-center">
                                    @if($isPdf)
                                        <iconify-icon icon="teenyicons:pdf-solid" class="text-[#124375] text-2xl"></iconify-icon>
                                        <span class="text-[14px] font-medium">ملف PDF </span>
                                    @else
                                        <iconify-icon icon="material-symbols:image-rounded" class="text-[#124375] text-2xl"></iconify-icon>
                                        <span class="text-[14px] font-medium ">صورة </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 border-l border-[#D1D5DB] text-[#021219]">
                                {{ $attachment->type }}
                            </td>
                            <td class="py-4 border-l border-[#D1D5DB]">
                                {{ $attachment->created_at->isoFormat('D MMMM YYYY') }}
                            </td>
                            <td class="py-5">
                                <div class="text-2xl flex gap-7 items-center justify-center text-[#124375]">
                                    <a href="{{ route('documents.view', $attachment->id) }}" target="_blank" class="hover:text-[#0e3560]">
                                        <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                    </a>
                                    <a href="{{ route('documents.download', $attachment->id) }}" class="hover:text-[#0e3560]">
                                        <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-[#6D6D6D]">
                                لا توجد مستندات مرفوعة لهذا العضو.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div id="modal-add-doc"
        class="hidden w-full max-w-xl mx-auto absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[70] rounded-2xl bg-[#F4F7F9] navy-shadow pt-2 pb-10">
        <button
            class="modal-close text-[#124375] text-2xl  navy-shadow rounded mx-4 mt-2 flex items-center justify-center py-1 px-1">
            <iconify-icon icon="weui:close-filled"></iconify-icon>
        </button>
        <div class="modal-body space-y-7 px-7">
            <div class="modal-title text-center">
                <h1 class="text-xl font-semibold text-[#124375]">
                    إرفاق مستند
                </h1>
            </div>
            <form action="{{ route('members.documents.store', $member->id) }}" method="POST" enctype="multipart/form-data" class="space-y-7">
                @csrf
                <div class="flex flex-col gap-5">
                    <div class="relative w-full">
                        <label class="text-base font-medium text-[#124375] absolute top-[-15px] right-3 bg-[#F4F7F9] px-1">
                            أسم المستند<span class="text-[#D92D20]">*</span>
                        </label>
                        <input type="text" name="document_name" placeholder="أدخل أسم المستند" required
                            class="outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow transition py-3 w-full border border-[#124375] bg-[#F4F7F9] rounded-xl text-center">
                    </div>
                    <div class="border border-[#124375] rounded-2xl w-full hover:bg-[#EEF7FF] transition-colors relative overflow-hidden">
                        <label for="file-24" class="cursor-pointer py-10 text-[#124375] flex flex-col items-center justify-center gap-2">
                            <iconify-icon icon="mingcute:upload-3-fill" class="text-3xl"></iconify-icon>
                            <p id="file-name-display" class="font-medium">اضغط لإرفاق مستند</p>
                            <input type="file" name="document_file" id="file-24" class="hidden" required accept=".pdf,.jpg,.jpeg,.png,.webp">
                        </label>
                    </div>
                </div>
                <div class="btns flex gap-2">
                    <button id="submit-doc-btn" type="submit"
                        class="submit-btn rounded-[14px] w-full py-3 text-base font-medium flex items-center justify-center gap-2 bg-[#124375] text-[#EEF7FF] navy-shadow hover:bg-[#0e3560] transition-colors">
                        <span><iconify-icon icon="healthicons:yes" class="flex items-center text-2xl"></iconify-icon></span>
                        إرفاق
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/employee/documents.js') }}"></script>
@endsection
