<?php

namespace App\Services\Manager\Invoice;

use App\Models\BulkInvoices;
use App\Models\User;

class BulkInvoiceService
{
 public function create(User $manager, array $data): BulkInvoices
{
    $building = $manager->building;

    return BulkInvoices::create([
        'building_id' => $building->id,
        'base_amount' => $data['base_amount'],
        'water_cost' => $data['water_cost'] ?? null,
        'electricity_cost' => $data['electricity_cost'] ?? null,
        'gas_cost' => $data['gas_cost'] ?? null,
        'due_date' => $data['due_date'],
        'description' => $data['description'] ?? null,
        'type' => $data['type'],
        'fixed_title' => $data['fixed_title'] ?? null,
    ]);
}

}
