<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>گزارش صورتحساب‌ها - چاپ</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* استایل ساده برای چاپ */
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

    <h3>گزارش صورتحساب‌ها - {{ $building->name ?? 'نامشخص' }}</h3>

    <button id="print-btn" onclick="window.print()" style="margin-bottom:15px;">چاپ</button>

    <table>
        <thead>
            <tr>
                <th>واحد</th>
                <th>مبلغ</th>
                <th>تاریخ سررسید</th>
                <th>وضعیت</th>
                <th>نوع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->unit->unit_number ?? $invoice->unit->name }}</td>
                    <td>{{ number_format($invoice->amount) }}</td>
                    <td>{{ jdate($invoice->due_date)->format('Y/m/d') }}</td>
                    <td>{{ $invoice->status == 'paid' ? 'پرداخت شده' : 'پرداخت نشده' }}</td>
                    <td>{{ $invoice->type == 'fixed' ? 'ثابت' : 'جاری' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        مجموع مبلغ این صفحه: {{ number_format($totalAmount) }} تومان
    </div>

</body>
</html>
