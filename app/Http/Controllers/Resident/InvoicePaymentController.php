<?php
namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Services\Resident\Invoice\InvoicePaymentService;

class InvoicePaymentController extends Controller
{
    protected $paymentService;

    public function __construct(InvoicePaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function paySingle(Invoice $invoice)
    {
        $this->paymentService->processSinglePayment(auth()->user(), $invoice);

        return redirect()->back()->with('success', 'پرداخت با موفقیت انجام شد.');
    }

    public function payMultiple(Request $request)
    {
        $request->validate([
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'exists:invoices,id',
        ]);

        $this->paymentService->processMultiplePayments(auth()->user(), $request->invoice_ids);

        return redirect()->back()->with('success', 'پرداخت گروهی با موفقیت انجام شد.');
    }
}
