@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-bar-chart"></i> خلاصه مالی سالانه</h6>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <select name="year" class="form-control form-control-sm search-input" style="max-width: 200px;">
                        <option value="">همه سال‌ها</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear === $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">فیلتر</button>
                    <a href="{{ route('superadmin.reports.annual_summary') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
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

            @if (!empty($summary))
                <table class="table table-bordered table-striped align-middle text-center table-units">
                    <thead>
                        <tr>
                            <th>سال</th>
                            <th>ماه</th>
                            <th>مجموع صورتحساب</th>
                            <th>پرداخت‌شده</th>
                            <th>باقی‌مانده</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($summary as $item)
                            <tr>
                                <td>{{ $item['year'] }}</td>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y/m', $item['year'] . '/' . $item['month'])->startOfMonth()->format('F') }}</td>
                                <td>{{ number_format($item['invoiced']) }} تومان</td>
                                <td>{{ number_format($item['paid']) }} تومان</td>
                                <td>{{ number_format($item['unpaid']) }} تومان</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">
                    هیچ داده‌ای برای نمایش وجود ندارد.
                </div>
            @endif
        </div>
    </div>
    <div class="mt-3 text-center">
        <a href="{{ route('superadmin.reports.annual_summary.print', request()->query()) }}" target="_blank"
            class="btn btn-info">چاپ گزارش</a>
    </div>
@endsection
