<?php

namespace App\Http\Controllers\Manager\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceRequest;
use App\Models\Invoice;
use App\Services\Manager\Invoice\InvoiceService;
use App\Services\Manager\Invoice\BulkInvoiceService;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceService $invoiceService,
        protected BulkInvoiceService $bulkInvoiceService
    ) {}

    public function index()
    {
        $invoices = $this->invoiceService->getManagerInvoices(auth()->user());
        return view('manager.invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('manager.invoices.create', [
            'building' => Auth::user()->building
        ]);
    }

    public function store(InvoiceRequest $request)
    {
        $validated = $request->validated();

        $bulkInvoice = $this->bulkInvoiceService->create(Auth::user(), $validated);

        $this->invoiceService->generateInvoicesFromBulk($bulkInvoice);

        return redirect()->route('manager.invoices.index')
            ->with('success', 'صورتحساب ماهانه با موفقیت ثبت شد.');
    }

    public function show($invoiceid)
    {
        $invoice = Invoice::with(['unit', 'items'])->findOrFail($invoiceid);
        return view('manager.invoices.show', compact('invoice'));
    }
}
