<?php

// app/Services/Manager/InvoiceService.php
namespace App\Services\Manager\Invoice;

use App\Models\Invoice;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function createMonthlyInvoices(array $data, $manager)
    {
        $building = $manager->building;

        // فقط واحدهای دارای ساکن
        $units = Unit::where('building_id', $building->id)->whereNotNull('resident_id')->get();
        $unitCount = $units->count();

        if ($unitCount === 0) {
            throw new \Exception('هیچ واحد فعالی برای صدور صورتحساب وجود ندارد.');
        }

        // محاسبه مبلغ کل
        $total = $data['base_amount'];

        if ($building->shared_water) {
            $total += $data['water_cost'] ?? 0;
        }

        if ($building->shared_electricity) {
            $total += $data['electricity_cost'] ?? 0;
        }

        if ($building->shared_gas) {
            $total += $data['gas_cost'] ?? 0;
        }

        $amountPerUnit = round($total / $unitCount, 2);

        // ایجاد صورتحساب برای هر واحد
        DB::transaction(function () use ($units, $amountPerUnit, $data) {
            foreach ($units as $unit) {
                Invoice::create([
                    'unit_id' => $unit->id,
                    'amount' => $amountPerUnit,
                    'type' => 'current',
                    'description' => $data['description'] ?? null,
                    'due_date' => $data['due_date'],
                    'status' => 'unpaid',
                ]);
            }
        });
    }
}
