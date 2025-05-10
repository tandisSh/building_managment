<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\Manager\Building\BuildingRequestService;

class ManagerController extends Controller
{
    public function __construct(protected BuildingRequestService $service) {}

    public function dashboard()
    {
        $requests = $this->service->getRequestsForUser(auth()->id());
        return view('manager.dashboard', compact('requests'));
    }
}
