@extends('layouts.app')

@section('title', 'گزارش بدهی واحدها')

@section('content')
<div class="container">
    <h4 class="mb-4">گزارش بدهی واحدها - {{ $building->name ?? 'نامشخص' }}</h4>

    <table class="table table-bordered text-center">
        <thead class="table-light">
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
                    <td>
                        {{ $unit['next_due'] ? jdate($unit['next_due'])->format('Y/m/d') : '---' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">هیچ بدهی ثبت نشده است.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
