<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش فعالیت کاربران</title>
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
        .status-very-active { background-color: #d4edda; color: #155724; }
        .status-active { background-color: #d1ecf1; color: #0c5460; }
        .status-average { background-color: #fff3cd; color: #856404; }
        .status-low-active { background-color: #e2e3e5; color: #383d41; }
        .status-inactive { background-color: #f8d7da; color: #721c24; }
        .progress-bar {
            background-color: #e9ecef;
            border-radius: 4px;
            height: 15px;
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
            font-size: 8px;
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
        .role-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            margin: 1px;
        }
        .role-super_admin { background-color: #dc3545; color: white; }
        .role-manager { background-color: #007bff; color: white; }
        .role-resident { background-color: #28a745; color: white; }
        @media print {
            body { background-color: white; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>گزارش فعالیت کاربران</h1>
        <p>تاریخ گزارش: {{ now()->format('Y/m/d H:i') }}</p>
    </div>

    @if(!empty($filters['start_date']) || !empty($filters['end_date']) || !empty($filters['role']) || !empty($filters['status']) || !empty($filters['building_id']))
        <div class="filters">
            <h4>فیلترهای اعمال شده:</h4>
            @if(!empty($filters['start_date']))
                <span class="filter-item">از تاریخ: {{ $filters['start_date'] }}</span>
            @endif
            @if(!empty($filters['end_date']))
                <span class="filter-item">تا تاریخ: {{ $filters['end_date'] }}</span>
            @endif
            @if(!empty($filters['role']))
                <span class="filter-item">نقش: {{ $filters['role'] }}</span>
            @endif
            @if(!empty($filters['status']))
                <span class="filter-item">وضعیت: {{ $filters['status'] }}</span>
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
            <h3>{{ $summary['total_users'] }}</h3>
            <p>کل کاربران</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['active_users'] }}</h3>
            <p>کاربران فعال</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['inactive_users'] }}</h3>
            <p>کاربران غیرفعال</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['average_activity_score'], 1) }}</h3>
            <p>میانگین امتیاز فعالیت</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['total_payments'] }}</h3>
            <p>کل پرداخت‌ها</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['total_repair_requests'] }}</h3>
            <p>کل درخواست‌های تعمیر</p>
        </div>
    </div>

    <div class="table-container">
        @if ($users->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>نام کاربر</th>
                        <th>ایمیل</th>
                        <th>نقش‌ها</th>
                        <th>وضعیت</th>
                        <th>واحدها</th>
                        <th>پرداخت‌ها</th>
                        <th>درخواست‌های تعمیر</th>
                        <th>اعلان‌ها</th>
                        <th>آخرین فعالیت</th>
                        <th>امتیاز فعالیت</th>
                        <th>وضعیت فعالیت</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>
                                @foreach($user['roles'] as $role)
                                    <span class="role-badge role-{{ $role }}">{{ $role }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user['status'] === 'active')
                                    <span style="color: #28a745;">فعال</span>
                                @else
                                    <span style="color: #ffc107;">غیرفعال</span>
                                @endif
                            </td>
                            <td>
                                <strong>کل:</strong> {{ $user['total_units'] }}<br>
                                <strong>فعال:</strong> {{ $user['active_units'] }}<br>
                                <strong>مالک:</strong> {{ $user['owner_units'] }}<br>
                                <strong>ساکن:</strong> {{ $user['resident_units'] }}
                            </td>
                            <td>
                                <strong>تعداد:</strong> {{ $user['total_payments'] }}<br>
                                <strong>مبلغ:</strong> {{ number_format($user['total_paid_amount']) }}<br>
                                @if($user['last_payment'])
                                    <strong>آخرین:</strong> {{ \Carbon\Carbon::parse($user['last_payment'])->format('Y/m/d') }}
                                @endif
                            </td>
                            <td>
                                <strong>کل:</strong> {{ $user['total_repair_requests'] }}<br>
                                <strong>در انتظار:</strong> {{ $user['pending_repair_requests'] }}<br>
                                <strong>تکمیل شده:</strong> {{ $user['completed_repair_requests'] }}<br>
                                @if($user['last_repair_request'])
                                    <strong>آخرین:</strong> {{ \Carbon\Carbon::parse($user['last_repair_request'])->format('Y/m/d') }}
                                @endif
                            </td>
                            <td>
                                <strong>کل:</strong> {{ $user['total_notifications'] }}<br>
                                <strong>نخوانده:</strong> {{ $user['unread_notifications'] }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($user['last_login'])->format('Y/m/d H:i') }}
                            </td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ min(100, $user['activity_score']) }}%; background-color: #ffc107;">
                                        <span class="progress-text">{{ number_format($user['activity_score'], 1) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="status-{{ str_replace(' ', '-', strtolower($user['activity_status'])) }}">
                                {{ $user['activity_status'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666;">هیچ کاربری یافت نشد.</p>
        @endif
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        <p>این گزارش در تاریخ {{ now()->format('Y/m/d H:i') }} تولید شده است.</p>
    </div>
</body>
</html> 