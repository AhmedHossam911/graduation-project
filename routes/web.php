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

    // ─── Members (member data CRUD) ──────────────────────────────────
    Route::get('/members', [\App\Http\Controllers\Membership\MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [\App\Http\Controllers\Membership\MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [\App\Http\Controllers\Membership\MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}/print', [\App\Http\Controllers\Membership\MemberController::class, 'print'])->name('members.print');
    Route::get('/members/{member}/upload-signed', [\App\Http\Controllers\Membership\MemberController::class, 'uploadSignedState'])->name('members.upload_signed');
    Route::get('/members/{member}/edit', [\App\Http\Controllers\Membership\MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [\App\Http\Controllers\Membership\MemberController::class, 'update'])->name('members.update');
    Route::post('/members/{member}/signed-form', [\App\Http\Controllers\Membership\MemberController::class, 'uploadSignedForm'])->name('members.signed-form');
    Route::post('/members/{member}/suspend', [\App\Http\Controllers\Membership\MemberController::class, 'suspend'])->name('members.suspend');
    Route::post('/members/{member}/claim', [\App\Http\Controllers\Claims\ClaimController::class, 'store'])->name('members.storeClaim');
    Route::delete('/members/{member}', [\App\Http\Controllers\Membership\MemberController::class, 'destroy'])->name('members.destroy');
    Route::get('/members/{member}', [\App\Http\Controllers\Membership\MemberController::class, 'show'])->name('members.show');

    // ─── Memberships (membership lifecycle: approve/reject/status) ───
    Route::post('/memberships/{membership}/approve', [\App\Http\Controllers\Membership\MembershipController::class, 'approve'])->name('memberships.approve');
    Route::post('/memberships/{membership}/reject', [\App\Http\Controllers\Membership\MembershipController::class, 'reject'])->name('memberships.reject');
    Route::post('/memberships/{membership}/status', [\App\Http\Controllers\Membership\MembershipController::class, 'changeStatus'])->name('memberships.changeStatus');

    // ─── Subscriptions (payment tracking, was previously "memberships") ───
    Route::get('/subscriptions/export', [\App\Http\Controllers\Membership\SubscriptionController::class, 'export'])->name('subscriptions.export');
    Route::get('/subscriptions', [\App\Http\Controllers\Membership\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/create', [\App\Http\Controllers\Membership\SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [\App\Http\Controllers\Membership\SubscriptionController::class, 'store'])->name('subscriptions.store');

    // ─── Legacy aliases (keep old route names working) ───────────────
    Route::get('/memberships/export', [\App\Http\Controllers\Membership\SubscriptionController::class, 'export'])->name('memberships.export');
    Route::get('/memberships', [\App\Http\Controllers\Membership\SubscriptionController::class, 'index'])->name('memberships.index');
        
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

    // Claims Management
    Route::get('/claims', [\App\Http\Controllers\Claims\ClaimController::class, 'index'])->name('claims.index');
    Route::get('/claims/{claim}', [\App\Http\Controllers\Claims\ClaimController::class, 'show'])->name('claims.show');
    Route::post('/claims/{claim}/approve', [\App\Http\Controllers\Claims\ClaimController::class, 'approve'])->name('claims.approve');

    // ─── Loans Management ──────────────────────────────────────────────
    Route::get('/loans', [\App\Http\Controllers\Loans\LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans', [\App\Http\Controllers\Loans\LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/search-members', [\App\Http\Controllers\Loans\LoanController::class, 'searchMembers'])->name('loans.searchMembers');
    Route::get('/loans/{loan}', [\App\Http\Controllers\Loans\LoanController::class, 'show'])->name('loans.show');
    Route::post('/loans/{loan}/payment', [\App\Http\Controllers\Loans\LoanController::class, 'recordPayment'])->name('loans.recordPayment');
    Route::post('/loans/{loan}/approve', [\App\Http\Controllers\Loans\LoanController::class, 'approve'])->name('loans.approve');
});
