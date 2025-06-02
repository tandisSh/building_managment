<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Resident\Invoice\InvoiceService;
use Illuminate\Support\Facades\Auth;

class ResidentInvoiceController extends Controller
{
    protected $service;

    public function __construct(InvoiceService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $user = Auth::user();
        $invoices = $this->service->getUserInvoices($user);

        return view('resident.invoices.index', compact('invoices'));
    }
    public function unpaid()
    {
        $user = Auth::user();
        $invoices = $this->service->getUserInvoices($user, onlyUnpaid: true);

        return view('resident.invoices.unpaid', compact('invoices'));
    }
    public function show($invoice)
    {
        $invoice = Invoice::with('unit')->findOrFail($invoice);
        return view('manager.invoices.single.show', compact('invoice'));
    }
}
