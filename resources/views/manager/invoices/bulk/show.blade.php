@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-collection"></i> جزئیات صورتحساب کلی ({{ $bulkInvoice->type === 'fixed' ? 'ثابت' : 'جاری' }})</h5>
        <a href="{{ route('bulk_invoices.index') }}" class="btn btn-sm btn-secondary">
            بازگشت به لیست
        </a>
    </div>

    <div class="card-body">

        @if($bulkInvoice->type === 'fixed')
            <div class="mb-3">
                <strong>عنوان صورتحساب:</strong>
                {{ $bulkInvoice->title }}
            </div>
        @endif

        <div class="mb-3">
            <strong>نوع صورتحساب:</strong>
            <span class="badge bg-{{ $bulkInvoice->type === 'fixed' ? 'primary' : 'info' }}">
                {{ $bulkInvoice->type === 'fixed' ? 'ثابت' : 'جاری' }}
            </span>
        </div>

        <div class="mb-3">
            <strong>مبلغ پایه:</strong> {{ number_format($bulkInvoice->base_amount) }} تومان
        </div>

        <div class="mb-3">
            <strong>توضیحات:</strong>
            {{ $bulkInvoice->description ?? '-' }}
        </div>

        <div class="mb-3">
            <strong>تاریخ سررسید:</strong>
            {{ jdate($bulkInvoice->due_date)->format('Y/m/d') }}
        </div>

        <hr>
        <h6 class="mt-4">لیست واحدهایی که این صورتحساب برایشان صادر شده:</h6>
        <table class="table table-bordered">
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
@endsection
