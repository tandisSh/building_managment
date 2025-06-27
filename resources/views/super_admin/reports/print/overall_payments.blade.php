<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش کلی پرداخت‌ها - چاپ</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            direction: rtl;
            font-family: Tahoma, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #eee;
        }
        .summary {
            margin-top: 10px;
            font-weight: bold;
        }
        @media print {
            button#print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

    <h3>گزارش کلی پرداخت‌ها</h3>

    <button id="print-btn" onclick="window.print()" style="margin-bottom:15px;">چاپ</button>

    <table>
        <thead>
            <tr>
                <th>کاربر</th>
                <th>واحد</th>
                <th>مبلغ پرداختی</th>
                <th>تاریخ پرداخت</th>
                <th>ساختمان</th>
                <th>وضعیت</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->user->name ?? '-' }}</td>
                    <td>{{ $payment->invoice->unit->unit_number ?? '-' }}</td>
                    <td>{{ number_format($payment->amount) }} تومان</td>
                    <td>{{ jdate($payment->paid_at)->format('Y/m/d') }}</td>
                    <td>{{ $payment->invoice->unit->building->name ?? '-' }}</td>
                    <td>{{ $payment->status === 'success' ? 'موفق' : 'ناموفق' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        مجموع پرداخت‌ها: {{ number_format($totalAmount) }} تومان
    </div>

</body>
</html>
