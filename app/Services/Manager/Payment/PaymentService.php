<?php

namespace App\Services\Manager\Payment;

use App\Models\Payment;
use App\Models\User;

class PaymentService
{
    public function getPaymentsForManager($manager, array $filters = [])
    {
        $buildingId = $manager->buildingUser->building_id ?? null;

        if (!$buildingId) {
            return collect(); // یا paginate خالی
        }

        $userIds = User::whereHas('units', function ($q) use ($buildingId) {
            $q->where('building_id', $buildingId);
        })->pluck('id');

        $query = Payment::with(['user', 'invoice.unit'])
            ->whereIn('user_id', $userIds);

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhereHas('invoice', function ($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                });
            });
        }

        return $query->latest()->paginate(20);
    }
}
