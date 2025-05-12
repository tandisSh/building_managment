<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable =
    [
        'manager_id',
        'building_name',
        'address',
        'shared_electricity',
        'shared_water',
        'shared_gas',
        // 'has_elevator', 'has_guard', 'has_cctv',
        'number_of_floors',
        'number_of_units',
        'is_residential'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
