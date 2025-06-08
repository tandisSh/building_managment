@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>گزارش پرداخت‌ها - {{ $building->name ?? 'نامشخص' }}</h2>

    {{-- فرم فیلتر --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="از تاریخ">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="تا تاریخ">
        </div>
        <div class="col-md-2">
            <input type="text" name="unit_number" class="form-control" placeholder="شماره واحد (مثلاً 2 یا 3B)" value="{{ request('unit_number') }}">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">فیلتر</button>
            <a href="{{ route('reports.payments') }}" class="btn btn-secondary w-100">حذف فیلتر</a>
        </div>
    </form>

    {{-- مجموع و دکمه چاپ --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <strong>جمع کل پرداخت‌ها:</strong> {{ number_format($totalAmount) }} تومان
        </div>
        <div>
            <a href="{{ route('reports.payments.print', request()->query()) }}" target="_blank" class="btn btn-info">چاپ گزارش</a>
        </div>
    </div>

    {{-- جدول پرداخت‌ها --}}
    @if($payments->count() > 0)
        <table class="table table-bordered table-striped text-center">
            <thead class="table-light">
                <tr>
                    <th>کاربر</th>
                    <th>واحد</th>
                    <th>مبلغ</th>
                    <th>تاریخ پرداخت</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->user->name ?? '---' }}</td>
                        <td>{{ $payment->invoice->unit->unit_number ?? '---' }}</td>
                        <td>{{ number_format($payment->amount) }} تومان</td>
                        <td>{{ $payment->paid_at ? $payment->paid_at->format('Y/m/d') : '---' }}</td>
                        <td>
                            <span class="badge bg-{{ $payment->status === 'success' ? 'success' : 'warning text-dark' }}">
                                {{ $payment->status === 'success' ? 'موفق' : 'ناموفق' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $payments->withQueryString()->links() }}
    @else
        <div class="alert alert-info text-center">
            هیچ پرداختی یافت نشد.
        </div>
    @endif
</div>
@endsection
