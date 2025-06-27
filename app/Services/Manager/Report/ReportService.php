<?php

namespace App\Services\Manager\Report;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $units = Unit::where('building_id', $buildingId)
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

    public function getOverduePaymentsReport(array $filters = [])
    {
        $user = auth()->user();
        $buildingId = $user->buildingUser?->building_id;

        if (!$buildingId) {
            return [
                'overdueInvoices' => collect(),
                'totalOverdueAmount' => 0,
                'filters' => $filters,
                'building' => null,
            ];
        }

        $query = Invoice::query()
            ->with(['unit'])
            ->whereHas('unit', function ($q) use ($buildingId, $filters) {
                $q->where('building_id', $buildingId);

                if (!empty($filters['unit_number'])) {
                    $q->where('unit_number', 'like', '%' . $filters['unit_number'] . '%');
                }
            })
            ->where('status', 'unpaid')
            ->whereDate('due_date', '<', now());

        if (!empty($filters['date_from'])) {
            $query->whereDate('due_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('due_date', '<=', $filters['date_to']);
        }

        $overdueInvoices = $query->get()->map(function ($invoice) {
            $dueDate = \Carbon\Carbon::parse($invoice->due_date);
            $daysOverdue = now()->diffInDays($dueDate); // این عدد صحیح برمی‌گردونه
            return [
                'invoice' => $invoice,
                'days_overdue' => $daysOverdue,
                'unit_number' => $invoice->unit->unit_number ?? 'نامشخص',
                'amount' => $invoice->amount,
            ];
        });



        $totalOverdueAmount = $overdueInvoices->sum('amount');

        return [
            'overdueInvoices' => $overdueInvoices,
            'totalOverdueAmount' => $totalOverdueAmount,
            'filters' => $filters,
            'building' => $user->buildingUser?->building,
        ];
    }

    public function getMonthlyFinancialSummary(int $buildingId, int $months = 6): array
    {
        $startDate = now()->subMonths($months)->startOfMonth();

        $invoices = Invoice::with('payments')
            ->whereHas('unit', function ($query) use ($buildingId) {
                $query->where('building_id', $buildingId);
            })
            ->where('created_at', '>=', $startDate)
            ->get()
            ->groupBy(function ($invoice) {
                return jdate($invoice->created_at)->format('Y/m'); // مثلا 1403/02
            });

        $data = [];

        foreach ($invoices as $month => $group) {
            $total = $group->sum('amount');
            $paid = $group->sum(fn($invoice) => $invoice->payments->sum('amount'));

            $data[] = [
                'month' => $month,
                'invoiced' => $total,
                'paid' => $paid,
                'unpaid' => $total - $paid,
            ];
        }

        return $data;
    }

    public function getResidentAccountStatusReport(Request $request)
    {
        $user = auth()->user();
        $buildingId = $user->buildingUser?->building_id;

        if (!$buildingId) {
            return [
                'residents' => collect(),
                'building' => null,
            ];
        }

        $search = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = User::whereHas('unitUsers.unit', function ($q) use ($buildingId) {
            $q->where('building_id', $buildingId);
        });

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $residents = $query->with(['unitUsers.unit', 'payments'])
            ->get()
            ->map(function ($resident) use ($dateFrom, $dateTo) {

                $unitUsers = $resident->unitUsers ?? collect();

                $unitIds = $unitUsers->pluck('unit_id')->toArray();

                // فیلتر صورتحساب‌ها (بدهی) بر اساس تاریخ ایجاد، اگر تاریخ داده شده بود
                $invoiceQuery = Invoice::whereIn('unit_id', $unitIds)
                    ->where('status', 'unpaid');

                if ($dateFrom) {
                    $invoiceQuery->whereDate('created_at', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $invoiceQuery->whereDate('created_at', '<=', $dateTo);
                }

                $totalDebt = $invoiceQuery->sum('amount');

                // فیلتر پرداخت‌ها بر اساس تاریخ پرداخت (paid_at)
                $paymentQuery = $resident->payments()
                    ->where('status', 'success');

                if ($dateFrom) {
                    $paymentQuery->whereDate('paid_at', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $paymentQuery->whereDate('paid_at', '<=', $dateTo);
                }

                $totalPaid = $paymentQuery->sum('amount');

                return [
                    'resident_name' => $resident->name,
                    'units' => $unitUsers->pluck('unit.unit_number')->toArray(),
                    'total_debt' => $totalDebt,
                    'total_paid' => $totalPaid,
                ];
            });

        return [
            'residents' => $residents,
            'building' => $user->buildingUser?->building,
        ];
    }

    public function getDashboardStats($buildingId)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return [
            'unitCount' => Unit::where('building_id', $buildingId)->count(),
            'userCount' => User::whereHas('units', fn($q) => $q->where('building_id', $buildingId))->count(),
            'invoiceCount' => Invoice::whereHas('unit', fn($q) => $q->where('building_id', $buildingId))
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count(),
            'totalPaid' => Payment::whereHas('invoice.unit', fn($q) => $q->where('building_id', $buildingId))
                ->where('status', 'success')
                ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
                ->sum('amount'),
        ];
    }

    public function getMonthlyInvoiceAndPaymentChart($buildingId)
    {
        $months = [];
        $invoices = [];
        $payments = [];

        for ($i = 1; $i <= 12; $i++) {
            $start = Carbon::create(null, $i, 1)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            $months[] = jdate($start)->format('F');

            $invoiceSum = Invoice::whereHas('unit', fn($q) => $q->where('building_id', $buildingId))
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount');

            $paymentSum = Payment::whereHas('invoice.unit', fn($q) => $q->where('building_id', $buildingId))
                ->where('status', 'success')
                ->whereBetween('paid_at', [$start, $end])
                ->sum('amount');

            $invoices[] = $invoiceSum;
            $payments[] = $paymentSum;
        }

        return [
            'labels' => $months,
            'invoices' => $invoices,
            'payments' => $payments,
        ];
    }

    public function getExpenseTypeChart($buildingId)
    {
        $labels = ['شارژ', 'آب', 'برق', 'گاز'];
        $keywords = ['شارژ', 'آب', 'برق', 'گاز'];
        $data = [];

        foreach ($keywords as $keyword) {
            $sum = Invoice::whereHas('unit', fn($q) => $q->where('building_id', $buildingId))
                ->where('type', 'current')
                ->where('title', 'like', "%{$keyword}%")
                ->sum('amount');

            $data[] = $sum;
        }

        return ['labels' => $labels, 'values' => $data];
    }
}
