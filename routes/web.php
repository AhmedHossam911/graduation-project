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
use App\Http\Controllers\Employee\Finance\FinanceController;
use App\Http\Controllers\Employee\PrintController;
use App\Http\Controllers\Common\NotificationController;
use App\Http\Controllers\Common\ProfileController;
use App\Http\Middleware\EnsureEmployee;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\AuditLogController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ─── Authentication & Access Control ─────────────────────────────────
// Routes handling login, registration, password resets, and 2-Factor Authentication.
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

Route::middleware(['auth', \App\Http\Middleware\CheckIsRestricted::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('log-out');

    // ─── Administration Panel ──────────────────────────────────────────
    // Routes strictly reserved for the Super Admin role to manage system-wide settings, permissions, and view high-level reports.
    Route::middleware([EnsureAdmin::class])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/dashboard/chart-data', [AdminDashboardController::class, 'chartData'])->name('admin.dashboard.chartData');
        Route::get('/admin/search', [AdminDashboardController::class, 'search'])->name('admin.search');

        // Comprehensive Reporting System
        Route::get('/admin/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
        
        Route::get('/admin/reports/revenue-expenses', [\App\Http\Controllers\Admin\ReportController::class, 'revenueExpenses'])->name('admin.reports.revenue_expenses');
        
        Route::get('/admin/reports/financial-position', [\App\Http\Controllers\Admin\ReportController::class, 'financialPosition'])->name('admin.reports.financial_position');
        Route::get('/admin/reports/financial-position/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportFinancialPosition'])->name('admin.reports.export_financial_position');
        
        Route::get('/admin/reports/subscriptions', [\App\Http\Controllers\Admin\ReportController::class, 'subscriptions'])->name('admin.reports.subscriptions');
        
        Route::get('/admin/reports/arrears', [\App\Http\Controllers\Admin\ReportController::class, 'arrears'])->name('admin.reports.arrears');
        
        Route::get('/admin/reports/loans', [\App\Http\Controllers\Admin\ReportController::class, 'loans'])->name('admin.reports.loans');
        Route::get('/admin/reports/loans/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportLoans'])->name('admin.reports.export_loans');
        
        Route::get('/admin/reports/installments', [\App\Http\Controllers\Admin\ReportController::class, 'installments'])->name('admin.reports.installments');
        Route::get('/admin/reports/installments/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportInstallments'])->name('admin.reports.export_installments');
        
        Route::get('/admin/reports/claims', [\App\Http\Controllers\Admin\ReportController::class, 'claims'])->name('admin.reports.claims');
        
        Route::get('/admin/reports/pending-claims', [\App\Http\Controllers\Admin\ReportController::class, 'pendingClaims'])->name('admin.reports.pending_claims');
        
        Route::get('/admin/reports/members-distribution', [\App\Http\Controllers\Admin\ReportController::class, 'membersDistribution'])->name('admin.reports.members_distribution');
        Route::get('/admin/reports/members-distribution/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportMembersDistribution'])->name('admin.reports.export_members_distribution');
        
        Route::get('/admin/reports/audit-logs', [\App\Http\Controllers\Admin\ReportController::class, 'auditLogs'])->name('admin.reports.audit_logs');
        Route::get('/admin/reports/audit-logs/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportAuditLogs'])->name('admin.reports.export_audit_logs');

        Route::middleware(['permission:إعدادات اللائحة'])->group(function () {
            Route::get('/admin/settings', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
            Route::post('/admin/settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
            Route::post('/admin/settings/reset', [AdminSettingsController::class, 'reset'])->name('admin.settings.reset');
        });

        Route::middleware(['permission:سجل العمليات'])->group(function () {
            Route::get('/admin/auditlog', [AuditLogController::class, 'index'])->name('admin.auditlog.index');
        });

        Route::middleware(['permission:إدارة الصلاحيات'])->group(function () {
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
        });

        Route::get('/admin/departments', [DepartmentController::class, 'index'])->name('admin.departments.index');
        Route::get('/admin/departments/{department}', [DepartmentController::class, 'show'])->name('admin.departments.show');
        Route::post('/admin/departments', [DepartmentController::class, 'store'])->name('admin.departments.store');
        Route::put('/admin/departments/{department}', [DepartmentController::class, 'update'])->name('admin.departments.update');
        Route::post('/admin/departments/{department}/archive', [DepartmentController::class, 'archive'])->name('admin.departments.archive');
        Route::post('/admin/departments/{department}/restore', [DepartmentController::class, 'restore'])->name('admin.departments.restore');
    });


    Route::middleware(['staff'])->group(function () {
        // ─── Shared Staff Profile ───────────────────────────────────────
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

        // ─── In-App Notifications (Staff) ───────────────────────────────
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/notifications', [NotificationController::class, 'clear'])->name('notifications.clear');
    });

    // ─── Member Portal ───────────────────────────────────────────────
    Route::middleware(['member'])->prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Member\DashboardController::class, 'index'])->name('dashboard');
        
        Route::get('/profile', [\App\Http\Controllers\Member\ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\Member\ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/change-password', [\App\Http\Controllers\Member\ProfileController::class, 'changePassword'])->name('profile.change-password');
        
        Route::get('/notifications', [\App\Http\Controllers\Member\NotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Member\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\Member\NotificationController::class, 'readAll'])->name('notifications.read-all');
        Route::delete('/notifications/{notification}', [\App\Http\Controllers\Member\NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/notifications', [\App\Http\Controllers\Member\NotificationController::class, 'clear'])->name('notifications.clear');

        Route::get('/loans', [\App\Http\Controllers\Member\LoanController::class, 'index'])->name('loans.index');
        Route::post('/loans', [\App\Http\Controllers\Member\LoanController::class, 'store'])->name('loans.store');
        Route::get('/claims', [\App\Http\Controllers\Member\ClaimController::class, 'index'])->name('claims.index');
        Route::post('/claims', [\App\Http\Controllers\Member\ClaimController::class, 'store'])->name('claims.store');
        Route::get('/receipts', [\App\Http\Controllers\Member\ReceiptController::class, 'index'])->name('receipts.index');
        Route::get('/membership/create', [\App\Http\Controllers\Member\MembershipController::class, 'create'])->name('membership.create');
        Route::post('/membership', [\App\Http\Controllers\Member\MembershipController::class, 'store'])->name('membership.store');
    });

    // ─── Employee Portal ─────────────────────────────────────────────
    // Operations handled by staff members: managing members, finances, claims, and loans.
    Route::middleware([EnsureEmployee::class])->group(function () {
        // Dashboard (Employee)
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/search-member', [EmployeeDashboardController::class, 'searchMember'])->name('dashboard.searchMember');
        // ─── Membership Management ───────────────────────────────────────
        // Create, update, and soft-delete member records.
        Route::middleware(['permission:إدارة الأعضاء'])->group(function () {
            Route::get('members/create', [MemberController::class, 'create'])->name('members.create');
            Route::post('members', [MemberController::class, 'store'])->name('members.store');
            Route::get('members/{member}/print', [MemberController::class, 'print'])->name('members.print');
            Route::get('members/{member}/upload-signed', [MemberController::class, 'uploadSignedState'])->name('members.upload_signed');
            Route::post('members/{member}/signed-form', [MemberController::class, 'uploadSignedForm'])->name('members.signed-form');
            Route::post('members/{member}/quick-update', [MemberController::class, 'quickUpdate'])->name('members.quickUpdate');

            Route::resource('members', MemberController::class)->except(['create', 'store', 'destroy', 'show']);
            Route::delete('members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');
            Route::post('members/{member}/suspend', [MemberController::class, 'suspend'])->name('members.suspend');

            Route::post('/members/{member}/notify', [MemberController::class, 'notify'])->name('members.notify');
            Route::post('/members/{member}/claim', [ClaimController::class, 'store'])->name('members.storeClaim');
            Route::get('/members/{member}/documents', [MemberController::class, 'documents'])->name('members.documents');
            Route::post('/members/{member}/documents', [MemberController::class, 'storeAdditionalDocument'])->name('members.documents.store');
            Route::get('/documents/{attachment}/view', [MemberController::class, 'viewDocument'])->name('documents.view');
            Route::get('/documents/{attachment}/download', [MemberController::class, 'downloadDocument'])->name('documents.download');

            // ─── Membership Lifecycle ──────────────────────────────────────
            // Approve, reject, or manually change the status of a member's application.
            Route::post('/memberships/{membership}/approve', [MembershipController::class, 'approve'])->name('memberships.approve');
            Route::post('/memberships/{membership}/reject', [MembershipController::class, 'reject'])->name('memberships.reject');
            Route::post('/memberships/{membership}/status', [MembershipController::class, 'changeStatus'])->name('memberships.changeStatus');
        });

        // ─── Member Profile (View Only for specific roles) ───────────────
        Route::middleware(['permission:إدارة الأعضاء|إدارة المطالبات|إدارة القروض|إدارة الاشتراكات'])->group(function () {
            Route::get('members/{member}', [MemberController::class, 'show'])->name('members.show');
        });

        // ─── Subscription & Dues Management ──────────────────────────────
        // Record and track monthly payments. (Previously referred to internally as "memberships").
        Route::middleware(['permission:إدارة الاشتراكات'])->group(function () {
            Route::get('/subscriptions/export', [SubscriptionController::class, 'export'])->name('subscriptions.export');
            Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
            Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
            Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
            Route::post('/subscriptions/{subscription}/pay', [SubscriptionController::class, 'pay'])->name('subscriptions.pay');
            Route::get('/subscriptions/{subscription}/view-receipt', [SubscriptionController::class, 'viewReceipt'])->name('subscriptions.view_receipt');
            Route::get('/subscriptions/{subscription}/download-receipt', [SubscriptionController::class, 'downloadReceipt'])->name('subscriptions.download_receipt');
            Route::post('/subscriptions/{subscription}/notify', [SubscriptionController::class, 'notify'])->name('subscriptions.notify');

            // Legacy aliases retained to ensure backward compatibility with older links.
            Route::get('/memberships/export', [SubscriptionController::class, 'export'])->name('memberships.export');
            Route::get('/memberships', [SubscriptionController::class, 'index'])->name('memberships.index');
            Route::post('/subscriptions/{subscription}/send-notice', [SubscriptionController::class, 'sendNotice'])->name('subscriptions.send_notice');
        });

        // ─── Claims Workflow ─────────────────────────────────────────────
        // Process end-of-service, death, or resignation insurance claims.
        Route::middleware(['permission:إدارة المطالبات'])->group(function () {
            Route::get('/claims/export', [ClaimController::class, 'export'])->name('claims.export');
            Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
            Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show');
            Route::post('/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');
            Route::post('/claims/{claim}/reject', [ClaimController::class, 'reject'])->name('claims.reject');
            Route::post('/claims/{claim}/finalize', [ClaimController::class, 'finalize'])->name('claims.finalize');
        });

        // ─── Loan Operations ─────────────────────────────────────────────
        // Approve loans, track installments, and handle early repayment scenarios.
        Route::middleware(['permission:إدارة القروض'])->group(function () {
            Route::get('/loans/export', [LoanController::class, 'export'])->name('loans.export');
            Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
            Route::post('/loans/validate-request', [LoanController::class, 'validateLoanRequest'])->name('loans.validateRequest');
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

        // ─── General Financial Transactions ──────────────────────────────
        // Overview of all cash inflows and outflows across the system.
        Route::middleware(['permission:الشؤون المالية'])->group(function () {
            Route::get('/finance/export', [FinanceController::class, 'export'])->name('finance.export');
            Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
            Route::post('/finance', [FinanceController::class, 'store'])->name('finance.store');
            Route::get('/finance/{transaction}', [FinanceController::class, 'show'])->name('finance.show');
        });
        // ─── Document Printing ───────────────────────────────────────────
        // Generate printable receipts, declarations, and reports.
        Route::prefix('print')->name('print.')->group(function () {
            Route::get('/transaction/{id}', [PrintController::class, 'transaction'])->name('transaction');
            Route::get('/claim-receipt/{id}', [PrintController::class, 'claimReceipt'])->name('claim_receipt');
            Route::get('/claim-details/{id}', [PrintController::class, 'claimDetails'])->name('claim_details');
            Route::get('/claim-declaration/{id}', [PrintController::class, 'claimDeclaration'])->name('claim_declaration');
            Route::get('/loan-declaration/{id}', [PrintController::class, 'loanDeclaration'])->name('loan_declaration');
            Route::get('/new-loan-declaration/{member}', [PrintController::class, 'newLoanDeclaration'])->name('new_loan_declaration');
            Route::get('/board-details/{id}', [PrintController::class, 'boardDetails'])->name('board_details');
            Route::get('/new-membership-receipt/{id}', [PrintController::class, 'newMembershipReceipt'])->name('new_membership_receipt');
        });
    });
});
