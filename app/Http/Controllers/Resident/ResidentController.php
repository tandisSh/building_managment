<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\Resident\Dashboard\DashboardService;
use Illuminate\Http\Request;

class ResidentController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        
        // دریافت واحدهای کاربر
        $userUnits = $user->unitUsers()->with('unit')->get();
        
        $invoices = collect();
        
        foreach ($userUnits as $userUnit) {
            $unitId = $userUnit->unit_id;
            $userRole = $userUnit->role; // resident یا owner
            
            // تعیین نوع صورتحساب بر اساس نقش کاربر
            $invoiceType = ($userRole === 'resident') ? 'current' : 'fixed';
            
            // دریافت صورتحساب‌های مربوط به این واحد و نوع
            $unitInvoices = Invoice::where('unit_id', $unitId)
                ->where('type', $invoiceType)
                ->where('status', 'unpaid')
                ->whereDate('due_date', '<=', now()->addDays(7))
                ->orderBy('due_date')
                ->get();
            
            $invoices = $invoices->merge($unitInvoices);
        }
        
        // مرتب‌سازی بر اساس تاریخ سررسید و محدود کردن به 5 مورد
        $Invoices = $invoices->sortBy('due_date')->take(5);

        return view('resident.dashboard', compact('Invoices'));
    }
}


