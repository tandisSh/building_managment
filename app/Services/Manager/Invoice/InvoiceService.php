<?php

namespace App\Services\Manager\Invoice;

use App\Models\Invoice;
use App\Models\Unit;
use App\Models\BulkInvoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class InvoiceService
{
    //دریافت صورتحساب های یک واحد خاص
    public function getUnitInvoices($unitId, $filters = [])
    {
        $query = Invoice::where('unit_id', $unitId)
            ->with('unit');

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }


        return $query->latest()->get();
    }

    // دریافت تمام صورتحساب‌های واحدهای ساختمان مدیر
    public function getManagerInvoices($manager, $filters = [])
    {
        $query = Invoice::whereHas('unit.building', function ($q) use ($manager) {
            $q->where('manager_id', $manager->id);
        })->with('unit');

        if (!empty($filters['search'])) {
            $query->whereHas('unit', function ($q) use ($filters) {
                $q->where('unit_number', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('users', function ($qu) use ($filters) {
                        $qu->where('name', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }

        return $query->latest()->get();
    }


    // ساخت صورتحساب‌های واحدها بر اساس صورتحساب کلی تایید شده
    public function generateInvoicesFromBulk(BulkInvoice $bulkInvoice)
    {
        $bulkInvoice->loadMissing('building');
        $building = $bulkInvoice->building;

        // دریافت واحدها مربوط به ساختمان
        $units = Unit::where('building_id', $building->id)->get();

        // فقط واحدهایی که حداقل یک ساکن دارند
        $unitsWithResidents = $units->filter(function ($unit) {
            return $unit->totalResidentsCount() > 0;
        });

        if ($bulkInvoice->distribution_type === 'equal') {
            $unitCount = max($unitsWithResidents->count(), 1);
            $perUnitAmount = $bulkInvoice->base_amount / $unitCount;

            foreach ($unitsWithResidents as $unit) {
                Invoice::create([
                    'unit_id' => $unit->id,
                    'bulk_invoice_id' => $bulkInvoice->id,
                    'title' => $bulkInvoice->title,
                    'amount' => $perUnitAmount,
                    'due_date' => $bulkInvoice->due_date,
                    'status' => 'unpaid',
                    'type' => $bulkInvoice->type,
                ]);
            }
        } elseif ($bulkInvoice->distribution_type === 'per_person') {
            $unitCount = $unitsWithResidents->count();
            $totalResidents = $unitsWithResidents->sum(function ($unit) {
                return $unit->totalResidentsCount();
            });

            // محاسبه مبلغ ثابت و متغیر
            $fixedAmount = ($bulkInvoice->base_amount * ($bulkInvoice->fixed_percent ?? 0)) / 100;
            $remainingAmount = $bulkInvoice->base_amount - $fixedAmount;

            $fixedPerUnit = $unitCount > 0 ? $fixedAmount / $unitCount : 0;
            $perPersonAmount = $totalResidents > 0 ? $remainingAmount / $totalResidents : 0;

            foreach ($unitsWithResidents as $unit) {
                $variableAmount = $unit->totalResidentsCount() * $perPersonAmount;
                $unitTotalAmount = $fixedPerUnit + $variableAmount;

                Invoice::create([
                    'unit_id' => $unit->id,
                    'bulk_invoice_id' => $bulkInvoice->id,
                    'title' => $bulkInvoice->title,
                    'amount' => round($unitTotalAmount, 2),
                    'due_date' => $bulkInvoice->due_date,
                    'status' => 'unpaid',
                    'type' => $bulkInvoice->type,
                ]);
            }
        }
    }
}
