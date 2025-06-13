<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Manager\Payment\PaymentController;
use App\Http\Controllers\Resident\InvoicePaymentController as ResidentInvoicePaymentController;

use App\Http\Controllers\SuperAdmin\BuildingManager\BuildingManagerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ManagerController;
use App\Http\Controllers\Manager\Unit\UnitController;
use App\Http\Controllers\Manager\Invoice\InvoiceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Manager\Building\BuildingController;
use App\Http\Controllers\Manager\Report\ReportController;
use App\Http\Controllers\Manager\Request\RequestController;
use App\Http\Controllers\Resident\ResidentController as ResidentDashboardController;
use App\Http\Controllers\Manager\Resident\ResidentController as ManagerResidentController;
use App\Http\Controllers\Resident\RepairRequestController;
use App\Http\Controllers\Resident\ResidentProfileController;
use App\Http\Controllers\Resident\ResidentInvoiceController;
use App\Http\Controllers\Resident\ResidentPaymentController;
use App\Http\Controllers\SuperAdmin\Building\BuildingController as AdminBuildingController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;


Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/auth', function () {
    return view('auth.auth');
})->name('auth');

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
    Route::get('buildings/{building}/units/create', [UnitController::class, 'create'])->name('units.create');
    Route::post('buildings/{building}/units', [UnitController::class, 'store'])->name('units.store');
    Route::get('buildings/{building}/units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
    Route::put('buildings/{building}/units/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::get('buildings/{building}/units', [UnitController::class, 'index'])->name('units.index');
    Route::get('buildings/{building}/units/{unit}', [UnitController::class, 'show'])->name('units.show');
    Route::delete('buildings/{building}/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');

    // مدیریت ساکنین
    Route::prefix('residents')->name('residents.')->group(function () {
        Route::get('/', [ManagerResidentController::class, 'index'])->name('index');
        Route::get('/create', [ManagerResidentController::class, 'create'])->name('create');
        Route::post('/', [ManagerResidentController::class, 'store'])->name('store');
        Route::get('/{resident}/edit', [ManagerResidentController::class, 'edit'])->name('edit');
        Route::post('/{resident}', [ManagerResidentController::class, 'update'])->name('update');
        Route::get('/{resident}', [ManagerResidentController::class, 'show'])->name('show');
        Route::delete('/{resident}', [ManagerResidentController::class, 'destroy'])->name('destroy');
        Route::put('/admin/users/{user}/activate', [ManagerResidentController::class, 'activate'])->name('activate');
        Route::put('/admin/users/{user}/deactivate', [ManagerResidentController::class, 'deactivate'])->name('deactivate');
    });

    //requests
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::post('/requests/{repairRequest}', [RequestController::class, 'updateStatus'])->name('requests.update');
    Route::get('/requests/{repairrequest}', [RequestController::class, 'show'])->name('requests.show');



    // مدیریت پرداخت ها
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{id}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/receipt', [PaymentController::class, 'receipt'])->name('receipt');
    });


    //گزارشگیری
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/payments', [ReportController::class, 'index'])->name('payments');
        Route::get('/invoices', [ReportController::class, 'invoices'])->name('invoices');
        Route::get('/unit-debts', [ReportController::class, 'unitDebts'])->name('unit_debts');
        Route::get('/reports/invoices/print', [ReportController::class, 'print'])->name('invoices.print');
        Route::get('/reports/payments/print', [ReportController::class, 'Paymentprint'])->name('payments.print');
        Route::get('/overdue-payments', [ReportController::class, 'overduePayments'])->name('overduePayments');
        Route::get('/financialOverview', [ReportController::class, 'financialOverview'])->name('financialOverview');
        Route::get('/ResidentAccountStatus', [ReportController::class, 'ResidentAccountStatus'])->name('ResidentAccountStatus');
    });


    // لیست صورتحساب‌ها
    Route::get('invoices', [InvoiceController::class, 'index'])->name('manager.invoices.index');
    Route::get('/units/{unit}/invoices', [InvoiceController::class, 'unitInvoices'])->name('manager.units.invoices');

    // صورتحساب کلی (bulk)
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('manager.invoices.create');
    Route::post('invoices', [InvoiceController::class, 'storebulk'])->name('manager.invoices.store');
    Route::get('bulk-invoices', [InvoiceController::class, 'bulkindex'])->name('bulk_invoices.index');
    Route::post('bulk-invoices/{bulkInvoice}/approve', [InvoiceController::class, 'approve'])->name('bulk_invoices.approve');
    Route::get('manager/bulk_invoices/{bulkInvoice}/edit', [InvoiceController::class, 'editBulkInvoice'])->name('manager.bulk_invoices.edit');
    Route::post('manager/bulk_invoices/{bulkInvoice}', [InvoiceController::class, 'updateBulkInvoice'])->name('manager.bulk_invoices.update');
    Route::get('manager/bulk_invoices/{bulkInvoice}', [InvoiceController::class, 'showBulk'])->name('manager.bulk_invoices.show');

    // صورتحساب تکی (single)
    Route::get('invoices/single/create', [InvoiceController::class, 'createSingle'])->name('invoices.single.create');
    Route::post('invoices/single/store', [InvoiceController::class, 'storeSingle'])->name('invoices.single.store');
    Route::get('/single-invoices/{invoice}/edit', [InvoiceController::class, 'editSingle'])->name('manager.single-invoices.edit');
    Route::put('/single-invoices/{invoice}', [InvoiceController::class, 'updateSingle'])->name('manager.single-invoices.update');
    Route::get('invoice/show/{invoice}', [InvoiceController::class, 'show'])->name('manager.invoices.show');
});

