<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\Manager\Building\BuildingRequestService;
use App\Services\Manager\Report\ReportService;

class ManagerController extends Controller
{
    public function __construct(
        protected BuildingRequestService $buildingRequestService,
        protected ReportService $reportService
    ) {}

    public function dashboard()
    {
        $userId = auth()->id();
        $buildingId = auth()->user()->buildingUser?->building_id;

        $requests = $this->buildingRequestService->getRequestsForUser($userId);
        $chartData = $this->reportService->getMonthlyFinancialSummary($buildingId, 6);

        return view('manager.dashboard', compact('requests', 'chartData'));
    }
}
