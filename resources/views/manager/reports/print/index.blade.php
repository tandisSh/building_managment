@extends('layouts.app')
@section('title', 'چاپ گزارش پرداخت‌ها')
@section('content')
    <div class="text-center mb-3">
        <h5 class="fw-bold">گزارش پرداخت‌ها - {{ $building->name ?? 'نامشخص' }}</h5>
        <div><strong>جمع کل پرداخت‌ها:</strong> {{ number_format($totalAmount) }} تومان</div>
    </div>
    <table class="table table-bordered table-striped align-middle text-center table-units">
        <thead>
            <tr>
                <th>کاربر</th>
                <th>واحد</th>
                <th>مبلغ</th>
                <th>تاریخ پرداخت</th>
                <th>وضعیت</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->user->name ?? '---' }}</td>
                    <td>{{ $payment->invoice->unit->unit_number ?? '---' }}</td>
                    <td>{{ number_format($payment->amount) }} تومان</td>
                    <td>{{ $payment->paid_at ? $payment->paid_at->format('Y/m/d') : '---' }}</td>
                    <td>
                        <span class="badge bg-{{ $payment->status === 'success' ? 'success' : 'warning text-dark' }} text-center">
                            {{ $payment->status === 'success' ? 'موفق' : 'ناموفق' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection 