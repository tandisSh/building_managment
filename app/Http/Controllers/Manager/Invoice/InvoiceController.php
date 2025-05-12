<?php
namespace App\Http\Controllers\Manager\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\StoreInvoiceRequest;
use App\Services\Manager\Invoice\InvoiceService;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function create()
    {
        $building = Auth::user()->building;
        return view('manager.invoices.create', compact('building'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        try {
            $this->invoiceService->createMonthlyInvoices($request->validated(), Auth::user());
            return redirect()->route('manager.dashboard')->with('success', 'صورتحساب با موفقیت صادر شد.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
