<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>گزارش تفکیک ساختمان‌ها بر اساس استان و شهر</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        
        body {
            font-family: 'Tahoma', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        
        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .filters h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
        }
        
        .filter-item {
            display: inline-block;
            margin: 0 10px 5px 0;
            padding: 3px 8px;
            background-color: #e9ecef;
            border-radius: 3px;
            font-size: 11px;
        }
        
        .summary-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .summary-card {
            flex: 1;
            min-width: 120px;
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        
        .summary-card h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: #007bff;
        }
        
        .summary-card p {
            margin: 0;
            font-size: 11px;
            color: #666;
        }
        
        .table-container {
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
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
            font-size: 11px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .progress-bar {
            width: 100%;
            height: 15px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 9px;
            font-weight: bold;
        }
        
        .progress-text {
            color: #333;
            font-size: 9px;
        }
        
        .text-success {
            color: #28a745;
        }
        
        .text-danger {
            color: #dc3545;
        }
        
        .text-warning {
            color: #ffc107;
        }
        
        .text-info {
            color: #17a2b8;
        }
        
        .fw-bold {
            font-weight: bold;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>گزارش تفکیک ساختمان‌ها بر اساس استان و شهر</h1>
        <p>تاریخ گزارش: {{ now()->format('Y/m/d H:i') }}</p>
    </div>

    @if(!empty($filters['search']))
    <div class="filters">
        <h4>فیلترهای اعمال شده:</h4>
        <div class="filter-item">جستجو: {{ $filters['search'] }}</div>
    </div>
    @endif

    <div class="summary-cards">
        <div class="summary-card">
            <h3>{{ $summary['total_provinces'] }}</h3>
            <p>کل استان‌ها</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['total_cities'] }}</h3>
            <p>کل شهرها</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['total_buildings'] }}</h3>
            <p>کل ساختمان‌ها</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['total_units'] }}</h3>
            <p>کل واحدها</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['avg_buildings_per_province'] }}</h3>
            <p>میانگین ساختمان در هر استان</p>
        </div>
        <div class="summary-card">
            <h3>{{ $summary['avg_buildings_per_city'] }}</h3>
            <p>میانگین ساختمان در هر شهر</p>
        </div>
    </div>

    <div class="section-title">لیست ساختمان‌ها</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ردیف</th>
                    <th>نام ساختمان</th>
                    <th>استان</th>
                    <th>شهر</th>
                    <th>آدرس</th>
                    <th>تعداد واحدها</th>
                    <th>تعداد طبقات</th>
                    <th>مدیر</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buildings as $index => $building)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $building->name }}</td>
                        <td>{{ $building->province ?? 'تعریف نشده' }}</td>
                        <td>{{ $building->city ?? 'تعریف نشده' }}</td>
                        <td>{{ $building->address }}</td>
                        <td>{{ $building->number_of_units }}</td>
                        <td>{{ $building->number_of_floors }}</td>
                        <td>{{ $building->manager->name ?? 'تعیین نشده' }}</td>
                        <td>
                            @if($building->manager_id)
                                <span class="text-success">دارای مدیر</span>
                            @else
                                <span class="text-warning">بدون مدیر</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">هیچ ساختمانی یافت نشد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="section-title">آمار تفکیکی بر اساس استان</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>استان</th>
                    <th>تعداد ساختمان</th>
                    <th>تعداد واحد</th>
                    <th>ساختمان‌های دارای مدیر</th>
                    <th>ساختمان‌های بدون مدیر</th>
                    <th>میانگین واحد در هر ساختمان</th>
                </tr>
            </thead>
            <tbody>
                @foreach($province_stats as $stat)
                    <tr>
                        <td>{{ $stat->province }}</td>
                        <td>{{ $stat->total_buildings }}</td>
                        <td>{{ $stat->total_units }}</td>
                        <td>{{ $stat->buildings_with_manager }}</td>
                        <td>{{ $stat->buildings_without_manager }}</td>
                        <td>{{ round($stat->avg_units_per_building, 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="section-title">آمار تفکیکی بر اساس شهر</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>استان</th>
                    <th>شهر</th>
                    <th>تعداد ساختمان</th>
                    <th>تعداد واحد</th>
                    <th>ساختمان‌های دارای مدیر</th>
                    <th>ساختمان‌های بدون مدیر</th>
                    <th>میانگین واحد در هر ساختمان</th>
                </tr>
            </thead>
            <tbody>
                @foreach($city_stats as $stat)
                    <tr>
                        <td>{{ $stat->province }}</td>
                        <td>{{ $stat->city }}</td>
                        <td>{{ $stat->total_buildings }}</td>
                        <td>{{ $stat->total_units }}</td>
                        <td>{{ $stat->buildings_with_manager }}</td>
                        <td>{{ $stat->buildings_without_manager }}</td>
                        <td>{{ round($stat->avg_units_per_building, 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 