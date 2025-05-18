@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-receipt"></i> جزئیات صورتحساب واحد {{ $invoice->unit->id }}</h5>
        <a href="{{ route('manager.invoices.index') }}" class="btn btn-sm btn-secondary">
            بازگشت به لیست
        </a>
    </div>

    <div class="card-body">
        <div class="mb-3">
            <strong>عنوان صورتحساب:</strong>
            {{ $invoice->title }}
        </div>

        <div class="mb-3">
            <strong>توضیحات:</strong>
            {{ $invoice->description ?? '-' }}
        </div>

        <div class="mb-3">
            <strong>تاریخ سررسید:</strong>
            {{ jdate($invoice->due_date)->format('Y/m/d') }}
        </div>

        <div class="mb-3">
            <strong>وضعیت پرداخت:</strong>
            <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partial' ? 'warning' : 'danger') }}">
                {{ $invoice->status === 'paid' ? 'پرداخت شده' : ($invoice->status === 'partial' ? 'پرداخت جزئی' : 'پرداخت نشده') }}
            </span>
        </div>

        <div class="mb-3">
            <strong>مبلغ کل:</strong> {{ number_format($invoice->amount) }} تومان
        </div>
    </div>
</div>
@endsection
