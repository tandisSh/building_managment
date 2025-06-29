@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 rounded flex-wrap" style="background-color: #4e3cb3; color: #fff; padding: 8px 16px;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-building-gear me-2"></i>ویرایش اطلاعات ساختمان
        </h6>
    </div>

    <div class="admin-table-card p-4">
        <form method="POST" action="{{ route('manager.building.update', $building->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- ردیف اول --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label small">نام ساختمان *</label>
                        <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                               id="name" name="name"
                               value="{{ old('name', $building->name) }}">
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="number_of_floors" class="form-label small">تعداد طبقات *</label>
                        <input type="number" class="form-control form-control-sm @error('number_of_floors') is-invalid @enderror"
                               id="number_of_floors" name="number_of_floors" min="1"
                               value="{{ old('number_of_floors', $building->number_of_floors) }}">
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
                               id="number_of_units" name="number_of_units" min="1"
                               value="{{ old('number_of_units', $building->number_of_units) }}">
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
                                <input type="hidden" name="shared_water" value="0">
                                <input class="form-check-input" type="checkbox" id="shared_water" name="shared_water" value="1"
                                    {{ old('shared_water', $building->shared_water) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_water">آب</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="hidden" name="shared_electricity" value="0">
                                <input class="form-check-input" type="checkbox" id="shared_electricity" name="shared_electricity"
                                    value="1" {{ old('shared_electricity', $building->shared_electricity) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_electricity">برق</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="hidden" name="shared_gas" value="0">
                                <input class="form-check-input" type="checkbox" id="shared_gas" name="shared_gas" value="1"
                                    {{ old('shared_gas', $building->shared_gas) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="shared_gas">گاز</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ردیف سوم - استان و شهر --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="province" class="form-label small">استان *</label>
                        <select class="form-select form-select-sm @error('province') is-invalid @enderror"
                                id="province" name="province">
                            <option value="">انتخاب استان</option>
                        </select>
                        @error('province')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city" class="form-label small">شهر *</label>
                        <select class="form-select form-select-sm @error('city') is-invalid @enderror"
                                id="city" name="city">
                            <option value="">انتخاب شهر</option>
                        </select>
                        @error('city')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ردیف چهارم --}}
                <div class="col-12">
                    <div class="form-group">
                        <label for="address" class="form-label small">آدرس جزئیات (خیابان، کوچه و ...) *</label>
                        <textarea class="form-control form-control-sm @error('address') is-invalid @enderror"
                                  id="address" name="address" rows="2">{{ old('address', $building->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ردیف پنجم --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="document" class="form-label small">مدارک ملک (اختیاری)</label>
                        <input type="file" class="form-control form-control-sm @error('document') is-invalid @enderror"
                               id="document" name="document" accept=".pdf,.jpg,.png">
                        <small class="text-muted small">فرمت‌های مجاز: PDF, JPG, PNG (حداکثر 2MB)</small>
                        @error('document')
                            <div class="invalid-feedback small">{{ $message }}</div>
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
                </div>

                {{-- دکمه ثبت --}}
                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('manager.building.show') }}" class="btn btn-sm cancel-btn">
                            <i class="bi bi-x-circle me-1"></i> انصراف
                        </a>
                        <button type="submit" class="btn btn-sm add-btn">
                            <i class="bi bi-check-circle me-1"></i> ذخیره تغییرات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/iran-cities.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeProvinceCityDropdowns('province', 'city');
        
        // تنظیم مقادیر فعلی استان و شهر
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        
        // ابتدا استان را تنظیم کن
        provinceSelect.value = '{{ old('province', $building->province) }}';
        
        // شهرهای مربوطه را بارگذاری کن
        populateCities(provinceSelect, citySelect);
        
        // سپس شهر را تنظیم کن
        setTimeout(() => {
            citySelect.value = '{{ old('city', $building->city) }}';
        }, 100);
    });
</script>
@endsection
