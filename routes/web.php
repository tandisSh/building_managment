<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Manager\Resident\ResidentController;
use App\Http\Controllers\Manager\Unit\UnitController;
use App\Http\Controllers\Manager\Invoice\InvoiceController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Manager\Building\BuildingController;

Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


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
    Route::get('/buildings/create', [BuildingController::class, 'createRequest'])->name('manager.buildings.create');
    Route::post('/buildings', [BuildingController::class, 'storeRequest'])->name('manager.buildings.store');
    Route::get('/buildings/{building}/edit', [BuildingController::class, 'editBuilding'])->name('manager.building.edit');
    Route::put('/buildings/{building}', [BuildingController::class, 'updateBuilding'])->name('manager.building.update');
    Route::get('/building', [BuildingController::class, 'showBuilding'])->name('manager.building.show');

    //مدیریت واحد ها
    Route::get('buildings/{building}/units', [UnitController::class, 'index'])->name('units.index');
    Route::get('buildings/{building}/units/{unit}', [UnitController::class, 'show'])->name('units.show');
    Route::get('buildings/{building}/units/create', [UnitController::class, 'create'])->name('units.create');
    Route::post('buildings/{building}/units', [UnitController::class, 'store'])->name('units.store');
    Route::get('buildings/{building}/units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
    Route::put('buildings/{building}/units/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('buildings/{building}/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');

    // مدیریت ساکنین
    Route::prefix('residents')->name('residents.')->group(function () {
        Route::get('/', [ResidentController::class, 'index'])->name('index');
        Route::get('/create', [ResidentController::class, 'create'])->name('create');
        Route::post('/', [ResidentController::class, 'store'])->name('store');
        Route::get('/{resident}/edit', [ResidentController::class, 'edit'])->name('edit');
        Route::post('/{resident}', [ResidentController::class, 'update'])->name('update');
        Route::get('/{resident}', [ResidentController::class, 'show'])->name('show');
        Route::delete('/{resident}', [ResidentController::class, 'destroy'])->name('destroy');
    });

    // لیست صورتحساب‌ها
    Route::get('invoices', [InvoiceController::class, 'index'])->name('manager.invoices.index');

    // صورتحساب کلی (bulk)
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('manager.invoices.create');
    Route::post('invoices', [InvoiceController::class, 'storebulk'])->name('manager.invoices.store');

    // نمایش جزئیات صورتحساب
    Route::get('invoice/show/{invoice}', [InvoiceController::class, 'show'])->name('manager.invoices.show');

    // صورتحساب تکی (single)
    Route::get('invoices/single/create', [InvoiceController::class, 'createSingle'])->name('invoices.single.create');
    Route::post('invoices/single/store', [InvoiceController::class, 'storeSingle'])->name('invoices.single.store');
    Route::get('/single-invoices/{invoice}/edit', [InvoiceController::class, 'editSingle'])->name('manager.single-invoices.edit');
    Route::put('/single-invoices/{invoice}', [InvoiceController::class, 'updateSingle'])->name('manager.single-invoices.update');


    // صورتحساب‌های کلی (bulk) مدیریت
    Route::get('bulk-invoices', [InvoiceController::class, 'bulkindex'])->name('bulk_invoices.index');
    Route::post('bulk-invoices/{bulkInvoice}/approve', [InvoiceController::class, 'approve'])->name('bulk_invoices.approve');
    Route::get('manager/bulk_invoices/{bulkInvoice}/edit', [InvoiceController::class, 'editBulkInvoice'])->name('manager.bulk_invoices.edit');
    Route::post('manager/bulk_invoices/{bulkInvoice}', [InvoiceController::class, 'updateBulkInvoice'])->name('manager.bulk_invoices.update');
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

