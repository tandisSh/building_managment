@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-white text-center"><i class="bi bi-building-add"></i> گزارش درخواست‌های ساختمان</h6>
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
                    <select name="status" class="form-control form-control-sm search-input" style="max-width: 200px;">
                        <option value="">همه وضعیت‌ها</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>تایید شده</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>رد شده</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary filter-btn">فیلتر</button>
                    <a href="{{ route('superadmin.reports.building_requests') }}" class="btn btn-sm btn-outline-secondary filter-btn">حذف فیلتر</a>
                </div>
            </form>
        </div>
    </div>

    <!-- خلاصه کلی -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_requests'] }}</h5>
                    <p class="card-text small">کل درخواست‌ها</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['pending_requests'] }}</h5>
                    <p class="card-text small">در انتظار بررسی</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['approved_requests'] }}</h5>
                    <p class="card-text small">تایید شده</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['rejected_requests'] }}</h5>
                    <p class="card-text small">رد شده</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($summary['average_processing_days'], 1) }}</h5>
                    <p class="card-text small">میانگین روزهای بررسی</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center bg-secondary text-white">
                <div class="card-body">
                    <h5 class="card-title">{{ $summary['total_units_requested'] }}</h5>
                    <p class="card-text small">کل واحدهای درخواستی</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center text-center">
        <div>
            <strong>تعداد درخواست‌ها:</strong> {{ $requests->count() }}
        </div>
        <div>
            <a href="{{ route('superadmin.reports.building_requests.print', request()->query()) }}" target="_blank"
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

            @if ($requests->count() > 0)
                <table class="table table-bordered table-striped align-middle text-center table-units">
                    <thead>
                        <tr>
                            <th>نام متقاضی</th>
                            <th>اطلاعات تماس</th>
                            <th>نام ساختمان</th>
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
                                <td>
                                    <strong>{{ $request['user_name'] }}</strong>
                                </td>
                                <td>
                                    <small>
                                        <strong>ایمیل:</strong> {{ $request['user_email'] }}<br>
                                        <strong>تلفن:</strong> {{ $request['user_phone'] }}
                                    </small>
                                </td>
                                <td>{{ $request['building_name'] }}</td>
                                <td>
                                    <small>{{ $request['building_address'] }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $request['total_units'] }} واحد</span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($request['status']) {
                                            'pending' => 'bg-warning text-dark',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        $statusText = match($request['status']) {
                                            'pending' => 'در انتظار',
                                            'approved' => 'تایید شده',
                                            'rejected' => 'رد شده',
                                            default => 'نامشخص'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    <small>
                                        {{ \Carbon\Carbon::parse($request['created_at'])->format('Y/m/d') }}<br>
                                        {{ \Carbon\Carbon::parse($request['created_at'])->format('H:i') }}
                                    </small>
                                </td>
                                <td>
                                    @if($request['status'] === 'pending')
                                        <small class="text-warning">
                                            <strong>{{ $request['waiting_days'] }} روز</strong><br>
                                            در انتظار
                                        </small>
                                    @else
                                        <small class="text-info">
                                            <strong>{{ $request['processing_days'] }} روز</strong><br>
                                            بررسی شد
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if($request['has_document'])
                                        <span class="badge bg-success">
                                            <i class="bi bi-file-earmark-check"></i> موجود
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-file-earmark-x"></i> موجود نیست
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($request['description'])
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $request['id'] }}">
                                            <i class="bi bi-eye"></i> مشاهده
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="descriptionModal{{ $request['id'] }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">توضیحات درخواست</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ $request['description'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info text-center">
                    هیچ درخواستی یافت نشد.
                </div>
            @endif
        </div>
    </div>
@endsection
