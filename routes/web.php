<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboard;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboard;
use App\Http\Controllers\Manager\BuildingRequestController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register/manager', [AuthController::class, 'showManagerRegisterForm'])->name('register.manager');
    Route::post('/register/manager', [AuthController::class, 'registerManager']);
});

// صفحات اختصاصی هر نقش:
// Route::prefix('super-admin')->middleware(['auth', 'role:super_admin'])->group(function () {
//     Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('super_admin.dashboard');
// });

// Route::prefix('manager')->middleware(['auth', 'role:manager'])->group(function () {
//     Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
// });

// Route::prefix('resident')->middleware(['auth', 'role:resident'])->group(function () {
//     Route::get('/dashboard', [ResidentController::class, 'dashboard'])->name('resident.dashboard');
// });

