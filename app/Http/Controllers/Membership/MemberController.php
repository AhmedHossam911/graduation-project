<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\System\Department;
use App\Models\Membership\Member;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::all();
        
        $query = Member::with(['person', 'divisions.department', 'employments']);
        
        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('member_number', 'like', "%{$search}%")
                  ->orWhereHas('person', function($q) use ($search) {
                      $q->where('national_id', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('second_name', 'like', "%{$search}%")
                        ->orWhere('third_name', 'like', "%{$search}%")
                        ->orWhere('fourth_name', 'like', "%{$search}%");
                  });
        }
        
        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Department Filter
        if ($request->filled('department') && $request->department !== 'all') {
            $departmentId = $request->department;
            $query->whereHas('divisions', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }
        
        $members = $query->paginate(25)->withQueryString();
        
        $statusMap = [
            'active' => ['label' => 'نشط', 'class' => 'active'],
            'registering' => ['label' => 'قيد التسجيل', 'class' => 'registering'],
            'loan' => ['label' => 'إعارة', 'class' => 'loan'],
            'pension' => ['label' => 'محال للمعاش', 'class' => 'pension'],
            'withdrawn' => ['label' => 'منسحب', 'class' => 'withdrawn'],
            'dismissed' => ['label' => 'مفصول', 'class' => 'dismissed'],
            'unpaid_leave' => ['label' => 'إجازة بدون راتب', 'class' => 'unpaid-leave'],
            'expired' => ['label' => 'منتهي العضوية', 'class' => 'expired'],
            // Fallbacks for DB enums if they differ
            'suspended' => ['label' => 'موقوف', 'class' => 'expired'],
            'terminated' => ['label' => 'منتهي', 'class' => 'expired'],
            'deceased' => ['label' => 'متوفي', 'class' => 'expired'],
        ];
        
        return view('members.index', compact('departments', 'members', 'statusMap'));
    }
}
