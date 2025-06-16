<?php

namespace App\Services\Admin\Payment;

use App\Models\Payment;

class PaymentService
{
    public function getPaymentsForSuperAdmin(array $filters = [])
    {
        $query = Payment::with(['user', 'invoice.unit']);

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

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->get();
    }
}
