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

        return BuildingRequest::create([
            'user_id' => $user->id,
            'building_name' => $data['building_name'],
            'address' => $data['address'],
            'number_of_floors' => $data['number_of_floors'],
            'number_of_units' => $data['number_of_units'],
            'shared_utilities' => $data['shared_utilities'],
            'document_path' => $path,
        ]);
    }
}
