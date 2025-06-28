@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-people-fill"></i> گزارش فعالیت کاربران</h6>
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
                    <select name="role" class="form-control form-control-sm search-input" style="max-width: 200px;">
                        <option value="">همه نقش‌ها</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>سوپر ادمین</option>
                        <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>مدیر</option>
                        <option value="resident" {{ request('role') == 'resident' ? 'selected' : '' }}>ساکن</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="status" class="form-control form-control-sm search-input" style="max-width: 200px;">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                    </select>
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
                    <a href="{{ route('superadmin.reports.user_activity') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <!-- خلاصه کلی -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_users'] }}</h5>
                    <p class="card-text small">کل کاربران</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['active_users'] }}</h5>
                    <p class="card-text small">کاربران فعال</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['inactive_users'] }}</h5>
                    <p class="card-text small">کاربران غیرفعال</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($summary['average_activity_score'], 1) }}</h5>
                    <p class="card-text small">میانگین امتیاز فعالیت</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_payments'] }}</h5>
                    <p class="card-text small">کل پرداخت‌ها</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_repair_requests'] }}</h5>
                    <p class="card-text small">کل درخواست‌های تعمیر</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center text-center">
        <div>
            <strong>تعداد کاربران:</strong> {{ $users->count() }}
        </div>
        <div>
            <a href="{{ route('superadmin.reports.user_activity.print', request()->query()) }}" target="_blank"
                class="btn btn-info btn-sm">چاپ گزارش</a>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            @if ($users->count() > 0)
                <table class="table table-bordered table-striped align-middle text-center table-units">
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
                                        @php
                                            $roleClass = match($role) {
                                                'super_admin' => 'bg-danger',
                                                'manager' => 'bg-primary',
                                                'resident' => 'bg-success',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $roleClass }} me-1">{{ $role }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user['status'] === 'active' ? 'success' : 'warning text-dark' }} text-center">
                                        {{ $user['status'] === 'active' ? 'فعال' : 'غیرفعال' }}
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <strong>کل:</strong> {{ $user['total_units'] }}<br>
                                        <strong>فعال:</strong> {{ $user['active_units'] }}<br>
                                        <strong>مالک:</strong> {{ $user['owner_units'] }}<br>
                                        <strong>ساکن:</strong> {{ $user['resident_units'] }}
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <strong>تعداد:</strong> {{ $user['total_payments'] }}<br>
                                        <strong>مبلغ:</strong> {{ number_format($user['total_paid_amount']) }} تومان<br>
                                        @if($user['last_payment'])
                                            <strong>آخرین:</strong> {{ \Carbon\Carbon::parse($user['last_payment'])->format('Y/m/d') }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <strong>کل:</strong> {{ $user['total_repair_requests'] }}<br>
                                        <strong>در انتظار:</strong> {{ $user['pending_repair_requests'] }}<br>
                                        <strong>تکمیل شده:</strong> {{ $user['completed_repair_requests'] }}<br>
                                        @if($user['last_repair_request'])
                                            <strong>آخرین:</strong> {{ \Carbon\Carbon::parse($user['last_repair_request'])->format('Y/m/d') }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        <strong>کل:</strong> {{ $user['total_notifications'] }}<br>
                                        <strong>نخوانده:</strong> {{ $user['unread_notifications'] }}
                                    </small>
                                </td>
                                <td>
                                    <small>
                                        {{ \Carbon\Carbon::parse($user['last_login'])->format('Y/m/d H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             style="width: {{ min(100, $user['activity_score']) }}%">
                                            {{ number_format($user['activity_score'], 1) }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $activityClass = match($user['activity_status']) {
                                            'خیلی فعال' => 'bg-success',
                                            'فعال' => 'bg-info',
                                            'متوسط' => 'bg-warning',
                                            'کم‌فعال' => 'bg-secondary',
                                            'غیرفعال' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $activityClass }} text-center">
                                        {{ $user['activity_status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">
                    هیچ کاربری یافت نشد.
                </div>
            @endif
        </div>
    </div>
@endsection 