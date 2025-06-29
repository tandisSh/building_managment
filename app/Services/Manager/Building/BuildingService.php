<?php

namespace App\Services\Manager\Building;

use App\Models\Building;
use Illuminate\Support\Facades\Storage;

class BuildingService
{
    public function createBuilding(array $data)
    {
        $path = $data['document']->store('building_documents');

        return Building::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'address' => $data['address'],
            'province' => $data['province'],
            'city' => $data['city'],
            'number_of_floors' => $data['number_of_floors'],
            'number_of_units' => $data['number_of_units'],
            'shared_electricity' => !empty($data['shared_electricity']),
            'shared_water' => !empty($data['shared_water']),
            'shared_gas' => !empty($data['shared_gas']),
            'document_path' => $path,
        ]);
    }

    public function updateBuilding(Building $building, array $data)
    {
        // پردازش فایل اگر وجود دارد
        if (request()->hasFile('document')) {
            // حذف فایل قبلی
            if ($building->document_path) {
                Storage::delete($building->document_path);
            }
            // ذخیره فایل جدید
            $data['document_path'] = request()->file('document')->store('building_documents');
        }

        $building->update($data);
        return $building;
    }
}
