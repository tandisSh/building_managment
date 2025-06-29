@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 rounded flex-wrap"
        style="background-color: #4e3cb3; color: #fff; padding: 8px 16px;">
        <h6 class="mb-0 fw-bold text-white text-center fs-6 py-2 px-3">
            <i class="bi bi-building me-2"></i>مشاهده اطلاعات ساختمان
        </h6>
    </div>



    <div class="card admin-table-card border-0 mb-3">
        <div class="card-body p-2">
            <div class="row g-3">
                {{-- نام ساختمان --}}
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-building icon"></i>
                        <div><strong>نام ساختمان:</strong>{{ $building->name ?? 'تعریف نشده' }}
                        </div>
                    </div>
                </div>

                {{-- آدرس --}}
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-geo-alt icon"></i>
                        <div><strong>آدرس:</strong> {{ $building->address ?? 'تعریف نشده' }}
                        </div>
                    </div>
                </div>

                {{-- استان --}}
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-geo-alt icon"></i>
                        <div><strong>استان:</strong> {{ $building->province ?? 'تعریف نشده' }}</div>
                    </div>
                </div>

                {{-- شهر --}}
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-geo-alt icon"></i>
                        <div><strong>شهر:</strong> {{ $building->city ?? 'تعریف نشده' }}</div>
                    </div>
                </div>

                {{-- نام مدیر --}}
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-person-circle icon"></i>
                        <div><strong>مدیر ساختمان:</strong> {{ $building->manager->name ?? 'تعریف نشده' }}</div>
                    </div>
                </div>

                {{-- تعداد طبقات --}}
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-sort-numeric-up icon"></i>
                        <div><strong>تعداد طبقات:</strong> {{ $building->number_of_floors }}</div>
                    </div>
                </div>

                {{-- تعداد واحدها --}}
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-house-door icon"></i>
                        <div><strong>تعداد واحدها:</strong> {{ $building->number_of_units }}</div>
                    </div>
                </div>

                {{-- وضعیت منابع مشترک --}}
                <div class="col-12 col-md-6">
                    <div class="compact-info-card">
                        <i class="bi bi-sliders icon"></i>
                        <strong>منابع مشترک:</strong><br>
                        <span class="badge bg-{{ $building->shared_water ? 'primary' : 'secondary' }} mx-1">آب</span>
                        <span class="badge bg-{{ $building->shared_gas ? 'primary' : 'secondary' }} mx-1">گاز</span>
                        <span class="badge bg-{{ $building->shared_electricity ? 'primary' : 'secondary' }} mx-1">برق</span>
                    </div>
                </div>

                   <div class="d-flex justify-content-end gap-2">

                <a href="{{ route('superadmin.buildings.index') }}" class="btn cancel-btn">
                    <i class="bi bi-arrow-right me-1"></i>بازگشت
                </a>
            </div>
            </div>
        </div>
    </div>
@endsection
