{{-- 
    Member Profile Header Partial:
    Displays the member's basic information (name, membership number, status) 
    and action buttons for editing data or suspending the membership.
--}}
<div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60] print:hidden"></div>

    {{-- head --}}
    <div class="flex justify-between items-center px-10 py-5 print:hidden">
        <div class="flex flex-col gap-4">
            <div class="flex gap-7 items-center">
                <p class="text-[28px] font-semibold text-[#124375]">{{ $member->full_name }}</p>
                <p class="mt-3 status {{ $badgeClass }} rounded-lg px-10 border">{{ $statusData['label'] }}</p>
            </div>
            <p class="text-[#021219] text-sm font-medium flex items-center gap-4">
                رقم العضوية :
                <span class="text-[#124375] font-semibold text-xl">{{ $membership->membership_number ?? '-' }}</span>
            </p>
        </div>
        <div class="space-y-2 mt-3">
            @if (auth()->user() && auth()->user()->hasPermission('إدارة الأعضاء'))
                <button data-modal="modal-edit"
                    class="open-modal flex items-center justify-center navy-shadow bg-[#124375] text-[#FEFFFC] rounded-xl gap-2 w-full  py-3 ">
                    <iconify-icon icon="ic:round-edit" class="mt-1 text-xl"></iconify-icon> تعديل بيانات
                </button>
                <button data-modal="modal1"
                    class="flex open-modal items-center red-shadow bg-[#F4F7F9] text-[#D92D20] rounded-xl gap-2 px-20 py-3 border border-[#D92D20]">
                    <iconify-icon icon="carbon:close-filled" class="mt-1 text-xl"></iconify-icon> إيقاف العضوية
                </button>
            @endif
        </div>
    </div>

    <hr class="border border-[#124375] mx-7 my-2 print:hidden">

