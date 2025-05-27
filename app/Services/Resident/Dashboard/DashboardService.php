<?php

namespace App\Services\Resident\Dashboard;

use App\Models\User;

class DashboardService
{
 public function getDashboardData(User $user): array
{
    $unit = $user->unit;

    $invoices = collect();

    if ($unit) {
        $invoices = $unit->invoices()->where('status', 'unpaid')->latest()->take(5)->get();
    }

    return [
        'user' => $user,
        'unit' => $unit,
        'invoices' => $invoices,
    ];
}

}
