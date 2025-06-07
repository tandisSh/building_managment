@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-house-add me-2"></i>افزودن واحد جدید
        </h6>
    </div>

    <div class="admin-table-card p-4">
        <form action="{{ route('units.store', $building->id) }}" method="POST">
            @csrf
            <input type="hidden" name="building_id" value="{{ $building->id }}">

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label small">شماره واحد *</label>
                        <input type="text" name="unit_number" class="form-control form-control-sm @error('unit_number') is-invalid @enderror"
                               value="{{ old('unit_number') }}" placeholder="مثال: 101">
                        @error('unit_number')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label small">طبقه *</label>
                        <input type="number" name="floor" class="form-control form-control-sm @error('floor') is-invalid @enderror"
                               value="{{ old('floor') }}">
                        @error('floor')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label small">متراژ (متر مربع)</label>
                        <input type="number" step="0.01" name="area" class="form-control form-control-sm @error('area') is-invalid @enderror"
                               value="{{ old('area') }}" placeholder="مثال: 85.50">
                        @error('area')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label small">تعداد جای پارک</label>
                        <input type="number" name="parking_slots" class="form-control form-control-sm @error('parking_slots') is-invalid @enderror"
                               value="{{ old('parking_slots', 0) }}">
                        @error('parking_slots')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label small">تعداد انباری</label>
                        <input type="number" name="storerooms" class="form-control form-control-sm @error('storerooms') is-invalid @enderror"
                               value="{{ old('storerooms', 0) }}">
                        @error('storerooms')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('units.index', $building->id) }}" class="btn btn-sm filter-btn">
                            <i class="bi bi-arrow-right me-1"></i> انصراف
                        </a>
                        <button type="submit" class="btn btn-sm add-btn">
                            <i class="bi bi-check-circle me-1"></i> ثبت واحد
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
