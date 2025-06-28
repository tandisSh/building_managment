<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش آمار کلی سیستم</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .summary-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 5px;
            flex: 1;
            min-width: 150px;
        }
        .summary-card h3 {
            margin: 0;
            color: #007bff;
            font-size: 18px;
        }
        .summary-card p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        .table-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-success {
            color: #28a745 !important;
        }
        .text-primary {
            color: #007bff !important;
        }
        .text-info {
            color: #17a2b8 !important;
        }
        .text-warning {
            color: #ffc107 !important;
        }
        .text-danger {
            color: #dc3545 !important;
        }
        .text-secondary {
            color: #6c757d !important;
        }
        .fw-bold {
            font-weight: bold !important;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stats-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
        }
        .stats-card h4 {
            margin: 0 0 15px 0;
            color: #007bff;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .stats-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .stats-label {
            font-weight: bold;
            color: #666;
        }
        .stats-value {
            color: #007bff;
            font-weight: bold;
        }
        @media print {
            body { background-color: white; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>گزارش آمار کلی سیستم</h1>
        <p>تاریخ گزارش: {{ now()->format('Y/m/d H:i') }}</p>
    </div>

    <!-- آمار کلی -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>{{ $users['total'] }}</h3>
            <p>کل کاربران</p>
        </div>
        <div class="summary-card">
            <h3>{{ $buildings['total'] }}</h3>
            <p>کل ساختمان‌ها</p>
        </div>
        <div class="summary-card">
            <h3>{{ $units['total'] }}</h3>
            <p>کل واحدها</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($payments['total_amount']) }} تومان</h3>
            <p>کل درآمد</p>
        </div>
    </div>

    <!-- آمار تفصیلی -->
    <div class="stats-grid">
        <!-- آمار کاربران -->
        <div class="stats-card">
            <h4>آمار کاربران</h4>
            <div class="stats-row">
                <span class="stats-label">کل کاربران:</span>
                <span class="stats-value">{{ $users['total'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">فعال:</span>
                <span class="stats-value text-success">{{ $users['active'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">غیرفعال:</span>
                <span class="stats-value text-danger">{{ $users['inactive'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">نرخ فعالیت:</span>
                <span class="stats-value text-primary">{{ number_format($users['activity_rate'], 1) }}%</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">سوپر ادمین:</span>
                <span class="stats-value text-info">{{ $users['super_admins'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">مدیر:</span>
                <span class="stats-value text-warning">{{ $users['managers'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">ساکن:</span>
                <span class="stats-value text-secondary">{{ $users['residents'] }}</span>
            </div>
        </div>

        <!-- آمار ساختمان‌ها -->
        <div class="stats-card">
            <h4>آمار ساختمان‌ها</h4>
            <div class="stats-row">
                <span class="stats-label">کل ساختمان‌ها:</span>
                <span class="stats-value">{{ $buildings['total'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">فعال:</span>
                <span class="stats-value text-success">{{ $buildings['active'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">غیرفعال:</span>
                <span class="stats-value text-danger">{{ $buildings['inactive'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">نرخ فعالیت:</span>
                <span class="stats-value text-primary">{{ number_format($buildings['activity_rate'], 1) }}%</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">دارای مدیر:</span>
                <span class="stats-value text-info">{{ $buildings['with_manager'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">بدون مدیر:</span>
                <span class="stats-value text-warning">{{ $buildings['without_manager'] }}</span>
            </div>
        </div>

        <!-- آمار واحدها -->
        <div class="stats-card">
            <h4>آمار واحدها</h4>
            <div class="stats-row">
                <span class="stats-label">کل واحدها:</span>
                <span class="stats-value">{{ $units['total'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">اشغال شده:</span>
                <span class="stats-value text-success">{{ $units['occupied'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">خالی:</span>
                <span class="stats-value text-warning">{{ $units['vacant'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">نرخ اشغال:</span>
                <span class="stats-value text-primary">{{ number_format($units['occupancy_rate'], 1) }}%</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">مالک:</span>
                <span class="stats-value text-info">{{ $units['owner'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">مستاجر:</span>
                <span class="stats-value text-secondary">{{ $units['tenant'] }}</span>
            </div>
        </div>

        <!-- آمار فاکتورها -->
        <div class="stats-card">
            <h4>آمار فاکتورها</h4>
            <div class="stats-row">
                <span class="stats-label">کل فاکتورها:</span>
                <span class="stats-value">{{ $invoices['total'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">پرداخت شده:</span>
                <span class="stats-value text-success">{{ $invoices['paid'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">پرداخت نشده:</span>
                <span class="stats-value text-danger">{{ $invoices['unpaid'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">نرخ پرداخت:</span>
                <span class="stats-value text-primary">{{ number_format($invoices['payment_rate'], 1) }}%</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">معوق:</span>
                <span class="stats-value text-warning">{{ $invoices['overdue'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">کل مبلغ:</span>
                <span class="stats-value text-info">{{ number_format($invoices['total_amount']) }} تومان</span>
            </div>
        </div>

        <!-- آمار پرداخت‌ها -->
        <div class="stats-card">
            <h4>آمار پرداخت‌ها</h4>
            <div class="stats-row">
                <span class="stats-label">کل پرداخت‌ها:</span>
                <span class="stats-value">{{ $payments['total'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">موفق:</span>
                <span class="stats-value text-success">{{ $payments['successful'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">ناموفق:</span>
                <span class="stats-value text-danger">{{ $payments['failed'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">نرخ موفقیت:</span>
                <span class="stats-value text-primary">{{ number_format($payments['success_rate'], 1) }}%</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">میانگین پرداخت:</span>
                <span class="stats-value text-info">{{ number_format($payments['average_amount']) }} تومان</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">کل درآمد:</span>
                <span class="stats-value text-warning">{{ number_format($payments['total_amount']) }} تومان</span>
            </div>
        </div>

        <!-- آمار درخواست‌های تعمیر -->
        <div class="stats-card">
            <h4>آمار درخواست‌های تعمیر</h4>
            <div class="stats-row">
                <span class="stats-label">کل درخواست‌ها:</span>
                <span class="stats-value">{{ $repair_requests['total'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">در انتظار:</span>
                <span class="stats-value text-warning">{{ $repair_requests['pending'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">در حال انجام:</span>
                <span class="stats-value text-info">{{ $repair_requests['in_progress'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">تکمیل شده:</span>
                <span class="stats-value text-success">{{ $repair_requests['completed'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">لغو شده:</span>
                <span class="stats-value text-danger">{{ $repair_requests['cancelled'] }}</span>
            </div>
            <div class="stats-row">
                <span class="stats-label">نرخ تکمیل:</span>
                <span class="stats-value text-primary">{{ number_format($repair_requests['completion_rate'], 1) }}%</span>
            </div>
        </div>
    </div>

    <!-- آمار ماهانه -->
    <div class="table-container">
        <h4>آمار ماهانه (آخرین 6 ماه)</h4>
        @if ($monthly_stats->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ماه</th>
                        <th>کاربران جدید</th>
                        <th>ساختمان‌های جدید</th>
                        <th>پرداخت‌های جدید</th>
                        <th>مبلغ پرداخت‌ها</th>
                        <th>درخواست‌های تعمیر</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthly_stats as $stat)
                        <tr>
                            <td><strong>{{ $stat['month'] }}</strong></td>
                            <td class="text-success">{{ $stat['new_users'] }}</td>
                            <td class="text-primary">{{ $stat['new_buildings'] }}</td>
                            <td class="text-info">{{ $stat['new_payments'] }}</td>
                            <td class="text-warning">{{ number_format($stat['payment_amount']) }} تومان</td>
                            <td class="text-secondary">{{ $stat['new_repair_requests'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666;">هیچ داده‌ای یافت نشد.</p>
        @endif
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        <p>این گزارش در تاریخ {{ now()->format('Y/m/d H:i') }} تولید شده است.</p>
    </div>
</body>
</html> 