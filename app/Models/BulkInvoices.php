<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkInvoices extends Model
{
    protected $fillable = [
        'building_id', 'base_amount', 'water_cost', 'electricity_cost',
        'gas_cost', 'type', 'due_date', 'description'
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
