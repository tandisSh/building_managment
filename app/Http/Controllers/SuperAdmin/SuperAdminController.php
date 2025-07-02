<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Requests\RejectBuildingRequest;
use App\Http\Controllers\Controller;
use App\Mail\BuildingRequestApproved;
use App\Models\BuildingRequest;
use App\Models\Building;
use App\Models\InitialFeePayment;
use App\Models\Payment;
use App\Services\Admin\Report\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SuperAdminController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function initialPayments()
    {
        $payments = InitialFeePayment::with('building.manager')->latest()->paginate(20);
        return view('super_admin.payments.initial_payments_index', compact('payments'));
    }

    public function dashboard()
    {
        // دریافت آمار کلی سیستم
        $stats = $this->reportService->getSystemStatisticsReport();
        
        // دریافت آخرین درخواست‌های ساختمان
        $recentBuildingRequests = BuildingRequest::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // دریافت آخرین پرداخت‌ها
        $recentPayments = Payment::with('user')
            ->where('status', 'success')
            ->latest('paid_at')
            ->take(5)
            ->get();
        
        // آمار ماهانه برای نمودار
        $monthlyStats = $this->getMonthlyStats();

        return view('super_admin.dashboard', compact(
            'stats',
            'recentBuildingRequests',
            'recentPayments',
            'monthlyStats'
        ));
    }

    private function getMonthlyStats()
    {
        $labels = [];
        $users = [];
        $buildings = [];
        $payments = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('Y/m');
            
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $users[] = \App\Models\User::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $buildings[] = Building::whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $payments[] = Payment::where('status', 'success')
                ->whereBetween('paid_at', [$monthStart, $monthEnd])
                ->count();
        }

        return [
            'labels' => $labels,
            'users' => $users,
            'buildings' => $buildings,
            'payments' => $payments,
        ];
    }

    public function requests()
    {
        $requests = BuildingRequest::with('user')->latest()->paginate(20);
        return view('super_admin.requests', compact('requests'));
    }

    public function approveRequest($id)
    {
        $req = BuildingRequest::findOrFail($id);

        try {
            DB::transaction(function () use ($req) {
                $building = Building::create([
                    'manager_id' => $req->user_id,
                    'name' => $req->name,
                    'address' => $req->address,
                    'province' => $req->province,
                    'city' => $req->city,
                    'shared_electricity' => $req->shared_electricity,
                    'shared_water' => $req->shared_water,
                    'shared_gas' => $req->shared_gas,
                    'number_of_floors' => $req->number_of_floors,
                    'number_of_units' => $req->number_of_units,
                ]);

                InitialFeePayment::create([
                    'building_id' => $building->id,
                    'amount' => 500000.00,
                    'status' => 'pending',
                ]);

                $building->users()->attach($req->user_id, ['role' => 'manager']);

                $req->update(['status' => 'approved']);
            });
        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Building approval failed: ' . $e->getMessage());
            return back()->with('error', 'خطایی در هنگام تأیید درخواست رخ داد. لطفاً دوباره تلاش کنید.');
        }

        return back()->with('success', 'درخواست تأیید شد و ایمیل اطلاع‌رسانی برای مدیر ارسال گردید.');
    }

    public function rejectRequest(RejectBuildingRequest $request, $id)
    {
        BuildingRequest::findOrFail($id)
            ->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason
            ]);

        return back()->with('success', 'درخواست رد شد');
    }
}
