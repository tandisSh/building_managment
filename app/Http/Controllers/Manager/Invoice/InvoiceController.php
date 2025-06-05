<?php

namespace App\Http\Controllers\Manager\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceRequest;
use App\Http\Requests\Invoice\SingleInvoiceRequest;
use App\Models\BulkInvoice;
use App\Models\Invoice;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use App\Services\Manager\Invoice\InvoiceService;
use App\Services\Manager\Invoice\BulkInvoiceService;
use App\Services\Manager\Invoice\SingleInvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceService $invoiceService,
        protected BulkInvoiceService $bulkInvoiceService,
        protected SingleInvoiceService $singleInvoiceService
    ) {}

    //bulk Invoices

    // لیست صورتحساب‌های کلی (bulk)
    public function bulkindex(Request $request)
    {
        $filters = $request->only(['search', 'status']);
        $user = Auth::user();

        $bulkInvoices = $this->bulkInvoiceService->getBulkInvoicesByManager($user, $filters);

        return view('manager.invoices.bulk.index', compact('bulkInvoices'));
    }


    // فرم ثبت صورتحساب کلی (bulk)
    public function create()
    {
        $building = Auth::user()->building;
        return view('manager.invoices.bulk.create', compact('building'));
    }

    // ذخیره صورتحساب کلی (bulk)
    public function storebulk(InvoiceRequest $request)
    {
        $validated = $request->validated();
        $this->bulkInvoiceService->create(Auth::user(), $validated);

        return redirect()->route('bulk_invoices.index')
            ->with('success', 'صورتحساب کلی با موفقیت ثبت شد.');
    }

    // تایید و پخش صورتحساب کلی بین واحدها
    public function approve(BulkInvoice $bulkInvoice)
    {
        if ($bulkInvoice->status !== 'pending') {
            return redirect()->back()->with('error', 'این صورتحساب قبلا تایید شده است.');
        }

        $this->invoiceService->generateInvoicesFromBulk($bulkInvoice);
        $this->bulkInvoiceService->markAsApproved($bulkInvoice);

        return redirect()->route('bulk_invoices.index')
            ->with('success', 'صورتحساب کلی با موفقیت تایید و بین واحدها پخش شد.');
    }

    //فرم ویرایش صورتحساب کلی
    public function editBulkInvoice(BulkInvoice $bulkInvoice)
    {
        try {
            // فقط اجازه میدیم فرم ویرایش برای bulk invoice با وضعیت pending باز بشه
            if ($bulkInvoice->status !== 'pending') {
                return redirect()->route('bulk_invoices.index')
                    ->with('error', 'امکان ویرایش این صورتحساب کلی وجود ندارد چون قبلاً تایید شده است.');
            }

            return view('manager.invoices.bulk.edit', compact('bulkInvoice'));
        } catch (\Exception $e) {
            return redirect()->route('bulk_invoices.index')
                ->with('error', $e->getMessage());
        }
    }

    //ویرایش صورتحساب کلی
    public function updateBulkInvoice(InvoiceRequest $request, BulkInvoice $bulkInvoice)
    {
        $this->bulkInvoiceService->updateBulkInvoice($bulkInvoice, $request->validated());

        return redirect()->route('bulk_invoices.index')
            ->with('success', 'صورتحساب کلی با موفقیت ویرایش شد.');
    }

    //نمایش صورتحساب کلی
    public function showBulk($id)
    {
        $bulkInvoice = BulkInvoice::with('invoices.unit')->findOrFail($id);
        return view('manager.invoices.bulk.show', compact('bulkInvoice'));
    }


    //single invoices

    // لیست تمام صورتحساب‌های واحدی که مدیر دارد
    public function index()
    {
        $invoices = $this->invoiceService->getManagerInvoices(Auth::user(), request()->all());
        $units = Unit::whereHas('building', fn($q) => $q->where('manager_id', Auth::id()))->get();
        return view('manager.invoices.single.index', compact('invoices', 'units'));
    }


    // نمایش جزئیات یک صورتحساب
    public function show($invoiceId)
    {
        $invoice = Invoice::with('unit')->findOrFail($invoiceId);
        return view('manager.invoices.single.show', compact('invoice'));
    }

    // فرم ثبت صورتحساب تکی برای یک واحد خاص
    public function createSingle()
    {
        $units = Auth::user()->building->units()->with('resident')->get();
        return view('manager.invoices.single.create', compact('units'));
    }

    // ذخیره صورتحساب تکی (single)
    public function storeSingle(SingleInvoiceRequest $request)
    {
        $data = $request->validated();
        $this->singleInvoiceService->create(Auth::user(), $data);

        return redirect()->route('manager.invoices.index')
            ->with('success', 'صورتحساب با موفقیت ثبت شد.');
    }

    //فرم ویرایش صورتحساب تکی
    public function editSingle(Invoice $invoice)
    {
        $units = $invoice->unit->building->units;

        return view('manager.invoices.single.edit', [
            'invoice' => $invoice,
            'units' => $units,
        ]);
    }

    // ویرایش صورتحساب تکی
    public function updateSingle(SingleInvoiceRequest $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'امکان ویرایش صورتحساب پرداخت‌شده وجود ندارد.');
        }

        try {
            $this->singleInvoiceService->update($invoice, $request->validated());

            return redirect()->route('manager.invoices.index')
                ->with('success', 'صورتحساب تکی با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('error', 'خطا در ویرایش صورتحساب: ' . $e->getMessage());
        }
    }

    //نمایش صورتحساب های یک واحد خاص
    public function unitInvoices(Unit $unit)
    {
        $invoices = $this->invoiceService->getUnitInvoices($unit->id);
        // dd($invoices);
        return view('manager.invoices.unit_index', compact('invoices', 'unit'));
    }
}
