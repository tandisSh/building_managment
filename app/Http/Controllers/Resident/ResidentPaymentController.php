<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResidentPaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');

        $payments = Payment::with('invoice')
            ->where('user_id', $user->id)
            ->whereHas('invoice', function ($query) use ($search) {
                if ($search) {
                    $query->where('title', 'like', '%' . $search . '%');
                }
            })
            ->get();

        return view('resident.payments.index', compact('payments', 'search'));
    }


    public function show($id)
    {
        $payment = Payment::with('invoice', 'user')->findOrFail($id);
        return view('resident.payments.show', compact('payment'));
    }
    public function receipt($id)
    {
        $payment = Payment::with('invoice', 'user')->findOrFail($id);
        return view('resident.payments.receipt', compact('payment'));
    }
}
