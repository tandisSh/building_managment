<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>خلاصه مالی سالانه - چاپ</title>
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

    <h3>خلاصه مالی سالانه</h3>

    <button id="print-btn" onclick="window.print()" style="margin-bottom:15px;">چاپ</button>

    <table>
        <thead>
            <tr>
                <th>سال</th>
                <th>ماه</th>
                <th>مجموع صورتحساب</th>
                <th>پرداخت‌شده</th>
                <th>باقی‌مانده</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary as $item)
                <tr>
                    <td>{{ $item['year'] }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y/m', $item['year'] . '/' . $item['month'])->startOfMonth()->format('F') }}</td>
                    <td>{{ number_format($item['invoiced']) }} تومان</td>
                    <td>{{ number_format($item['paid']) }} تومان</td>
                    <td>{{ number_format($item['unpaid']) }} تومان</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
