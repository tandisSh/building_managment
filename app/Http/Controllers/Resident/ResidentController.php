<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Services\Resident\Dashboard\DashboardService;
use Illuminate\Http\Request;

class ResidentController extends Controller
{

    protected $dashboardService;

    public function __construct(DashboardService $DashboardService)
    {
        $this->dashboardService = $DashboardService;
    }

    public function index()
    {
        $data = $this->dashboardService->getDashboardData(auth()->user());
        return view('resident.dashboard', $data);
    }
}


