<?php

namespace App\Http\Controllers\SuperAdmin\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Admin\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminPaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);

        $payments = $this->paymentService->getPaymentsForSuperAdmin($filters);

        return view('super_admin.payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with('invoice')->findOrFail($id);
        return view('super_admin.payments.show', compact('payment'));
    }

    public function receipt($id)
    {
        $payment = Payment::with('invoice', 'user')->findOrFail($id);
        return view('super_admin.payments.receipt', compact('payment'));
    }
}
