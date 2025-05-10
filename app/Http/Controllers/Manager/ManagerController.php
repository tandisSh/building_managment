<?php

namespace App\Http\Controllers\Manager;

use App\Http\Requests\StoreBuildingRequest;
use App\Services\Manager\BuildingRequestService;
use App\Http\Controllers\Controller;
use App\Models\BuildingRequest;
use Illuminate\Http\Request;
use App\Models\User;

class ManagerController extends Controller
{
    public function __construct(protected BuildingRequestService $service) {}

    public function dashboard()
    {
        $requests = $this->service->getRequestsForUser(auth()->id());
        return view('manager.dashboard', compact('requests'));
    }

    public function createRequest()
    {
        if ($this->service->hasPendingOrApproved(auth()->id())) {
            return redirect()->route('manager.dashboard')
                ->with('error', 'شما قبلاً یک درخواست ثبت کرده‌اید که هنوز در حال بررسی یا تایید شده است.');
        }

        return view('manager.buildings.request');
    }

    public function storeRequest(StoreBuildingRequest $request)
    {
        if ($this->service->hasPendingOrApproved(auth()->id())) {
            return redirect()->route('manager.dashboard')
                ->with('error', 'شما قبلاً یک درخواست ثبت کرده‌اید.');
        }

        $this->service->storeRequest(auth()->user(), $request->validated(), $request->file('document'));

        return redirect()->route('manager.dashboard')
            ->with('success', 'درخواست با موفقیت ثبت شد');
    }
}

