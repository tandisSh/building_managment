@extends('layouts.app')
@section('title', 'چاپ گزارش وضعیت حساب ساکنین')
@section('content')
    <div class="text-center mb-3">
        <h5 class="fw-bold">گزارش وضعیت حساب ساکنین - {{ $building->name ?? 'نامشخص' }}</h5>
    </div>
    <table class="table table-bordered table-striped align-middle text-center table-units">
        <thead>
            <tr>
                <th>نام ساکن</th>
                <th>واحدها</th>
                <th>مجموع بدهی (تومان)</th>
                <th>مجموع پرداختی (تومان)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($residents as $resident)
                <tr>
                    <td>{{ $resident['resident_name'] }}</td>
                    <td>
                        @foreach ($resident['units'] as $unitNumber)
                            <span class="badge bg-secondary mx-1">{{ $unitNumber }}</span>
                        @endforeach
                    </td>
                    <td>{{ number_format($resident['total_debt']) }}</td>
                    <td>{{ number_format($resident['total_paid']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection 