<?php

namespace App\Services\Manager\Invoice;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SingleInvoiceService
{
    public function create(User $manager, array $data): Invoice
    {
        // $building = $manager->building; // در صورت نیاز

        return DB::transaction(function () use ($data) {
            return Invoice::create([
                'unit_id' => $data['unit_id'],
                'title' => $data['title'],
                'total_amount' => $data['amount'],
                'due_date' => $data['due_date'],
                'description' => $data['description'] ?? null,
                'type' => $data['type'],
                'status' => 'unpaid',
            ]);
        });
    }
    public function update(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data) {
            $invoice->update([
                'unit_id' => $data['unit_id'],
                'title' => $data['title'],
                'amount' => $data['amount'],
                'due_date' => $data['due_date'],
                'description' => $data['description'] ?? null,
                // 'type' => $data['type'],
            ]);

            return $invoice;
        });
    }
}
