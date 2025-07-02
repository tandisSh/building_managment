<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialFeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'amount',
        'status',
        'transaction_id',
        'paid_at',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
} 