@extends('layouts.dashboard')

@section('title', 'قائمة الأعضاء')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-[24px] font-bold text-primary">قائمة الأعضاء</h2>
        <button class="inline-flex items-center gap-2 bg-primary text-white py-2.5 px-5 rounded-md font-semibold transition-colors duration-150 hover:bg-primary-light">
            <i class="fa-solid fa-user-plus"></i>
            تسجيل عضو جديد
        </button>
    </div>

    <div class="flex flex-wrap gap-4 mb-6">
        <div class="flex-1 min-w-[300px] relative">
            <input type="text" placeholder="الاسم أو رقم العضوية أو الرقم القومي" class="w-full py-2.5 pr-10 pl-3 border border-slate-200 rounded-md outline-none focus:border-primary text-sm text-slate-800">
            <i class="fa-solid fa-magnifying-glass absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
        </div>

        <div class="relative min-w-[200px]">
            <select class="w-full appearance-none py-2.5 px-3 pl-9 border border-slate-200 rounded-md bg-white text-sm text-slate-800 outline-none focus:border-primary cursor-pointer">
                <option value="all">الجهة : جميع الجهات</option>
                @if(isset($departments) && $departments->count() > 0)
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                @else
                    <option value="tagara">كلية تجارة وإدارة أعمال</option>
                @endif
            </select>
        </div>

        <div class="relative min-w-[200px]">
            <select class="w-full appearance-none py-2.5 px-3 pl-9 border border-slate-200 rounded-md bg-white text-sm text-slate-800 outline-none focus:border-primary cursor-pointer">
                <option value="all">الحالة : الكل</option>
                <option value="active">نشط</option>
                <option value="registering">قيد التسجيل</option>
                <option value="loan">إعارة</option>
                <option value="pension">محال للمعاش</option>
                <option value="withdrawn">منسحب</option>
                <option value="dismissed">مفصول</option>
                <option value="unpaid_leave">إجازة بدون راتب</option>
                <option value="expired">منتهي العضوية</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-x-auto mb-6">
        <table class="w-full border-collapse min-w-[900px] text-right">
            <thead>
                <tr>
                    <th class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 bg-slate-50 text-slate-500 font-semibold text-sm">رقم العضوية</th>
                    <th class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 bg-slate-50 text-slate-500 font-semibold text-sm">اسم العضو</th>
                    <th class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 bg-slate-50 text-slate-500 font-semibold text-sm">الرقم القومي</th>
                    <th class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 bg-slate-50 text-slate-500 font-semibold text-sm">الجهة</th>
                    <th class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 bg-slate-50 text-slate-500 font-semibold text-sm">رقم الهاتف</th>
                    <th class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 bg-slate-50 text-slate-500 font-semibold text-sm">الحالة</th>
                    <th class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 bg-slate-50 text-slate-500 font-semibold text-sm">تاريخ الانضمام</th>
                    <th class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 bg-slate-50 text-slate-500 font-semibold text-sm">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    @php
                        $statusLabel = $statusMap[$member->status]['label'] ?? $member->status;
                        
                        $departmentName = 'غير محدد';
                        if($member->divisions->first() && $member->divisions->first()->department) {
                            $departmentName = $member->divisions->first()->department->name;
                        } elseif ($member->employments->where('is_current', true)->first()) {
                            $departmentName = $member->employments->where('is_current', true)->first()->department ?? 'غير محدد';
                        }
                        
                        $statusColors = [
                            'active' => 'text-green-600 border-green-600 bg-green-50',
                            'registering' => 'text-blue-600 border-blue-600 bg-blue-50',
                            'loan' => 'text-purple-600 border-purple-600 bg-purple-50',
                            'pension' => 'text-slate-600 border-slate-600 bg-slate-100',
                            'withdrawn' => 'text-orange-600 border-orange-600 bg-orange-50',
                            'dismissed' => 'text-red-700 border-red-700 bg-red-100',
                            'unpaid_leave' => 'text-amber-600 border-amber-600 bg-amber-50',
                            'expired' => 'text-red-600 border-red-600 bg-red-50',
                        ];
                        
                        $mappedStatus = $member->status; // or if db differs map it
                        $twClass = $statusColors[$mappedStatus] ?? 'text-slate-600 border-slate-600 bg-slate-50';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 text-sm align-middle">{{ $member->member_number }}</td>
                        <td class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 text-sm align-middle">{{ $member->person->full_name ?? 'غير متوفر' }}</td>
                        <td class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 text-sm align-middle">{{ $member->person->national_id ?? 'غير متوفر' }}</td>
                        <td class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 text-sm align-middle">{{ $departmentName }}</td>
                        <td class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 text-sm align-middle">{{ $member->person->phone ?? 'غير متوفر' }}</td>
                        <td class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 text-sm align-middle">
                            <span class="inline-block py-1 px-3 rounded-full text-xs font-semibold text-center border {{ $twClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 text-sm align-middle">{{ \Carbon\Carbon::parse($member->join_date)->translatedFormat('j F Y') }}</td>
                        <td class="py-3.5 px-4 border-b border-l border-slate-200 last:border-l-0 text-sm align-middle">
                            <button class="text-primary-light text-lg transition-colors hover:text-primary"><i class="fa-regular fa-eye"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-6 text-center text-slate-500">لا يوجد أعضاء مضافين بعد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $members->links() }}
    </div>

@endsection
