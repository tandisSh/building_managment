@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">مشاهده اطلاعات ساختمان</h5>
        </div>

        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-2 g-3 px-2">

                <div class="col">
                    <div class="card border rounded shadow-sm p-3 h-100">
                        <div class="d-flex align-items-center">
                            <label class="fw-bold text-secondary me-2 mb-0 flex-shrink-0">نام ساختمان:</label>
                            <span class="text-dark text-truncate">{{ $building->building_name }}</span>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border rounded shadow-sm p-3 h-100">
                        <div class="d-flex align-items-center">
                            <label class="fw-bold text-secondary me-2 mb-0 flex-shrink-0">آدرس:</label>
                            <span class="text-dark text-truncate">{{ $building->address }}</span>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border rounded shadow-sm p-3 h-100">
                        <div class="d-flex align-items-center">
                            <label class="fw-bold text-secondary me-2 mb-0 flex-shrink-0">تعداد طبقات:</label>
                            <span class="text-dark">{{ $building->number_of_floors }}</span>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border rounded shadow-sm p-3 h-100">
                        <div class="d-flex align-items-center">
                            <label class="fw-bold text-secondary me-2 mb-0 flex-shrink-0">تعداد واحدها:</label>
                            <span class="text-dark">{{ $building->number_of_units }}</span>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card border rounded shadow-sm p-3 h-100">
                        <div class="d-flex align-items-center">
                            <label class="fw-bold text-secondary me-2 mb-0 flex-shrink-0">مشترکات:</label>
                            <span class="text-dark">{{ $building->shared_water ? 'آب' : '-'}}</span>
                            <span class="text-dark">{{ $building->shared_gas ? 'گاز' : '-' }}</span>
                            <span class="text-dark">{{ $building->shared_electricity ? 'برق': '-' }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-4 px-2">
                <a href="{{ route('manager.building.edit', $building->id) }}" class="btn btn-outline-primary">
                    ویرایش ساختمان
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
