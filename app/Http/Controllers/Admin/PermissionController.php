<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Membership\Member;
use App\Models\System\Department;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $activeUsers = User::with('role')->where('is_restricted', false)->whereNotNull('role_id')->paginate(10, ['*'], 'active_page');
        $suspendedUsers = User::with('role')->where('is_restricted', true)->whereNotNull('role_id')->paginate(10, ['*'], 'suspended_page');
        $pendingRequests = User::with('role')->where('is_restricted', true)->whereNull('role_id')->paginate(10, ['*'], 'pending_page');
        $rejectedRequests = User::with('role')->onlyTrashed()->paginate(10, ['*'], 'rejected_page');

        return view('admin.permissions.index', compact('activeUsers', 'suspendedUsers', 'pendingRequests', 'rejectedRequests'));
    }
    public function create()
    {
        $departments = Department::where('status', 'active')->get();
        $roles = Role::all();
        return view('admin.permissions.create', compact('departments', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'national_id' => 'nullable|string|size:14',
            'role_name' => 'nullable|string',
            'faculties' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt('12345678'); // default password

        if (!empty($validated['role_name'])) {
            $role = Role::firstOrCreate(['name' => $validated['role_name']]);
            $user->role_id = $role->id;
        }

        $user->faculties = $validated['faculties'] ?? [];
        $user->custom_permissions = $validated['permissions'] ?? [];
        $user->is_restricted = false; 
        $user->save();

        if (!empty($validated['national_id'])) {
            $member = Member::firstOrNew(['user_id' => $user->id]);
            $member->national_id = $validated['national_id'];
            
            if (!$member->exists) {
                $member->full_name = $validated['name'];
                $department = null;
                if (!empty($validated['faculties'])) {
                    $department = Department::where('name', $validated['faculties'][0])->first();
                }
                if (!$department) {
                    $department = Department::first();
                }
                if ($department) {
                    $member->department_id = $department->id;
                }
            }
            
            $member->save();
        }

        return redirect()->route('admin.permissions.index')->with('success', 'تم إضافة المستخدم بنجاح.');
    }
    public function edit(User $user)
    {
        $departments = Department::where('status', 'active')->get();
        $roles = Role::all();
        return view('admin.permissions.edit', compact('user', 'departments', 'roles'));
    }

    public function approve(User $user, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'national_id' => 'nullable|string|size:14',
            'role_name' => 'nullable|string',
            'faculties' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Assign or update Role
        if (!empty($validated['role_name'])) {
            $role = Role::firstOrCreate(['name' => $validated['role_name']]);
            $user->role_id = $role->id;
        }

        // Assign JSON columns
        $user->faculties = $validated['faculties'] ?? [];
        $user->custom_permissions = $validated['permissions'] ?? [];
        
        $user->is_restricted = false; // Mark user as approved
        $user->save();

        // Update or create Member if national_id is provided
        if (!empty($validated['national_id'])) {
            $member = Member::firstOrNew(['user_id' => $user->id]);
            $member->national_id = $validated['national_id'];
            
            if (!$member->exists) {
                $member->full_name = $validated['name'];
                $department = null;
                if (!empty($validated['faculties'])) {
                    $department = Department::where('name', $validated['faculties'][0])->first();
                }
                if (!$department) {
                    $department = Department::first();
                }
                if ($department) {
                    $member->department_id = $department->id;
                }
            }
            
            $member->save();
        }

        return redirect()->route('admin.permissions.index')->with('success', 'تم حفظ الصلاحيات واعتماد المستخدم بنجاح.');
    }

    public function reject(User $user)
    {
        // Placeholder: soft delete
        $user->delete();
        return back()->with('success', 'تم رفض الطلب بنجاح.');
    }

    public function suspend(User $user)
    {
        $user->is_restricted = true;
        $user->save();
        return back()->with('success', 'تم إيقاف حساب المستخدم بنجاح.');
    }

    public function reactivate(User $user)
    {
        $user->is_restricted = false;
        $user->save();
        return back()->with('success', 'تم تفعيل حساب المستخدم بنجاح.');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return back()->with('success', 'تمت استعادة المستخدم للمراجعة.');
    }

    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
        return back()->with('success', 'تم الحذف النهائي.');
    }
}
