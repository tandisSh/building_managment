<?php

namespace App\Services\Manager\Building;

use App\Models\Building;

class BuildingService
{
    public function createBuilding(array $data)
    {
        $path = $data['document']->store('building_documents');

        return Building::create([
            'user_id' => auth()->id(),
            'building_name' => $data['building_name'],
            'address' => $data['address'],
            'number_of_floors' => $data['number_of_floors'],
            'number_of_units' => $data['number_of_units'],
            'shared_utilities' => $data['shared_utilities'],
            'document_path' => $path,
        ]);
    }

    public function updateBuilding(Building $building, array $data)
    {
        $building->update($data);
        return $building;
    }
}
