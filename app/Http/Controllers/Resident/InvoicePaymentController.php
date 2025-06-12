<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Services\Resident\Invoice\InvoicePaymentService;
use Illuminate\Support\Facades\Log;

class InvoicePaymentController extends Controller
{
    protected $paymentService;

    public function __construct(InvoicePaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function paySingle(Invoice $invoice)
    {
        return redirect()->route('resident.payment.fake.form.single', $invoice->id);
    }

    public function payMultiple(Request $request)
    {
        $request->validate([
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'exists:invoices,id',
        ]);

        session(['invoice_ids' => $request->invoice_ids]);
        return redirect()->route('resident.payment.fake.form.multiple');
    }

    public function showFakePaymentForm(Invoice $invoice)
    {

        return view('resident.payments.fake-payment', ['invoiceId' => $invoice->id]);
    }

    public function showFakePaymentFormMultiple(Request $request)
    {
        $invoiceIds = $request->input('invoice_ids', []);
        if (!$invoiceIds || empty($invoiceIds)) {
            return redirect()->route('resident.invoices.unpaid')->with('error', 'هیچ صورتحساب انتخاب نشده است.');
        }
        return view('resident.payments.fake-payment', ['invoiceIds' => $invoiceIds]);
    }

    public function processFakePayment(Request $request)
    {
        $request->validate([
            'card_number' => 'required|digits:16',
            'expiry_date' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv' => 'required|digits:3',
        ]);

        $cardNumber = $request->input('card_number');
        $expiryDate = $request->input('expiry_date');
        $cvv = $request->input('cvv');

        Log::info('Fake Payment Attempt - Card: ' . $cardNumber . ', Expiry: ' . $expiryDate . ', CVV: ' . $cvv);

        if ($request->has('invoice_id') && $request->invoice_id) {
            $invoice = Invoice::findOrFail($request->invoice_id);
            $this->paymentService->processSinglePayment(auth()->user(), $invoice);
            return redirect()->route('resident.invoices.index')->with('success', 'پرداخت تک صورتحساب با موفقیت انجام شد.');
        }

        if ($request->has('invoice_ids') && $request->invoice_ids) {
            $invoiceIds = json_decode($request->invoice_ids, true);
            if (!$invoiceIds || empty($invoiceIds)) {
                return redirect()->back()->with('error', 'هیچ صورتحساب انتخاب نشده است.');
            }
            $this->paymentService->processMultiplePayments(auth()->user(), $invoiceIds);
            return redirect()->route('resident.invoices.unpaid')->with('success', 'پرداخت گروهی با موفقیت انجام شد.');
        }

        return redirect()->back()->with('error', 'خطا در پردازش پرداخت.');
    }
}
