<?php

namespace App\Services\Admin\Building;

use App\Models\Building;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BuildingService
{


    public function getFilteredBuildings(Request $request)
    {
        $query = Building::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%");
            });
        }

        if (!is_null($request->input('shared_water'))) {
            $query->where('shared_water', $request->input('shared_water'));
        }

        if (!is_null($request->input('shared_gas'))) {
            $query->where('shared_gas', $request->input('shared_gas'));
        }

        if (!is_null($request->input('shared_electricity'))) {
            $query->where('shared_electricity', $request->input('shared_electricity'));
        }

        return $query->latest()->paginate(15);
    }

    public function createBuilding(array $data, $documentFile = null): Building
    {
        if ($documentFile) {
            $data['document_path'] = $documentFile->store('building_documents');
        }
        return Building::create($data);
    }

    public function updateBuilding(Building $building, array $data, $documentFile = null): Building
    {
        if ($documentFile) {
            if ($building->document_path) {
                Storage::delete($building->document_path);
            }
            $data['document_path'] = $documentFile->store('building_documents');
        }
        $building->update($data);
        return $building;
    }

    public function deleteBuilding(Building $building): void
    {
        if ($building->document_path) {
            Storage::delete($building->document_path);
        }
        $building->delete();
    }
}
