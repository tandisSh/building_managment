<?php

namespace App\Services\Manager\Invoice;

use App\Models\BulkInvoice;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class BulkInvoiceService
{
    public function create(User $manager, array $data): BulkInvoice
    {
        $building = $manager->building;

        return BulkInvoice::create([
            'building_id' => $building->id,
            'title' => $data['title'],
            'base_amount' => $data['base_amount'],
            'due_date' => $data['due_date'],
            'description' => $data['description'] ?? null,
            'type' => $data['type'],
            'distribution_type' => $data['distribution_type'] ?? 'equal', // این خط اضافه شد
            'fixed_percent' => $data['fixed_percent'] ?? null,
            'status' => 'pending',
        ]);
    }

    public function getBulkInvoicesByManager(User $manager, array $filters = [])
    {
        $query = BulkInvoice::whereHas('building', function ($q) use ($manager) {
            $q->where('manager_id', $manager->id);
        });

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('title', 'like', "%{$search}%");
        }

        if (!empty($filters['status'])) {
            $status = $filters['status'];
            if ($status === 'approved') {
                $query->where('status', 'approved');
            } elseif ($status === 'pending') {
                $query->where('status', '!=', 'approved');
            }
        }

        return $query->latest()->paginate(20);
    }


    public function markAsApproved(BulkInvoice $bulkInvoice)
    {
        $bulkInvoice->status = 'approved';
        $bulkInvoice->save();
    }
    public function updateBulkInvoice(BulkInvoice $bulkInvoice, array $data)
    {
        if ($bulkInvoice->status !== 'pending') {
            throw new \Exception('این صورتحساب کلی قبلاً تایید شده و قابل ویرایش نیست.');
        }
        $bulkInvoice->update([
            'title' => $data['title'],
            'base_amount' => $data['base_amount'],
            'due_date' => $data['due_date'],
            'type' => $data['type'],
            'description' => $data['description'] ?? null,
            'distribution_type' => $data['distribution_type'] ?? 'equal',
            'fixed_precent' => $data['fixed_precent'] ?? null,
           

        ]);

    }
}
