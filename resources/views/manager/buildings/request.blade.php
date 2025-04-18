@extends('layouts.app')

@section('title', 'ثبت درخواست ساختمان جدید')

@section('content')
<div class="card shadow-lg">
    <div class="card-header text-white py-3" style="background: linear-gradient(90deg, #7b1fa2, #4fc3f7);">
        <h5 class="mb-0"><i class="bi bi-building-add"></i> فرم درخواست ساختمان جدید</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('manager.buildings.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="building_name" class="form-label">نام ساختمان *</label>
                    <input type="text" class="form-control" id="building_name" name="building_name" required>
                </div>
                <div class="col-12">
                    <label for="address" class="form-label">آدرس کامل *</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <div class="col-md-6">
                    <label for="document" class="form-label">مدارک ملک *</label>
                    <input type="file" class="form-control" id="document" name="document" accept=".pdf,.jpg,.png" required>
                    <small class="text-muted">فرمت‌های مجاز: PDF, JPG, PNG (حداکثر 2MB)</small>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" class="btn w-100 py-2" style="background: linear-gradient(to left, #7b1fa2, #4fc3f7); color: #fff;">
                        <i class="bi bi-send-check"></i> ارسال درخواست
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
