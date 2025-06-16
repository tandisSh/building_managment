<?php

namespace App\Http\Controllers\SuperAdmin\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\SingleInvoiceRequest;
use App\Models\Building;
use App\Models\Invoice;
use App\Models\Unit;
use App\Services\Admin\Invoice\SingleInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SuperAdminInvoiceController extends Controller
{
    public function __construct(protected SingleInvoiceService $singleInvoiceService) {}

    // لیست تمام صورتحساب‌های تکی همه ساختمان‌ها
    public function index()
    {
        $invoices = $this->singleInvoiceService->getSuperAdminInvoices(Auth::user(), request()->all());
        $units = Unit::with('building')->get();
        return view('super_admin.invoices.index', compact('invoices', 'units'));
    }

    // نمایش جزئیات یک صورتحساب
    public function show($invoiceId)
    {
        $invoice = Invoice::with('unit.building')->findOrFail($invoiceId);
        return view('super_admin.invoices.show', compact('invoice'));
    }

    // فرم ثبت صورتحساب تکی برای یک واحد خاص
    public function createSingle()
    {
        $buildings = Building::all(); // دریافت تمام ساختمان‌ها
        return view('super_admin.invoices.create', compact('buildings'));
    }

    // ذخیره صورتحساب تکی
    public function storeSingle(SingleInvoiceRequest $request)
    {
        $data = $request->validated();
        $this->singleInvoiceService->create(Auth::user(), $data);

        return redirect()->route('superadmin.invoices.index')
            ->with('success', 'صورتحساب با موفقیت ثبت شد.');
    }

    // فرم ویرایش صورتحساب تکی
    public function editSingle(Invoice $invoice)
    {
        try {
            $buildings = Building::all();
            $units = Unit::where('building_id', $invoice->unit->building_id)->get();
            return view('super_admin.invoices.edit', compact('invoice', 'buildings', 'units'));
        } catch (\Exception $e) {
            Log::error('Error in editSingle: ' . $e->getMessage());
            return redirect()->route('superadmin.invoices.index')
                ->with('error', 'خطا در بارگذاری فرم ویرایش: ' . $e->getMessage());
        }
    }

    // ویرایش صورتحساب تکی
    public function updateSingle(SingleInvoiceRequest $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'امکان ویرایش صورتحساب پرداخت‌شده وجود ندارد.');
        }

        try {
            $updatedInvoice = $this->singleInvoiceService->update($invoice, $request->validated());
            return redirect()->route('superadmin.invoices.index')
                ->with('success', 'صورتحساب تکی با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'خطا در ویرایش صورتحساب: ' . $e->getMessage());
        }
    }

    // نمایش صورتحساب‌های یک واحد خاص
    public function unitInvoices(Unit $unit)
    {
        $invoices = $this->singleInvoiceService->getUnitInvoices($unit->id);
        return view('super_admin.invoices.unit_index', compact('invoices', 'unit'));
    }

    // دریافت واحدها برای یک ساختمان خاص (برای AJAX)
    public function getUnits($buildingId)
    {
        try {
            $units = Unit::where('building_id', $buildingId)->select('id', 'unit_number')->get();
            return response()->json(['units' => $units], 200);
        } catch (\Exception $e) {
            Log::error('Error in getUnits: ' . $e->getMessage());
            return response()->json(['error' => 'خطا در دریافت واحدها'], 500);
        }
    }
}
