<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System\Department;
use App\Models\Membership\Member;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $activeDepartmentsQuery = Department::withCount('members')->where('status', 'active');
        $archivedDepartmentsQuery = Department::withCount('members')->where('status', 'archived');

        if ($search) {
            $activeDepartmentsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
            $archivedDepartmentsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $activeDepartments = $activeDepartmentsQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'active_page');
        $archivedDepartments = $archivedDepartmentsQuery->orderBy('created_at', 'desc')->paginate(10, ['*'], 'archived_page');

        $totalActive = Department::where('status', 'active')->count();
        $totalArchived = Department::where('status', 'archived')->count();
        $totalMembers = Member::count();

        return view('admin.departments.index', compact('activeDepartments', 'archivedDepartments', 'totalActive', 'totalArchived', 'totalMembers', 'search'));
    }

    public function show(Request $request, Department $department)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $membersQuery = $department->members()->with('membershipInfo');

        if ($search) {
            $membersQuery->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhereHas('membershipInfo', function($q2) use ($search) {
                      $q2->where('membership_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($status && $status !== 'الكل') {
            $statusMap = [
                'نشط' => 'active',
                'قيد التسجيل' => 'pending',
                'إعارة' => 'loan',
                'محال للمعاش' => 'pension',
                'منسحب' => 'withdrawn',
                'مفصول' => 'dismissed',
                'أجازه بدون مرتب' => 'unpaid_leave',
                'منتهية العضوية' => 'expired',
                'موقوف' => 'suspended'
            ];
            $mappedStatus = $statusMap[$status] ?? null;
            if ($mappedStatus) {
                $membersQuery->whereHas('membershipInfo', function($q) use ($mappedStatus) {
                    $q->where('status', $mappedStatus);
                });
            }
        }

        $members = $membersQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.departments.show', compact('department', 'members', 'search', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Code can be auto-generated or input
        ]);

        $code = $request->input('code');
        if (!$code) {
            // Auto generate code e.g. DEP-XXX
            $code = 'DEP-' . strtoupper(substr(uniqid(), -4));
        }

        Department::create([
            'name' => $request->name,
            'code' => $code,
            'status' => 'active',
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'تم إضافة الكلية بنجاح');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $department->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.departments.index')->with('success', 'تم تعديل بيانات الكلية بنجاح');
    }

    public function archive(Department $department)
    {
        $department->update(['status' => 'archived']);
        return redirect()->route('admin.departments.index')->with('success', 'تم أرشفة الكلية بنجاح');
    }

    public function restore(Department $department)
    {
        $department->update(['status' => 'active']);
        return redirect()->route('admin.departments.index')->with('success', 'تم استعادة الكلية بنجاح');
    }
}
