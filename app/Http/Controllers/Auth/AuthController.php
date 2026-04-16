<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\User;
use App\Models\Auth\OtpCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Show views
    public function showLogin() {
        return view('auth.login');
    }
    
    public function showRegister() {
        return view('auth.register');
    }
    
    public function showForgotPassword() {
        return view('auth.forgot-password');
    }

    // Login logic
    public function login(Request $request) {
        $request->validate([
            'national_id' => 'required',
            'password' => 'required'
        ], [
            'national_id.required' => 'يرجى إدخال الرقم القومي.',
            'password.required' => 'يرجى إدخال كلمة المرور.'
        ]);

        $user = User::where('national_id', $request->national_id)->first();

        // Check if user exists
        if (!$user) {
            return back()->withErrors(['national_id' => 'الرقم القومي غير مسجل لدينا.'])->withInput();
        }

        // Check if password is correct
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'كلمة المرور غير صحيحة.'])->withInput();
        }

        // Check status / suspension
        if ($user->status === 'pending') {
            return back()->withInput()->with('error', 'الحساب قيد المراجعة بواسطة الإدارة.');
        }
        if (!$user->is_active || $user->status === 'suspended') {
            return back()->withInput()->with('error', 'suspended'); 
        }

        Auth::login($user);
        $user->update(['last_login_at' => now()]);
        return redirect()->intended('/dashboard');
    }

    // Register logic
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'national_id' => 'required|string|size:14|unique:users',
            'password' => 'required|string|min:6|max:20|regex:/[A-Z]/|regex:/[@$!%*#?&]/'
        ], [
            'name.required' => 'يرجى إدخال الاسم.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً.',
            'national_id.required' => 'يرجى إدخال الرقم القومي.',
            'national_id.size' => 'الرقم القومي يجب أن يتكون من 14 رقماً بالضبط.',
            'national_id.unique' => 'الرقم القومي مسجل مسبقاً.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.min' => 'كلمة المرور يجب أن لا تقل عن 6 أحرف.',
            'password.max' => 'كلمة المرور يجب أن لا تزيد عن 20 حرفاً.',
            'password.regex' => 'يجب أن تحتوي كلمة المرور على حرف كبير ورمز خاص على الأقل.'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'national_id' => $request->national_id,
            'password' => Hash::make($request->password),
            'is_active' => false 
        ]);

        return redirect()->route('login')->with('success', 'تم إنشاء الحساب بنجاح، يرجى الانتظار لحين موافقة الإدارة.');
    }

    // Password Reset via OTP - Step 1
    public function sendOtp(Request $request) {
        $request->validate([
            'national_id' => 'required',
            'email' => 'required|email'
        ], [
            'national_id.required' => 'يرجى إدخال الرقم القومي.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.'
        ]);

        $user = User::where('national_id', $request->national_id)->where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'البيانات غير متطابقة.');
        }

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);
        
        OtpCode::create([
            'user_id' => $user->id,
            'type' => 'password_reset',
            'code' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'is_used' => false
        ]);

        // Send Email (We will mock this or use Mail::raw for simplicity until custom Mailable is made)
        try {
            Mail::raw("رقم المرور المؤقت الخاص بك هو: $otp \n\n صالح لمدة 10 دقائق.", function($msg) use ($user) {
                $msg->to($user->email)->subject('رمز استعادة كلمة المرور - صندوق الزمالة');
            });
        } catch(\Exception $e) {
            // Log if needed
        }

        session(['reset_user_id' => $user->id]);
        return redirect()->route('password.verify');
    }

    // Show Verify OTP View
    public function showVerifyOtp() {
        if(!session('reset_user_id')) return redirect()->route('password.request');
        return view('auth.otp-verify');
    }

    // Verify OTP Logic
    public function verifyOtp(Request $request) {
        $request->validate([
            'code' => 'required|digits:6'
        ], [
            'code.required' => 'يرجى إدخال رمز التحقق.',
            'code.digits' => 'رمز التحقق يجب أن يتكون من 6 أرقام.'
        ]);
        $userId = session('reset_user_id');

        $otpRecord = OtpCode::where('user_id', $userId)
            ->where('code', $request->code)
            ->where('type', 'password_reset')
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'الرمز غير صحيح أو منتهي الصلاحية.');
        }

        $otpRecord->update(['is_used' => true, 'used_at' => Carbon::now()]);
        session(['otp_verified' => true]);

        return redirect()->route('password.reset');
    }

    // Show Reset Password View
    public function showResetPassword() {
        if(!session('otp_verified') || !session('reset_user_id')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    // Reset Password Logic
    public function resetPassword(Request $request) {
        $request->validate([
            'password' => 'required|string|min:6|max:20|regex:/[A-Z]/|regex:/[@$!%*#?&]/|confirmed'
        ], [
            'password.required' => 'يرجى إدخال كلمة المرور الجديدة.',
            'password.min' => 'كلمة المرور يجب أن لا تقل عن 6 أحرف.',
            'password.max' => 'كلمة المرور يجب أن لا تزيد عن 20 حرفاً.',
            'password.regex' => 'يجب أن تحتوي كلمة المرور على حرف كبير ورمز خاص على الأقل.',
            'password.confirmed' => 'كلمة المرور غير متطابقة.'
        ]);

        $userId = session('reset_user_id');
        $user = User::find($userId);
        $user->update(['password' => Hash::make($request->password)]);

        // Clear sessions
        session()->forget(['reset_user_id', 'otp_verified']);

        return redirect()->route('login')->with('success', 'تم إعادة تعيين كلمة المرور بنجاح.');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
