@extends('layouts.app')

@section('content')

    <div class="container mt-3">
        <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #e5ddfa;">
            <h6 class="mb-0 fw-bold text-dark py-2 px-3">
                <i class="bi bi-building-gear me-2"></i>ویرایش اطلاعات ساختمان
            </h6>
        </div>

        <div class="admin-table-card p-4">
            <form method="POST" action="{{ route('superadmin.buildings.update', $building->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- نام ساختمان --}}
                    <div class="col-md-6">
                        <label class="form-label small">نام ساختمان *</label>
                        <input type="text" name="name"
                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                            value="{{ old('name', $building->name) }}">
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- تعداد طبقات --}}
                    <div class="col-md-6">
                        <label class="form-label small">تعداد طبقات *</label>
                        <input type="number" name="number_of_floors" min="1"
                            class="form-control form-control-sm @error('number_of_floors') is-invalid @enderror"
                            value="{{ old('number_of_floors', $building->number_of_floors) }}">
                        @error('number_of_floors')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- تعداد واحدها --}}
                    <div class="col-md-6">
                        <label class="form-label small">تعداد واحدها *</label>
                        <input type="number" name="number_of_units" min="1"
                            class="form-control form-control-sm @error('number_of_units') is-invalid @enderror"
                            value="{{ old('number_of_units', $building->number_of_units) }}">
                        @error('number_of_units')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- ادمین --}}
                    <div class="col-md-6">
                        <label for="manager_id" class="form-label small">مدیر ساختمان</label>
                        <select name="manager_id" id="manager_id" class="form-select">
                            <option value="">انتخاب مدیر</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}">
                                    {{ $manager->name }} - {{ $manager->mobile }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- هزینه‌های مشترک --}}
                    <div class="col-md-6">
                        <label class="form-label small d-block">هزینه‌های مشترک:</label>
                        <div class="d-flex gap-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="shared_water" id="shared_water"
                                    value="1" {{ old('shared_water', $building->shared_water) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_water">آب</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="shared_electricity"
                                    id="shared_electricity" value="1"
                                    {{ old('shared_electricity', $building->shared_electricity) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_electricity">برق</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="shared_gas" id="shared_gas"
                                    value="1" {{ old('shared_gas', $building->shared_gas) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_gas">گاز</label>
                            </div>
                        </div>
                    </div>

                    {{-- آدرس --}}
                    <div class="col-12">
                        <label class="form-label small">آدرس کامل *</label>
                        <textarea name="address" rows="2" class="form-control form-control-sm @error('address') is-invalid @enderror">{{ old('address', $building->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- مدارک --}}
                    <div class="col-md-6">
                        <label class="form-label small">مدارک ملک (در صورت نیاز به تغییر)</label>
                        <input type="file" name="document" accept=".pdf,.jpg,.png"
                            class="form-control form-control-sm @error('document') is-invalid @enderror">
                        <small class="text-muted small">فرمت‌های مجاز: PDF, JPG, PNG (حداکثر 2MB)</small>
                        @error('document')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- دکمه ویرایش --}}
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-sm add-btn w-100 py-2">
                            <i class="bi bi-send-check me-1"></i> ذخیره تغییرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
