@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <h4 class="text-center text-primary">مشاهده اطلاعات ساختمان</h4>
        </div>
    </div>

    <div class="row g-3 justify-content-center">
        <div class="col-md-5">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">نام ساختمان</h6>
                    <p class="card-text">{{ $building->name }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">آدرس</h6>
                    <p class="card-text">{{ $building->address }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">تعداد طبقات</h6>
                    <p class="card-text">{{ $building->number_of_floors }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">تعداد واحدها</h6>
                    <p class="card-text">{{ $building->number_of_units }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="card-title text-muted">مشترکات</h6>
                    <p class="card-text">{{ $building->shared_utilities ? 'بله' : 'خیر' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 justify-content-center">
        <div class="col-md-4 text-center">
            <a href="{{ route('manager.building.edit', $building->id) }}" class="btn btn-outline-primary w-100">
                ویرایش ساختمان
            </a>
        </div>
    </div>
</div>
@endsection
