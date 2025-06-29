<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    protected $fillable =
    [
        'unit_id',
        'user_id',
        'title',
        'description',
        'status'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isDeletable(): bool
    {
        return !in_array($this->status, ['approved', 'in_progress', 'done']);
    }
}
