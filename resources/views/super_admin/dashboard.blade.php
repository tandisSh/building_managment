@extends('layouts.app')

@section('title', 'داشبورد سوپرادمین')

@section('content')
<div class="card shadow-lg">
    <div class="card-header text-white py-3" style="background: linear-gradient(90deg, #d81b60, #8e24aa);">
        <h5 class="mb-0"><i class="bi bi-shield-lock"></i> مدیریت سیستم</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100" style="border-right: 5px solid #64b5f6;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-primary">مدیران سیستم</h6>
                                <h3 class="mb-0">8</h3>
                            </div>
                            <i class="bi bi-people fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100" style="border-right: 5px solid #ffb74d;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-warning">درخواست‌های جدید</h6>
                                <h3 class="mb-0">5</h3>
                            </div>
                            <i class="bi bi-list-check fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100" style="border-right: 5px solid #81c784;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-success">ساختمان‌های فعال</h6>
                                <h3 class="mb-0">23</h3>
                            </div>
                            <i class="bi bi-building fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h5 class="mb-3"><i class="bi bi-clock-history"></i> آخرین درخواست‌ها</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ردیف</th>
                            <th>نام ساختمان</th>
                            <th>مدیر</th>
                            <th>تاریخ</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $index => $request)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $request->building_name }}</td>
                                <td>{{ $request->user->name ?? '-' }}</td>
                                <td>{{ jdate($request->created_at)->format('Y/m/d') }}</td>
                                <td>
                                    @switch($request->status)
                                        @case('pending')
                                            <span class="badge bg-warning">در انتظار</span>
                                            @break
                                        @case('approved')
                                            <span class="badge bg-success">تایید شده</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">رد شده</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">نامشخص</span>
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">هیچ درخواستی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>
</div>
@endsection
