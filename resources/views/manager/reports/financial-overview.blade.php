@extends('layouts.app')

@section('content')
    <h3 class="text-xl font-bold mb-4">گزارش مالی ماهانه</h3>

    <form method="GET" class="mb-4 flex items-center gap-4">
        <label for="month">انتخاب ماه:</label>
        <select name="month" id="month" class="form-select w-48">
            <option value="">-- همه ماه‌ها --</option>
            @foreach ($months as $month)
                <option value="{{ $month }}" {{ ($selectedMonth ?? '') == $month ? 'selected' : '' }}>
                    {{ $month }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">اعمال فیلتر</button>
    </form>


    <table class="table table-bordered text-center mb-6">
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

    <div>
        <canvas id="financialChart" height="80"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('financialChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_column($summary, 'month')) !!},
                datasets: [{
                        label: 'پرداخت‌شده',
                        data: {!! json_encode(array_column($summary, 'paid')) !!},
                        borderColor: 'green',
                        fill: false
                    },
                    {
                        label: 'بدهی باقی‌مانده',
                        data: {!! json_encode(array_column($summary, 'unpaid')) !!},
                        borderColor: 'red',
                        fill: false
                    }
                ]
            }
        });
    </script>
@endsection
