@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 rounded flex-wrap" style="background-color: #4e3cb3; color: #fff; padding: 8px 16px;">
        <h6 class="mb-0 fw-bold text-white text-center fs-6 py-2 px-3">
            <i class="bi bi-building me-2"></i>مشاهده اطلاعات ساختمان
        </h6>
    </div>

    <div class="card admin-table-card border-0 mb-3">
        <div class="card-body p-2">
            <div class="row g-2">
                <!-- کارت اطلاعات ساختمان -->
                <div class="col-12 col-md-6">
                    <div class="compact-info-card" style="min-height: 40px; padding: 5px 10px;">
                        <i class="bi bi-building icon" style="width: 28px; height: 28px; font-size: 1rem;"></i>
                        <div class="d-flex align-items-center">
                            <span class="label fs-6">نام ساختمان:</span>
                            <span class="value ms-1 fs-6">{{ $building->name }}</span>
                        </div>
                    </div>
                </div>

                <!-- کارت آدرس -->
                <div class="col-12 col-md-6">
                    <div class="compact-info-card" style="min-height: 40px; padding: 5px 10px;">
                        <i class="bi bi-geo-alt icon" style="width: 28px; height: 28px; font-size: 1rem;"></i>
                        <div class="d-flex align-items-center">
                            <span class="label fs-6">آدرس:</span>
                            <span class="value ms-1 fs-6 text-truncate">{{ $building->address }}</span>
                        </div>
                    </div>
                </div>

                <!-- کارت تعداد طبقات -->
                <div class="col-12 col-md-6">
                    <div class="compact-info-card" style="min-height: 40px; padding: 5px 10px;">
                        <i class="bi bi-layers icon" style="width: 28px; height: 28px; font-size: 1rem;"></i>
                        <div class="d-flex align-items-center">
                            <span class="label fs-6">تعداد طبقات:</span>
                            <span class="value ms-1 fs-6">{{ $building->number_of_floors }}</span>
                        </div>
                    </div>
                </div>

                <!-- کارت تعداد واحدها -->
                <div class="col-12 col-md-6">
                    <div class="compact-info-card" style="min-height: 40px; padding: 5px 10px;">
                        <i class="bi bi-door-closed icon" style="width: 28px; height: 28px; font-size: 1rem;"></i>
                        <div class="d-flex align-items-center">
                            <span class="label fs-6">تعداد واحدها:</span>
                            <span class="value ms-1 fs-6">{{ $building->number_of_units }}</span>
                        </div>
                    </div>
                </div>

                <!-- کارت مشترکات -->
                <div class="col-12">
                    <div class="compact-info-card" style="min-height: 40px; padding: 5px 10px;">
                        <i class="bi bi-lightning-charge icon" style="width: 28px; height: 28px; font-size: 1rem;"></i>
                        <div class="d-flex align-items-center">
                            <span class="label fs-6">مشترکات:</span>
                            <div class="d-flex gap-1 ms-1">
                                <span class="badge bg-{{ $building->shared_water ? 'primary' : 'secondary' }} py-0 px-2 fs-6">
                                    آب
                                </span>
                                <span class="badge bg-{{ $building->shared_gas ? 'primary' : 'secondary' }} py-0 px-2 fs-6">
                                    گاز
                                </span>
                                <span class="badge bg-{{ $building->shared_electricity ? 'primary' : 'secondary' }} py-0 px-2 fs-6">
                                    برق
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 text-end">
                <a href="{{ route('manager.building.edit', $building->id) }}" class="btn btn-sm add-btn">
                    <i class="bi bi-pencil-square me-1"></i>ویرایش ساختمان
                </a>
            </div>
        </div>
    </div>
@endsection
