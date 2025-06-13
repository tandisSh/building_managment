<?php

namespace App\Http\Controllers\SuperAdmin\Building;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Building\BuildingRequest;
use App\Models\Building;
use App\Models\User;
use App\Services\Admin\Building\BuildingService;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function __construct(protected BuildingService $buildingService) {}

   public function index(Request $request, BuildingService $buildingService)
{
    $buildings = $buildingService->getFilteredBuildings($request);
    return view('super_admin.buildings.index', compact('buildings'));
}

    public function create()
    {
        $managers = User::whereHas('roles', function ($q) {
            $q->where('name', 'manager');
        })->get();
        return view('super_admin.buildings.create' ,compact('managers'));
    }

    public function store(BuildingRequest $request)
    {
        $this->buildingService->createBuilding($request->validated(), $request->file('document'));
        return redirect()->route('superadmin.buildings.index')->with('success', 'ساختمان با موفقیت ثبت شد.');
    }

    public function show(Building $building)
    {
        $building->load('manager');
        // //    dd($building->load('manager')->toArray());

        return view('super_admin.buildings.show', compact('building'));
    }

    public function edit(Building $building)
    {
         $managers = User::whereHas('roles', function ($q) {
            $q->where('name', 'manager');
        })->get();
        return view('super_admin.buildings.edit', compact('building','managers'));
    }

    public function update(BuildingRequest $request, Building $building)
    {
        $this->buildingService->updateBuilding($building, $request->validated(), $request->file('document'));
        return redirect()->route('superadmin.buildings.index')->with('success', 'ساختمان با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Building $building)
    {
        $this->buildingService->deleteBuilding($building);
        return redirect()->route('superadmin.buildings.index')->with('success', 'ساختمان با موفقیت حذف شد.');
    }
}
