<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش تحلیل درآمد</title>
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
        .filters {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .filters h4 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            font-size: 12px;
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
        .fw-bold {
            font-weight: bold !important;
        }
        @media print {
            body { background-color: white; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>گزارش تحلیل درآمد</h1>
        <p>تاریخ گزارش: {{ now()->format('Y/m/d H:i') }}</p>
    </div>

    @if(!empty($filters['start_date']) || !empty($filters['end_date']) || !empty($filters['building_id']))
        <div class="filters">
            <h4>فیلترهای اعمال شده:</h4>
            @if(!empty($filters['start_date']))
                <span class="filter-item">از تاریخ: {{ $filters['start_date'] }}</span>
            @endif
            @if(!empty($filters['end_date']))
                <span class="filter-item">تا تاریخ: {{ $filters['end_date'] }}</span>
            @endif
            @if(!empty($filters['building_id']))
                @php
                    $building = \App\Models\Building::find($filters['building_id']);
                @endphp
                <span class="filter-item">ساختمان: {{ $building->name ?? 'نامشخص' }}</span>
            @endif
        </div>
    @endif

    <div class="summary-cards">
        <div class="summary-card">
            <h3>{{ number_format($summary['total_revenue']) }} تومان</h3>
            <p>کل درآمد</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['total_payments'] }}</h3>
            <p>کل پرداخت‌ها</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['average_payment']) }} تومان</h3>
            <p>میانگین پرداخت</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['unique_users'] }}</h3>
            <p>کاربران منحصر به فرد</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['unique_buildings'] }}</h3>
            <p>ساختمان‌های فعال</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['revenue_growth'], 1) }}%</h3>
            <p>رشد درآمد</p>
        </div>
    </div>

    <!-- کارت‌های اضافی -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>{{ number_format($summary['current_month_revenue']) }} تومان</h3>
            <p>درآمد ماه جاری</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['previous_month_revenue']) }} تومان</h3>
            <p>درآمد ماه گذشته</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['forecast_next_month']) }} تومان</h3>
            <p>پیش‌بینی ماه آینده</p>
        </div>
    </div>

    <!-- تحلیل درآمد ماهانه -->
    <div class="table-container">
        <h4>تحلیل درآمد ماهانه</h4>
        @if ($monthly_revenue->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>ماه</th>
                        <th>کل درآمد</th>
                        <th>تعداد پرداخت</th>
                        <th>میانگین پرداخت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthly_revenue as $month => $data)
                        <tr>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('Y/m') }}</td>
                            <td class="text-success fw-bold">{{ number_format($data['total_amount']) }} تومان</td>
                            <td>{{ $data['payment_count'] }}</td>
                            <td>{{ number_format($data['average_amount']) }} تومان</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666;">هیچ داده‌ای یافت نشد.</p>
        @endif
    </div>

    <!-- تحلیل درآمد بر اساس ساختمان -->
    <div class="table-container">
        <h4>درآمد بر اساس ساختمان</h4>
        @if ($building_revenue->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>نام ساختمان</th>
                        <th>کل درآمد</th>
                        <th>تعداد پرداخت</th>
                        <th>میانگین پرداخت</th>
                        <th>تعداد واحدها</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($building_revenue as $building => $data)
                        <tr>
                            <td><strong>{{ $building }}</strong></td>
                            <td class="text-success fw-bold">{{ number_format($data['total_amount']) }} تومان</td>
                            <td>{{ $data['payment_count'] }}</td>
                            <td>{{ number_format($data['average_amount']) }} تومان</td>
                            <td>{{ $data['units_count'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666;">هیچ داده‌ای یافت نشد.</p>
        @endif
    </div>

    <!-- تحلیل درآمد بر اساس نوع فاکتور -->
    <div class="table-container">
        <h4>درآمد بر اساس نوع فاکتور</h4>
        @if ($invoice_type_revenue->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>نوع فاکتور</th>
                        <th>کل درآمد</th>
                        <th>تعداد پرداخت</th>
                        <th>میانگین پرداخت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice_type_revenue as $type => $data)
                        <tr>
                            <td><strong>{{ $type }}</strong></td>
                            <td class="text-success fw-bold">{{ number_format($data['total_amount']) }} تومان</td>
                            <td>{{ $data['payment_count'] }}</td>
                            <td>{{ number_format($data['average_amount']) }} تومان</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666;">هیچ داده‌ای یافت نشد.</p>
        @endif
    </div>

    <!-- برترین کاربران -->
    <div class="table-container">
        <h4>برترین کاربران (از نظر درآمد)</h4>
        @if ($top_users->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>نام کاربر</th>
                        <th>ایمیل</th>
                        <th>کل درآمد</th>
                        <th>تعداد پرداخت</th>
                        <th>میانگین پرداخت</th>
                        <th>آخرین پرداخت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($top_users as $user)
                        <tr>
                            <td><strong>{{ $user['user_name'] }}</strong></td>
                            <td>{{ $user['user_email'] }}</td>
                            <td class="text-success fw-bold">{{ number_format($user['total_amount']) }} تومان</td>
                            <td>{{ $user['payment_count'] }}</td>
                            <td>{{ number_format($user['average_amount']) }} تومان</td>
                            <td>{{ \Carbon\Carbon::parse($user['last_payment'])->format('Y/m/d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666;">هیچ داده‌ای یافت نشد.</p>
        @endif
    </div>

    <!-- آخرین پرداخت‌ها -->
    <div class="table-container">
        <h4>آخرین پرداخت‌ها</h4>
        @if ($payments->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>کاربر</th>
                        <th>ساختمان</th>
                        <th>مبلغ</th>
                        <th>روش پرداخت</th>
                        <th>تاریخ پرداخت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td><strong>{{ $payment->user->name }}</strong></td>
                            <td>{{ $payment->invoice->unit->building->name ?? 'نامشخص' }}</td>
                            <td class="text-success fw-bold">{{ number_format($payment->amount) }} تومان</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td>{{ $payment->paid_at->format('Y/m/d H:i') }}</td>
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