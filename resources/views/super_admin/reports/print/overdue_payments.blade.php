<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش پرداخت‌های معوق - چاپ</title>
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

    <h3>گزارش پرداخت‌های معوق</h3>

    <button id="print-btn" onclick="window.print()" style="margin-bottom:15px;">چاپ</button>

    <table>
        <thead>
            <tr>
                <th>واحد</th>
                <th>مبلغ</th>
                <th>تاریخ سررسید</th>
                <th>روزهای معوق</th>
                <th>ساختمان</th>
            </tr>
        </thead>
        <tbody>
            @foreach($overdueInvoices as $item)
                <tr>
                    <td>{{ $item['unit_number'] }}</td>
                    <td>{{ number_format($item['amount']) }} تومان</td>
                    <td>{{ $item['invoice']->due_date ? \Carbon\Carbon::parse($item['invoice']->due_date)->format('Y/m/d') : '-' }}</td>
                    <td>{{ $item['days_overdue'] }}</td>
                    <td>{{ $item['invoice']->unit->building->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        مجموع مبلغ معوق: {{ number_format($totalOverdueAmount) }} تومان
    </div>

</body>
</html>
