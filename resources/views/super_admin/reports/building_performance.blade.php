@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-graph-up"></i> گزارش عملکرد ساختمان‌ها</h6>
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
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">فیلتر</button>
                    <a href="{{ route('superadmin.reports.building_performance') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <!-- خلاصه کلی -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center bg-purple-300 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_buildings'] }}</h5>
                    <p class="card-text small">کل ساختمان‌ها</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-purple-500 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($summary['average_occupancy'], 1) }}%</h5>
                    <p class="card-text small">میانگین اشغال</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-purple-700 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($summary['average_payment_rate'], 1) }}%</h5>
                    <p class="card-text small">میانگین پرداخت</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-purple-900 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($summary['average_performance_score'], 1) }}</h5>
                    <p class="card-text small">امتیاز عملکرد</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-purple-100 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($summary['total_revenue']) }}</h5>
                    <p class="card-text small">کل درآمد (تومان)</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-danger text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($summary['total_overdue']) }}</h5>
                    <p class="card-text small">کل بدهی معوق (تومان)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center text-center">
        <div>
            <strong>تعداد ساختمان‌ها:</strong> {{ $buildings->count() }}
        </div>
        <div>
            <a href="{{ route('superadmin.reports.building_performance.print', request()->query()) }}" target="_blank"
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

            @if ($buildings->count() > 0)
                <table class="table table-bordered table-striped align-middle text-center table-units">
                    <thead>
                        <tr>
                            <th>رتبه</th>
                            <th>نام ساختمان</th>
                            <th>آدرس</th>
                            <th>استان</th>
                            <th>شهر</th>
                            <th>مدیر</th>
                            <th>واحدها</th>
                            <th>اشغال (%)</th>
                            <th>درآمد (تومان)</th>
                            <th>پرداخت (%)</th>
                            <th>بدهی معوق (تومان)</th>
                            <th>امتیاز عملکرد</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($buildings as $index => $building)
                            <tr>
                                <td>
                                    @if($index < 3)
                                        <span class="badge bg-warning text-dark">{{ $index + 1 }}</span>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>
                                <td>{{ $building['name'] }}</td>
                                <td>{{ $building['address'] }}</td>
                                <td>{{ $building['province'] ?? 'تعریف نشده' }}</td>
                                <td>{{ $building['city'] ?? 'تعریف نشده' }}</td>
                                <td>{{ $building['manager_name'] }}</td>
                                <td>{{ $building['occupied_units'] }}/{{ $building['total_units'] }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                             style="width: {{ $building['occupancy_rate'] }}%">
                                            {{ number_format($building['occupancy_rate'], 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($building['monthly_revenue']) }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-info" role="progressbar"
                                             style="width: {{ $building['payment_rate'] }}%">
                                            {{ number_format($building['payment_rate'], 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($building['total_overdue'] > 0)
                                        <span class="text-danger">{{ number_format($building['total_overdue']) }}</span>
                                    @else
                                        <span class="text-success">0</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                             style="width: {{ $building['performance_score'] }}%">
                                            {{ number_format($building['performance_score'], 1) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($building['status']) {
                                            'عالی' => 'bg-success',
                                            'خوب' => 'bg-info',
                                            'متوسط' => 'bg-warning',
                                            'ضعیف' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} text-center">
                                        {{ $building['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">
                    هیچ ساختمانی یافت نشد.
                </div>
            @endif
        </div>
    </div>
@endsection
