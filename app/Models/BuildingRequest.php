<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingRequest extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'province',
        'city',
        'shared_water',
        'shared_electricity',
        'shared_gas',
        'number_of_floors',
        'number_of_units',
        'document_path',
        'status',
        'rejection_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
