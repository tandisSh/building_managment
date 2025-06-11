@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-bar-chart-line"></i> گزارش مالی ماهانه</h6>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center text-center justify-content-center">
                <div class="col-auto">
                    <label for="month" class="form-label fw-bold">انتخاب ماه:</label>
                    <select name="month" id="month" class="form-select form-select-sm search-input"
                        style="min-width: 160px;">
                        <option value="">-- همه ماه‌ها --</option>
                        @foreach ($months as $month)
                            <option value="{{ $month }}" @selected(($selectedMonth ?? '') == $month)>
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn mt-4">اعمال فیلتر</button>
                </div>
            </form>
        </div>
    </div>

    @if (count($summary) > 0)
        <div class="card admin-table-card mb-4">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped align-middle text-center table-units">
                    <thead>
                        <tr>
                            <th>ماه</th>
                            <th>کل صورتحساب‌ها</th>
                            <th>پرداخت‌شده</th>
                            <th>بدهی باقی‌مانده</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($summary as $item)
                            <tr>
                                <td>{{ $item['month'] }}</td>
                                <td>{{ number_format($item['invoiced']) }} تومان</td>
                                <td class="text-success">{{ number_format($item['paid']) }} تومان</td>
                                <td class="text-danger">{{ number_format($item['unpaid']) }} تومان</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card p-3 shadow-sm">
            <canvas id="financialChart" height="200" class="w-100"></canvas>
        </div>
    @else
        <div class="alert alert-info text-center">داده‌ای برای نمایش وجود ندارد.</div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('financialChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($summary, 'month')) !!},
                datasets: [{
                        label: 'پرداخت‌شده',
                        data: {!! json_encode(array_column($summary, 'paid')) !!},
                        borderColor: 'green',
                        backgroundColor: 'rgba(0,128,0,0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'بدهی باقی‌مانده',
                        data: {!! json_encode(array_column($summary, 'unpaid')) !!},
                        borderColor: 'red',
                        backgroundColor: 'rgba(255,0,0,0.1)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('fa-IR') + ' تومان';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
