<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش عملکرد ساختمان‌ها</title>
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
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .status-excellent { background-color: #d4edda; color: #155724; }
        .status-good { background-color: #d1ecf1; color: #0c5460; }
        .status-average { background-color: #fff3cd; color: #856404; }
        .status-poor { background-color: #f8d7da; color: #721c24; }
        .progress-bar {
            background-color: #e9ecef;
            border-radius: 4px;
            height: 20px;
            position: relative;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            position: relative;
        }
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 10px;
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
        @media print {
            body { background-color: white; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>گزارش عملکرد ساختمان‌ها</h1>
        <p>تاریخ گزارش: {{ now()->format('Y/m/d H:i') }}</p>
    </div>

    @if(!empty($filters['start_date']) || !empty($filters['end_date']))
        <div class="filters">
            <h4>فیلترهای اعمال شده:</h4>
            @if(!empty($filters['start_date']))
                <span class="filter-item">از تاریخ: {{ $filters['start_date'] }}</span>
            @endif
            @if(!empty($filters['end_date']))
                <span class="filter-item">تا تاریخ: {{ $filters['end_date'] }}</span>
            @endif
        </div>
    @endif

    <div class="summary-cards">
        <div class="summary-card">
            <h3>{{ $summary['total_buildings'] }}</h3>
            <p>کل ساختمان‌ها</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['average_occupancy'], 1) }}%</h3>
            <p>میانگین اشغال</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['average_payment_rate'], 1) }}%</h3>
            <p>میانگین پرداخت</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['average_performance_score'], 1) }}</h3>
            <p>امتیاز عملکرد</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['total_revenue']) }}</h3>
            <p>کل درآمد (تومان)</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['total_overdue']) }}</h3>
            <p>کل بدهی معوق (تومان)</p>
        </div>
    </div>

    <div class="table-container">
        @if ($buildings->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>رتبه</th>
                        <th>نام ساختمان</th>
                        <th>آدرس</th>
                        <th>مدیر</th>
                        <th>واحدها</th>
                        <th>اشغال (%)</th>
                        <th>درآمد (تومان)</th>
                        <th>پرداخت (%)</th>
                        <th>بدهی معوق (تومان)</th>
                        <th>امتیاز عملکرد</th>
                        <th>وضعیت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($buildings as $index => $building)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $building['name'] }}</td>
                            <td>{{ $building['address'] }}</td>
                            <td>{{ $building['manager_name'] }}</td>
                            <td>{{ $building['occupied_units'] }}/{{ $building['total_units'] }}</td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $building['occupancy_rate'] }}%; background-color: #28a745;">
                                        <span class="progress-text">{{ number_format($building['occupancy_rate'], 1) }}%</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ number_format($building['monthly_revenue']) }}</td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $building['payment_rate'] }}%; background-color: #17a2b8;">
                                        <span class="progress-text">{{ number_format($building['payment_rate'], 1) }}%</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($building['total_overdue'] > 0)
                                    <span style="color: #dc3545;">{{ number_format($building['total_overdue']) }}</span>
                                @else
                                    <span style="color: #28a745;">0</span>
                                @endif
                            </td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $building['performance_score'] }}%; background-color: #ffc107;">
                                        <span class="progress-text">{{ number_format($building['performance_score'], 1) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="status-{{ strtolower($building['status']) }}">
                                {{ $building['status'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666;">هیچ ساختمانی یافت نشد.</p>
        @endif
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        <p>این گزارش در تاریخ {{ now()->format('Y/m/d H:i') }} تولید شده است.</p>
    </div>
</body>
</html> 