@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
            <h6 class="mb-0 fw-bold text-white py-2 px-3">
                <i class="bi bi-building-add me-2"></i>فرم ثبت ساختمان جدید
            </h6>
        </div>

        <div class="admin-table-card p-4">
            <form method="POST" action="{{ route('superadmin.buildings.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    {{-- نام ساختمان --}}
                    <div class="col-md-6">
                        <label class="form-label small">نام ساختمان *</label>
                        <input type="text" name="name"
                            class="form-control form-control-sm @error('name') is-invalid @enderror"
                            value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- تعداد طبقات --}}
                    <div class="col-md-6">
                        <label class="form-label small">تعداد طبقات *</label>
                        <input type="number" name="number_of_floors" min="1"
                            class="form-control form-control-sm @error('number_of_floors') is-invalid @enderror"
                            value="{{ old('number_of_floors') }}">
                        @error('number_of_floors')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- تعداد واحدها --}}
                    <div class="col-md-6">
                        <label class="form-label small">تعداد واحدها *</label>
                        <input type="number" name="number_of_units" min="1"
                            class="form-control form-control-sm @error('number_of_units') is-invalid @enderror"
                            value="{{ old('number_of_units') }}">
                        @error('number_of_units')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ادمین --}}
                    <div class="col-md-6">
                        <label for="manager_id" class="form-label small">مدیر ساختمان</label>
                        <select name="manager_id" id="manager_id" class="form-select" >
                            <option value="">انتخاب مدیر</option>
                            @foreach ($managers as $manager)
                                <option value="{{ $manager->id }}">
                                    {{ $manager->name }} - {{ $manager->mobile }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- استان --}}
                    <div class="col-md-6">
                        <label for="province" class="form-label small">استان *</label>
                        <select class="form-select form-select-sm @error('province') is-invalid @enderror"
                                id="province" name="province">
                            <option value="">انتخاب استان</option>
                        </select>
                        @error('province')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- شهر --}}
                    <div class="col-md-6">
                        <label for="city" class="form-label small">شهر *</label>
                        <select class="form-select form-select-sm @error('city') is-invalid @enderror"
                                id="city" name="city">
                            <option value="">انتخاب شهر</option>
                        </select>
                        @error('city')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- هزینه‌های مشترک --}}
                    <div class="col-md-6">
                        <label class="form-label small d-block">هزینه‌های مشترک:</label>
                        <div class="d-flex gap-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="shared_water" id="shared_water"
                                    value="1" {{ old('shared_water') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_water">آب</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="shared_electricity"
                                    id="shared_electricity" value="1"
                                    {{ old('shared_electricity') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_electricity">برق</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="shared_gas" id="shared_gas"
                                    value="1" {{ old('shared_gas') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_gas">گاز</label>
                            </div>
                        </div>
                    </div>

                    {{-- آدرس جزئیات --}}
                    <div class="col-12">
                        <label class="form-label small">آدرس جزئیات (خیابان، کوچه و ...) *</label>
                        <textarea name="address" rows="2" class="form-control form-control-sm @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- مدارک --}}
                    <div class="col-md-6">
                        <label class="form-label small">مدارک ملک *</label>
                        <input type="file" name="document" accept=".pdf,.jpg,.png"
                            class="form-control form-control-sm @error('document') is-invalid @enderror">
                        <small class="text-muted small">فرمت‌های مجاز: PDF, JPG, PNG (حداکثر 2MB)</small>
                        @error('document')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- دکمه ثبت --}}
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-sm add-btn w-100 py-2">
                            <i class="bi bi-send-check me-1"></i> ثبت ساختمان
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/iran-cities.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeProvinceCityDropdowns('province', 'city');
            
            // اگر مقادیر قبلی وجود دارند، آنها را تنظیم کن
            @if(old('province'))
                const provinceSelect = document.getElementById('province');
                const citySelect = document.getElementById('city');
                
                // ابتدا استان را تنظیم کن
                provinceSelect.value = '{{ old('province') }}';
                
                // شهرهای مربوطه را بارگذاری کن
                populateCities(provinceSelect, citySelect);
                
                // سپس شهر را تنظیم کن
                setTimeout(() => {
                    citySelect.value = '{{ old('city') }}';
                }, 100);
            @endif
        });
    </script>
@endsection
