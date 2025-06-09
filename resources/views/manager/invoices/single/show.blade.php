@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold text-white">
            <i class="bi bi-receipt me-2"></i>جزئیات صورتحساب واحد {{ $invoice->unit->id }}
        </h6>
        <a href="{{ route('manager.invoices.index') }}" class="btn filter-btn">
            <i class="bi bi-arrow-right me-1"></i>بازگشت به لیست
        </a>
    </div>

    <div class="admin-table-card p-4">
        <div class="compact-info-card mb-4 mx-3">
            <i class="bi bi-card-text icon"></i>
            <div class="w-100">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center">
                        <span class="label me-3">عنوان صورتحساب:</span>
                        <span class="value">{{ $invoice->title }}</span>
                    </div>
                    <div class="d-flex align-items-start">
                        <span class="label me-3">توضیحات:</span>
                        <span class="value">{{ $invoice->description ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="compact-info-card mb-4 mx-3">
            <i class="bi bi-calendar-check icon"></i>
            <div class="w-100">
                <div class="d-flex flex-wrap gap-4">
                    <div class="d-flex align-items-center">
                        <span class="label me-3">تاریخ سررسید:</span>
                        <span class="value">{{ jdate($invoice->due_date)->format('Y/m/d') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="label me-3">وضعیت پرداخت:</span>
                        <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'danger') }}">
                            {{ $invoice->status === 'paid' ? 'پرداخت شده' : ($invoice->status === 'partial' ? 'پرداخت جزئی' : 'پرداخت نشده') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="compact-info-card mx-3">
            <i class="bi bi-cash-stack icon"></i>
            <div class="w-100">
                <div class="d-flex align-items-center">
                    <span class="label me-3">مبلغ کل:</span>
                    <span class="value">{{ number_format($invoice->amount) }} تومان</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
