{{-- 
    Member Profile Header Partial:
    Displays the member's basic information (name, membership number, status) 
    and action buttons for editing data or suspending the membership.
--}}
<div class="overlay backdrop-brightness-50 inset-0 fixed hidden z-[60] print:hidden"></div>

    {{-- head --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-10 py-5 gap-4 print:hidden">
        <div class="flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-7 items-start sm:items-center">
                <p class="text-[28px] font-semibold text-[#124375]">{{ $member->user->name }}</p>
                <p class="mt-3 status {{ $badgeClass }} rounded-lg px-10 border">{{ $statusData['label'] }}</p>
            </div>
            <p class="text-[#021219] text-sm font-medium flex items-center gap-4">
                رقم العضوية :
                <span class="text-[#124375] font-semibold text-xl">{{ $membership->membership_number ?? '-' }}</span>
            </p>
        </div>
        <div class="space-y-2 mt-3 w-full md:w-auto">
            @if (auth()->user() && auth()->user()->hasPermission('إدارة الأعضاء'))
                @if(!$isMembershipClosed)
                    <button data-modal="modal-edit"
                        class="open-modal flex items-center justify-center navy-shadow bg-[#124375] text-[#FEFFFC] rounded-xl gap-2 w-full  py-3 ">
                        <iconify-icon icon="ic:round-edit" class="mt-1 text-xl"></iconify-icon> تعديل بيانات
                    </button>
                    <button data-modal="modal1"
                        class="flex open-modal justify-center items-center red-shadow bg-[#F4F7F9] text-[#D92D20] rounded-xl gap-2 px-4 sm:px-20 py-3 border border-[#D92D20] w-full">
                        <iconify-icon icon="carbon:close-filled" class="mt-1 text-xl"></iconify-icon> إيقاف العضوية
                    </button>
                    <div class="relative w-full">
                        <button type="button" class="dropDownBtn flex items-center justify-between navy-shadow bg-[#F4F7F9] text-[#124375] rounded-xl gap-2 w-full px-4 py-3 border border-[#124375]">
                            <span class="flex items-center gap-2">
                                <iconify-icon icon="mdi:list-status" class="mt-1 text-xl"></iconify-icon> تعديل حالة العضوية
                            </span>
                            <iconify-icon icon="fe:arrow-down" class="text-xl"></iconify-icon>
                        </button>
                        <div class="dropDown hidden absolute z-[80] bg-[#F4F7F9] left-0 top-full mt-2 flex flex-col gap-2 px-3 py-3 rounded-xl navy-shadow w-full">
                            <form action="{{ route('memberships.changeStatus', $membership->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="loaned">
                                <button type="submit" class="w-full text-center navy-shadow py-2 rounded-xl text-base text-[#124375] hover:bg-[#EEF7FF]">معار</button>
                            </form>
                            <form action="{{ route('memberships.changeStatus', $membership->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="unpaid_leave">
                                <button type="submit" class="w-full text-center navy-shadow py-2 rounded-xl text-base text-[#124375] hover:bg-[#EEF7FF]">إجازة بدون مرتب</button>
                            </form>
                        </div>
                    </div>
                @else
                    @if(auth()->user()->isAdmin() && !$membership->claims()->whereIn('status', ['approved', 'paid'])->exists())
                        <form action="{{ route('members.reactivate', $member->id) }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="flex justify-center items-center bg-[#ECFDF3] text-[#067647] rounded-xl gap-2 px-4 sm:px-20 py-3 border border-[#067647] w-full" style="box-shadow: 0 4px 6px -1px rgba(6, 118, 71, 0.1), 0 2px 4px -1px rgba(6, 118, 71, 0.06);">
                                <iconify-icon icon="mdi:restore" class="mt-1 text-xl"></iconify-icon> إعادة تنشيط العضوية
                            </button>
                        </form>
                    @endif
                @endif
            @endif
        </div>
    </div>

    <hr class="border border-[#124375] mx-7 my-2 print:hidden">

