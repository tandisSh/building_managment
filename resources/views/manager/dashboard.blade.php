@extends('layouts.app')

@section('title', 'داشبورد مدیر')

@section('content')
<div class="card shadow-sm" style="font-size: 0.9rem">
    <div class="card-header text-white py-2" style="background: linear-gradient(90deg, #7e57c2, #64b5f6);">
        <h6 class="mb-0"><i class="bi bi-speedometer2 me-1"></i> وضعیت درخواست‌های ثبت ساختمان</h6>
    </div>

    <div class="card-body p-3">
        @if($requests->isEmpty())
            <div class="alert alert-warning">
                شما هنوز هیچ درخواستی برای ثبت ساختمان ارسال نکرده‌اید.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>نام ساختمان</th>
                            <th>تاریخ درخواست</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $index => $request)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $request->building_name ?? '---' }}</td>
                                <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($request->created_at)->format('Y/m/d') }}</td>
                                <td>
                                    @switch($request->status)
                                        @case('approved')
                                            <span class="badge bg-success">تایید شده</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">در انتظار بررسی</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-danger">رد شده</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">نامشخص</span>
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
