<?php

namespace App\Services\Manager\Report;

use App\Models\Payment;
use App\Models\Invoice;

class ReportService
{
    public function getPaymentReport(array $filters = [])
    {
        $user = auth()->user();
        $buildingId = $user->buildingUser?->building_id;

        if (!$buildingId) {
            return [
                'payments' => collect([]),
                'totalAmount' => 0,
                'filters' => $filters,
                'building' => null,
            ];
        }

        $query = Payment::query()
            ->with(['user', 'invoice.unit.building'])
            ->whereHas('invoice.unit', function ($q) use ($buildingId, $filters) {
                $q->where('building_id', $buildingId);

                if (!empty($filters['unit_number'])) {
                    $q->where('unit_number', $filters['unit_number']);
                }
            })
            ->where('status', 'success')
            ->whereNotNull('paid_at');

        if (!empty($filters['date_from'])) {
            $query->whereDate('paid_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('paid_at', '<=', $filters['date_to']);
        }

        $payments = $query->latest('paid_at')->paginate(20);
        $totalAmount = $query->sum('amount'); // جمع کل درست محاسبه شود حتی با paginate

        return [
            'payments' => $payments,
            'totalAmount' => $totalAmount,
            'filters' => $filters,
            'building' => $user->buildingUser?->building,
        ];
    }

    public function getInvoiceReport(array $filters = [])
    {
        $user = auth()->user();
        $buildingId = $user->buildingUser?->building_id;

        if (!$buildingId) {
            return [
                'invoices' => collect([]),
                'totalAmount' => 0,
                'filters' => $filters,
                'building' => null,
                'units' => collect(),
            ];
        }

        $query = Invoice::query()
            ->with(['unit', 'bulkInvoice'])
            ->whereHas('unit', function ($q) use ($buildingId) {
                $q->where('building_id', $buildingId);
            });

        if (!empty($filters['date_from'])) {
            $query->whereDate('due_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('due_date', '<=', $filters['date_to']);
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'paid') {
                $query->where('status', 'paid');
            } elseif ($filters['status'] === 'unpaid') {
                $query->where('status', 'unpaid');
            }
        }

        // فیلتر نوع صورتحساب
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // فیلتر انتخاب واحد
        if (!empty($filters['unit_id'])) {
            $query->where('unit_id', $filters['unit_id']);
        }

        $invoices = $query->latest('due_date')->paginate(20);
        $totalAmount = $invoices->sum('amount');

        // برای نمایش لیست واحدها در select فرم
        $units = \App\Models\Unit::where('building_id', $buildingId)->get();

        return [
            'invoices' => $invoices,
            'totalAmount' => $totalAmount,
            'filters' => $filters,
            'building' => $user->buildingUser?->building,
            'units' => $units,
        ];
    }

    public function getUnitDebtReport()
    {
        $user = auth()->user();
        $buildingId = $user->buildingUser?->building_id;

        if (!$buildingId) {
            return [
                'units' => collect(),
                'building' => null,
            ];
        }

        // دریافت تمام واحدهای ساختمان
        $units = \App\Models\Unit::where('building_id', $buildingId)
            ->with(['invoices' => function ($q) {
                $q->where('status', 'unpaid');
            }])
            ->get()
            ->map(function ($unit) {
                $unpaidInvoices = $unit->invoices;

                return [
                    'unit_number' => $unit->unit_number,
                    'debt_count' => $unpaidInvoices->count(),
                    'total_debt' => $unpaidInvoices->sum('amount'),
                    'next_due' => $unpaidInvoices->min('due_date'),
                ];
            });

        return [
            'units' => $units,
            'building' => $user->buildingUser?->building,
        ];
    }
}
