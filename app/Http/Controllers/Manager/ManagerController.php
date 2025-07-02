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
        $building = auth()->user()->building;

        $initialPaymentPending = false;
        $buildingNotReady = false;
        $stats = [];
        $monthlyChart = [];
        $expenseChart = [];

        if ($building) {
            if ($building->activation_status === 'pending_payment') {
                $initialPaymentPending = true;
            } else {
                $stats = $this->reportService->getDashboardStats($building->id);
                $monthlyChart = $this->reportService->getMonthlyInvoiceAndPaymentChart($building->id);
                $expenseChart = $this->reportService->getExpenseTypeChart($building->id);
            }
        } else {
            $buildingNotReady = true;
        }

        return view('manager.dashboard', compact('stats', 'monthlyChart', 'expenseChart', 'initialPaymentPending', 'buildingNotReady'));
    }
}
