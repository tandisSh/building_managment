<?php

namespace App\Services\Manager\Unit;

use App\Models\Building;
use App\Models\Unit;

class UnitService
{
  public function getUnitsWithResidents(Building $building, array $filters = [])
{
    $query = $building->units()->with(['users' => function ($query) {
        $query->wherePivot('role', 'resident');
    }]);

    if (!empty($filters['search'])) {
        $search = $filters['search'];
        $query->where(function ($q) use ($search) {
            $q->where('unit_number', 'like', "%$search%")
              ->orWhereHas('users', function ($uq) use ($search) {
                  $uq->where('unit_user.role', 'resident')
                     ->where('name', 'like', "%$search%");
              });
        });
    }

    return $query->get();
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
