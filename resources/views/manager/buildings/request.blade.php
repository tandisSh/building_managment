@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header text-white py-3">
        <h5 class="mb-0"><i class="bi bi-building-add"></i> فرم درخواست ساختمان جدید</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('manager.buildings.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="building_name" class="form-label">نام ساختمان *</label>
                    <input type="text" class="form-control @error('building_name') is-invalid @enderror" id="building_name" name="building_name" required>
                    @error('building_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="address" class="form-label">آدرس کامل *</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required></textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="document" class="form-label">مدارک ملک *</label>
                    <input type="file" class="form-control @error('document') is-invalid @enderror" id="document" name="document" accept=".pdf,.jpg,.png" required>
                    <small class="text-muted">فرمت‌های مجاز: PDF, JPG, PNG (حداکثر 2MB)</small>
                    @error('document')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn w-100 py-2 btn-submit">
                        <i class="bi bi-send-check"></i> ارسال درخواست
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
