<?php

namespace App\Http\Controllers\Manager\Building;

use App\Http\Controllers\Controller;
use App\Http\Requests\Building\BuildingRequest;
use App\Models\Building;
use App\Services\Manager\Building\BuildingRequestService;
use App\Services\Manager\Building\BuildingService;

class BuildingController extends Controller
{
    public function __construct(
        protected BuildingRequestService $requestService,
        protected BuildingService $buildingService
    ) {}

    public function createRequest()
    {
        if ($this->requestService->hasPendingOrApproved(auth()->id())) {
            return redirect()->route('manager.dashboard')
                ->with('error', 'شما قبلاً یک درخواست ثبت کرده‌اید که هنوز در حال بررسی یا تایید شده است.');
        }

        return view('manager.buildings.request');
    }

    public function storeRequest(BuildingRequest $request)
    {
        if ($this->requestService->hasPendingOrApproved(auth()->id())) {
            return redirect()->route('manager.dashboard')
                ->with('error', 'شما قبلاً یک درخواست ثبت کرده‌اید.');
        }

        $this->requestService->storeRequest(auth()->user(), $request->validated(), $request->file('document'));

        return redirect()->route('manager.dashboard')->with('success', 'درخواست با موفقیت ثبت شد');
    }

    public function showBuilding()
    {
        $building = auth()->user()->buildingUser->building ?? null;

        if (!$building) {
            return redirect()->route('manager.dashboard')->with('error', 'ساختمانی برای شما یافت نشد.');
        }

        return view('manager.buildings.show', compact('building'));
    }

    public function editBuilding(Building $building)
    {
        if (auth()->user()->buildingUser->building_id !== $building->id) {
            abort(403);
        }

        return view('manager.buildings.edit', compact('building'));
    }

    public function updateBuilding(BuildingRequest $request, Building $building)
    {
        if (auth()->user()->buildingUser->building_id !== $building->id) {
            abort(403);
        }

        $this->buildingService->updateBuilding($building, $request->validated());

        return redirect()->route('manager.building.show')->with('success', 'ساختمان با موفقیت به‌روزرسانی شد.');
    }
}
