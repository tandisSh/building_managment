<?php

namespace App\Services\Manager\Invoice;

use App\Models\Invoice;
use App\Models\Unit;
use App\Models\BulkInvoice;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    //دریافت صورتحساب های یک واحد خاص
    public function getUnitInvoices($unitId)
    {
        return Invoice::where('unit_id', $unitId)
            ->with('unit')
            ->latest()
            ->get();
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
            $query->where('type', $filters['type']); // فرض بر اینکه فیلد `type` در جدول invoices هست
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

        // دریافت واحدها با تعداد ساکنین از فیلد residents_count
        $units = Unit::where('building_id', $building->id)
            ->where('residents_count', '>', 0)
            ->get();

        if ($bulkInvoice->distribution_type === 'equal') {
            // محاسبات تقسیم مساوی
            $unitCount = max($units->count(), 1);
            $perUnitAmount = $bulkInvoice->base_amount / $unitCount;

            foreach ($units as $unit) {
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
            $unitCount = $units->count();
            $totalResidents = $units->sum('residents_count');

            // محاسبه مبلغ پایه (ثابت)
            $fixedAmount = ($bulkInvoice->base_amount * ($bulkInvoice->fixed_percent ?? 0)) / 100;
            $remainingAmount = $bulkInvoice->base_amount - $fixedAmount;

            // تقسیم مبلغ ثابت به طور مساوی بین واحدها
            $fixedPerUnit = $fixedAmount / $unitCount;

            // تقسیم مبلغ باقیمانده بر اساس نفرات
            $perPersonAmount = $remainingAmount / $totalResidents;



            // ادامه کد ایجاد فاکتورها
            foreach ($units as $unit) {
                $variableAmount = $unit->residents_count * $perPersonAmount;
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
