@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-exclamation-triangle"></i> گزارش کل بدهی‌ها</h6>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center text-center">
        <div>
            <strong>جمع کل بدهی‌ها:</strong> {{ number_format($totalDebt) }} تومان
        </div>
        <div>
            <a href="{{ route('superadmin.reports.system_debts.print') }}" target="_blank"
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

            @if ($units->count() > 0)
                <table class="table table-bordered table-striped align-middle text-center table-units">
                    <thead>
                        <tr>
                            <th>شماره واحد</th>
                            <th>ساختمان</th>
                            <th>تعداد بدهی</th>
                            <th>مبلغ کل بدهی</th>
                            {{-- <th>نزدیک‌ترین سررسید</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $unit)
                            <tr>
                                <td>{{ $unit['unit_number'] ?? '---' }}</td>
                                <td>{{ \App\Models\Building::find($unit['building_id'])->name ?? '---' }}</td>
                                <td>{{ $unit['debt_count'] }}</td>
                                <td>{{ number_format($unit['total_debt']) }} تومان</td>
                                {{-- <td>{{ $unit['next_due'] ? parse($unit['next_due'])->format('Y/m/d') : '---' }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">
                    هیچ بدهی یافت نشد.
                </div>
            @endif
        </div>
    </div>
@endsection
