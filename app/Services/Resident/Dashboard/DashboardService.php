<?php

namespace App\Services\Resident\Dashboard;

use App\Models\Invoice;
use App\Models\User;

class DashboardService
{
 public function getDashboardData(User $user)
{

 $upcomingInvoices = Invoice::with('unit.users')
            ->where('status', 'unpaid')
            ->whereDate('due_date', '<=', now()->addDays(7))
            ->orderBy('due_date')
            ->get();

            return $upcomingInvoices;
}
}
