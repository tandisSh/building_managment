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

    public function getBuildingPerformanceReport(array $filters = [])
    {
        $query = \App\Models\Building::with([
            'units',
            'units.unitUsers',
            'units.invoices',
            'units.invoices.payments',
            'manager'
        ]);

        // فیلتر بر اساس تاریخ
        if (!empty($filters['start_date'])) {
            $query->whereHas('units.invoices', function ($q) use ($filters) {
                $q->whereDate('created_at', '>=', $filters['start_date']);
            });
        }

        if (!empty($filters['end_date'])) {
            $query->whereHas('units.invoices', function ($q) use ($filters) {
                $q->whereDate('created_at', '<=', $filters['end_date']);
            });
        }

        $buildings = $query->get()->map(function ($building) use ($filters) {
            $startDate = $filters['start_date'] ?? now()->subYear()->startOfYear();
            $endDate = $filters['end_date'] ?? now();

            // محاسبه آمار واحدها
            $totalUnits = $building->units->count();
            $occupiedUnits = $building->units->filter(function ($unit) {
                return $unit->unitUsers->count() > 0;
            })->count();
            $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 2) : 0;

            // محاسبه آمار فاکتورها
            $invoices = $building->units->flatMap(function ($unit) use ($startDate, $endDate) {
                return $unit->invoices->filter(function ($invoice) use ($startDate, $endDate) {
                    return $invoice->created_at >= $startDate && $invoice->created_at <= $endDate;
                });
            });

            $totalInvoiced = $invoices->sum('amount');
            $paidInvoices = $invoices->where('status', 'paid');
            $totalPaid = $paidInvoices->sum('amount');
            $totalUnpaid = $totalInvoiced - $totalPaid;
            $paymentRate = $totalInvoiced > 0 ? round(($totalPaid / $totalInvoiced) * 100, 2) : 0;

            // محاسبه بدهی‌های معوق
            $overdueInvoices = $building->units->flatMap(function ($unit) {
                return $unit->invoices->where('status', 'unpaid')->where('due_date', '<', now());
            });
            $totalOverdue = $overdueInvoices->sum('amount');

            // محاسبه درآمد ماهانه
            $monthlyRevenue = $building->units->flatMap(function ($unit) use ($startDate, $endDate) {
                return $unit->invoices->where('status', 'paid')->filter(function ($invoice) use ($startDate, $endDate) {
                    return $invoice->created_at >= $startDate && $invoice->created_at <= $endDate;
                });
            })->sum('amount');

            // محاسبه امتیاز عملکرد (Performance Score)
            $performanceScore = 0;
            $performanceScore += $occupancyRate * 0.3; // 30% وزن اشغال
            $performanceScore += $paymentRate * 0.4;   // 40% وزن پرداخت
            $performanceScore += ($totalOverdue == 0 ? 100 : max(0, 100 - ($totalOverdue / $totalInvoiced * 100))) * 0.3; // 30% وزن بدهی

            return [
                'id' => $building->id,
                'name' => $building->name,
                'address' => $building->address,
                'manager_name' => $building->manager->name ?? 'نامشخص',
                'total_units' => $totalUnits,
                'occupied_units' => $occupiedUnits,
                'occupancy_rate' => $occupancyRate,
                'total_invoiced' => $totalInvoiced,
                'total_paid' => $totalPaid,
                'total_unpaid' => $totalUnpaid,
                'payment_rate' => $paymentRate,
                'total_overdue' => $totalOverdue,
                'monthly_revenue' => $monthlyRevenue,
                'performance_score' => round($performanceScore, 2),
                'status' => $this->getPerformanceStatus($performanceScore),
            ];
        });

        // مرتب‌سازی بر اساس امتیاز عملکرد
        $buildings = $buildings->sortByDesc('performance_score');

        return [
            'buildings' => $buildings,
            'filters' => $filters,
            'summary' => [
                'total_buildings' => $buildings->count(),
                'average_occupancy' => $buildings->avg('occupancy_rate'),
                'average_payment_rate' => $buildings->avg('payment_rate'),
                'average_performance_score' => $buildings->avg('performance_score'),
                'total_revenue' => $buildings->sum('total_paid'),
                'total_overdue' => $buildings->sum('total_overdue'),
            ]
        ];
    }

    private function getPerformanceStatus($score)
    {
        if ($score >= 80) return 'عالی';
        if ($score >= 60) return 'خوب';
        if ($score >= 40) return 'متوسط';
        return 'ضعیف';
    }

    public function getUserActivityReport(array $filters = [])
    {
        $query = User::with([
            'roles',
            'unitUsers.unit.building',
            'payments',
            'repairRequests',
            'notifications'
        ]);

        // فیلتر بر اساس نقش
        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        // فیلتر بر اساس وضعیت
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // فیلتر بر اساس ساختمان
        if (!empty($filters['building_id'])) {
            $query->whereHas('unitUsers.unit', function ($q) use ($filters) {
                $q->where('building_id', $filters['building_id']);
            });
        }

        $users = $query->get()->map(function ($user) use ($filters) {
            $startDate = $filters['start_date'] ?? now()->subMonth()->startOfMonth();
            $endDate = $filters['end_date'] ?? now();

            // محاسبه آمار پرداخت‌ها
            $payments = $user->payments->filter(function ($payment) use ($startDate, $endDate) {
                return $payment->paid_at >= $startDate && $payment->paid_at <= $endDate;
            });
            $totalPayments = $payments->count();
            $totalPaidAmount = $payments->sum('amount');

            // محاسبه آمار درخواست‌های تعمیر
            $repairRequests = $user->repairRequests->filter(function ($request) use ($startDate, $endDate) {
                return $request->created_at >= $startDate && $request->created_at <= $endDate;
            });
            $totalRepairRequests = $repairRequests->count();
            $pendingRepairRequests = $repairRequests->where('status', 'pending')->count();
            $completedRepairRequests = $repairRequests->where('status', 'completed')->count();

            // محاسبه آمار اعلان‌ها
            $notifications = $user->notifications->filter(function ($notification) use ($startDate, $endDate) {
                return $notification->created_at >= $startDate && $notification->created_at <= $endDate;
            });
            $totalNotifications = $notifications->count();
            $unreadNotifications = $notifications->where('read_at', null)->count();

            // محاسبه آمار واحدها
            $totalUnits = $user->unitUsers->count();
            $activeUnits = $user->unitUsers->where('status', 'active')->count();
            $ownerUnits = $user->unitUsers->where('role', 'owner')->count();
            $residentUnits = $user->unitUsers->where('role', 'resident')->count();

            // محاسبه آخرین فعالیت
            $lastPayment = $payments->max('paid_at');
            $lastRepairRequest = $repairRequests->max('created_at');
            $lastLogin = $user->last_login_at ?? $user->created_at;

            // محاسبه امتیاز فعالیت
            $activityScore = 0;
            $activityScore += $totalPayments * 10; // هر پرداخت ۱۰ امتیاز
            $activityScore += $totalRepairRequests * 5; // هر درخواست تعمیر ۵ امتیاز
            $activityScore += $activeUnits * 20; // هر واحد فعال ۲۰ امتیاز
            $activityScore += ($totalNotifications - $unreadNotifications) * 2; // هر اعلان خوانده شده ۲ امتیاز

            // محاسبه وضعیت فعالیت
            $activityStatus = $this->getActivityStatus($activityScore, $lastLogin);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'status' => $user->status,
                'roles' => $user->roles->pluck('name')->toArray(),
                'total_units' => $totalUnits,
                'active_units' => $activeUnits,
                'owner_units' => $ownerUnits,
                'resident_units' => $residentUnits,
                'total_payments' => $totalPayments,
                'total_paid_amount' => $totalPaidAmount,
                'total_repair_requests' => $totalRepairRequests,
                'pending_repair_requests' => $pendingRepairRequests,
                'completed_repair_requests' => $completedRepairRequests,
                'total_notifications' => $totalNotifications,
                'unread_notifications' => $unreadNotifications,
                'last_payment' => $lastPayment,
                'last_repair_request' => $lastRepairRequest,
                'last_login' => $lastLogin,
                'activity_score' => $activityScore,
                'activity_status' => $activityStatus,
                'buildings' => $user->unitUsers->map(function ($unitUser) {
                    return $unitUser->unit->building->name ?? 'نامشخص';
                })->unique()->toArray(),
            ];
        });

        // مرتب‌سازی بر اساس امتیاز فعالیت
        $users = $users->sortByDesc('activity_score');

        return [
            'users' => $users,
            'filters' => $filters,
            'summary' => [
                'total_users' => $users->count(),
                'active_users' => $users->where('status', 'active')->count(),
                'inactive_users' => $users->where('status', 'inactive')->count(),
                'average_activity_score' => $users->avg('activity_score'),
                'total_payments' => $users->sum('total_payments'),
                'total_paid_amount' => $users->sum('total_paid_amount'),
                'total_repair_requests' => $users->sum('total_repair_requests'),
                'total_notifications' => $users->sum('total_notifications'),
            ]
        ];
    }

    private function getActivityStatus($score, $lastLogin)
    {
        $daysSinceLastLogin = now()->diffInDays($lastLogin);
        
        if ($score >= 100 && $daysSinceLastLogin <= 7) return 'خیلی فعال';
        if ($score >= 50 && $daysSinceLastLogin <= 30) return 'فعال';
        if ($score >= 20 && $daysSinceLastLogin <= 90) return 'متوسط';
        if ($daysSinceLastLogin > 90) return 'غیرفعال';
        return 'کم‌فعال';
    }
}
