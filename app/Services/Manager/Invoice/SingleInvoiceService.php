<?php

namespace App\Services\Manager\Invoice;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SingleInvoiceService
{
    public function create(User $manager, array $data): Invoice
    {
        $building = $manager->building;

        return DB::transaction(function () use ($data) {
            // ایجاد صورتحساب
            $invoice = Invoice::create([
                'unit_id' => $data['unit_id'],
                'total_amount' => $data['amount'],
                'due_date' => $data['due_date'],
                'description' => $data['description'],
                'type' => $data['type'],
            ]);

            // ایجاد آیتم صورتحساب
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'title' => $data['title'],
                'amount' => $data['amount'],
                'paid_amount' => 0, // مبلغ پرداخت نشده
            ]);

            return $invoice;
        });
    }
}


