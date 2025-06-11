@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center">
            <i class="bi bi-clock-history"></i> گزارش پرداخت‌های معوق - {{ $building->name ?? 'نامشخص' }}
        </h6>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="date" name="date_from" class="form-control form-control-sm search-input"
                        value="{{ $filters['date_from'] ?? '' }}" placeholder="از تاریخ" style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <input type="date" name="date_to" class="form-control form-control-sm search-input"
                        value="{{ $filters['date_to'] ?? '' }}" placeholder="تا تاریخ" style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <input type="text" name="unit_number" class="form-control form-control-sm search-input"
                        value="{{ $filters['unit_number'] ?? '' }}" placeholder="شماره واحد" style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">فیلتر</button>
                    <a href="{{ route('reports.overduePayments') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center text-center">
        <div>
            <strong>مجموع بدهی معوق:</strong> {{ number_format($totalOverdueAmount) }} تومان
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if ($overdueInvoices->count() > 0)
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead>
                        <tr>
                            <th>واحد</th>
                            <th>مبلغ</th>
                            <th>تاریخ سررسید</th>
                            <th>تعداد روزهای تاخیر</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($overdueInvoices as $item)
                            <tr>
                                <td>{{ $item['unit_number'] }}</td>
                                <td>{{ number_format($item['amount']) }}</td>
                                <td>{{ jdate($item['invoice']->due_date)->format('Y/m/d') }}</td>
                                <td>{{ $item['days_overdue'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">هیچ صورتحساب معوقی یافت نشد.</div>
            @endif
        </div>
    </div>
@endsection
