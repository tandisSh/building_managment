<?php

namespace App\Http\Controllers\Manager\Payment;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Manager\Payment\PaymentService;
use App\Models\Payment;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);

        $payments = $this->paymentService->getPaymentsForManager(auth()->user(), $filters);

        return view('manager.payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with('invoice')->findOrFail($id);
        return view('resident.payments.show', compact('payment'));
    }

    public function receipt($id)
{
    $payment = Payment::with('invoice', 'user')->findOrFail($id);
    return view('manager.payments.receipt', compact('payment'));
}

}
