<?php
namespace App\Services\Manager\Unit;

use App\Models\Building;
use App\Models\Unit;

class UnitService
{
    public function getUnitsWithResidents(Building $building)
    {
        return $building->units()->with(['users' => function ($query) {
            $query->wherePivot('role', 'resident');
        }])->get();
    }

    public function createUnit(Building $building, array $data): Unit
    {
        return $building->units()->create($data);
    }

    public function updateUnit(Unit $unit, array $data): Unit
    {
        $unit->update($data);
        return $unit;
    }

    public function deleteUnit(Unit $unit): void
    {
        $unit->delete();
    }
}

