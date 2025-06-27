@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="admin-header d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 fw-bold text-white">
                <i class="bi bi-receipt me-2"></i>جزئیات پرداخت {{ $payment->invoice->id }}
            </h6>
        </div>

        <div class="admin-table-card p-4">
            <div class="compact-info-card mb-4 mx-3">
                <i class="bi bi-card-text icon"></i>
                <div class="w-100">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center">
                            <span class="label me-3">عنوان پرداخت:</span>
                            <span class="value">{{ $payment->invoice->title }}</span>
                        </div>
                        <div class="d-flex align-items-start">
                            <span class="label me-3">توضیحات:</span>
                            <span class="value">{{ $payment->invoice->description ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="compact-info-card mb-4 mx-3">
                <i class="bi bi-calendar-check icon"></i>
                <div class="w-100">
                    <div class="d-flex flex-wrap gap-4">
                        <div class="d-flex align-items-center">
                            <span class="label me-3">تاریخ پرداخت:</span>
                            <span class="value">{{ jdate($payment->paid_at)->format('Y/m/d') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="compact-info-card mx-3">
                <i class="bi bi-cash-stack icon"></i>
                <div class="w-100">
                    <div class="d-flex align-items-center">
                        <span class="label me-3">مبلغ :</span>
                        <span class="value">{{ number_format($payment->amount) }} تومان</span>
                    </div>
                </div>
            </div>
            <br>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('resident.payments.receipt', $payment->id) }}" target="_blank" class="btn cancel-btn">
                    <i class="bi me-1"></i>چاپ رسید
                </a>
                <a href="{{ route('resident.payments.index') }}" class="btn cancel-btn">
                    <i class="bi bi-arrow-right me-1"></i>بازگشت
                </a>
            </div>
            
        </div>
    </div>
    </div>
@endsection
