<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable =
    [
        'manager_id',
        'name',
        'address'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
