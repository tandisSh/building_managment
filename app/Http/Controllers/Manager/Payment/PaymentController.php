<?php

namespace App\Http\Controllers\Manager\Payment;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $buildingId = Auth::user()->buildingUser->building_id;

        $userIds = User::whereHas('units', function ($q) use ($buildingId) {
            $q->where('building_id', $buildingId);
        })->pluck('id');

        $payments = Payment::with(['user', 'invoice.unit'])
            ->whereIn('user_id', $userIds)
            ->latest()
            ->get();

        return view('manager.payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with('invoice')->findOrFail($id);
        return view('resident.payments.show', compact('payment'));
    }
}
