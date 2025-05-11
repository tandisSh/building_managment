<?php

namespace App\Http\Controllers\Manager\Unit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\UnitRequest;
use App\Models\Building;
use App\Models\Unit;
use App\Services\Manager\Unit\UnitService;

class UnitController extends Controller
{
    public function __construct(protected UnitService $service) {}

    public function index($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $units = $this->service->getUnitsWithResidents($building);
        return view('manager.units.index', compact('building', 'units'));
    }
    public function show($buildingId, $unitId)
    {
        $building = Building::findOrFail($buildingId);
        $unit = Unit::with(['owner', 'resident'])->findOrFail($unitId);

        return view('manager.units.show', compact('building', 'unit'));
    }


    public function create(Building $building)
    {
        return view('manager.units.create', compact('building'));
    }

    public function store(UnitRequest $request, $buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $this->service->createUnit($building, $request->validated());
        return redirect()->route('units.index', $building->id)->with('success', 'واحد جدید با موفقیت اضافه شد.');
    }

    public function edit($buildingId, $unitId)
    {
        $building = Building::findOrFail($buildingId);
        $unit = Unit::findOrFail($unitId);
        return view('manager.units.edit', compact('building', 'unit'));
    }

    public function update(UnitRequest $request, $buildingId, $unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->service->updateUnit($unit, $request->validated());
        return redirect()->route('units.index', $buildingId)->with('success', 'واحد با موفقیت ویرایش شد.');
    }

    public function destroy($buildingId, $unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->service->deleteUnit($unit);
        return redirect()->route('units.index', $buildingId)->with('success', 'واحد با موفقیت حذف شد.');
    }
}
