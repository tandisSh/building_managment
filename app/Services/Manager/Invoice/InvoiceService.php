<?php

namespace App\Services\Manager\Invoice;

use App\Models\Unit;
use App\Models\Invoice;
use App\Models\InvoiceItem;
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

    public function getInvoiceFormData(User $manager)
    {
        $building = $manager->building;
        $units = Unit::where('building_id', $building->id)
            ->whereHas('resident')
            ->get();


        return [
            'units' => $units,
            'building' => $building,
        ];
    }

    public function createMonthlyInvoice(User $manager, array $data)
    {
        $building = $manager->building;
        $units = Unit::where('building_id', $building->id)
            ->whereHas('resident')
            ->get();

        $items = [
            'شارژ ساختمان' => $data['base_amount'],
        ];

        if ($building->shared_water && !empty($data['water_cost'])) {
            $items['آب'] = $data['water_cost'];
        }

        if ($building->shared_electricity && !empty($data['electricity_cost'])) {
            $items['برق'] = $data['electricity_cost'];
        }

        if ($building->shared_gas && !empty($data['gas_cost'])) {
            $items['گاز'] = $data['gas_cost'];
        }

        $totalCost = array_sum($items);
        $unitCount = max($units->count(), 1);
        $perUnitTotal = $totalCost / $unitCount;

        foreach ($units as $unit) {
            DB::transaction(function () use ($unit, $items, $data, $perUnitTotal, $unitCount) {
                $invoice = Invoice::create([
                    'unit_id' => $unit->id,
                    'total_amount' => $perUnitTotal,
                    'due_date' => $data['due_date'],
                    'status' => 'unpaid',
                    'type' => 'current',
                ]);

                foreach ($items as $title => $cost) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'title' => $title,
                        'amount' => $cost / $unitCount,
                    ]);
                }
            });
        }
    }
}
