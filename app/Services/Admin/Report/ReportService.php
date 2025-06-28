<?php

namespace App\Services\Admin\Report;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Building;
use App\Models\RepairRequest;
use App\Models\Notification;
use App\Models\BuildingRequest;

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

    public function getBuildingRequestsReport(array $filters = [])
    {
        $query = \App\Models\BuildingRequest::with([
            'user'
        ]);

        // فیلتر بر اساس تاریخ
        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        // فیلتر بر اساس وضعیت
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $requests = $query->get()->map(function ($request) {
            // محاسبه مدت زمان در انتظار
            $waitingDays = 0;
            if ($request->status === 'pending') {
                $waitingDays = now()->diffInDays($request->created_at);
            }

            // محاسبه مدت زمان بررسی
            $processingDays = 0;
            if ($request->status === 'approved' || $request->status === 'rejected') {
                $processingDays = $request->updated_at->diffInDays($request->created_at);
            }

            return [
                'id' => $request->id,
                'user_name' => $request->user->name,
                'user_email' => $request->user->email,
                'user_phone' => $request->user->phone,
                'building_name' => $request->name,
                'building_address' => $request->address,
                'total_units' => $request->number_of_units,
                'description' => $request->description,
                'status' => $request->status,
                'document_path' => $request->document_path,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
                'waiting_days' => $waitingDays,
                'processing_days' => $processingDays,
                'has_document' => !empty($request->document_path),
            ];
        });

        // مرتب‌سازی بر اساس تاریخ ایجاد (جدیدترین اول)
        $requests = $requests->sortByDesc('created_at');

        return [
            'requests' => $requests,
            'filters' => $filters,
            'summary' => [
                'total_requests' => $requests->count(),
                'pending_requests' => $requests->where('status', 'pending')->count(),
                'approved_requests' => $requests->where('status', 'approved')->count(),
                'rejected_requests' => $requests->where('status', 'rejected')->count(),
                'average_processing_days' => $requests->where('status', '!=', 'pending')->avg('processing_days'),
                'average_waiting_days' => $requests->where('status', 'pending')->avg('waiting_days'),
                'total_units_requested' => $requests->sum('total_units'),
                'requests_with_documents' => $requests->where('has_document', true)->count(),
            ]
        ];
    }

    public function getRevenueAnalysisReport(array $filters = [])
    {
        $startDate = $filters['start_date'] ?? now()->subYear()->startOfYear();
        $endDate = $filters['end_date'] ?? now();

        // دریافت تمام پرداخت‌های موفق در بازه زمانی
        $payments = Payment::with(['user', 'invoice.unit.building'])
            ->where('status', 'success')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->get();

        // فیلتر بر اساس ساختمان
        if (!empty($filters['building_id'])) {
            $payments = $payments->filter(function ($payment) use ($filters) {
                return $payment->invoice->unit->building_id == $filters['building_id'];
            });
        }

        // تحلیل درآمد ماهانه
        $monthlyRevenue = $payments->groupBy(function ($payment) {
            return $payment->paid_at->format('Y-m');
        })->map(function ($monthPayments) {
            return [
                'total_amount' => $monthPayments->sum('amount'),
                'payment_count' => $monthPayments->count(),
                'average_amount' => $monthPayments->avg('amount'),
            ];
        })->sortKeys();

        // تحلیل درآمد بر اساس ساختمان
        $buildingRevenue = $payments->groupBy(function ($payment) {
            return $payment->invoice->unit->building->name ?? 'نامشخص';
        })->map(function ($buildingPayments) {
            return [
                'total_amount' => $buildingPayments->sum('amount'),
                'payment_count' => $buildingPayments->count(),
                'average_amount' => $buildingPayments->avg('amount'),
                'units_count' => $buildingPayments->pluck('invoice.unit_id')->unique()->count(),
            ];
        })->sortByDesc('total_amount');

        // تحلیل درآمد بر اساس نوع فاکتور
        $invoiceTypeRevenue = $payments->groupBy(function ($payment) {
            return $payment->invoice->type ?? 'عمومی';
        })->map(function ($typePayments) {
            return [
                'total_amount' => $typePayments->sum('amount'),
                'payment_count' => $typePayments->count(),
                'average_amount' => $typePayments->avg('amount'),
            ];
        })->sortByDesc('total_amount');


        // تحلیل درآمد بر اساس کاربران
        $userRevenue = $payments->groupBy('user_id')->map(function ($userPayments) {
            $user = $userPayments->first()->user;
            return [
                'user_name' => $user->name,
                'user_email' => $user->email,
                'total_amount' => $userPayments->sum('amount'),
                'payment_count' => $userPayments->count(),
                'average_amount' => $userPayments->avg('amount'),
                'last_payment' => $userPayments->max('paid_at'),
            ];
        })->sortByDesc('total_amount')->take(20);

        // محاسبه آمار کلی
        $totalRevenue = $payments->sum('amount');
        $totalPayments = $payments->count();
        $averagePayment = $payments->avg('amount');
        $uniqueUsers = $payments->pluck('user_id')->unique()->count();
        $uniqueBuildings = $payments->pluck('invoice.unit.building_id')->unique()->count();

        // محاسبه رشد درآمد
        $currentMonthRevenue = $payments->filter(function ($payment) {
            return $payment->paid_at->format('Y-m') === now()->format('Y-m');
        })->sum('amount');

        $previousMonthRevenue = $payments->filter(function ($payment) {
            return $payment->paid_at->format('Y-m') === now()->subMonth()->format('Y-m');
        })->sum('amount');

        $revenueGrowth = $previousMonthRevenue > 0 ?
            (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 : 0;

        // پیش‌بینی درآمد آینده (بر اساس میانگین ۳ ماه گذشته)
        $lastThreeMonths = $payments->filter(function ($payment) {
            return $payment->paid_at >= now()->subMonths(3);
        })->sum('amount') / 3;

        return [
            'filters' => $filters,
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_payments' => $totalPayments,
                'average_payment' => $averagePayment,
                'unique_users' => $uniqueUsers,
                'unique_buildings' => $uniqueBuildings,
                'current_month_revenue' => $currentMonthRevenue,
                'previous_month_revenue' => $previousMonthRevenue,
                'revenue_growth' => $revenueGrowth,
                'forecast_next_month' => $lastThreeMonths,
            ],
            'monthly_revenue' => $monthlyRevenue,
            'building_revenue' => $buildingRevenue,
            'invoice_type_revenue' => $invoiceTypeRevenue,
            'top_users' => $userRevenue,
            'payments' => $payments->sortByDesc('paid_at')->take(50),
        ];
    }

    public function getSystemStatisticsReport()
    {
        // آمار کاربران
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
        $superAdmins = User::whereHas('roles', function ($query) {
            $query->where('name', 'super_admin');
        })->count();
        $managers = User::whereHas('roles', function ($query) {
            $query->where('name', 'manager');
        })->count();
        $residents = User::whereHas('roles', function ($query) {
            $query->where('name', 'resident');
        })->count();

        // آمار ساختمان‌ها
        $totalBuildings = Building::count();
        $activeBuildings = Building::whereHas('manager')->count(); // ساختمان‌های فعال = دارای مدیر
        $inactiveBuildings = Building::whereDoesntHave('manager')->count(); // ساختمان‌های غیرفعال = بدون مدیر
        $buildingsWithManager = Building::whereHas('manager')->count();
        $buildingsWithoutManager = $totalBuildings - $buildingsWithManager;

        // آمار واحدها
        $totalUnits = Unit::count();
        $occupiedUnits = Unit::whereHas('unitUsers')->count();
        $vacantUnits = $totalUnits - $occupiedUnits;
        $ownerUnits = Unit::whereHas('unitUsers', function ($query) {
            $query->where('role', 'owner');
        })->count();
        $tenantUnits = Unit::whereHas('unitUsers', function ($query) {
            $query->where('role', 'resident');
        })->count();

        // آمار فاکتورها
        $totalInvoices = Invoice::count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $unpaidInvoices = Invoice::where('status', 'unpaid')->count();
        $overdueInvoices = Invoice::where('due_date', '<', now())->where('status', 'unpaid')->count();
        $totalInvoiceAmount = Invoice::sum('amount');
        $paidInvoiceAmount = Invoice::where('status', 'paid')->sum('amount');
        $unpaidInvoiceAmount = Invoice::where('status', 'unpaid')->sum('amount');

        // آمار پرداخت‌ها
        $totalPayments = Payment::count();
        $successfulPayments = Payment::where('status', 'success')->count();
        $failedPayments = Payment::where('status', 'failed')->count();
        $totalPaymentAmount = Payment::where('status', 'success')->sum('amount');
        $averagePaymentAmount = Payment::where('status', 'success')->avg('amount');

        // آمار درخواست‌های تعمیر
        $totalRepairRequests = RepairRequest::count();
        $pendingRepairRequests = RepairRequest::where('status', 'pending')->count();
        $inProgressRepairRequests = RepairRequest::where('status', 'in_progress')->count();
        $completedRepairRequests = RepairRequest::where('status', 'completed')->count();
        $cancelledRepairRequests = RepairRequest::where('status', 'cancelled')->count();

        // آمار اعلان‌ها
        $totalNotifications = Notification::count();
        $readNotifications = Notification::where('read_at', '!=', null)->count();
        $unreadNotifications = Notification::where('read_at', null)->count();

        // آمار درخواست‌های ساختمان
        $totalBuildingRequests = BuildingRequest::count();
        $pendingBuildingRequests = BuildingRequest::where('status', 'pending')->count();
        $approvedBuildingRequests = BuildingRequest::where('status', 'approved')->count();
        $rejectedBuildingRequests = BuildingRequest::where('status', 'rejected')->count();

        // آمار فعالیت‌های اخیر
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();
        $recentBuildings = Building::orderBy('created_at', 'desc')->take(5)->get();
        $recentPayments = Payment::where('status', 'success')->orderBy('paid_at', 'desc')->take(5)->get();
        $recentRepairRequests = RepairRequest::orderBy('created_at', 'desc')->take(5)->get();

        // محاسبه درصدها
        $userActivityRate = $totalUsers > 0 ? ($activeUsers / $totalUsers) * 100 : 0;
        $buildingActivityRate = $totalBuildings > 0 ? ($activeBuildings / $totalBuildings) * 100 : 0;
        $unitOccupancyRate = $totalUnits > 0 ? ($occupiedUnits / $totalUnits) * 100 : 0;
        $invoicePaymentRate = $totalInvoices > 0 ? ($paidInvoices / $totalInvoices) * 100 : 0;
        $paymentSuccessRate = $totalPayments > 0 ? ($successfulPayments / $totalPayments) * 100 : 0;
        $repairCompletionRate = $totalRepairRequests > 0 ? ($completedRepairRequests / $totalRepairRequests) * 100 : 0;

        // آمار ماهانه (آخرین 6 ماه)
        $monthlyStats = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthStart = $month->startOfMonth();
            $monthEnd = $month->endOfMonth();

            $monthlyStats->push([
                'month' => $month->format('Y/m'),
                'new_users' => User::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'new_buildings' => Building::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'new_payments' => Payment::where('status', 'success')->whereBetween('paid_at', [$monthStart, $monthEnd])->count(),
                'payment_amount' => Payment::where('status', 'success')->whereBetween('paid_at', [$monthStart, $monthEnd])->sum('amount'),
                'new_repair_requests' => RepairRequest::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            ]);
        }

        return [
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers,
                'inactive' => $inactiveUsers,
                'super_admins' => $superAdmins,
                'managers' => $managers,
                'residents' => $residents,
                'activity_rate' => $userActivityRate,
                'recent' => $recentUsers,
            ],
            'buildings' => [
                'total' => $totalBuildings,
                'active' => $activeBuildings,
                'inactive' => $inactiveBuildings,
                'with_manager' => $buildingsWithManager,
                'without_manager' => $buildingsWithoutManager,
                'activity_rate' => $buildingActivityRate,
                'recent' => $recentBuildings,
            ],
            'units' => [
                'total' => $totalUnits,
                'occupied' => $occupiedUnits,
                'vacant' => $vacantUnits,
                'owner' => $ownerUnits,
                'tenant' => $tenantUnits,
                'occupancy_rate' => $unitOccupancyRate,
            ],
            'invoices' => [
                'total' => $totalInvoices,
                'paid' => $paidInvoices,
                'unpaid' => $unpaidInvoices,
                'overdue' => $overdueInvoices,
                'total_amount' => $totalInvoiceAmount,
                'paid_amount' => $paidInvoiceAmount,
                'unpaid_amount' => $unpaidInvoiceAmount,
                'payment_rate' => $invoicePaymentRate,
            ],
            'payments' => [
                'total' => $totalPayments,
                'successful' => $successfulPayments,
                'failed' => $failedPayments,
                'total_amount' => $totalPaymentAmount,
                'average_amount' => $averagePaymentAmount,
                'success_rate' => $paymentSuccessRate,
                'recent' => $recentPayments,
            ],
            'repair_requests' => [
                'total' => $totalRepairRequests,
                'pending' => $pendingRepairRequests,
                'in_progress' => $inProgressRepairRequests,
                'completed' => $completedRepairRequests,
                'cancelled' => $cancelledRepairRequests,
                'completion_rate' => $repairCompletionRate,
                'recent' => $recentRepairRequests,
            ],
            'notifications' => [
                'total' => $totalNotifications,
                'read' => $readNotifications,
                'unread' => $unreadNotifications,
            ],
            'building_requests' => [
                'total' => $totalBuildingRequests,
                'pending' => $pendingBuildingRequests,
                'approved' => $approvedBuildingRequests,
                'rejected' => $rejectedBuildingRequests,
            ],
            'monthly_stats' => $monthlyStats,
        ];
    }
}
