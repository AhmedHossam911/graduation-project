<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\User;
use App\Models\Membership\Member;
use App\Models\System\Department;
use App\Models\Membership\EmploymentInfo;
use App\Models\Auth\OtpCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Centralized controller managing the entire authentication lifecycle.
 * Handles Login, 2-Factor Authentication (2FA), Registration, and Password Recovery.
 * Heavily relies on OTPs (One-Time Passwords) via Email to ensure security.
 */
class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function showRegister() {
        $departments = \App\Models\System\Department::all();
        return view('auth.register', compact('departments'));
    }

    public function showForgotPassword() {
        return view('auth.forgot-password');
    }

    /**
     * Authenticate a user via their National ID and password.
     * Enforces account verification and 2FA before establishing a session.
     */
    public function login(Request $request) {
        $request->validate([
            'national_id' => 'required',
            'password' => 'required'
        ], [
            'national_id.required' => 'يرجى إدخال الرقم القومي.',
            'password.required' => 'يرجى إدخال كلمة المرور.'
        ]);

        $user = User::where('national_id', $request->national_id)->first();

        if (!$user) {
            return back()->withErrors(['national_id' => 'الرقم القومي غير مسجل لدينا.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'كلمة المرور غير صحيحة.'])->withInput();
        }

        // If the account exists but the email hasn't been verified yet, force OTP verification.
        if (is_null($user->email_verified_at)) {
            $otp = rand(100000, 999999);
            OtpCode::create([
                'user_id' => $user->id,
                'code' => (string) $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
                'is_used' => false
            ]);

            try {
                Mail::to($user->email)->send(new \App\Mail\OtpMail($otp, 'تفعيل الحساب'));
            } catch(\Exception $e) { }

            session(['register_user_id' => $user->id]);
            return redirect()->route('register.verify')->with('success', 'حسابك غير مفعل. تم إرسال رمز تفعيل جديد إلى بريدك الإلكتروني.');
        }

        // Block login if the account is flagged as restricted by an administrator.
        if ($user->is_restricted === true) {
            return back()->withInput()->with('error', 'الحساب قيد المراجعة أو موقوف بواسطة الإدارة.');
        }

        // 2FA Flow: Store user ID temporarily in session and require OTP validation to proceed.
        session(['login_2fa_user_id' => $user->id]);

        $otp = rand(100000, 999999);
        OtpCode::create([
            'user_id' => $user->id,
            'code' => (string) $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\OtpMail($otp, 'تأكيد تسجيل الدخول (2FA)'));
        } catch(\Exception $e) { }

        session(['login_2fa_otp_sent' => true]);
        return redirect()->route('login.2fa.otp')->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.');
    }

    /**
     * Re-send a fresh 2FA OTP code if the user requests a new one.
     */
    public function send2faOtp(Request $request) {
        $userId = session('login_2fa_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::find($userId);

        $otp = rand(100000, 999999);
        OtpCode::create([
            'user_id' => $user->id,
            'code' => (string) $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\OtpMail($otp, 'تأكيد تسجيل الدخول (2FA)'));
        } catch(\Exception $e) { }

        session(['login_2fa_otp_sent' => true]);
        return redirect()->route('login.2fa.otp')->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.');
    }

    public function show2faOtpVerify() {
        if (!session('login_2fa_otp_sent') || !session('login_2fa_user_id')) {
            return redirect()->route('login.2fa');
        }
        return view('auth.2fa-otp');
    }

    /**
     * Validate the provided 2FA OTP code against the database.
     * On success, establishes the actual login session and redirects based on user role.
     */
    public function verify2faOtp(Request $request) {
        $request->validate(['code' => 'required|digits:6'], [
            'code.required' => 'يرجى إدخال رمز التحقق.',
            'code.digits' => 'الرمز يجب أن يتكون من 6 أرقام.'
        ]);

        $userId = session('login_2fa_user_id');
        if (!$userId) return redirect()->route('login');

        $otpRecord = OtpCode::where('user_id', $userId)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) return back()->with('error', 'الرمز غير صحيح أو منتهي الصلاحية.');

        $otpRecord->update(['is_used' => true]);

        $user = User::find($userId);

        session()->forget(['login_2fa_user_id', 'login_2fa_otp_sent']);

        Auth::login($user);
        $user->update(['last_login' => now()]);

        if ($user->role && strtolower($user->role->name) === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->role && strtolower($user->role->name) === 'member') {
            return redirect()->intended(route('profile.index'));
        }
        return redirect()->intended(route('dashboard'));
    }



    /**
     * Handle the registration of a new member.
     * Uses database transactions to ensure the User, Member, and Employment info are all created atomically.
     */
    public function register(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\p{Arabic}]+(?:\s+[\p{Arabic}]+){3}$/u'],
            'email' => 'required|string|email|max:255|unique:users',
            'national_id' => 'required|string|size:14|unique:users,national_id',
            'password' => 'required|string|min:6|max:20|regex:/[A-Z]/|regex:/[@$!%*#?&]/|confirmed',
            'phone' => 'required|string|max:20|unique:members,phone',
            'workplace' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
        ], [
            'name.required' => 'يرجى إدخال الاسم.',
            'name.regex' => 'الاسم يجب أن يكون باللغة العربية ويتكون من 4 أسماء فقط.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً.',
            'national_id.required' => 'يرجى إدخال الرقم القومي.',
            'national_id.size' => 'الرقم القومي يجب أن يتكون من 14 رقماً.',
            'national_id.unique' => 'الرقم القومي مسجل مسبقاً.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.min' => 'كلمة المرور يجب أن تتكون من 6 أحرف على الأقل.',
            'password.max' => 'كلمة المرور يجب ألا تتجاوز 20 حرفاً.',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير ورمز خاص.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'phone.required' => 'يرجى إدخال رقم التليفون.',
            'phone.unique' => 'رقم التليفون مسجل مسبقاً.',
            'workplace.required' => 'يرجى إدخال جهة العمل.',
            'job_title.required' => 'يرجى إدخال الوظيفة.',
        ]);

        $user = null;
        DB::transaction(function() use ($request, &$user) {
            $memberRole = \App\Models\Auth\Role::where('name', 'member')->first();
            $user = User::create([
                'name' => $request->name,
                'national_id' => $request->national_id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $memberRole ? $memberRole->id : 3,
                'is_restricted' => true
            ]);

            $department = Department::where('name', $request->workplace)->first();
            if (!$department) {
                $department = Department::firstOrCreate(['name' => $request->workplace]);
            }

            $member = Member::create([
                'user_id' => $user->id,
                'department_id' => $department->id,
                'phone' => $request->phone,
            ]);

            EmploymentInfo::create([
                'member_id' => $member->id,
                'workplace' => $request->workplace,
                'job_title' => $request->job_title,
            ]);
        });

        $otp = rand(100000, 999999);
        OtpCode::create([
            'user_id' => $user->id,
            'code' => (string) $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\OtpMail($otp, 'تفعيل الحساب الجديد'));
        } catch(\Exception $e) { }

        session(['register_user_id' => $user->id]);
        return redirect()->route('register.verify')->with('success', 'تم إرسال رمز التفعيل إلى بريدك الإلكتروني.');
    }

    public function showVerifyRegistrationOtp() {
        if(!session('register_user_id')) return redirect()->route('register');
        return view('auth.register-otp');
    }

    public function verifyRegistrationOtp(Request $request) {
        $request->validate(['code' => 'required|digits:6'], [
            'code.required' => 'يرجى إدخال رمز التحقق.',
            'code.digits' => 'الرمز يجب أن يتكون من 6 أرقام.'
        ]);
        $userId = session('register_user_id');

        $otpRecord = OtpCode::where('user_id', $userId)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) return back()->with('error', 'الرمز غير صحيح أو منتهي الصلاحية.');

        $otpRecord->update(['is_used' => true]);

        $user = User::find($userId);
        $user->update([
            'is_restricted' => false,
            'email_verified_at' => now()
        ]);

        session()->forget('register_user_id');

        Auth::login($user);
        $user->update(['last_login' => now()]);

        if ($user->role && strtolower($user->role->name) === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'تم تفعيل الحساب بنجاح.');
        } elseif ($user->role && strtolower($user->role->name) === 'member') {
            return redirect()->route('profile.index')->with('success', 'تم تفعيل الحساب بنجاح.');
        }
        return redirect()->route('dashboard')->with('success', 'تم تفعيل الحساب بنجاح.');
    }

    /**
     * Trigger the Password Recovery process by generating and sending an OTP to the user's email.
     */
    public function sendOtp(Request $request) {
        $request->validate([
            'national_id' => 'required',
            'email' => 'required|email'
        ], [
            'national_id.required' => 'يرجى إدخال الرقم القومي.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.'
        ]);

        $user = User::where('national_id', $request->national_id)->first();

        if (!$user || $user->email !== $request->email) {
            return back()->with('error', 'البيانات غير متطابقة.');
        }

        $otp = rand(100000, 999999);

        OtpCode::create([
            'user_id' => $user->id,
            'code' => (string) $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\OtpMail($otp, 'رمز استعادة كلمة المرور'));
        } catch(\Exception $e) { }

        session(['reset_user_id' => $user->id]);
        return redirect()->route('password.verify');
    }

    public function showVerifyOtp() {
        if(!session('reset_user_id')) return redirect()->route('password.request');
        return view('auth.otp-verify');
    }

    /**
     * Verify the OTP sent for password recovery.
     * Grants temporary authorization in the session to reset the password.
     */
    public function verifyOtp(Request $request) {
        $request->validate(['code' => 'required|digits:6'], [
            'code.required' => 'يرجى إدخال رمز التحقق.',
            'code.digits' => 'الرمز يجب أن يتكون من 6 أرقام.'
        ]);
        $userId = session('reset_user_id');

        $otpRecord = OtpCode::where('user_id', $userId)
            ->where('code', $request->code)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) return back()->with('error', 'الرمز غير صحيح أو منتهي الصلاحية.');

        $otpRecord->update(['is_used' => true]);
        session(['otp_verified' => true]);

        return redirect()->route('password.reset');
    }

    public function showResetPassword() {
        if(!session('otp_verified') || !session('reset_user_id')) return redirect()->route('password.request');
        return view('auth.reset-password');
    }

    /**
     * Complete the password recovery cycle by applying the new hashed password to the user account.
     */
    public function resetPassword(Request $request) {
        $request->validate([
            'password' => 'required|string|min:6|max:20|regex:/[A-Z]/|regex:/[@$!%*#?&]/|confirmed'
        ], [
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.string' => 'كلمة المرور يجب أن تكون نصاً.',
            'password.min' => 'كلمة المرور يجب أن تتكون من 6 أحرف على الأقل.',
            'password.max' => 'كلمة المرور يجب ألا تتجاوز 20 حرفاً.',
            'password.regex' => 'كلمة المرور يجب أن تحتوي على حرف كبير ورمز خاص.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.'
        ]);

        $userId = session('reset_user_id');
        $user = User::find($userId);
        $user->update(['password' => Hash::make($request->password)]);

        session()->forget(['reset_user_id', 'otp_verified']);

        return redirect()->route('login')->with('success', 'تم إعادة تعيين كلمة المرور بنجاح.');
    }

    /**
     * Terminate the user session and redirect to the login page.
     */
    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
