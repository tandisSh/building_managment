<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'unit_id', 'bulk_invoice_id', 'total_amount',
        'due_date', 'status', 'type'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function bulkInvoice()
    {
        return $this->belongsTo(BulkInvoices::class);
    }

    public function calculateStatus()
    {
        $totalPaid = $this->items->sum('paid_amount');

        return match (true) {
            $totalPaid == 0 => 'unpaid',
            $totalPaid >= $this->total_amount => 'paid',
            default => 'partial',
        };
    }
}
