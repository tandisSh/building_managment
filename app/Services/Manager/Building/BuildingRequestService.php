<?php

namespace App\Services\Manager\Building;

use App\Models\BuildingRequest;
use App\Models\User;

class BuildingRequestService
{
    public function getRequestsForUser($userId)
    {
        return BuildingRequest::where('user_id', $userId)->get();
    }

    public function hasPendingOrApproved($userId): bool
    {
        return BuildingRequest::where('user_id', $userId)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
    }

    public function storeRequest(User $user, array $data, $file)
    {
        $path = $file->store('building_documents');
// dd($data);
        return BuildingRequest::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'address' => $data['address'],
            'province' => $data['province'],
            'city' => $data['city'],
            'number_of_floors' => $data['number_of_floors'],
            'number_of_units' => $data['number_of_units'],
            'shared_electricity' => !empty($data['shared_electricity']),
            'shared_water' => !empty($data['shared_water']),
            'shared_gas' => !empty($data['shared_gas']),

        ]);
    }
}
