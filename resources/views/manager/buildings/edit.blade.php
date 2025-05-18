@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-header text-white py-3">
            <h5 class="mb-0"><i class="bi bi-building-gear"></i> ویرایش اطلاعات ساختمان</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('manager.building.update', $building->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="building_name" class="form-label">نام ساختمان *</label>
                        <input type="text" class="form-control @error('building_name') is-invalid @enderror"
                            id="building_name" name="building_name"
                            value="{{ old('building_name', $building->building_name) }}">
                        @error('building_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="number_of_floors" class="form-label">تعداد طبقات *</label>
                        <input type="number" class="form-control @error('number_of_floors') is-invalid @enderror"
                            id="number_of_floors" name="number_of_floors" min="1"
                            value="{{ old('number_of_floors', $building->number_of_floors) }}">
                        @error('number_of_floors')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="number_of_units" class="form-label">تعداد واحدها *</label>
                        <input type="number" class="form-control @error('number_of_units') is-invalid @enderror"
                            id="number_of_units" name="number_of_units" min="1"
                            value="{{ old('number_of_units', $building->number_of_units) }}">
                        @error('number_of_units')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label d-block">هزینه‌های مشترک:</label>
                        <!-- آب -->
                        <input type="hidden" name="shared_water" value="0">
                        <input class="form-check-input" type="checkbox" id="shared_water" name="shared_water" value="1"
                            {{ old('shared_water', $building->shared_water) ? 'checked' : '' }}>
                        <label class="form-check-label" for="shared_water">آب</label>

                        <!-- برق -->
                        <input type="hidden" name="shared_electricity" value="0">
                        <input class="form-check-input" type="checkbox" id="shared_electricity" name="shared_electricity"
                            value="1" {{ old('shared_electricity', $building->shared_electricity) ? 'checked' : '' }}>
                        <label class="form-check-label" for="shared_electricity">برق</label>

                        <!-- گاز -->
                        <input type="hidden" name="shared_gas" value="0">
                        <input class="form-check-input" type="checkbox" id="shared_gas" name="shared_gas" value="1"
                            {{ old('shared_gas', $building->shared_gas) ? 'checked' : '' }}>
                        <label class="form-check-label" for="shared_gas">گاز</label>

                    </div>
                    <div class="col-12">
                        <label for="address" class="form-label">آدرس کامل *</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $building->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="document" class="form-label">مدارک ملک (اختیاری)</label>
                        <input type="file" class="form-control @error('document') is-invalid @enderror" id="document"
                            name="document" accept=".pdf,.jpg,.png">
                        <small class="text-muted">فرمت‌های مجاز: PDF, JPG, PNG (حداکثر 2MB)</small>
                        @error('document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($building->document_path)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $building->document_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> مشاهده مدارک فعلی
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn w-100 py-2 btn-submit">
                            <i class="bi bi-check-circle"></i> ثبت تغییرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
