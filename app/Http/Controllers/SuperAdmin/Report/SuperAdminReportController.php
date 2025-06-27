<?php

namespace App\Http\Controllers\SuperAdmin\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\Report\ReportService;

class SuperAdminReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function overallPayments(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'building_id']);
        $data = $this->reportService->getOverallPaymentReport($filters);

        return view('super_admin.reports.overall_payments', $data);
    }

    public function aggregateInvoices(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status', 'building_id']);
        $data = $this->reportService->getAggregateInvoiceReport($filters);

        return view('super_admin.reports.aggregate_invoices', $data);
    }

    public function systemDebts()
    {
        $data = $this->reportService->getSystemDebtReport();

        return view('super_admin.reports.system_debts', $data);
    }

    public function systemOverduePayments(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'building_id']);
        $data = $this->reportService->getSystemOverduePaymentsReport($filters);

        return view('super_admin.reports.overdue_payments', $data);
    }

    public function annualFinancialSummary(Request $request)
    {
        $filters = $request->only(['year']);
        $summary = $this->reportService->getAnnualFinancialSummary($filters);

        return view('super_admin.reports.annual_summary', [
            'summary' => $summary,
            'years' => array_unique(array_column($this->reportService->getAnnualFinancialSummary([]), 'year')),
            'selectedYear' => $request->year,
        ]);
    }

    // متدهای چاپ
    public function printOverallPayments(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'building_id']);
        $data = $this->reportService->getOverallPaymentReport($filters);

        return view('super_admin.reports.print.overall_payments', $data);
    }

    public function printAggregateInvoices(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'status', 'building_id']);
        $data = $this->reportService->getAggregateInvoiceReport($filters);

        return view('super_admin.reports.print.aggregate_invoices', $data);
    }

    public function printSystemDebts()
    {
        $data = $this->reportService->getSystemDebtReport();

        return view('super_admin.reports.print.system_debts', $data);
    }

    public function printOverduePayments(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'building_id']);
        $data = $this->reportService->getSystemOverduePaymentsReport($filters);

        return view('super_admin.reports.print.overdue_payments', $data);
    }

    public function printAnnualSummary(Request $request)
    {
        $filters = $request->only(['year']);
        $summary = $this->reportService->getAnnualFinancialSummary($filters);

        return view('super_admin.reports.print.annual_summary', [
            'summary' => $summary,
        ]);
    }
}
