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
        // دریافت invoice_ids از request
        $invoiceIds = $request->input('invoice_ids', []);
        
        // Debug: لاگ کردن داده‌های دریافتی
        Log::info('showFakePaymentFormMultiple - Request data:', [
            'invoice_ids' => $invoiceIds,
            'all_request_data' => $request->all()
        ]);
        
        // اگر در request نبود، از session بررسی می‌کنیم
        if (empty($invoiceIds)) {
            $invoiceIds = session('invoice_ids', []);
            Log::info('Using session data:', ['invoice_ids' => $invoiceIds]);
        }
        
        if (!$invoiceIds || empty($invoiceIds)) {
            Log::warning('No invoice IDs found');
            return redirect()->route('resident.invoices.unpaid')->with('error', 'هیچ صورتحساب انتخاب نشده است.');
        }
        
        // اعتبارسنجی که همه ID ها معتبر هستند
        $validInvoiceIds = Invoice::whereIn('id', $invoiceIds)
            ->where('status', 'unpaid')
            ->pluck('id')
            ->toArray();
            
        Log::info('Valid invoice IDs:', ['valid_ids' => $validInvoiceIds]);
            
        if (empty($validInvoiceIds)) {
            Log::warning('No valid invoices found');
            return redirect()->route('resident.invoices.unpaid')->with('error', 'هیچ صورتحساب معتبری برای پرداخت یافت نشد.');
        }
        
        // ذخیره در session برای استفاده بعدی
        session(['invoice_ids' => $validInvoiceIds]);
        
        return view('resident.payments.fake-payment', ['invoiceIds' => $validInvoiceIds]);
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

        try {
            // پرداخت تک صورتحساب
            if ($request->has('invoice_id') && $request->invoice_id) {
                $invoice = Invoice::findOrFail($request->invoice_id);
                
                // بررسی اینکه آیا صورتحساب قبلاً پرداخت شده یا نه
                if ($invoice->status === 'paid') {
                    return redirect()->route('resident.invoices.index')->with('error', 'این صورتحساب قبلاً پرداخت شده است.');
                }
                
                $this->paymentService->processSinglePayment(auth()->user(), $invoice);
                return redirect()->route('resident.invoices.index')->with('success', 'پرداخت تک صورتحساب با موفقیت انجام شد.');
            }

            // پرداخت گروهی
            if ($request->has('invoice_ids') && $request->invoice_ids) {
                $invoiceIds = $request->invoice_ids;
                
                // اگر رشته JSON است، آن را decode کنیم
                if (is_string($invoiceIds)) {
                    $invoiceIds = json_decode($invoiceIds, true);
                }
                
                // اگر آرایه نیست، آن را به آرایه تبدیل کنیم
                if (!is_array($invoiceIds)) {
                    $invoiceIds = [$invoiceIds];
                }
                
                if (empty($invoiceIds)) {
                    return redirect()->back()->with('error', 'هیچ صورتحساب انتخاب نشده است.');
                }
                
                // اعتبارسنجی نهایی
                $validInvoices = Invoice::whereIn('id', $invoiceIds)
                    ->where('status', 'unpaid')
                    ->get();
                    
                if ($validInvoices->isEmpty()) {
                    return redirect()->back()->with('error', 'هیچ صورتحساب معتبری برای پرداخت یافت نشد.');
                }
                
                $this->paymentService->processMultiplePayments(auth()->user(), $invoiceIds);
                return redirect()->route('resident.invoices.unpaid')->with('success', 'پرداخت گروهی با موفقیت انجام شد.');
            }

            return redirect()->back()->with('error', 'خطا در پردازش پرداخت: اطلاعات صورتحساب نامعتبر است.');
            
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'خطا در پردازش پرداخت. لطفاً دوباره تلاش کنید.');
        }
    }

    public function processMultiplePayment(Request $request)
    {
        $request->validate([
            'invoice_ids' => 'required|array',
            'invoice_ids.*' => 'exists:invoices,id',
        ]);

        try {
            $invoiceIds = $request->input('invoice_ids');
            $this->paymentService->processMultiplePayments(auth()->user(), $invoiceIds);
            
            return redirect()->route('resident.invoices.unpaid')->with('success', 'پرداخت گروهی با موفقیت انجام شد.');
        } catch (\Exception $e) {
            Log::error('Multiple payment processing error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'خطا در پردازش پرداخت گروهی. لطفاً دوباره تلاش کنید.');
        }
    }
}
