<?php

use App\Http\Controllers\Membership\SubscriptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Claims\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Loans\LoanController;
use App\Http\Controllers\Membership\MemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Services\MembershipController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Members (member data CRUD) ──────────────────────────────────
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}/print', [MemberController::class, 'print'])->name('members.print');
    Route::get('/members/{member}/upload-signed', [MemberController::class, 'uploadSignedState'])->name('members.upload_signed');
    Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::post('/members/{member}/signed-form', [MemberController::class, 'uploadSignedForm'])->name('members.signed-form');
    Route::post('/members/{member}/suspend', [MemberController::class, 'suspend'])->name('members.suspend');
    Route::post('/members/{member}/claim', [ClaimController::class, 'store'])->name('members.storeClaim');
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
    Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');

    // ─── Memberships (membership lifecycle: approve/reject/status) ───
    Route::post('/memberships/{membership}/approve', [MembershipController::class, 'approve'])->name('memberships.approve');
    Route::post('/memberships/{membership}/reject', [MembershipController::class, 'reject'])->name('memberships.reject');
    Route::post('/memberships/{membership}/status', [MembershipController::class, 'changeStatus'])->name('memberships.changeStatus');

    // ─── Subscriptions (payment tracking, was previously "memberships") ───
    Route::get('/subscriptions/export', [SubscriptionController::class, 'export'])->name('subscriptions.export');
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');

    // ─── Legacy aliases (keep old route names working) ───────────────
    Route::get('/memberships/export', [SubscriptionController::class, 'export'])->name('memberships.export');
    Route::get('/memberships', [SubscriptionController::class, 'index'])->name('memberships.index');
        
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'clear'])->name('notifications.clear');

    // Claims Management
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
    Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show');
    Route::post('/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');

    // ─── Loans Management ──────────────────────────────────────────────
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/search-members', [LoanController::class, 'searchMembers'])->name('loans.searchMembers');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::post('/loans/{loan}/payment', [LoanController::class, 'recordPayment'])->name('loans.recordPayment');
    Route::post('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
});
