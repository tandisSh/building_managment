@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-graph-up"></i> گزارش تحلیل درآمد</h6>
    </div>

    <div class="card search-filter-card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center text-center">
                <div class="col-auto">
                    <input type="date" name="start_date" class="form-control form-control-sm search-input"
                        value="{{ request('start_date') }}" placeholder="از تاریخ" style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <input type="date" name="end_date" class="form-control form-control-sm search-input"
                        value="{{ request('end_date') }}" placeholder="تا تاریخ" style="max-width: 200px;">
                </div>
                <div class="col-auto">
                    <select name="building_id" class="form-control form-control-sm search-input" style="max-width: 200px;">
                        <option value="">همه ساختمان‌ها</option>
                        @foreach(\App\Models\Building::all() as $building)
                            <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">فیلتر</button>
                    <a href="{{ route('superadmin.reports.revenue_analysis') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <!-- خلاصه کلی -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($summary['total_revenue']) }} تومان</h5>
                    <p class="card-text small">کل درآمد</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_payments'] }}</h5>
                    <p class="card-text small">کل پرداخت‌ها</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($summary['average_payment']) }} تومان</h5>
                    <p class="card-text small">میانگین پرداخت</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['unique_users'] }}</h5>
                    <p class="card-text small">کاربران منحصر به فرد</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-secondary text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['unique_buildings'] }}</h5>
                    <p class="card-text small">ساختمان‌های فعال</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center {{ $summary['revenue_growth'] >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($summary['revenue_growth'], 1) }}%</h5>
                    <p class="card-text small">رشد درآمد</p>
                </div>
            </div>
        </div>
    </div>

    <!-- کارت‌های اضافی -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h6 class="card-title text-success">{{ number_format($summary['current_month_revenue']) }} تومان</h6>
                    <p class="card-text small">درآمد ماه جاری</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h6 class="card-title text-primary">{{ number_format($summary['previous_month_revenue']) }} تومان</h6>
                    <p class="card-text small">درآمد ماه گذشته</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <h6 class="card-title text-info">{{ number_format($summary['forecast_next_month']) }} تومان</h6>
                    <p class="card-text small">پیش‌بینی ماه آینده</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-light">
                <div class="card-body">
                    <a href="{{ route('superadmin.reports.revenue_analysis.print', request()->query()) }}" target="_blank"
                        class="btn btn-info btn-sm">چاپ گزارش</a>
                </div>
            </div>
        </div>
    </div>

    <!-- تحلیل درآمد ماهانه -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-calendar3"></i> تحلیل درآمد ماهانه</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
            </div>
        </div>
    </div>

    <!-- تحلیل درآمد بر اساس ساختمان -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-building"></i> درآمد بر اساس ساختمان</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
            </div>
        </div>
    </div>

    <!-- تحلیل درآمد بر اساس نوع فاکتور -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-receipt"></i> درآمد بر اساس نوع فاکتور</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
            </div>
        </div>
    </div>


    <!-- برترین کاربران -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-trophy"></i> برترین کاربران (از نظر درآمد)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
            </div>
        </div>
    </div>

    <!-- آخرین پرداخت‌ها -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-clock-history"></i> آخرین پرداخت‌ها</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
            </div>
        </div>
    </div>
@endsection
