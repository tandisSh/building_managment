@extends('layouts.app')
@section('title', 'چاپ گزارش مالی ماهانه')
@section('content')
    <div class="text-center mb-3">
        <h5 class="fw-bold">گزارش مالی ماهانه</h5>
    </div>
    @if (count($summary) > 0)
        <table class="table table-bordered table-striped align-middle text-center table-units">
            <thead>
                <tr>
                    <th>ماه</th>
                    <th>کل صورتحساب‌ها</th>
                    <th>پرداخت‌شده</th>
                    <th>بدهی باقی‌مانده</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary as $item)
                    <tr>
                        <td>{{ $item['month'] }}</td>
                        <td>{{ number_format($item['invoiced']) }} تومان</td>
                        <td class="text-success">{{ number_format($item['paid']) }} تومان</td>
                        <td class="text-danger">{{ number_format($item['unpaid']) }} تومان</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info text-center">داده‌ای برای نمایش وجود ندارد.</div>
    @endif
@endsection 