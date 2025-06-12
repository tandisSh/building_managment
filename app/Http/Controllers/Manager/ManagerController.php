<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\Manager\Building\BuildingRequestService;
use App\Services\Manager\Report\ReportService;

class ManagerController extends Controller
{
   protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function dashboard()
    {
        $buildingId = auth()->user()->building()->pluck('id')->first();

        $stats = $this->reportService->getDashboardStats($buildingId);
        $monthlyChart = $this->reportService->getMonthlyInvoiceAndPaymentChart($buildingId);
        $expenseChart = $this->reportService->getExpenseTypeChart($buildingId);
// dd($monthlyChart);
// dd($expenseChart);
        return view('manager.dashboard', compact('stats', 'monthlyChart', 'expenseChart'));
    }
}
