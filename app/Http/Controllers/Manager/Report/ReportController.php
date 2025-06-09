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
}
