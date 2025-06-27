@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="admin-header d-flex justify-content-between align-items-center mb-4 shadow-sm rounded"
             style="background-color: #4e3cb3;">
            <h6 class="mb-0 fw-bold text-white py-2 px-3">
                <i class="bi bi-person me-2"></i>اطلاعات ساکن
            </h6>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-sm btn-light me-3">بازگشت</a>
        </div>

        <div class="admin-table-card p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="small text-muted">نام کامل:</label>
                    <div class="fw-bold">{{ $resident->name ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted">شماره موبایل:</label>
                    <div class="fw-bold">{{ $resident->phone ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted">ایمیل:</label>
                    <div class="fw-bold">{{ $resident->email ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted">واحد:</label>
                    <div class="fw-bold">واحد {{ $resident->unit->unit_number ?? '-' }} - طبقه {{ $resident->unit->floor ?? '-' }}</div>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted">نقش:</label>
                    <div class="fw-bold">{{ $resident->roles }}</div>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted">تعداد افراد خانوار:</label>
                    <div class="fw-bold">{{ $resident->resident_count }}</div>
                </div>
                <div class="col-md-6">
                    <label class="small text-muted">تاریخ شروع سکونت:</label>
                    <div class="fw-bold">{{ jdate($resident->from_date)->format('Y/m/d') }}</div>
                </div>
                @if($resident->to_date)
                <div class="col-md-6">
                    <label class="small text-muted">تاریخ پایان سکونت:</label>
                    <div class="fw-bold">{{ jdate($resident->to_date)->format('Y/m/d') }}</div>
                </div>
                @endif
                <div class="col-md-6">
                    <label class="small text-muted">وضعیت:</label>
                    <div class="fw-bold">
                        @if($resident->status == 'active')
                            <span class="badge bg-success">فعال</span>
                        @else
                            <span class="badge bg-danger">غیرفعال</span>
                        @endif
                    </div>
                </div>
                {{-- <div class="col-12 mt-4">
                    <a href="{{ route('superadmin.users.edit', $resident->user->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square me-1"></i>ویرایش اطلاعات
                    </a>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
