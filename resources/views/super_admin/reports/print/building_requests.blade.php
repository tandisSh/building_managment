<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش درخواست‌های ساختمان</title>
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
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .type-residential { background-color: #d4edda; color: #155724; }
        .type-commercial { background-color: #d1ecf1; color: #0c5460; }
        .type-mixed { background-color: #fff3cd; color: #856404; }
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
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            margin: 1px;
        }
        @media print {
            body { background-color: white; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>گزارش درخواست‌های ساختمان</h1>
        <p>تاریخ گزارش: {{ now()->format('Y/m/d H:i') }}</p>
    </div>

    @if(!empty($filters['start_date']) || !empty($filters['end_date']) || !empty($filters['status']) || !empty($filters['building_type']))
        <div class="filters">
            <h4>فیلترهای اعمال شده:</h4>
            @if(!empty($filters['start_date']))
                <span class="filter-item">از تاریخ: {{ $filters['start_date'] }}</span>
            @endif
            @if(!empty($filters['end_date']))
                <span class="filter-item">تا تاریخ: {{ $filters['end_date'] }}</span>
            @endif
            @if(!empty($filters['status']))
                @php
                    $statusText = match($filters['status']) {
                        'pending' => 'در انتظار',
                        'approved' => 'تایید شده',
                        'rejected' => 'رد شده',
                        default => $filters['status']
                    };
                @endphp
                <span class="filter-item">وضعیت: {{ $statusText }}</span>
            @endif
            @if(!empty($filters['building_type']))
                @php
                    $typeText = match($filters['building_type']) {
                        'residential' => 'مسکونی',
                        'commercial' => 'تجاری',
                        'mixed' => 'ترکیبی',
                        default => $filters['building_type']
                    };
                @endphp
                <span class="filter-item">نوع ساختمان: {{ $typeText }}</span>
            @endif
        </div>
    @endif

    <div class="summary-cards">
        <div class="summary-card">
            <h3>{{ $summary['total_requests'] }}</h3>
            <p>کل درخواست‌ها</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['pending_requests'] }}</h3>
            <p>در انتظار بررسی</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['approved_requests'] }}</h3>
            <p>تایید شده</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['rejected_requests'] }}</h3>
            <p>رد شده</p>
        </div>
        <div class="summary-card">
            <h3>{{ number_format($summary['average_processing_days'], 1) }}</h3>
            <p>میانگین روزهای بررسی</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['total_units_requested'] }}</h3>
            <p>کل واحدهای درخواستی</p>
        </div>
    </div>

    <div class="table-container">
        @if ($requests->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>نام متقاضی</th>
                        <th>اطلاعات تماس</th>
                        <th>نام ساختمان</th>
                        <th>نوع ساختمان</th>
                        <th>آدرس</th>
                        <th>تعداد واحدها</th>
                        <th>وضعیت</th>
                        <th>تاریخ درخواست</th>
                        <th>مدت زمان</th>
                        <th>مستندات</th>
                        <th>توضیحات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td><strong>{{ $request['user_name'] }}</strong></td>
                            <td>
                                <strong>ایمیل:</strong> {{ $request['user_email'] }}<br>
                                <strong>تلفن:</strong> {{ $request['user_phone'] }}
                            </td>
                            <td>{{ $request['building_name'] }}</td>
                            <td>
                                @php
                                    $typeText = match($request['building_type']) {
                                        'residential' => 'مسکونی',
                                        'commercial' => 'تجاری',
                                        'mixed' => 'ترکیبی',
                                        default => 'نامشخص'
                                    };
                                @endphp
                                <span class="badge type-{{ $request['building_type'] }}">{{ $typeText }}</span>
                            </td>
                            <td>{{ $request['building_address'] }}</td>
                            <td>{{ $request['total_units'] }} واحد</td>
                            <td>
                                @php
                                    $statusText = match($request['status']) {
                                        'pending' => 'در انتظار',
                                        'approved' => 'تایید شده',
                                        'rejected' => 'رد شده',
                                        default => 'نامشخص'
                                    };
                                @endphp
                                <span class="badge status-{{ $request['status'] }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($request['created_at'])->format('Y/m/d') }}<br>
                                {{ \Carbon\Carbon::parse($request['created_at'])->format('H:i') }}
                            </td>
                            <td>
                                @if($request['status'] === 'pending')
                                    <strong>{{ $request['waiting_days'] }} روز</strong><br>
                                    در انتظار
                                @else
                                    <strong>{{ $request['processing_days'] }} روز</strong><br>
                                    بررسی شد
                                @endif
                            </td>
                            <td>
                                @if($request['has_document'])
                                    <span style="color: #28a745;">موجود</span>
                                @else
                                    <span style="color: #6c757d;">موجود نیست</span>
                                @endif
                            </td>
                            <td>
                                @if($request['description'])
                                    {{ Str::limit($request['description'], 50) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666;">هیچ درخواستی یافت نشد.</p>
        @endif
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        <p>این گزارش در تاریخ {{ now()->format('Y/m/d H:i') }} تولید شده است.</p>
    </div>
</body>
</html> 