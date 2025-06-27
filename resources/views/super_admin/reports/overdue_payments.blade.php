@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-clock-history"></i> گزارش پرداخت‌های معوق</h6>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="date" name="start_date" class="form-control form-control-sm search-input"
                        value="{{ request('start_date') }}" placeholder="از تاریخ" style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <input type="date" name="end_date" class="form-control form-control-sm search-input"
                        value="{{ request('end_date') }}" placeholder="تا تاریخ" style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <select name="building_id" class="form-control form-control-sm search-input" style="max-width: 200px;">
                        <option value="">همه ساختمان‌ها</option>
                        @foreach(\App\Models\Building::all() as $building)
                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">فیلتر</button>
                    <a href="{{ route('superadmin.reports.overdue_payments') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center text-center">
        <div>
            <strong>جمع کل مبلغ معوق:</strong> {{ number_format($totalOverdueAmount) }} تومان
        </div>
        <div>
            <a href="{{ route('superadmin.reports.overdue_payments.print', request()->query()) }}" target="_blank"
                class="btn btn-info btn-sm">چاپ گزارش</a>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            @if ($overdueInvoices->count() > 0)
                <table class="table table-bordered table-striped align-middle text-center table-units">
                    <thead>
                        <tr>
                            <th>واحد</th>
                            <th>مبلغ</th>
                            <th>تاریخ سررسید</th>
                            <th>روزهای معوق</th>
                            <th>ساختمان</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($overdueInvoices as $item)
                            <tr>
                                <td>{{ $item['unit_number'] }}</td>
                                <td>{{ number_format($item['amount']) }} تومان</td>
                                <td>{{ $item['invoice']->due_date->format('Y/m/d') }}</td>
                                <td>{{ $item['days_overdue'] }}</td>
                                <td>{{ $item['invoice']->unit->building->name ?? '---' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">
                    هیچ پرداخت معوق یافت نشد.
                </div>
            @endif
        </div>
    </div>
@endsection
