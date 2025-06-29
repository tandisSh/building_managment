<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'unit_id',
        'bulk_invoice_id',
        'amount',
        'due_date',
        'status',
        'type',
        'title'
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function scopeCurrent($query)
    {
        return $query->where('type', 'current');
    }

    public function scopeFixed($query)
    {
        return $query->where('type', 'fixed');
    }
    public function bulkInvoice()
    {
        return $this->belongsTo(BulkInvoice::class);
    }
    public function calculateStatus(): string
    {
        return match (true) {
            $this->status === 'paid' => 'paid',
            default => 'unpaid',
        };
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isDeletable(): bool
    {
        return $this->status !== 'paid';
    }
}
