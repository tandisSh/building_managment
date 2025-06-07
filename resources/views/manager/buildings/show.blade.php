@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark fs-6 py-2 px-3">
            <i class="bi bi-building me-2"></i>مشاهده اطلاعات ساختمان
        </h6>
    </div>

    <div class="card admin-table-card border-0">
        <div class="card-body p-3">
            <div class="row g-3">
                <!-- کارت اطلاعات ساختمان -->
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-building icon"></i>
                        <div class="d-flex align-items-center">
                            <span class="label">نام ساختمان:</span>
                            <span class="value ms-2 fs-6">{{ $building->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- کارت آدرس -->
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-geo-alt icon"></i>
                        <div class="d-flex align-items-center">
                            <span class="label">آدرس:</span>
                            <span class="value ms-2 fs-6 text-truncate">{{ $building->address }}</span>
                        </div>
                    </div>
                </div>

                <!-- کارت تعداد طبقات -->
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-layers icon"></i>
                        <div class="d-flex align-items-center">
                            <span class="label">تعداد طبقات:</span>
                            <span class="value ms-2 fs-6">{{ $building->number_of_floors }}</span>
                        </div>
                    </div>
                </div>

                <!-- کارت تعداد واحدها -->
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-door-closed icon"></i>
                        <div class="d-flex align-items-center">
                            <span class="label">تعداد واحدها:</span>
                            <span class="value ms-2 fs-6">{{ $building->number_of_units }}</span>
                        </div>
                    </div>
                </div>

                <!-- کارت مشترکات -->
                <div class="col-12">
                    <div class="compact-info-card">
                        <i class="bi bi-lightning-charge icon"></i>
                        <div class="d-flex align-items-center">
                            <span class="label">مشترکات:</span>
                            <div class="d-flex gap-2 ms-2">
                                <span class="badge bg-{{ $building->shared_water ? 'primary' : 'secondary' }} py-1 px-2 fs-6">
                                    آب
                                </span>
                                <span class="badge bg-{{ $building->shared_gas ? 'primary' : 'secondary' }} py-1 px-2 fs-6">
                                    گاز
                                </span>
                                <span class="badge bg-{{ $building->shared_electricity ? 'primary' : 'secondary' }} py-1 px-2 fs-6">
                                    برق
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('manager.building.edit', $building->id) }}" class="btn btn-sm add-btn">
                    <i class="bi bi-pencil-square me-1"></i>ویرایش ساختمان
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
