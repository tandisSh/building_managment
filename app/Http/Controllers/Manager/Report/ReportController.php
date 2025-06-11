<?php

namespace App\Http\Controllers\Manager\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Manager\Report\ReportService;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'unit_number']);
        $data = $this->reportService->getPaymentReport($filters);

        return view('manager.reports.index', $data);
    }

    public function invoices(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'status', 'type', 'unit_id']);
        $data = $this->reportService->getInvoiceReport($filters);

        return view('manager.reports.invoice', $data);
    }

    public function unitDebts()
    {
        $data = $this->reportService->getUnitDebtReport();

        return view('manager.reports.unit_debts', $data);
    }

    public function print(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'status', 'type', 'unit_id']);
        $data = $this->reportService->getInvoiceReport($filters);

        return view('manager.reports.print.invoice', $data);
    }
    public function Paymentprint(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'unit_number']);
        $data = $this->reportService->getPaymentReport($filters);

        return view('manager.reports.print.payment', $data);
    }

    public function overduePayments(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'unit_number']);
        $data = $this->reportService->getOverduePaymentsReport($filters);

        return view('manager.reports.overdue_payments', $data);
    }

    public function financialOverview(Request $request, ReportService $reportService)
    {
        $buildingId = auth()->user()->buildingUser?->building_id;

        if (!$buildingId) {
            abort(403, 'شما به هیچ ساختمانی متصل نیستید.');
        }

        $summary = $reportService->getMonthlyFinancialSummary($buildingId);

        // اگر ماه خاصی انتخاب شده باشد، فقط همان را نگه داریم
        if ($request->filled('month')) {
            $summary = array_filter($summary, fn($item) => $item['month'] === $request->month);
        }

        return view('manager.reports.financial-overview', [
            'summary' => $summary,
            'months' => array_unique(array_column($reportService->getMonthlyFinancialSummary($buildingId), 'month')),
            'selectedMonth' => $request->month,
        ]);
    }

    public function ResidentAccountStatus(Request $request)
    {
        $data = $this->reportService->getResidentAccountStatusReport($request);
        return view('manager.reports.resident_account_status', $data);
    }
}
