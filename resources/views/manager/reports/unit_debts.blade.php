@extends('layouts.app')

@section('title', 'گزارش بدهی واحدها')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark text-center"><i class="bi bi-exclamation-triangle"></i> گزارش بدهی واحدها - {{ $building->name ?? 'نامشخص' }}</h6>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered align-middle text-center table-units">
                <thead>
                    <tr>
                        <th>شماره واحد</th>
                        <th>تعداد صورتحساب پرداخت‌نشده</th>
                        <th>جمع بدهی (تومان)</th>
                        <th>نزدیک‌ترین سررسید</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td>{{ $unit['unit_number'] }}</td>
                            <td>{{ $unit['debt_count'] }}</td>
                            <td>{{ number_format($unit['total_debt']) }}</td>
                            <td>{{ $unit['next_due'] ? jdate($unit['next_due'])->format('Y/m/d') : '---' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">هیچ بدهی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
