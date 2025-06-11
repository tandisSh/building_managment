@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap" >
        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-speedometer2 me-1"></i> وضعیت درخواست‌های ثبت ساختمان</h6>
    </div>

    <div class="card admin-table-card">
        <div class="card-body p-3">
            @if($requests->isEmpty())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    شما هنوز هیچ درخواستی برای ثبت ساختمان ارسال نکرده‌اید.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle small table-units">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>نام ساختمان</th>
                                <th class="text-center">تاریخ درخواست</th>
                                <th class="text-center">وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $index => $request)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $request->building_name ?? '---' }}</td>
                                    <td class="text-center">{{ \Morilog\Jalali\Jalalian::fromDateTime($request->created_at)->format('Y/m/d') }}</td>
                                    <td class="text-center">
                                        @switch($request->status)
                                            @case('approved')
                                                <span class="badge bg-success py-2 px-3 rounded-pill">
                                                    <i class="bi bi-check-circle me-1"></i> تایید شده
                                                </span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning text-dark py-2 px-3 rounded-pill">
                                                    <i class="bi bi-hourglass-split me-1"></i> در انتظار بررسی
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger py-2 px-3 rounded-pill">
                                                    <i class="bi bi-x-circle me-1"></i> رد شده
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary py-2 px-3 rounded-pill">
                                                    <i class="bi bi-question-circle me-1"></i> نامشخص
                                                </span>
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