Route::middleware(['auth', 'role:resident'])->prefix('resident')->name('resident.')->group(function () {
    Route::get('/dashboard', [ResidentDashboardController::class, 'index'])->name('dashboard');
    // profile
    Route::get('/profile', [ResidentProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ResidentProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ResidentProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ResidentProfileController::class, 'updatePassword'])->name('profile.password');
    // payment
    Route::get('/pay/fake/{invoice}', [ResidentInvoicePaymentController::class, 'showFakePaymentForm'])->name('payment.fake.form.single');

    Route::post('pay/fake/process', [ResidentInvoicePaymentController::class, 'processFakePayment'])->name('payment.fake.process');

    Route::post('/pay/fake/multiple', [ResidentInvoicePaymentController::class, 'showFakePaymentFormMultiple'])->name('payment.fake.form.multiple'); // تغییر به POST
    Route::post('/pay/multiple', [ResidentInvoicePaymentController::class, 'processMultiplePayment'])->name('payment.process.multiple');


    Route::post('/pay/{invoice}', [ResidentInvoicePaymentController::class, 'paySingle'])->name('invoices.pay');

    Route::get('/payments', [ResidentPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [ResidentPaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/receipt', [ResidentPaymentController::class, 'receipt'])->name('payments.receipt');
    // requests
    Route::get('/requests', [RepairRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [RepairRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [RepairRequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{request}/edit', [RepairRequestController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{request}', [RepairRequestController::class, 'update'])->name('requests.update');
    Route::get('/requests/{request}', [RepairRequestController::class, 'show'])->name('requests.show');

    // invoices
    Route::get('/invoices', [ResidentInvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/unpaid', [ResidentInvoiceController::class, 'unpaid'])->name('invoices.unpaid');
    Route::get('/{invoice}', [ResidentInvoiceController::class, 'show'])->name('invoices.show');
});


Route::prefix('admin')->middleware(['auth', 'role:super_admin'])->name('superadmin.')->group(function () {

    // داشبورد سوپر ادمین
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    //  ساختمان‌
    Route::get('buildings', [AdminBuildingController::class, 'index'])->name('buildings.index');
    Route::get('buildings/create', [AdminBuildingController::class, 'create'])->name('buildings.create');
    Route::post('buildings', [AdminBuildingController::class, 'store'])->name('buildings.store');
    Route::get('buildings/{building}', [AdminBuildingController::class, 'show'])->name('buildings.show');
    Route::get('buildings/{building}/edit', [AdminBuildingController::class, 'edit'])->name('buildings.edit');
    Route::put('buildings/{building}', [AdminBuildingController::class, 'update'])->name('buildings.update');
    Route::delete('buildings/{building}', [AdminBuildingController::class, 'destroy'])->name('buildings.destroy');
    // مدیریت مدیران ساختمان
    Route::get('building-managers', [BuildingManagerController::class, 'index'])->name('building_managers.index');
    Route::get('building-managers/{building}/edit', [BuildingManagerController::class, 'edit'])->name('building_managers.edit');
    Route::put('building-managers/{building}', [BuildingManagerController::class, 'update'])->name('building_managers.update');
    //  درخواست‌های ساختمان
    Route::get('/requests', [SuperAdminController::class, 'requests'])->name('requests');
    Route::post('/building-requests/{id}/approve', [SuperAdminController::class, 'approveRequest'])->name('requests.approve');
    Route::post('/building-requests/{id}/reject', [SuperAdminController::class, 'rejectRequest'])->name('requests.reject');
});
