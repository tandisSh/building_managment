@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>گزارش صورتحساب‌ها - {{ $building->name ?? 'نامشخص' }}</h2>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}" placeholder="از تاریخ">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}" placeholder="تا تاریخ">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-control">
                <option value="">همه وضعیت‌ها</option>
                <option value="paid" @selected(($filters['status'] ?? '') === 'paid')>پرداخت شده</option>
                <option value="unpaid" @selected(($filters['status'] ?? '') === 'unpaid')>پرداخت نشده</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="type" class="form-control">
                <option value="">همه نوع‌ها</option>
                <option value="fixed" @selected(($filters['type'] ?? '') === 'fixed')>ثابت</option>
                <option value="current" @selected(($filters['type'] ?? '') === 'current')>جاری</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="unit_id" class="form-control">
                <option value="">همه واحدها</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" @selected(($filters['unit_id'] ?? '') == $unit->id)>{{ $unit->unit_number ?? $unit->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">فیلتر</button>
            <a href="{{ route('reports.invoices') }}" class="btn btn-secondary w-100">حذف فیلتر</a>
        </div>
    </form>
<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        <strong>مجموع مبلغ این صفحه:</strong> {{ number_format($totalAmount) }} تومان
    </div>
    <div>
        <a href="{{ route('reports.payments.print', request()->query()) }}" target="_blank" class="btn btn-info">چاپ گزارش</a>
    </div>
</div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>واحد</th>
                <th>مبلغ</th>
                <th>تاریخ سررسید</th>
                <th>وضعیت</th>
                <th>نوع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->unit->unit_number ?? $invoice->unit->name }}</td>
                    <td>{{ number_format($invoice->amount) }}</td>
                    <td>{{ jdate($invoice->due_date)->format('Y/m/d') }}</td>
                    <td>{{ $invoice->status == 'paid' ? 'پرداخت شده' : 'پرداخت نشده' }}</td>
                    <td>{{ $invoice->type == 'fixed' ? 'ثابت' : 'جاری' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $invoices->withQueryString()->links() }}
</div>
@endsection
