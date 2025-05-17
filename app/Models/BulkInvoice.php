<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkInvoice extends Model
{
    protected $fillable = [
        'building_id', 'base_amount', 'water_cost', 'electricity_cost',
        'gas_cost', 'type', 'due_date', 'description', 'fixed_title'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}
