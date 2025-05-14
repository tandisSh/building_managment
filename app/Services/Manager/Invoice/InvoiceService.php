<?php

namespace App\Services\Manager\Invoice;

use App\Models\Unit;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\BulkInvoices;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function getManagerInvoices(User $manager)
    {
        return Invoice::whereHas('unit.building', function ($q) use ($manager) {
            $q->where('manager_id', $manager->id);
        })->with('unit')->latest()->get();
    }

    public function generateInvoicesFromBulk(BulkInvoices $bulkInvoice)
    {
        $bulkInvoice->loadMissing('building');
        $building = $bulkInvoice->building;

        $units = Unit::where('building_id', $building->id)
            ->whereHas('resident')
            ->get();

        // منطق صورتحساب جاری
        if ($bulkInvoice->type === 'current') {
            $items = [
                'شارژ ساختمان' => $bulkInvoice->base_amount,
            ];

            if ($building->shared_water && !empty($bulkInvoice->water_cost)) {
                $items['آب'] = $bulkInvoice->water_cost;
            }

            if ($building->shared_electricity && !empty($bulkInvoice->electricity_cost)) {
                $items['برق'] = $bulkInvoice->electricity_cost;
            }

            if ($building->shared_gas && !empty($bulkInvoice->gas_cost)) {
                $items['گاز'] = $bulkInvoice->gas_cost;
            }
        }
        // منطق صورتحساب ثابت
        else {
            $items = [
                $bulkInvoice->fixed_title  => $bulkInvoice->base_amount,
            ];
        }
// dd($items);
        $totalCost = array_sum($items);
        $unitCount = max($units->count(), 1);
        $perUnitTotal = $totalCost / $unitCount;

        foreach ($units as $unit) {
            DB::transaction(function () use ($unit, $bulkInvoice, $items, $perUnitTotal, $unitCount) {
                $invoice = Invoice::create([
                    'unit_id' => $unit->id,
                    'bulk_invoice_id' => $bulkInvoice->id,
                    'total_amount' => $perUnitTotal,
                    'due_date' => $bulkInvoice->due_date,
                    'status' => 'unpaid',
                    'type' => $bulkInvoice->type,
                ]);

                foreach ($items as $title => $cost) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'title' => $title,
                        'amount' => $cost / $unitCount,
                        'paid_amount' => 0,
                    ]);
                }
            });
        }
    }
}
