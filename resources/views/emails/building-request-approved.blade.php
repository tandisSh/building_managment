<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درخواست ساختمان شما تأیید شد</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
            line-height: 1.6;
            text-align: right;
        }
        .content h2 {
            color: #4CAF50;
        }
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>درخواست شما تأیید شد!</h1>
        </div>
        <div class="content">
            <h2>سلام {{ $user->name }}،</h2>
            <p>
                خبر خوبی برایتان داریم! درخواست ثبت ساختمان شما با نام <strong>"{{ $building->name }}"</strong> توسط مدیریت سیستم تأیید شد.
            </p>
            <p>
                برای فعال‌سازی کامل پنل مدیریت و دسترسی به تمام امکانات، تنها یک قدم باقی مانده است. لطفاً با کلیک بر روی دکمه زیر، هزینه اولیه را پرداخت نمایید.
            </p>
            <div class="button-container">
                <a href="{{ route('manager.initial-payment.show') }}" class="button">
                    رفتن به صفحه پرداخت
                </a>
            </div>
            <p style="margin-top: 30px;">
                پس از پرداخت موفق، حساب شما به صورت خودکار فعال خواهد شد.
            </p>
        </div>
        <div class="footer">
            <p>با تشکر،<br>تیم مدیریت ساختمان</p>
        </div>
    </div>
</body>
</html>
