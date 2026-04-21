@extends('layouts.app')

@section('title', 'قائمة الأعضاء')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-[24px] font-bold text-[#124375]">قائمة الأعضاء</h2>
        <a href="{{ route('members.create') }}"
            class="inline-flex items-center surface-shadow gap-2 bg-[#124375] text-white py-8 px-5 rounded-xl font-semibold transition-colors duration-150 hover:bg-primary-light w-[322px] h-[50px] justify-center">
            <iconify-icon icon="ic:round-group-add" width="24" height="24"></iconify-icon>
            تسجيل عضو جديد
        </a>
    </div>

    <form action="{{ route('members.index') }}" method="GET">
        <div class="flex flex-wrap gap-4 mb-6">
            <!-- start search -->
            <div class="flex-1 items-center gap-5">
                <input type="search" name="search" value="{{ request('search') }}"
                    placeholder=" الاسم  أو  رقم العضوية  أو  الرقم القومي أو رقم القرض" icon="bitcoin-icons:search-outline"
                    class="w-full rounded-xl py-2 pr-2 surface-shadow outline-none focus:ring-1 focus:ring-[#124375] focus:shadow-[#124375] focus:shadow">
            </div>
            <!-- end search -->

            <div class="relative min-w-[200px]">
                <select name="department"
                    class="w-full appearance-none py-2.5 px-3 pl-9 border border-slate-200 rounded-md bg-white text-sm text-slate-800 outline-none focus:border-primary cursor-pointer">
                    <option value="all">الجهة : جميع الجهات</option>
                    @if (isset($departments) && $departments->count() > 0)
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department') == $department->id ? 'selected' : '' }}>{{ $department->name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="relative min-w-[200px]">
                <select name="status"
                    class="w-full appearance-none py-2.5 px-3 pl-9 border border-slate-200 rounded-md bg-white text-sm text-slate-800 outline-none focus:border-primary cursor-pointer">
                    <option value="all">الحالة : الكل</option>
                    @foreach ($statusMap as $key => $status)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $status['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <button class="bg-[#124375] text-white rounded-xl px-7 surface-shadow">
                <iconify-icon icon="bitcoin-icons:search-outline" class="text-4xl"></iconify-icon>
            </button>
        </div>
    </form>

    <!-- start table -->
    <section>
        <div class="rounded-2xl overflow-hidden surface-shadow">
            <table class="w-full">
                <tr class="bg-[#EEF7FF] border-b border-[#6D6D6D]">
                    <th class="py-3 border-l border-[#6D6D6D]">رقم العضوية</th>
                    <th class="py-3 border-l border-[#6D6D6D]">اسم العضو</th>
                    <th class="py-3 border-l border-[#6D6D6D]">الرقم القومي</th>
                    <th class="py-3 border-l border-[#6D6D6D]">الجهة</th>
                    <th class="py-3 border-l border-[#6D6D6D]">رقم الهاتف</th>
                    <th class="py-3 border-l border-[#6D6D6D]">الحالة</th>
                    <th class="py-3 border-l border-[#6D6D6D]">تاريخ الانضمام</th>
                    <th class="py-3 border-l border-[#6D6D6D]">الإجراءات</th>
                </tr>
                @if ($members->count() > 0)
                    @foreach ($members as $member)
                        <tr class="text-center even:bg-[#F4F7F9] odd:bg-[#EFEFEF]">
                            <td class="py-3 border-l border-b border-[#6D6D6D]">{{ $member->membership->membership_number }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">{{ $member->user->name }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">{{ $member->national_id }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                {{ $member->user->department?->name ?? '-' }}
                            </td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">{{ $member->user->phone }}</td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                @php
                                    $status = $member->membership->status ?? null;
                                @endphp
                                @if ($status == null)
                                    <span
                                        class="text-[#067647] bg-[#067647]/10 w-[90%] py-1 block mx-auto rounded-2xl border-[1px] border-[#067647]">غير
                                        معروف</span>
                                @elseif ($status == 'active')
                                    <span
                                        class="text-[#067647] bg-[#067647]/10 w-[90%] py-1 block mx-auto rounded-2xl border-[1px] border-[#067647]">نشط</span>
                                @elseif($status == 'registering')
                                    <span
                                        class="text-[#175CD3] bg-[#175CD3]/10 w-[90%] py-1 block mx-auto rounded-2xl border-[1px] border-[#175CD3]">قيد
                                        التسجيل</span>
                                @elseif($status == 'loan' || $status == 'another_entity')
                                    <span
                                        class="text-[#5925DC] bg-[#5925DC]/10 w-[90%] py-1 block mx-auto rounded-2xl border-[1px] border-[#5925DC]">إعارة</span>
                                @elseif($status == 'pension')
                                    <span
                                        class="text-[#E6B800] bg-[#E6B800]/10 w-[90%] py-1 block mx-auto rounded-2xl border-[1px] border-[#E6B800]">محال
                                        للمعاش</span>
                                @elseif($status == 'withdrawn')
                                    <span
                                        class="text-[#F79009] bg-[#F79009]/10 w-[90%] py-1 block mx-auto rounded-2xl border-[1px] border-[#F79009]">منسحب</span>
                                @elseif($status == 'dismissed')
                                    <span
                                        class="text-[#D92D20] bg-[#D92D20]/10 w-[90%] py-1 block mx-auto rounded-2xl border-[1px] border-[#D92D20]">مفصول</span>
                                @elseif($status == 'unpaid_leave')
                                    <span
                                        class="text-[#4B5A70] bg-[#4B5A70]/10 w-[90%] py-1 block mx-auto rounded-2xl border-[1px] border-[#4B5A70]">إجازة
                                        بدون راتب</span>
                                @elseif($status == 'expired' || $status == 'terminated')
                                    <span
                                        class="text-[#021219] bg-[#021219]/10 w-[90%] py-1 block mx-auto rounded-2xl border-[1px] border-[#021219]">منتهي
                                        العضوية</span>
                                @elseif($status == 'suspended')
                                    <span
                                        class="text-[#D92D20] bg-[#D92D20]/10 w-[90%] py-1 block mx-auto rounded-2xl border-[1px] border-[#D92D20]">موقوف</span>
                                @endif
                            </td>
                            <td class="py-3 border-l border-b border-[#6D6D6D]">
                                {{ $member->membership->start_date ?? $member->join_date }}</td>
                            <td class="p-3 border-l border-b border-[#6D6D6D]">
                                <a href="#">
                                    <iconify-icon
                                        class="text-[#124375] hover:scale-110 transition-all hover:duration-1000 hover:border-[1px] hover:border-[#124375] hover:p-1 cursor-pointer"
                                        icon="ic:baseline-remove-red-eye" width="24" height="24"></iconify-icon> </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="py-4 text-center text-gray-500">لا توجد بيانات</td>
                    </tr>
                @endif
            </table>
        </div>
    </section>

    <div class="sticky bottom-0 bg-[#F4F7FE] py-5 border-t border-[#A8A8A8] mt-8 -mx-6 px-6 backdrop-blur-md bg-white/80">
        {{ $members->links() }}
    </div>

@endsection
