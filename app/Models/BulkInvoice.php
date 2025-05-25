<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkInvoice extends Model
{
    protected $fillable = [
        'building_id',
        'title',
        'base_amount',
        'type',
        'due_date',
        'distribution_type',
        'fixed_percent',
        'description',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    protected $attributes = [
        'status' => 'pending',
    ];
}
