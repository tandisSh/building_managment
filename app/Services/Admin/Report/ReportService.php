<?php

namespace App\Services\Admin\Report;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Carbon;

class ReportService
{
    public function getOverallPaymentReport(array $filters = [])
    {
        $query = Payment::where('status', 'success')
            ->with(['user', 'invoice.unit.building'])
            ->when($filters['start_date'] ?? null, fn($q) => $q->whereDate('paid_at', '>=', $filters['start_date']))
            ->when($filters['end_date'] ?? null, fn($q) => $q->whereDate('paid_at', '<=', $filters['end_date']))
            ->when($filters['building_id'] ?? null, fn($q) => $q->whereHas('invoice.unit', fn($q) => $q->where('building_id', $filters['building_id'])));

        $totalAmount = $query->sum('amount');
        $payments = $query->latest('paid_at')->paginate(20);

        return [
            'payments' => $payments,
            'totalAmount' => $totalAmount,
            'filters' => $filters,
        ];
    }

    public function getAggregateInvoiceReport(array $filters = [])
    {
        $query = Invoice::with(['unit.building', 'bulkInvoice'])
            ->when($filters['start_date'] ?? null, fn($q) => $q->whereDate('due_date', '>=', $filters['start_date']))
            ->when($filters['end_date'] ?? null, fn($q) => $q->whereDate('due_date', '<=', $filters['end_date']))
            ->when($filters['status'] ?? null, fn($q) => $q->where('status', $filters['status']))
            ->when($filters['building_id'] ?? null, fn($q) => $q->whereHas('unit', fn($q) => $q->where('building_id', $filters['building_id'])));

        $totalAmount = $query->sum('amount');
        $invoices = $query->latest('due_date')->paginate(20);

        return [
            'invoices' => $invoices,
            'totalAmount' => $totalAmount,
            'filters' => $filters,
        ];
    }

    public function getSystemDebtReport()
    {
        $units = Unit::with(['invoices' => fn($q) => $q->where('status', 'unpaid')])->get()
            ->map(fn($unit) => [
                'unit_number' => $unit->unit_number,
                'building_id' => $unit->building_id,
                'debt_count' => $unit->invoices->count(),
                'total_debt' => $unit->invoices->sum('amount'),
                'next_due' => $unit->invoices->min('due_date'),
            ]);

        $totalDebt = $units->sum('total_debt');

        return [
            'units' => $units,
            'totalDebt' => $totalDebt,
        ];
    }

    public function getSystemOverduePaymentsReport(array $filters = [])
    {
        $query = Invoice::with(['unit.building'])
            ->where('status', 'unpaid')
            ->whereDate('due_date', '<', now())
            ->when($filters['start_date'] ?? null, fn($q) => $q->whereDate('due_date', '>=', $filters['start_date']))
            ->when($filters['end_date'] ?? null, fn($q) => $q->whereDate('due_date', '<=', $filters['end_date']))
            ->when($filters['building_id'] ?? null, fn($q) => $q->whereHas('unit', fn($q) => $q->where('building_id', $filters['building_id'])));

        $overdueInvoices = $query->get()->map(fn($invoice) => [
            'invoice' => $invoice,
            'days_overdue' => now()->diffInDays(Carbon::parse($invoice->due_date), false),
            'unit_number' => $invoice->unit->unit_number ?? 'نامشخص',
            'amount' => $invoice->amount,
        ]);

        $totalOverdueAmount = $overdueInvoices->sum('amount');

        return [
            'overdueInvoices' => $overdueInvoices,
            'totalOverdueAmount' => $totalOverdueAmount,
            'filters' => $filters,
        ];
    }

    public function getAnnualFinancialSummary(array $filters = [])
    {
        $startDate = now()->startOfYear();
        if ($filters['year'] ?? null) {
            $startDate = Carbon::create($filters['year'])->startOfYear();
        }

        $invoices = Invoice::with('payments')
            ->where('created_at', '>=', $startDate)
            ->get()
            ->groupBy(fn($invoice) => jdate($invoice->created_at)->format('Y/m'));

        $data = [];

        foreach ($invoices as $yearMonth => $group) {
            $total = $group->sum('amount');
            $paid = $group->sum(fn($invoice) => $invoice->payments->sum('amount'));
            $data[] = [
                'year' => explode('/', $yearMonth)[0],
                'month' => explode('/', $yearMonth)[1],
                'invoiced' => $total,
                'paid' => $paid,
                'unpaid' => $total - $paid,
            ];
        }

        usort($data, fn($a, $b) => $a['year'] . $a['month'] <=> $b['year'] . $b['month']);

        return $data;
    }
}
