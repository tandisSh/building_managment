<?php

namespace App\Services\Admin\Building;

use App\Models\Building;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuildingService
{
    public function getFilteredBuildings(Request $request)
    {
        $query = Building::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%")
                    ->orWhere('province', 'like', "%$search%")
                    ->orWhere('city', 'like', "%$search%");
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

    /**
     * گرفتن مدیرانی که به ساختمان دیگری اختصاص ندارند، به جز مدیر فعلی (برای فرم ایجاد یا ویرایش ساختمان)
     */
    public function getAvailableManagersForAssign(?int $currentManagerId = null)
    {
        $assignedManagerIds = Building::whereNotNull('manager_id')
            ->when($currentManagerId, function ($query) use ($currentManagerId) {
                $query->where('manager_id', '!=', $currentManagerId);
            })
            ->pluck('manager_id')
            ->toArray();

        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'manager');
        })->whereNotIn('id', $assignedManagerIds);

        if ($currentManagerId) {
            $query->orWhere('id', $currentManagerId);
        }

        return $query->orderBy('name')->get();
    }
}
