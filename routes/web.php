<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('loginForm');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register/manager', [AuthController::class, 'showManagerRegisterForm'])->name('register.manager');
    Route::post('/register/manager', [AuthController::class, 'registerManager']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// مدیران
Route::prefix('manager')->middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');

    // مدیریت ساختمان‌ها
    Route::get('/buildings/create', [ManagerController::class, 'createRequest'])->name('manager.buildings.create');
    Route::post('/buildings', [ManagerController::class, 'storeRequest'])->name('manager.buildings.store');
    Route::get('/buildings/{id}', [ManagerController::class, 'showRequest'])->name('manager.buildings.show');
});

Route::prefix('admin')->middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('super_admin.dashboard');

    // مدیریت درخواست‌های ساختمان
    Route::get('/requests', [SuperAdminController::class, 'requests'])->name('super_admin.requests');
    Route::post('/building-requests/{id}/approve', [SuperAdminController::class, 'approveRequest'])->name('admin.requests.approve');
    Route::post('/building-requests/{id}/reject', [SuperAdminController::class, 'rejectRequest'])->name('admin.requests.reject');
});

// Route::prefix('resident')->middleware(['auth', 'role:resident'])->group(function () {
//     Route::get('/dashboard', [ResidentController::class, 'dashboard'])->name('resident.dashboard');
//     Route::get('/payments', [ResidentController::class, 'payments'])->name('resident.payments');
// });
