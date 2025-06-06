<?php
namespace App\Services\Resident\Invoice;

use App\Models\User;

class InvoiceService
{

    public function getUserInvoices(User $user, bool $onlyUnpaid = false)
{
    $invoices = [];

    foreach ($user->units as $unit) {
        $role = $unit->pivot->role;

        $query = $unit->invoices();

        if ($role === 'owner') {
            $query->where('type', 'fixed');
        } elseif ($role === 'resident') {
            $query->where('type', 'current');
        }

        if ($onlyUnpaid) {
            $query->where('status', 'unpaid');
        }

        $invoices[$unit->id] = [
            'unit'     => $unit,
            'role'     => $role,
            'invoices' => $query->latest()->get()
        ];
    }

    return $invoices;
}

}

