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
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', function() {
        return view('dashboard.index');
    })->name('dashboard');

    // Members
    Route::get('/members', [\App\Http\Controllers\Membership\MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [\App\Http\Controllers\Membership\MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [\App\Http\Controllers\Membership\MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}/print', [\App\Http\Controllers\Membership\MemberController::class, 'print'])->name('members.print');
    Route::post('/members/{member}/signed-form', [\App\Http\Controllers\Membership\MemberController::class, 'uploadSignedForm'])->name('members.signed-form');
});
