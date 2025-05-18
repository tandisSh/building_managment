@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-building-add me-2"></i>فرم درخواست ساختمان جدید
        </h6>
    </div>

    <div class="admin-table-card p-4">
        <form method="POST" action="{{ route('manager.buildings.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                {{-- ردیف اول --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="building_name" class="form-label small">نام ساختمان *</label>
                        <input type="text" class="form-control form-control-sm @error('building_name') is-invalid @enderror"
                               id="building_name" name="building_name" value="{{ old('building_name') }}">
                        @error('building_name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="number_of_floors" class="form-label small">تعداد طبقات *</label>
                        <input type="number" class="form-control form-control-sm @error('number_of_floors') is-invalid @enderror"
                               id="number_of_floors" name="number_of_floors" min="1" value="{{ old('number_of_floors') }}">
                        @error('number_of_floors')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ردیف دوم --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="number_of_units" class="form-label small">تعداد واحدها *</label>
                        <input type="number" class="form-control form-control-sm @error('number_of_units') is-invalid @enderror"
                               id="number_of_units" name="number_of_units" min="1" value="{{ old('number_of_units') }}">
                        @error('number_of_units')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label small d-block">هزینه‌های مشترک:</label>
                        <div class="d-flex gap-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="shared_water" name="shared_water" value="1"
                                    {{ old('shared_water') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_water">آب</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="shared_electricity" name="shared_electricity"
                                    value="1" {{ old('shared_electricity') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_electricity">برق</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="shared_gas" name="shared_gas" value="1"
                                    {{ old('shared_gas') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_gas">گاز</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ردیف سوم --}}
                <div class="col-12">
                    <div class="form-group">
                        <label for="address" class="form-label small">آدرس کامل *</label>
                        <textarea class="form-control form-control-sm @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ردیف چهارم --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="document" class="form-label small">مدارک ملک *</label>
                        <input type="file" class="form-control form-control-sm @error('document') is-invalid @enderror"
                               id="document" name="document" accept=".pdf,.jpg,.png">
                        <small class="text-muted small">فرمت‌های مجاز: PDF, JPG, PNG (حداکثر 2MB)</small>
                        @error('document')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- دکمه ارسال --}}
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-sm add-btn w-100 py-2">
                        <i class="bi bi-send-check me-1"></i> ارسال درخواست
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
