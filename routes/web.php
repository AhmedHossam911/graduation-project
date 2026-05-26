<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\Claims\ClaimController;
use App\Http\Controllers\Employee\Loans\LoanController;
use App\Http\Controllers\Employee\Membership\MemberController;
use App\Http\Controllers\Employee\Membership\MembershipController;
use App\Http\Controllers\Employee\Membership\SubscriptionController;
use App\Http\Controllers\Member\NotificationController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Middleware\EnsureEmployee;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\AuditLogController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // 2FA Routes
    Route::post('/login/2fa/otp/send', [AuthController::class, 'send2faOtp'])->name('login.2fa.otp.send');
    Route::get('/login/2fa/otp', [AuthController::class, 'show2faOtpVerify'])->name('login.2fa.otp');
    Route::post('/login/2fa/otp/verify', [AuthController::class, 'verify2faOtp'])->name('login.2fa.otp.verify');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendOtp'])->name('password.email');

    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.verify');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify.post');

    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset.post');

    Route::get('/verify-registration-otp', [AuthController::class, 'showVerifyRegistrationOtp'])->name('register.verify');
    Route::post('/verify-registration-otp', [AuthController::class, 'verifyRegistrationOtp'])->name('register.verify.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('log-out');

    // Admin Routes (Admin Only)
    Route::middleware([EnsureAdmin::class])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/settings', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
        Route::post('/admin/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
        Route::post('/admin/settings/reset', [AdminSettingsController::class, 'reset'])->name('admin.settings.reset');
        
        Route::get('/admin/auditlog', [AuditLogController::class, 'index'])->name('admin.auditlog.index');

        Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');
        Route::get('/admin/permissions/create', [PermissionController::class, 'create'])->name('admin.permissions.create');
        Route::post('/admin/permissions', [PermissionController::class, 'store'])->name('admin.permissions.store');
        Route::get('/admin/permissions/{user}/edit', [PermissionController::class, 'edit'])->name('admin.permissions.edit');
        Route::post('/admin/permissions/{user}/approve', [PermissionController::class, 'approve'])->name('admin.permissions.approve');
        Route::post('/admin/permissions/{user}/reject', [PermissionController::class, 'reject'])->name('admin.permissions.reject');
        Route::post('/admin/permissions/{user}/suspend', [PermissionController::class, 'suspend'])->name('admin.permissions.suspend');
        Route::post('/admin/permissions/{user}/reactivate', [PermissionController::class, 'reactivate'])->name('admin.permissions.reactivate');
        Route::post('/admin/permissions/{user}/restore', [PermissionController::class, 'restore'])->name('admin.permissions.restore');
        Route::delete('/admin/permissions/{user}/destroy', [PermissionController::class, 'destroy'])->name('admin.permissions.destroy');

        Route::get('/admin/departments', [DepartmentController::class, 'index'])->name('admin.departments.index');
        Route::get('/admin/departments/{department}', [DepartmentController::class, 'show'])->name('admin.departments.show');
        Route::post('/admin/departments', [DepartmentController::class, 'store'])->name('admin.departments.store');
        Route::put('/admin/departments/{department}', [DepartmentController::class, 'update'])->name('admin.departments.update');
        Route::post('/admin/departments/{department}/archive', [DepartmentController::class, 'archive'])->name('admin.departments.archive');
        Route::post('/admin/departments/{department}/restore', [DepartmentController::class, 'restore'])->name('admin.departments.restore');
    });


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

    // Employee Routes
    Route::middleware([EnsureEmployee::class])->group(function () {
        // Dashboard (Employee)
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');

        // ─── Members (member data CRUD) ──────────────────────────────────
        Route::get('members/create', [MemberController::class, 'create'])->name('members.create');
        Route::post('members', [MemberController::class, 'store'])->name('members.store');
        Route::get('members/{member}/print', [MemberController::class, 'print'])->name('members.print');
        Route::get('members/{member}/upload-signed', [MemberController::class, 'uploadSignedState'])->name('members.upload_signed');
        Route::post('members/{member}/signed-form', [MemberController::class, 'uploadSignedForm'])->name('members.signed-form');
        
        Route::resource('members', MemberController::class)->except(['create', 'store', 'destroy']);
        Route::delete('members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
        Route::post('members/{member}/suspend', [MemberController::class, 'suspend'])->name('members.suspend');

        Route::post('/members/{member}/notify', [MemberController::class, 'notify'])->name('members.notify');
        Route::post('/members/{member}/claim', [ClaimController::class, 'store'])->name('members.storeClaim');
        Route::get('/members/{member}/documents', [MemberController::class, 'documents'])->name('members.documents');
        Route::post('/members/{member}/documents', [MemberController::class, 'storeAdditionalDocument'])->name('members.documents.store');
        Route::get('/documents/{attachment}/view', [MemberController::class, 'viewDocument'])->name('documents.view');
        Route::get('/documents/{attachment}/download', [MemberController::class, 'downloadDocument'])->name('documents.download');

        // ─── Memberships (membership lifecycle: approve/reject/status) ───
        Route::post('/memberships/{membership}/approve', [MembershipController::class, 'approve'])->name('memberships.approve');
        Route::post('/memberships/{membership}/reject', [MembershipController::class, 'reject'])->name('memberships.reject');
        Route::post('/memberships/{membership}/status', [MembershipController::class, 'changeStatus'])->name('memberships.changeStatus');

        // ─── Subscriptions (payment tracking, was previously "memberships") ───
        Route::get('/subscriptions/export', [SubscriptionController::class, 'export'])->name('subscriptions.export');
        Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
        Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
        Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
        Route::post('/subscriptions/{subscription}/pay', [SubscriptionController::class, 'pay'])->name('subscriptions.pay');
        Route::post('/subscriptions/{subscription}/notify', [SubscriptionController::class, 'notify'])->name('subscriptions.notify');

        // ─── Legacy aliases (keep old route names working) ───────────────
        Route::get('/memberships/export', [SubscriptionController::class, 'export'])->name('memberships.export');
        Route::get('/memberships', [SubscriptionController::class, 'index'])->name('memberships.index');
        Route::post('/subscriptions/{subscription}/send-notice', [SubscriptionController::class, 'sendNotice'])->name('subscriptions.send_notice');

        // Claims Management
        Route::get('/claims/export', [ClaimController::class, 'export'])->name('claims.export');
        Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
        Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show');
        Route::post('/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');
        Route::post('/claims/{claim}/finalize', [ClaimController::class, 'finalize'])->name('claims.finalize');

        // ─── Loans Management ──────────────────────────────────────────────
        Route::get('/loans/export', [LoanController::class, 'export'])->name('loans.export');
        Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
        Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
        Route::get('/loans/search-members', [LoanController::class, 'searchMembers'])->name('loans.searchMembers');
        Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
        Route::get('/members/{member}/previous-loans', [LoanController::class, 'previousLoans'])->name('members.previous-loans');
        Route::get('/loans/{loan}/data', [LoanController::class, 'getLoanData'])->name('loans.data');
        Route::post('/loans/{loan}/payment', [LoanController::class, 'recordPayment'])->name('loans.recordPayment');
        Route::post('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        
        // Modal routes
        Route::post('/loans/{loan}/start', [LoanController::class, 'startLoan'])->name('loans.start');
        Route::post('/loans/{loan}/cancel', [LoanController::class, 'cancelLoan'])->name('loans.cancel');
        Route::post('/loans/installments/{installment}/pay', [LoanController::class, 'payInstallment'])->name('loans.installments.pay');
        Route::post('/loans/{loan}/early-repayment', [LoanController::class, 'earlyRepayment'])->name('loans.earlyRepayment');
    });

});
