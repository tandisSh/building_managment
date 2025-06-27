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

        $Invoices = Invoice::with('unit.users')
            ->where('status', 'unpaid')
            ->whereDate('due_date', '<=', now()->addDays(7))
            ->orderBy('due_date')
            ->take(5)->get();

        return view('resident.dashboard', compact('Invoices'));
    }
}


