<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Manages the self-service profile portal for standard members.
 * Enables them to update basic contact info and securely change their passwords.
 */
class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function index()
    {
        $user = Auth::user();

        return view('member.profile.index', compact('user'));
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()
            ->route('profile.index')
            ->with('success', 'تم تحديث البيانات بنجاح.');
    }

    /**
     * Process a password change request.
     * Enforces checking the current password before allowing the update for security.
     */
public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => ['required', 'string'],
        'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors([
            'current_password' => 'كلمة المرور الحالية غير صحيحة.',
        ]);
    }

    $user->update([
        'password' => Hash::make($request->new_password), // ✅ صح كده
    ]);

    return redirect()
        ->route('profile.index')
        ->with('success', 'تم تغيير كلمة المرور بنجاح.');
}
}
