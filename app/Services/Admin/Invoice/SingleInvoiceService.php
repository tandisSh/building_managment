<?php

namespace App\Services\Admin\Invoice;

use App\Models\Building;
use App\Models\Invoice;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SingleInvoiceService
{
    // دریافت صورتحساب‌های یک واحد خاص
    public function getUnitInvoices($unitId)
    {
        return Invoice::where('unit_id', $unitId)
            ->with('unit.building')
            ->latest()
            ->paginate(20);
    }

    // دریافت تمام صورتحساب‌های تکی برای سوپرادمین
    public function getSuperAdminInvoices(User $superAdmin, $filters = [])
    {
        $query = Invoice::with('unit.building');
$qo=Building::with('units');

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
 if (!empty($filters['building_id'])) {
            $qo->where('id', $filters['building_id']);
        }
        if (!empty($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }

        return $query->latest()->paginate(20);
    }

    // ایجاد صورتحساب تکی
    public function create(User $superAdmin, array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            return Invoice::create([
                'unit_id' => $data['unit_id'],
                'title' => $data['title'],
                'amount' => $data['amount'],
                'due_date' => $data['due_date'],
                'description' => $data['description'] ?? null,
                'type' => $data['type'],
                'status' => 'unpaid',
            ]);
        });
    }

    // ویرایش صورتحساب تکی
    public function update(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data) {
            $invoice->update([
                'unit_id' => $data['unit_id'],
                'title' => $data['title'],
                'amount' => $data['amount'],
                'due_date' => $data['due_date'],
                'description' => $data['description'] ?? null,
                'type' => $data['type'],
            ]);

            return $invoice;
        });
    }
}
