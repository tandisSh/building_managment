@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-person-lines-fill"></i> گزارش وضعیت حساب ساکنین -
            {{ $building->name ?? 'نامشخص' }}</h6>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="text" name="search" class="form-control form-control-sm search-input"
                        value="{{ request('search') }}" placeholder="جستجو بر اساس نام" style="max-width: 250px;">
                </div>
                <div class="col-auto">
                    <input type="date" name="date_from" class="form-control form-control-sm search-input"
                        value="{{ request('date_from') }}" placeholder="از تاریخ" style="max-width: 150px;">
                </div>
                <div class="col-auto">
                    <input type="date" name="date_to" class="form-control form-control-sm search-input"
                        value="{{ request('date_to') }}" placeholder="تا تاریخ" style="max-width: 150px;">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">اعمال فیلتر</button>
                    <a href="{{ route('reports.ResidentAccountStatus') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if ($residents->count() > 0)
                <table class="table table-bordered table-striped align-middle text-center table-units">
                    <thead>
                        <tr>
                            <th>نام ساکن</th>
                            <th>واحدها</th>
                            <th>مجموع بدهی (تومان)</th>
                            <th>مجموع پرداختی (تومان)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($residents as $resident)
                            <tr>
                                <td>{{ $resident['resident_name'] }}</td>
                                <td>
                                    @foreach ($resident['units'] as $unitNumber)
                                        <span class="badge bg-secondary mx-1">{{ $unitNumber }}</span>
                                    @endforeach
                                </td>
                                <td>{{ number_format($resident['total_debt']) }}</td>
                                <td>{{ number_format($resident['total_paid']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">
                    هیچ داده‌ای یافت نشد.
                </div>
            @endif
        </div>
    </div>
@endsection
