<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'buildings';

    protected $fillable =
    [
        'manager_id',
        'name',
        'address',
        'shared_electricity',
        'shared_water',
        'shared_gas',
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
