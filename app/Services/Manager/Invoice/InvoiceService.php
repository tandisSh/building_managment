<?php

namespace App\Services\Manager\Invoice;

use App\Models\Invoice;
use App\Models\Unit;
use App\Models\BulkInvoice;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    // دریافت تمام صورتحساب‌های واحدهای ساختمان مدیر
    public function getManagerInvoices($manager)
    {
        return Invoice::whereHas('unit.building', function ($q) use ($manager) {
            $q->where('manager_id', $manager->id);
        })->with('unit')->latest()->get();
    }

    // ساخت صورتحساب‌های واحدها بر اساس صورتحساب کلی تایید شده
    public function generateInvoicesFromBulk(BulkInvoice $bulkInvoice)
    {
        $bulkInvoice->loadMissing('building');
        $building = $bulkInvoice->building;

        $units = Unit::where('building_id', $building->id)
            ->whereHas('resident')
            ->get();

        $unitCount = max($units->count(), 1);
        $perUnitAmount = $bulkInvoice->base_amount / $unitCount;

        foreach ($units as $unit) {
            DB::transaction(function () use ($unit, $bulkInvoice, $perUnitAmount) {
                Invoice::create([
                    'unit_id' => $unit->id,
                    'bulk_invoice_id' => $bulkInvoice->id,
                    'title' => $bulkInvoice->title,
                    'amount' => $perUnitAmount,
                    'due_date' => $bulkInvoice->due_date,
                    'status' => 'unpaid',
                    'type' => $bulkInvoice->type,
                ]);
            });
        }
    }
}
