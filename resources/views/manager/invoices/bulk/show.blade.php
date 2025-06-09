@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-4">
        <h6 class="mb-0 fw-bold text-white">
            <i class="bi bi-collection me-2"></i>جزئیات صورتحساب کلی ({{ $bulkInvoice->type === 'fixed' ? 'ثابت' : 'جاری' }})
        </h6>
        <a href="{{ route('bulk_invoices.index') }}" class="btn filter-btn">
            <i class="bi bi-arrow-right me-1"></i>بازگشت به لیست
        </a>
    </div>

    <div class="admin-table-card p-4">
        <div class="row">
            @if($bulkInvoice->type === 'fixed')
            <div class="col-md-6 mb-4">
                <div class="compact-info-card h-100 mx-2">
                    <i class="bi bi-card-text icon"></i>
                    <div class="w-100">
                        <div class="d-flex align-items-center">
                            <span class="label me-3">عنوان صورتحساب:</span>
                            <span class="value">{{ $bulkInvoice->title }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-6 mb-4">
                <div class="compact-info-card h-100 mx-2">
                    <i class="bi bi-tag icon"></i>
                    <div class="w-100">
                        <div class="d-flex align-items-center">
                            <span class="label me-3">نوع صورتحساب:</span>
                            <span class="badge bg-{{ $bulkInvoice->type === 'fixed' ? 'primary' : 'info' }}">
                                {{ $bulkInvoice->type === 'fixed' ? 'ثابت' : 'جاری' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="compact-info-card h-100 mx-2">
                    <i class="bi bi-cash-stack icon"></i>
                    <div class="w-100">
                        <div class="d-flex align-items-center">
                            <span class="label me-3">مبلغ پایه:</span>
                            <span class="value">{{ number_format($bulkInvoice->base_amount) }} تومان</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="compact-info-card h-100 mx-2">
                    <i class="bi bi-calendar-check icon"></i>
                    <div class="w-100">
                        <div class="d-flex align-items-center">
                            <span class="label me-3">تاریخ سررسید:</span>
                            <span class="value">{{ jdate($bulkInvoice->due_date)->format('Y/m/d') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="compact-info-card mb-4 mx-2">
            <i class="bi bi-chat-text icon"></i>
            <div class="w-100">
                <div class="d-flex align-items-start">
                    <span class="label me-3">توضیحات:</span>
                    <span class="value">{{ $bulkInvoice->description ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="mt-4 mx-2">
            <h6 class="fw-bold text-dark mb-3">لیست واحدهایی که این صورتحساب برایشان صادر شده:</h6>
            <div class="table-responsive">
                <table class="table table-units">
                    <thead>
                        <tr>
                            <th>شماره واحد</th>
                            <th>مبلغ کل</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bulkInvoice->invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->unit->unit_number ?? $invoice->unit->id }}</td>
                                <td>{{ number_format($invoice->amount) }} تومان</td>
                                <td>
                                    <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'danger') }}">
                                        {{ $invoice->status === 'paid' ? 'پرداخت شده' : ($invoice->status === 'partial' ? 'پرداخت جزئی' : 'پرداخت نشده') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
