<?php

namespace App\Http\Controllers\Manager\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\InvoiceRequest;
use App\Http\Requests\Invoice\SingleInvoiceRequest;
use App\Models\Invoice;
use App\Services\Manager\Invoice\InvoiceService;
use App\Services\Manager\Invoice\BulkInvoiceService;
use App\Services\Manager\Invoice\SingleInvoiceService;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceService $invoiceService,
        protected BulkInvoiceService $bulkInvoiceService,
        protected SingleInvoiceService $singleInvoiceService
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
        // dd($request->all());

        $validated = $request->validated();

        $bulkInvoice = $this->bulkInvoiceService->create(Auth::user(), $validated);

        $this->invoiceService->generateInvoicesFromBulk($bulkInvoice);

        return redirect()->route('manager.invoices.index')
            ->with('success', 'صورتحساب  با موفقیت ثبت شد.');
    }

    public function show($invoiceid)
    {
        $invoice = Invoice::with(['unit', 'items'])->findOrFail($invoiceid);
        return view('manager.invoices.show', compact('invoice'));
    }
      public function createSingle()
    {
        $units = auth()->user()->building->units()->with('resident')->get();
        return view('manager.invoices.single.create', compact('units'));
    }

    public function storeSingle(SingleInvoiceRequest $request)
    {
        $data = $request->validated();

        $invoice = $this->singleInvoiceService->create(Auth::user(), $data);

        return redirect()->route('manager.invoices.index')
            ->with('success', 'صورتحساب با موفقیت ثبت شد.');
    }
}
