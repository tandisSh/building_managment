@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-receipt"></i> گزارش کلی پرداخت‌ها</h6>
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
                    <a href="{{ route('superadmin.reports.overall_payments') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center text-center">
        <div>
            <strong>جمع کل پرداخت‌ها:</strong> {{ number_format($totalAmount) }} تومان
        </div>
        <div>
            <a href="{{ route('superadmin.reports.overall_payments.print', request()->query()) }}" target="_blank"
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

            @if ($payments->count() > 0)
                <table class="table table-bordered table-striped align-middle text-center table-units">
                    <thead>
                        <tr>
                            <th>کاربر</th>
                            <th>واحد</th>
                            <th>مبلغ</th>
                            <th>تاریخ پرداخت</th>
                            <th>ساختمان</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $payment->user->name ?? '---' }}</td>
                                <td>{{ $payment->invoice->unit->unit_number ?? '---' }}</td>
                                <td>{{ number_format($payment->amount) }} تومان</td>
                                <td>{{ $payment->paid_at ? $payment->paid_at->format('Y/m/d') : '---' }}</td>
                                <td>{{ $payment->invoice->unit->building->name ?? '---' }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'success' ? 'success' : 'warning text-dark' }} text-center">
                                        {{ $payment->status === 'success' ? 'موفق' : 'ناموفق' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3 text-center">
                    {{ $payments->withQueryString()->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    هیچ پرداختی یافت نشد.
                </div>
            @endif
        </div>
    </div>
@endsection
