<!-- resources/views/manager/buildings/request.blade.php -->
@extends('layouts.app')

@section('title', 'ثبت درخواست ساختمان جدید')

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-building"></i>
            فرم درخواست ثبت ساختمان جدید
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('manager.buildings.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <!-- نام ساختمان -->
                <div class="col-md-6">
                    <label for="building_name" class="form-label">نام ساختمان *</label>
                    <input type="text" class="form-control @error('building_name') is-invalid @enderror"
                           id="building_name" name="building_name" value="{{ old('building_name') }}" required>
                    @error('building_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- آدرس -->
                <div class="col-12">
                    <label for="address" class="form-label">آدرس کامل *</label>
                    <textarea class="form-control @error('address') is-invalid @enderror"
                              id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- آپلود مدارک -->
                <div class="col-md-6">
                    <label for="document" class="form-label">مدارک ملک *</label>
                    <input type="file" class="form-control @error('document') is-invalid @enderror"
                           id="document" name="document" accept=".pdf,.jpg,.png" required>
                    <small class="text-muted">فرمت‌های مجاز: PDF, JPG, PNG (حداکثر 2MB)</small>
                    @error('document')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- دکمه ارسال -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-send-check"></i>
                        ارسال درخواست
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer bg-light">
        <small class="text-muted">
            * پس از تأیید ادمین، ساختمان به سیستم اضافه خواهد شد.
        </small>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 15px;
    }
    .form-control {
        border-radius: 10px;
    }
</style>
@endpush
