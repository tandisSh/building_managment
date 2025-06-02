<?php
namespace App\Services\Resident\Payment;

use App\Models\User;
use App\Models\Payment;

class PaymentService
{


 public function getResidentPayments($resident)
    {
        return Payment::whereHas('unit.building', function ($q) use ($manager) {
            $q->where('manager_id', $manager->id);
        })->with('unit')->latest()->get();
    }




}
