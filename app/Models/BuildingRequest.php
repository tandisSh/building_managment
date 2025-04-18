<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingRequest extends Model
{
    protected $fillable = [
        'user_id',
        'building_name',
        'address',
        'document_path',
        'status',
        'rejection_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
