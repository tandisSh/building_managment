<?php

namespace App\Services\Resident\Invoice;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class InvoicePaymentService
{
    public function processSinglePayment(User $user, Invoice $invoice): void
    {
        if ($invoice->status === 'paid') return;

        DB::transaction(function () use ($user, $invoice) {
            $invoice->update(['status' => 'paid']);

            Payment::create([
                'user_id'    => $user->id,
                'invoice_id' => $invoice->id,
                'amount'     => $invoice->amount,
                'paid_at'    => Carbon::now(),
                'status'     => 'success',
            ]);
        });
    }

    public function processMultiplePayments(User $user, array $invoiceIds): void
    {
        $invoices = Invoice::whereIn('id', $invoiceIds)
            ->where('status', 'unpaid')
            ->get();

        DB::transaction(function () use ($user, $invoices) {
            foreach ($invoices as $invoice) {
                $invoice->update(['status' => 'paid']);

                Payment::create([
                    'user_id'    => $user->id,
                    'invoice_id' => $invoice->id,
                    'amount'     => $invoice->amount,
                    'paid_at'    => Carbon::now(),
                    'status'     => 'success',
                ]);
            }
        });
    }
}
