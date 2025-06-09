@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap" >
        <h6 class="mb-0 fw-bold text-white fs-6 py-2 px-3">
            <i class="bi bi-house-door me-2"></i>مشاهده واحد {{ $unit->unit_number }}
        </h6>
    </div>

    <div class="card admin-table-card border-0">
        <div class="card-body p-3">
            {{-- مشخصات واحد --}}
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-123 icon"></i>
                        <div class="d-flex align-items-center">
                            <span class="label">شماره واحد:</span>
                            <span class="value ms-2 fs-6">{{ $unit->unit_number }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-layers icon"></i>
                        <div class="d-flex align-items-center">
                            <span class="label">طبقه:</span>
                            <span class="value ms-2 fs-6">{{ $unit->floor ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-rulers icon"></i>
                        <div class="d-flex align-items-center">
                            <span class="label">متراژ:</span>
                            <span class="value ms-2 fs-6">{{ $unit->area ?? '-' }} متر</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-car-front icon"></i>
                        <div class="d-flex align-items-center">
                            <span class="label">تعداد جای پارک:</span>
                            <span class="value ms-2 fs-6">{{ $unit->parking_slots ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-box-seam icon"></i>
                        <div class="d-flex align-items-center">
                            <span class="label">تعداد انباری:</span>
                            <span class="value ms-2 fs-6">{{ $unit->storerooms ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- اطلاعات مالک و مستاجر --}}
            <div class="border-top pt-4 mt-4">
                <h6 class="fw-bold mb-3 text-dark">اطلاعات کاربران</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="compact-info-card">
                            <i class="bi bi-person-check icon"></i>
                            <div class="d-flex align-items-center">
                                <span class="label">مالک:</span>
                                @if($unit->owner->first())
                                    <span class="value ms-2 fs-6">{{ $unit->owner->first()->full_name ?? $unit->owner->first()->name }}</span>
                                @else
                                    <span class="value ms-2 fs-6 text-muted">ندارد</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="compact-info-card">
                            <i class="bi bi-person icon"></i>
                            <div class="d-flex align-items-center">
                                <span class="label">ساکن :</span>
                                @if($unit->resident->first())
                                    <span class="value ms-2 fs-6">{{ $unit->resident->first()->full_name ?? $unit->resident->first()->name }}</span>
                                @else
                                    <span class="value ms-2 fs-6 text-muted">ندارد</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- دکمه‌ها --}}
            <div class="mt-4 text-end">
                <a href="{{ route('units.edit', [$building->id, $unit->id]) }}" class="btn btn-sm add-btn me-2">
                    <i class="bi bi-pencil-square me-1"></i>ویرایش واحد
                </a>
                <a href="{{ route('units.index', $building->id) }}" class="btn btn-sm filter-btn">
                    <i class="bi bi-arrow-right me-1"></i>بازگشت
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
