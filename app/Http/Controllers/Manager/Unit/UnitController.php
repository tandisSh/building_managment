<?php

namespace App\Http\Controllers\Manager\Unit;

use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\UnitRequest;
use App\Models\Building;
use App\Models\Unit;
use App\Models\User;
use App\Services\Manager\Unit\UnitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class UnitController extends Controller
{
    public function __construct(protected UnitService $service) {}

    public function index($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $filters = [
            'search' => request('search'),
        ];
        $units = $this->service->getUnitsWithResidents($building, $filters);
        return view('manager.units.index', compact('building', 'units'));
    }


    public function show($buildingId, $unitId)
    {
        $building = Building::findOrFail($buildingId);
        $unit = Unit::with(['owner', 'resident'])->findOrFail($unitId);
        $resident = User::with('unit')->findOrFail($unit->resident_id);

        return view('manager.units.show', compact('building', 'unit', 'resident'));
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
        $data = $request->validated();
        $data['building_id'] = auth()->user()->buildingUser->building_id;
        $this->service->updateUnit($unit, $data);
        return redirect()->route('units.index', $buildingId)->with('success', 'واحد با موفقیت ویرایش شد.');
    }

    public function destroy($buildingId, $unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->service->deleteUnit($unit);
        return redirect()->route('units.index', $buildingId)->with('success', 'واحد با موفقیت حذف شد.');
    }

    public function getBuildingUnits(Building $building): JsonResponse
    {
        try {
            $units = $building->units()
                ->whereDoesntHave('unitUsers', function ($query) {
                    $query->where('status', 'active');
                })
                ->get(['id', 'unit_number', 'floor']);

            return response()->json($units);
        } catch (\Exception $e) {
            Log::error('Error fetching units: ' . $e->getMessage());
            return response()->json(['error' => 'خطا در بارگذاری واحدها'], 500);
        }
    }
}
