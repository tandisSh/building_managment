@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-bar-chart"></i> گزارش آمار کلی سیستم</h6>
    </div>

    <!-- آمار کلی -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-purple-300 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $users['total'] }}</h5>
                    <p class="card-text small">کل کاربران</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-purple-500 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $buildings['total'] }}</h5>
                    <p class="card-text small">کل ساختمان‌ها</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-purple-700 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $units['total'] }}</h5>
                    <p class="card-text small">کل واحدها</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-purple-900 text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($payments['total_amount']) }} تومان</h5>
                    <p class="card-text small">کل درآمد</p>
                </div>
            </div>
        </div>
    </div>

    <!-- آمار کاربران -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-people"></i> آمار کاربران</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-success">{{ $users['active'] }}</h4>
                            <small>فعال</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-danger">{{ $users['inactive'] }}</h4>
                            <small>غیرفعال</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-primary">{{ number_format($users['activity_rate'], 1) }}%</h4>
                            <small>نرخ فعالیت</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-4">
                            <h6 class="text-info">{{ $users['super_admins'] }}</h6>
                            <small>سوپر ادمین</small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-warning">{{ $users['managers'] }}</h6>
                            <small>مدیر</small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-secondary">{{ $users['residents'] }}</h6>
                            <small>ساکن</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-building"></i> آمار ساختمان‌ها</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-success">{{ $buildings['active'] }}</h4>
                            <small>فعال</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-danger">{{ $buildings['inactive'] }}</h4>
                            <small>غیرفعال</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-primary">{{ number_format($buildings['activity_rate'], 1) }}%</h4>
                            <small>نرخ فعالیت</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-info">{{ $buildings['with_manager'] }}</h6>
                            <small>دارای مدیر</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning">{{ $buildings['without_manager'] }}</h6>
                            <small>بدون مدیر</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- آمار واحدها و فاکتورها -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-house"></i> آمار واحدها</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-success">{{ $units['occupied'] }}</h4>
                            <small>اشغال شده</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning">{{ $units['vacant'] }}</h4>
                            <small>خالی</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-primary">{{ number_format($units['occupancy_rate'], 1) }}%</h4>
                            <small>نرخ اشغال</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-info">{{ $units['owner'] }}</h6>
                            <small>مالک</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-secondary">{{ $units['tenant'] }}</h6>
                            <small>مستاجر</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-receipt"></i> آمار فاکتورها</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-success">{{ $invoices['paid'] }}</h4>
                            <small>پرداخت شده</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-danger">{{ $invoices['unpaid'] }}</h4>
                            <small>پرداخت نشده</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-primary">{{ number_format($invoices['payment_rate'], 1) }}%</h4>
                            <small>نرخ پرداخت</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-warning">{{ $invoices['overdue'] }}</h6>
                            <small>معوق</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-info">{{ number_format($invoices['total_amount']) }} تومان</h6>
                            <small>کل مبلغ</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- آمار پرداخت‌ها و درخواست‌های تعمیر -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-credit-card"></i> آمار پرداخت‌ها</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-success">{{ $payments['successful'] }}</h4>
                            <small>موفق</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-danger">{{ $payments['failed'] }}</h4>
                            <small>ناموفق</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-primary">{{ number_format($payments['success_rate'], 1) }}%</h4>
                            <small>نرخ موفقیت</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-info">{{ number_format($payments['average_amount']) }} تومان</h6>
                            <small>میانگین پرداخت</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning">{{ number_format($payments['total_amount']) }} تومان</h6>
                            <small>کل درآمد</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-tools"></i> آمار درخواست‌های تعمیر</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-3">
                            <h6 class="text-warning">{{ $repair_requests['pending'] }}</h6>
                            <small>در انتظار</small>
                        </div>
                        <div class="col-3">
                            <h6 class="text-info">{{ $repair_requests['in_progress'] }}</h6>
                            <small>در حال انجام</small>
                        </div>
                        <div class="col-3">
                            <h6 class="text-success">{{ $repair_requests['completed'] }}</h6>
                            <small>تکمیل شده</small>
                        </div>
                        <div class="col-3">
                            <h6 class="text-danger">{{ $repair_requests['cancelled'] }}</h6>
                            <small>لغو شده</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-primary">{{ number_format($repair_requests['completion_rate'], 1) }}%</h6>
                            <small>نرخ تکمیل</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-secondary">{{ $repair_requests['total'] }}</h6>
                            <small>کل درخواست‌ها</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- آمار اعلان‌ها و درخواست‌های ساختمان -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-bell"></i> آمار اعلان‌ها</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-success">{{ $notifications['read'] }}</h4>
                            <small>خوانده شده</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning">{{ $notifications['unread'] }}</h4>
                            <small>نخوانده</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-primary">{{ $notifications['total'] }}</h4>
                            <small>کل اعلان‌ها</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-building-add"></i> آمار درخواست‌های ساختمان</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-warning">{{ $building_requests['pending'] }}</h4>
                            <small>در انتظار</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-success">{{ $building_requests['approved'] }}</h4>
                            <small>تایید شده</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-danger">{{ $building_requests['rejected'] }}</h4>
                            <small>رد شده</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <h6 class="text-primary">{{ $building_requests['total'] }}</h6>
                            <small>کل درخواست‌ها</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- آمار ماهانه -->
    <div class="card mb-4">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-calendar3"></i> آمار ماهانه (آخرین 6 ماه)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
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
            </div>
        </div>
    </div>

    <!-- فعالیت‌های اخیر -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> آخرین کاربران</h6>
                </div>
                <div class="card-body">
                    @if($users['recent']->count() > 0)
                        @foreach($users['recent'] as $user)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                                <small class="text-muted">{{ $user->created_at->format('Y/m/d') }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">هیچ کاربری یافت نشد.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> آخرین پرداخت‌ها</h6>
                </div>
                <div class="card-body">
                    @if($payments['recent']->count() > 0)
                        @foreach($payments['recent'] as $payment)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>{{ $payment->user->name }}</strong>
                                    <br>
                                    <small class="text-success">{{ number_format($payment->amount) }} تومان</small>
                                </div>
                                <small class="text-muted">{{ $payment->paid_at->format('Y/m/d') }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">هیچ پرداختی یافت نشد.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 text-center">
        <a href="{{ route('superadmin.reports.system_statistics.print') }}" target="_blank"
            class="btn btn-info">چاپ گزارش</a>
    </div>
@endsection
