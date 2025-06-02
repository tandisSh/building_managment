<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentPaymentController extends Controller
{
      public function index()
    {
        $user = Auth::user();
        $payments = Payment::with(['invoice'])->where('user_id', $user['id'])->get();

        return view('resident.payments.index', compact('payments'));
    }
   public function show($id)
    {
        $payment = Payment::with('invoice')->findOrFail($id);
        return view('resident.payments.show', compact('payment'));
    }
}
