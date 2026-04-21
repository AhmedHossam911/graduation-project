<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\User;
use App\Models\Membership\Member;
use App\Models\System\Department;
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

        Auth::login($user);
        $user->update(['last_login' => now()]);
        return redirect()->intended('/dashboard');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'national_id' => 'required|string|size:14|unique:members,national_id',
            'password' => 'required|string|min:6|max:20|regex:/[A-Z]/|regex:/[@$!%*#?&]/'
        ], [
            'name.required' => 'يرجى إدخال الاسم.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً.',
            'national_id.required' => 'يرجى إدخال الرقم القومي.',
            'national_id.size' => 'الرقم القومي يجب أن يكون 14 رقماً.',
            'national_id.unique' => 'الرقم القومي مسجل مسبقاً.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_restricted' => true 
            ]);

            $department = Department::firstOrCreate(['name' => 'Pending Registration']);

            Member::create([
                'user_id' => $user->id,
                'department_id' => $department->id,
                'full_name' => $request->name,
                'national_id' => $request->national_id
            ]);
        });

        return redirect()->route('login')->with('success', 'تم إنشاء الحساب بنجاح، يرجى الانتظار لحين موافقة الإدارة.');
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
            Mail::raw("رقم المرور المؤقت الخاص بك هو: $otp \n\n صالح لمدة 10 دقائق.", function($msg) use ($user) {
                $msg->to($user->email)->subject('رمز استعادة كلمة المرور');
            });
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
