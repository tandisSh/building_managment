<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'building_id',
        'unit_number',
        'floor',
        'area',
        'parking_slots',
        'storerooms'
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role', 'from_date', 'to_date');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function repairRequests()
    {
        return $this->hasMany(RepairRequest::class);
    }
}
