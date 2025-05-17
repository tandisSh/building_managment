<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'unit_id',
        'bulk_invoice_id',
        'total_amount',
        'due_date',
        'status',
        'type'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
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
}
