@extends('layouts.app')
@section('title', 'چاپ گزارش بدهی واحدها')
@section('content')
    <div class="text-center mb-3">
        <h5 class="fw-bold">گزارش بدهی واحدها - {{ $building->name ?? 'نامشخص' }}</h5>
    </div>
    <table class="table table-bordered align-middle text-center table-units">
        <thead>
            <tr>
                <th>شماره واحد</th>
                <th>تعداد صورتحساب پرداخت‌نشده</th>
                <th>جمع بدهی (تومان)</th>
                <th>نزدیک‌ترین سررسید</th>
            </tr>
        </thead>
        <tbody>
            @forelse($units as $unit)
                <tr>
                    <td>{{ $unit['unit_number'] }}</td>
                    <td>{{ $unit['debt_count'] }}</td>
                    <td>{{ number_format($unit['total_debt']) }}</td>
                    <td>{{ $unit['next_due'] ? jdate($unit['next_due'])->format('Y/m/d') : '---' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">هیچ بدهی ثبت نشده است.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection 