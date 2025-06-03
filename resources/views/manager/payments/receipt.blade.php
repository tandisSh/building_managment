<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>رسید پرداخت</title>
    <style>
        body {
            font-family: "Tahoma", sans-serif;
            direction: rtl;
            padding: 30px;
        }
        .receipt-box {
            max-width: 600px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
        }
        .title {
            text-align: center;
            font-size: 20px;
            margin-bottom: 30px;
        }
        .row {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #777;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="receipt-box">
    <div class="title">رسید پرداخت</div>

    <div class="row"><span class="label">پرداخت‌کننده:</span> {{ $payment->user->name }}</div>
    <div class="row"><span class="label">شماره واحد:</span> {{ $payment->user->unit_number ?? '-' }}</div>
    <div class="row"><span class="label">عنوان صورتحساب:</span> {{ $payment->invoice->title }}</div>
    <div class="row"><span class="label">توضیحات:</span> {{ $payment->invoice->description ?? '-' }}</div>
    <div class="row"><span class="label">مبلغ:</span> {{ number_format($payment->amount) }} تومان</div>
    <div class="row"><span class="label">تاریخ پرداخت:</span> {{ jdate($payment->paid_at)->format('Y/m/d') }}</div>
    <div class="row"><span class="label">روش پرداخت:</span> {{ $payment->method ?? '-' }}</div>

    <div class="footer no-print">
        <button onclick="window.print()">چاپ</button>
    </div>
</div>

</body>
</html>
