<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Auth\Role;
use App\Models\Membership\Member;
use App\Models\System\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountCreatedMail;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $roleSearch = $search;
        if ($search) {
            if (mb_strpos('مدير النظام', $search) !== false) $roleSearch = 'admin';
            elseif (mb_strpos('موظف', $search) !== false) $roleSearch = 'employee';
            elseif (mb_strpos('عضو', $search) !== false) $roleSearch = 'member';
        }

        $query = User::with('role')
            ->when($search, function ($q) use ($search, $roleSearch) {
                $q->where(function ($sub) use ($search, $roleSearch) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('custom_permissions', 'like', "%{$search}%")
                        ->orWhereHas('role', function ($r) use ($roleSearch) {
                            $r->where('name', 'like', "%{$roleSearch}%");
                        });
                });
            });

        $activeUsers = (clone $query)->where('is_restricted', false)->whereNotNull('role_id')->latest()->paginate(10, ['*'], 'active_page')->appends(['search' => $search]);
        $suspendedUsers = (clone $query)->where('is_restricted', true)->whereNotNull('role_id')->latest()->paginate(10, ['*'], 'suspended_page')->appends(['search' => $search]);
        $pendingRequests = (clone $query)->where('is_restricted', true)->whereNull('role_id')->latest()->paginate(10, ['*'], 'pending_page')->appends(['search' => $search]);
        $rejectedRequests = (clone $query)->onlyTrashed()->latest()->paginate(10, ['*'], 'rejected_page')->appends(['search' => $search]);

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
        $messages = [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نصاً.',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرفاً.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'الرجاء إدخال بريد إلكتروني صحيح.',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفاً.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل مسبقاً.',
            'national_id.size' => 'الرقم القومي يجب أن يتكون من 14 رقماً.',
            'national_id.unique' => 'هذا الرقم القومي مسجل مسبقاً.',
            'national_id.required' => 'حقل الرقم القومي مطلوب.'
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'national_id' => 'required|nullable|string|size:14|unique:users,national_id',
            'role_name' => 'nullable|string',
            'faculties' => 'nullable|array',
            'permissions' => 'nullable|array',
        ], $messages);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['national_id'])) {
            $user->national_id = $validated['national_id'];
        }
        $passwordStr = !empty($validated['national_id']) ? $validated['national_id'] : '12345678';
        $user->password = bcrypt($passwordStr);

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

            if (!$member->exists) {
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

        try {
            Mail::to($user->email)->send(new AccountCreatedMail($user, $passwordStr, $validated['permissions'] ?? []));
        } catch (\Exception $e) {
            Log::error('Failed to send account creation email: ' . $e->getMessage());
        }

        return redirect()->route('admin.permissions.index')->with('success', 'تم إضافة المستخدم بنجاح وإرسال بيانات الدخول إلى بريده الإلكتروني.');
    }

    public function edit(User $user)
    {
        $departments = Department::where('status', 'active')->get();
        $roles = Role::all();
        return view('admin.permissions.edit', compact('user', 'departments', 'roles'));
    }

    public function approve(User $user, Request $request)
    {
        $messages = [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.string' => 'الاسم يجب أن يكون نصاً.',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرفاً.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'الرجاء إدخال بريد إلكتروني صحيح.',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفاً.',
            'email.unique' => 'هذا البريد الإلكتروني مسجل مسبقاً.',
            'national_id.required' => 'حقل الرقم القومي مطلوب.',
            'national_id.size' => 'الرقم القومي يجب أن يتكون من 14 رقماً.',
            'national_id.unique' => 'هذا الرقم القومي مسجل مسبقاً.'
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'national_id' => 'required|nullable|string|size:14|unique:users,national_id,' . $user->id,
            'role_name' => 'nullable|string',
            'faculties' => 'nullable|array',
            'permissions' => 'nullable|array',
        ], $messages);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['national_id'])) {
            $user->national_id = $validated['national_id'];
        }

        // Assign the appropriate role to the user, creating it if it doesn't already exist.
        if (!empty($validated['role_name'])) {
            $role = Role::firstOrCreate(['name' => $validated['role_name']]);
            $user->role_id = $role->id;
        }

        // Assign the faculties and custom permissions as JSON arrays.
        $user->faculties = $validated['faculties'] ?? [];
        $user->custom_permissions = $validated['permissions'] ?? [];

        $user->is_restricted = false; // Mark the user as approved by lifting any restrictions.
        $user->save();

        // If a national ID is provided, either update the existing member record or create a new one.
        if (!empty($validated['national_id'])) {
            $member = Member::firstOrNew(['user_id' => $user->id]);

            if (!$member->exists) {
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
        // We use a soft delete here to mark the request as rejected without permanently removing the record.
        $user->delete();
        return back()->with('success', 'تم رفض الطلب بنجاح.');
    }

    public function suspend(User $user)
    {
        if ($user->isFirstAdmin()) {
            return back()->with('error', 'لا يمكن إيقاف حساب المدير الرئيسي.');
        }

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

        if ($user->isFirstAdmin()) {
            return back()->with('error', 'لا يمكن حذف حساب المدير الرئيسي.');
        }

        $user->forceDelete();
        return back()->with('success', 'تم الحذف النهائي.');
    }
}
