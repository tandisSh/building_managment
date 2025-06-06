<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Resident\Invoice\InvoiceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ResidentInvoiceController extends Controller
{
    protected $service;

    public function __construct(InvoiceService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $filters = $request->only(['title', 'status']);

        $units = $user->units ?? collect();
        $allInvoices = [];

        foreach ($units as $unit) {
            $role = $unit->pivot->role; // 'owner' یا 'resident'

            $query = $unit->invoices();

            // فقط صورتحساب‌های متناسب با نقش نمایش داده شوند
            if ($role === 'resident') {
                $query->where('type', 'current');
            } elseif ($role === 'owner') {
                $query->where('type', 'fixed');
            }

            // فیلترهای اضافه‌شده
            if (!empty($filters['title'])) {
                $query->where('title', 'like', '%' . $filters['title'] . '%');
            }

            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            $invoices = $query->get();

            if ($invoices->count()) {
                $allInvoices[] = [
                    'unit' => $unit,
                    'role' => $role,
                    'invoices' => $invoices,
                ];
            }
        }

        return view('resident.invoices.index', ['invoices' => $allInvoices]);
    }

    public function unpaid()
    {
        $user = Auth::user();
        $invoices = $this->service->getUserInvoices($user, onlyUnpaid: true);

        return view('resident.invoices.unpaid', compact('invoices'));
    }
    public function show($invoice)
    {
        $invoice = Invoice::with('unit')->findOrFail($invoice);
        return view('manager.invoices.single.show', compact('invoice'));
    }
}
