@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="admin-header d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="bi bi-wrench-adjustable-circle me-2"></i> جزئیات درخواست تعمیر
            </h6>
            <a href="{{ route('requests.index') }}" class="btn filter-btn">
                <i class="bi bi-arrow-right me-1"></i>بازگشت به لیست
            </a>
        </div>

        <div class="admin-table-card p-4">
            <div class="compact-info-card mb-4 mx-3">
                <i class="bi bi-card-text icon"></i>
                <div class="w-100">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-center">
                            <span class="label me-3">عنوان درخواست:</span>
                            <span class="value">{{ $request->title }}</span>
                        </div>
                        <div class="d-flex align-items-start">
                            <span class="label me-3">توضیحات:</span>
                            <span class="value">{{ $request->description }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="compact-info-card mb-4 mx-3">
                <i class="bi bi-house-door icon"></i>
                <div class="w-100 d-flex flex-wrap gap-4">
                    <div class="d-flex align-items-center">
                        <span class="label me-3">شماره واحد:</span>
                        <span class="value">{{ $request->unit->unit_number ?? '-' }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="label me-3">ساکن:</span>
                        <span class="value">{{ $request->user->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="compact-info-card mx-3">
                <i class="bi bi-calendar-check icon"></i>
                <div class="w-100 d-flex align-items-center">
                    <span class="label me-3">تاریخ ثبت:</span>
                    <span class="value">{{ jdate($request->created_at)->format('Y/m/d') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
