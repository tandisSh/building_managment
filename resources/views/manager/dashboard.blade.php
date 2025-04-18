@extends('layouts.app')

@section('title', 'داشبورد مدیر')

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white py-3">
        <h5 class="mb-0"><i class="bi bi-speedometer2"></i> آمار کلی</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-left-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-success">ساختمان‌های فعال</h6>
                                <h3 class="mb-0">5</h3>
                            </div>
                            <i class="bi bi-building fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- کارت‌های دیگر به همین صورت -->
        </div>
    </div>
</div>
@endsection
