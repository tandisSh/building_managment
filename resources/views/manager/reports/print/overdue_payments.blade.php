@extends('layouts.app')
@section('title', 'چاپ گزارش پرداخت‌های معوق')
@section('content')
    <div class="text-center mb-3">
        <h5 class="fw-bold">گزارش پرداخت‌های معوق - {{ $building->name ?? 'نامشخص' }}</h5>
        <div><strong>مجموع بدهی معوق:</strong> {{ number_format($totalOverdueAmount) }} تومان</div>
    </div>
    <table class="table table-bordered table-striped align-middle text-center">
        <thead>
            <tr>
                <th>واحد</th>
                <th>مبلغ</th>
                <th>تاریخ سررسید</th>
                <th>تعداد روزهای تاخیر</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($overdueInvoices as $item)
                <tr>
                    <td>{{ $item['unit_number'] }}</td>
                    <td>{{ number_format($item['amount']) }}</td>
                    <td>{{ jdate($item['invoice']->due_date)->format('Y/m/d') }}</td>
                    <td>{{ $item['days_overdue'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection 