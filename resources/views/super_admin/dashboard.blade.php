@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            @php
                $cards = [
                    [
                        'bg' => 'bg-purple-300',
                        'icon' => 'bi-building',
                        'label' => 'کل ساختمان‌ها',
                        'value' => $stats['buildings']['total'],
                    ],
                    [
                        'bg' => 'bg-purple-500',
                        'icon' => 'bi-people',
                        'label' => 'کل کاربران',
                        'value' => $stats['users']['total'],
                    ],
                    [
                        'bg' => 'bg-purple-700',
                        'icon' => 'bi-receipt',
                        'label' => 'کل صورتحساب‌ها',
                        'value' => $stats['invoices']['total'],
                    ],
                    [
                        'bg' => 'bg-purple-900',
                        'icon' => 'bi-currency-exchange',
                        'label' => 'کل درآمد',
                        'value' => number_format($stats['payments']['total_amount']) . ' تومان',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-md-3 mb-3">
                    <div class="card text-dark {{ $card['bg'] }} shadow rounded">
                        <div class="card-body text-center">
                            <i class="bi {{ $card['icon'] }} fs-2"></i>
                            <div class="mt-2">{{ $card['label'] }}</div>
                            <h4 class="fw-bold">{{ $card['value'] }}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- کارت‌های آمار تفصیلی --}}
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-purple-100 shadow rounded">
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle text-purple-600 fs-2"></i>
                        <div class="mt-2">ساختمان‌های فعال</div>
                        <h5 class="fw-bold text-purple-600">{{ $stats['buildings']['active'] }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-purple-100 shadow rounded">
                    <div class="card-body text-center">
                        <i class="bi bi-person-check text-purple-600 fs-2"></i>
                        <div class="mt-2">کاربران فعال</div>
                        <h5 class="fw-bold text-purple-600">{{ $stats['users']['active'] }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-purple-100 shadow rounded">
                    <div class="card-body text-center">
                        <i class="bi bi-credit-card text-purple-600 fs-2"></i>
                        <div class="mt-2">پرداخت‌های موفق</div>
                        <h5 class="fw-bold text-purple-600">{{ $stats['payments']['successful'] }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-purple-100 shadow rounded">
                    <div class="card-body text-center">
                        <i class="bi bi-exclamation-triangle text-purple-600 fs-2"></i>
                        <div class="mt-2">صورتحساب‌های معوق</div>
                        <h5 class="fw-bold text-purple-600">{{ $stats['invoices']['overdue'] }}</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- نمودارها --}}
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="card shadow rounded">
                    <div class="card-body">
                        <h5 class="mb-3">آمار ماهانه سیستم (۶ ماه گذشته)</h5>
                        <canvas id="monthlyStatsChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow rounded">
                    <div class="card-body">
                        <h5 class="mb-3">توزیع کاربران</h5>
                        <canvas id="userDistributionChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- آخرین فعالیت‌ها --}}
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple-600 text-black">
                        <h6 class="mb-0"><i class="bi bi-clock-history"></i> آخرین درخواست‌های ساختمان</h6>
                    </div>
                    <div class="card-body">
                        @if($recentBuildingRequests->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentBuildingRequests as $request)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $request->building_name }}</strong>
                                            <br><small class="text-muted">{{ $request->user->name ?? 'نامشخص' }}</small>
                                        </div>
                                        <span class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'approved' ? 'success' : 'danger') }}">
                                            {{ $request->status === 'pending' ? 'در انتظار' : ($request->status === 'approved' ? 'تایید شده' : 'رد شده') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center">هیچ درخواستی یافت نشد.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow rounded">
                    <div class="card-header bg-purple-600 text-black">
                        <h6 class="mb-0"><i class="bi bi-credit-card"></i> آخرین پرداخت‌ها</h6>
                    </div>
                    <div class="card-body">
                        @if($recentPayments->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentPayments as $payment)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ number_format($payment->amount) }} تومان</strong>
                                            <br><small class="text-muted">{{ $payment->user->name ?? 'نامشخص' }}</small>
                                        </div>
                                        <small class="text-muted">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('Y/m/d') : '-' }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center">هیچ پرداختی یافت نشد.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // نمودار آمار ماهانه
        const ctx1 = document.getElementById('monthlyStatsChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyStats['labels']) !!},
                datasets: [{
                        label: 'کاربران جدید',
                        data: {!! json_encode($monthlyStats['users']) !!},
                        backgroundColor: 'rgba(147, 51, 234, 0.7)',
                        borderRadius: 4
                    },
                    {
                        label: 'ساختمان‌های جدید',
                        data: {!! json_encode($monthlyStats['buildings']) !!},
                        backgroundColor: 'rgba(168, 85, 247, 0.7)',
                        borderRadius: 4
                    },
                    {
                        label: 'پرداخت‌ها',
                        data: {!! json_encode($monthlyStats['payments']) !!},
                        backgroundColor: 'rgba(196, 181, 253, 0.7)',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => value.toLocaleString()
                        }
                    }
                }
            }
        });

        // نمودار توزیع کاربران
        const ctx2 = document.getElementById('userDistributionChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['سوپر ادمین', 'مدیر', 'ساکن'],
                datasets: [{
                    data: [
                        {{ $stats['users']['super_admins'] }},
                        {{ $stats['users']['managers'] }},
                        {{ $stats['users']['residents'] }}
                    ],
                    backgroundColor: ['#7c3aed', '#a855f7', '#c4b5fd']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endpush
