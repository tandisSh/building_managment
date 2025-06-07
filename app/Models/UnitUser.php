<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UnitUser extends Pivot
{
    protected $table = 'unit_user';

    protected $fillable =
    [
        'unit_id',
        'user_id',
        'role',
        'resident_count',
        'status',
        'from_date',
        'to_date',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
