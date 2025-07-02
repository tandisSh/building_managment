@extends('layouts.app')

@section('content')
    <div class="container">

        @if (isset($initialPaymentPending) && $initialPaymentPending)
            <div class="alert alert-warning p-4 shadow-sm" role="alert">
                <h4 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i> حساب شما در انتظار فعال‌سازی
                    است!</h4>
                <p>برای دسترسی به تمامی امکانات پنل مدیریت و شروع به کار، لطفاً هزینه اولیه فعال‌سازی را پرداخت نمایید.</p>
                <hr>
                <p class="mb-0">
                    <a href="{{ route('manager.initial-payment.show') }}" class="btn btn-success fw-bold">
                        <i class="bi bi-credit-card me-2"></i>
                        رفتن به صفحه پرداخت
                    </a>
                </p>
            </div>
        @elseif (isset($buildingNotReady) && $buildingNotReady)
            <div class="alert alert-info p-4 shadow-sm" role="alert">
                <h4 class="alert-heading"><i class="bi bi-clock-history me-2"></i> در انتظار تایید ادمین</h4>
                <p>درخواست ثبت ساختمان شما ارسال شده و در حال بررسی توسط ادمین است. پس از تایید، به امکانات پنل دسترسی
                    خواهید داشت.</p>
            </div>
        @else
            <div class="row mb-4">
                @php
                    $cards = [
                        [
                            'bg' => 'bg-purple-300',
                            'icon' => 'bi-building',
                            'label' => 'تعداد واحدها',
                            'value' => $stats['unitCount'],
                        ],
                        [
                            'bg' => 'bg-purple-500',
                            'icon' => 'bi-people',
                            'label' => 'تعداد کاربران',
                            'value' => $stats['userCount'],
                        ],
                        [
                            'bg' => 'bg-purple-700',
                            'icon' => 'bi-receipt',
                            'label' => 'صورتحساب‌های این ماه',
                            'value' => $stats['invoiceCount'],
                        ],
                        [
                            'bg' => 'bg-purple-900',
                            'icon' => 'bi-currency-exchange',
                            'label' => 'پرداختی‌های این ماه',
                            'value' => number_format($stats['totalPaid']) . ' تومان',
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

            {{-- نمودارها --}}
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card shadow rounded">
                        <div class="card-body">
                            <h5 class="mb-3">مقایسه صورتحساب‌های صادر شده و پرداخت‌شده (۱۲ ماه گذشته)</h5>
                            <canvas id="invoicePaymentChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow rounded">
                        <div class="card-body">
                            <h5 class="mb-3">سهم آیتم‌های هزینه‌ای</h5>
                            <canvas id="expenseChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    @if (empty($initialPaymentPending) && empty($buildingNotReady))
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // نمودار صورتحساب vs پرداخت
                const ctx1 = document.getElementById('invoicePaymentChart');
                if (ctx1) {
                    new Chart(ctx1.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($monthlyChart['labels']) !!},
                            datasets: [{
                                    label: 'صورتحساب‌ها',
                                    data: {!! json_encode($monthlyChart['invoices']) !!},
                                    backgroundColor: 'rgba(255, 165, 0, 0.7)',
                                    borderRadius: 4
                                },
                                {
                                    label: 'پرداخت‌ها',
                                    data: {!! json_encode($monthlyChart['payments']) !!},
                                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
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
                }

                // نمودار دایره‌ای هزینه‌ها
                const ctx2 = document.getElementById('expenseChart');
                if (ctx2) {
                    new Chart(ctx2.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode($expenseChart['labels']) !!},
                            datasets: [{
                                data: {!! json_encode($expenseChart['values']) !!},
                                backgroundColor: ['#f39c12', '#3498db', '#9b59b6', '#2ecc71']
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
                }
            });
        </script>
    @endif
@endpush
