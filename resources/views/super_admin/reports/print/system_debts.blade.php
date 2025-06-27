<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش کل بدهی‌ها - چاپ</title>
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

    <h3>گزارش کل بدهی‌ها</h3>

    <button id="print-btn" onclick="window.print()" style="margin-bottom:15px;">چاپ</button>

    <table>
        <thead>
            <tr>
                <th>شماره واحد</th>
                <th>ساختمان</th>
                <th>تعداد بدهی</th>
                <th>مبلغ کل بدهی</th>
                <th>نزدیک‌ترین سررسید</th>
            </tr>
        </thead>
        <tbody>
            @foreach($units as $unit)
                <tr>
                    <td>{{ $unit['unit_number'] ?? '-' }}</td>
                    <td>{{ \App\Models\Building::find($unit['building_id'])->name ?? '-' }}</td>
                    <td>{{ $unit['debt_count'] }}</td>
                    <td>{{ number_format($unit['total_debt']) }} تومان</td>
                    <td>{{ $unit['next_due'] ? jdate($unit['next_due'])->format('Y/m/d') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        مجموع کل بدهی‌ها: {{ number_format($totalDebt) }} تومان
    </div>

</body>
</html>
