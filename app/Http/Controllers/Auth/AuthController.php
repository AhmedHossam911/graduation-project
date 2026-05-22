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

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }
    
    public function showRegister() {
        return view('auth.register');
    }
    
    public function showForgotPassword() {
        return view('auth.forgot-password');
    }

    public function login(Request $request) {
        $request->validate([
            'national_id' => 'required',
            'password' => 'required'
        ], [
            'national_id.required' => 'يرجى إدخال الرقم القومي.',
            'password.required' => 'يرجى إدخال كلمة المرور.'
        ]);

        $member = Member::where('national_id', $request->national_id)->first();

        if (!$member || !$member->user) {
            return back()->withErrors(['national_id' => 'الرقم القومي غير مسجل لدينا.'])->withInput();
        }

        $user = $member->user;

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'كلمة المرور غير صحيحة.'])->withInput();
        }

        if ($user->is_restricted === true) {
            return back()->withInput()->with('error', 'الحساب قيد المراجعة أو موقوف بواسطة الإدارة.');
        }

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

        // 2FA Flow
        session(['login_2fa_user_id' => $user->id]);
        return redirect()->route('login.2fa');
    }

    public function show2faChoice() {
        if (!session('login_2fa_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.2fa-choice');
    }

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

        if ($user->role_id == 3) {
            return redirect()->intended('/member/dashboard');
        }
        return redirect()->intended('/dashboard');
    }

    public function show2faFingerprint(Request $request) {
        $userId = session('login_2fa_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::find($userId);
        
        // Generate options for this specific user
        $options = app(\Laravel\Passkeys\Actions\GenerateVerificationOptions::class)($user);
        
        // Store options in session for verification later
        session(['passkeys_verification_options' => $options]);

        return view('auth.2fa-fingerprint', compact('options'));
    }

    public function verify2faFingerprint(Request $request) {
        $userId = session('login_2fa_user_id');
        if (!$userId) return response()->json(['message' => 'Unauthenticated'], 401);

        $user = User::find($userId);
        $options = session('passkeys_verification_options');

        if (!$options) {
            return response()->json(['message' => 'Invalid session'], 400);
        }

        try {
            app(\Laravel\Passkeys\Actions\VerifyPasskey::class)(
                $request->all(), // The credential payload from JS
                $options,
                $user
            );

            // Verified successfully
            session()->forget(['login_2fa_user_id', 'passkeys_verification_options']);
            
            Auth::login($user);
            $user->update(['last_login' => now()]);

            return response()->json([
                'redirect' => $user->role_id == 3 ? '/member/dashboard' : '/dashboard'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'national_id' => 'required|string|size:14|unique:members,national_id',
            'password' => 'required|string|min:6|max:20|regex:/[A-Z]/|regex:/[@$!%*#?&]/|confirmed',
            'phone' => 'required|string|max:20',
            'workplace' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
        ], [
            'name.required' => 'يرجى إدخال الاسم.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً.',
            'national_id.required' => 'يرجى إدخال الرقم القومي.',
            'national_id.size' => 'الرقم القومي يجب أن يكون 14 رقماً.',
            'national_id.unique' => 'الرقم القومي مسجل مسبقاً.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'phone.required' => 'يرجى إدخال رقم التليفون.',
            'workplace.required' => 'يرجى إدخال جهة العمل.',
            'job_title.required' => 'يرجى إدخال الوظيفة.',
        ]);

        $user = null;
        DB::transaction(function() use ($request, &$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 3,
                'is_restricted' => true 
            ]);

            $department = Department::firstOrCreate(['name' => 'Pending Registration']);

            $member = Member::create([
                'user_id' => $user->id,
                'department_id' => $department->id,
                'full_name' => $request->name,
                'national_id' => $request->national_id,
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
        $request->validate(['code' => 'required|digits:6']);
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

        return redirect()->route('member.dashboard')->with('success', 'تم تفعيل الحساب بنجاح.');
    }

    public function sendOtp(Request $request) {
        $request->validate([
            'national_id' => 'required',
            'email' => 'required|email'
        ]);

        $member = Member::where('national_id', $request->national_id)->first();

        if (!$member || !$member->user || $member->user->email !== $request->email) {
            return back()->with('error', 'البيانات غير متطابقة.');
        }

        $user = $member->user;
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

    public function verifyOtp(Request $request) {
        $request->validate(['code' => 'required|digits:6']);
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

    public function resetPassword(Request $request) {
        $request->validate([
            'password' => 'required|string|min:6|max:20|regex:/[A-Z]/|regex:/[@$!%*#?&]/|confirmed'
        ]);

        $userId = session('reset_user_id');
        $user = User::find($userId);
        $user->update(['password' => Hash::make($request->password)]);

        session()->forget(['reset_user_id', 'otp_verified']);

        return redirect()->route('login')->with('success', 'تم إعادة تعيين كلمة المرور بنجاح.');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
