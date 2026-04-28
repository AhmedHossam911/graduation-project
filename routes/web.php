<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');
    
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.verify');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify.post');
    
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('log-out');
    
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Members
    Route::get('/members', [\App\Http\Controllers\Membership\MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [\App\Http\Controllers\Membership\MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [\App\Http\Controllers\Membership\MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}/print', [\App\Http\Controllers\Membership\MemberController::class, 'print'])->name('members.print');
    Route::post('/members/{member}/signed-form', [\App\Http\Controllers\Membership\MemberController::class, 'uploadSignedForm'])->name('members.signed-form');
    Route::post('/members/{member}/suspend', [\App\Http\Controllers\Membership\MemberController::class, 'suspend'])->name('members.suspend');
    Route::get('/members/{member}', [\App\Http\Controllers\Membership\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}claim_type=retirement' , [\App\Http\Controllers\Membership\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}claim_type=resignation' , [\App\Http\Controllers\Membership\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}claim_type=early_retirement' , [\App\Http\Controllers\Membership\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}claim_type=withdrawal' , [\App\Http\Controllers\Membership\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}claim_type=expulsion' , [\App\Http\Controllers\Membership\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}claim_type=professional_disability' , [\App\Http\Controllers\Membership\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}claim_type=transfer' , [\App\Http\Controllers\Membership\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}claim_type=death' , [\App\Http\Controllers\Membership\MemberController::class, 'show'])->name('members.show');


    // Memberships
    Route::get('/memberships/export', [\App\Http\Controllers\Membership\MembershipController::class, 'export'])->name('memberships.export');
    Route::get('/memberships', [\App\Http\Controllers\Membership\MembershipController::class, 'index'])->name('memberships.index');
    Route::get('/memberships/create', [\App\Http\Controllers\Membership\MembershipController::class, 'create'])->name('memberships.create');
    Route::post('/memberships', [\App\Http\Controllers\Membership\MembershipController::class, 'store'])->name('memberships.store');
    Route::get('/memberships/{membership}/print', [\App\Http\Controllers\Membership\MembershipController::class, 'print'])->name('memberships.print');
    Route::post('/memberships/{membership}/signed-form', [\App\Http\Controllers\Membership\MembershipController::class, 'uploadSignedForm'])->name('memberships.signed-form');
        
    // Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [\App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.change-password');
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [\App\Http\Controllers\NotificationController::class, 'clear'])->name('notifications.clear');

    
});
