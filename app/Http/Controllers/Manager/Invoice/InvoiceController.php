<?php

namespace App\Http\Controllers\Manager\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceRequest;
use App\Http\Requests\Invoice\SingleInvoiceRequest;
use App\Models\BulkInvoice;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use App\Services\Manager\Invoice\InvoiceService;
use App\Services\Manager\Invoice\BulkInvoiceService;
use App\Services\Manager\Invoice\SingleInvoiceService;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceService $invoiceService,
        protected BulkInvoiceService $bulkInvoiceService,
        protected SingleInvoiceService $singleInvoiceService
    ) {}

    // لیست تمام صورتحساب‌های واحدی که مدیر دارد
    public function index()
    {
        $invoices = $this->invoiceService->getManagerInvoices(Auth::user());
        return view('manager.invoices.index', compact('invoices'));
    }

    // فرم ثبت صورتحساب کلی (bulk)
    public function create()
    {
        $building = Auth::user()->building;
        return view('manager.invoices.create', compact('building'));
    }

    // ذخیره صورتحساب کلی (bulk)
    public function storebulk(InvoiceRequest $request)
    {
        $validated = $request->validated();
        $this->bulkInvoiceService->create(Auth::user(), $validated);

        return redirect()->route('manager.invoices.index')
            ->with('success', 'صورتحساب کلی با موفقیت ثبت شد.');
    }

    // نمایش جزئیات یک صورتحساب
    public function show($invoiceId)
    {
        $invoice = Invoice::with('unit')->findOrFail($invoiceId);
        return view('manager.invoices.show', compact('invoice'));
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

    // لیست صورتحساب‌های کلی (bulk)
    public function bulkindex()
    {
        $bulkInvoices = $this->bulkInvoiceService->getByManager(Auth::user());
        return view('manager.invoices.bulk.index', compact('bulkInvoices'));
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
    public function editBulkInvoice(BulkInvoice $bulkInvoice)
    {
        try {
            // فقط اجازه میدیم فرم ویرایش برای bulk invoice با وضعیت pending باز بشه
            if ($bulkInvoice->status !== 'pending') {
                return redirect()->route('bulk_invoices.index')
                    ->with('error', 'امکان ویرایش این صورتحساب کلی وجود ندارد چون قبلاً تایید شده است.');
            }

            return view('manager.invoices.edit', compact('bulkInvoice'));
        } catch (\Exception $e) {
            return redirect()->route('bulk_invoices.index')
                ->with('error', $e->getMessage());
        }
    }
    public function updateBulkInvoice(InvoiceRequest $request, BulkInvoice $bulkInvoice)
    {
        $this->bulkInvoiceService->updateBulkInvoice($bulkInvoice, $request->validated());

        return redirect()->route('bulk_invoices.index')
            ->with('success', 'صورتحساب کلی با موفقیت ویرایش شد.');
    }
    public function editSingle(Invoice $invoice)
    {
        $units = $invoice->unit->building->units; // یا اگر دسترسی نداره، همه واحدها رو بیار

        return view('manager.invoices.single.edit', [
            'invoice' => $invoice,
            'units' => $units,
        ]);
    }
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
}
