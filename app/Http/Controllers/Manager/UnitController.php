<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Building;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $units = $building->units()->with('users')->where('role','resident')->get(); // اطلاعات ساکنین هم بگیر

        return view('manager.units.index', compact('building', 'units'));
    }

    public function create(Building $building)
    {
        return view('manager.units.create', compact('building'));
    }

    public function store(StoreUnitRequest $request, $buildingId)
    {
        $building = Building::findOrFail($buildingId);

        $building->units()->create($request->validated());

        return redirect()->route('units.index', $building->id)->with('success', 'واحد جدید با موفقیت اضافه شد.');
    }
    public function edit($buildingId, $unitId)
    {
        $building = Building::findOrFail($buildingId);
        $unit = Unit::findOrFail($unitId);

        return view('manager.units.edit', compact('building', 'unit'));
    }

    public function update(UpdateUnitRequest $request, $buildingId, $unitId)
    {
        $unit = Unit::findOrFail($unitId);

        $unit->update($request->validated());

        return redirect()->route('units.index', $buildingId)->with('success', 'واحد با موفقیت ویرایش شد.');
    }

    public function destroy($buildingId, $unitId)
    {
        $unit = Unit::findOrFail($unitId);

        $unit->delete();

        return redirect()->route('units.index', $buildingId)->with('success', 'واحد با موفقیت حذف شد.');
    }
}
