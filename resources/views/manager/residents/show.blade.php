@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="admin-header d-flex justify-content-between align-items-center mb-4">
            <h6 class="mb-0 fw-bold text-white">
                <i class="bi bi-person-badge me-2"></i>نمایش اطلاعات ساکن
            </h6>
        </div>

        <div class="admin-table-card p-4">
            {{-- اطلاعات شخصی --}}
            <div class="compact-info-card mb-4 mx-3">
                <i class="bi bi-person icon"></i>
                <div class="w-100">
                    <h6 class="text-muted mb-3">اطلاعات شخصی</h6>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="d-flex align-items-center">
                            <span class="label me-2">نام کامل:</span>
                            <span class="value">{{ $resident->name }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="label me-2">ایمیل:</span>
                            <span class="value">{{ $resident->email ?? '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="label me-2">تلفن:</span>
                            <span class="value">{{ $resident->phone ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- اطلاعات واحد --}}
            <div class="compact-info-card mb-4 mx-3">
                <i class="bi bi-house icon"></i>
                <div class="w-100">
                    <h6 class="text-muted mb-3">اطلاعات واحد</h6>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="d-flex align-items-center">
                            <span class="label me-2">شماره واحد:</span>
                            <span class="value">{{ $unitUser->unit->unit_number ?? '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="label me-2">نام ساختمان:</span>
                            <span class="value">{{ $unitUser->unit->building->name ?? '—' }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="label me-2">نقش:</span>
                            <span class="value">{{ $unitUser->role == 'owner' ? 'مالک' : 'ساکن' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- تاریخ اقامت --}}
            <div class="compact-info-card mb-4 mx-3">
                <i class="bi bi-calendar icon"></i>
                <div class="w-100">
                    <h6 class="text-muted mb-3">تاریخ اقامت</h6>
                    <div class="d-flex flex-wrap gap-4">
                        <div class="d-flex align-items-center">
                            <span class="label me-2">از تاریخ:</span>
                            <span class="value">{{ jdate($unitUser->from_date)->format('Y/m/d') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="label me-2">تا تاریخ:</span>
                            <span
                                class="value">{{ $unitUser->to_date ? jdate($unitUser->to_date)->format('Y/m/d') : 'نامشخص' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end px-3">
                <a href="{{ route('residents.index') }}" class="btn filter-btn">
                    <i class="bi bi-arrow-right me-1"></i>بازگشت
                </a>
            </div>
        </div>
    </div>
@endsection
