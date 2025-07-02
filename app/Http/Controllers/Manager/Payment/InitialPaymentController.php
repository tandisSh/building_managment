<?php

namespace App\Http\Controllers\Manager\Payment;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\InitialFeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InitialPaymentController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $building = Building::where('manager_id', $user->id)->firstOrFail();
        $payment = InitialFeePayment::where('building_id', $building->id)
                                    ->where('status', 'pending')
                                    ->firstOrFail();

        return view('manager.payments.initial_payment', compact('payment'));
    }

    public function pay(Request $request)
    {
        $user = Auth::user();
        $building = Building::where('manager_id', $user->id)->firstOrFail();
        $payment = InitialFeePayment::where('building_id', $building->id)
                                    ->where('status', 'pending')
                                    ->firstOrFail();

        // In a real scenario, you would redirect to a payment gateway.
        // Here, we'll simulate it with a fake gateway page.
        return view('manager.payments.fake_gateway', [
            'amount' => $payment->amount,
            'payment_id' => $payment->id,
            'callback_url' => route('manager.initial-payment.callback'),
        ]);
    }

    public function callback(Request $request)
    {
        $user = Auth::user();
        $paymentId = $request->input('payment_id');
        $isSuccess = $request->input('payment_status') === 'success';

        $payment = InitialFeePayment::findOrFail($paymentId);
        $building = $payment->building;

        // Ensure the payment belongs to the logged-in manager's building
        if ($building->manager_id !== $user->id) {
            return redirect()->route('manager.dashboard')->with('error', 'خطای امنیتی رخ داد.');
        }

        if ($isSuccess) {
            $payment->update([
                'status' => 'paid',
                'transaction_id' => 'TXN-' . time() . '-' . $payment->id, // Fake transaction ID
                'paid_at' => now(),
            ]);

            $building->update([
                'activation_status' => 'active',
            ]);

            return redirect()->route('manager.dashboard')->with('success', 'پرداخت با موفقیت انجام شد! حساب شما اکنون کاملاً فعال است.');
        }

        $payment->update(['status' => 'failed']);

        return redirect()->route('manager.initial-payment.show')->with('error', 'پرداخت ناموفق بود. لطفاً دوباره تلاش کنید.');
    }
} 