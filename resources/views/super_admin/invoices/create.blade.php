@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
            <h6 class="mb-0 fw-bold text-white py-2 px-3">
                <i class="bi bi-receipt me-2"></i>صدور صورتحساب تکی برای یک واحد
            </h6>
        </div>

        <div class="admin-table-card p-4">
            <form action="{{ route('superadmin.invoices.store-single') }}" method="POST" id="create-invoice-form">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small">ساختمان</label>
                        <select name="building_id" id="building_id" class="form-select form-select-sm">
                            <option value="">انتخاب ساختمان</option>
                            @foreach ($buildings as $building)
                                <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                    {{ $building->name ?? 'بدون نام' }}
                                </option>
                            @endforeach
                        </select>
                        @error('building_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">واحد</label>
                        <select name="unit_id" id="unit_id" class="form-select form-select-sm">
                            <option value="">ابتدا ساختمان را انتخاب کنید</option>
                        </select>
                        @error('unit_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">عنوان صورتحساب</label>
                        <input type="text" name="title" class="form-control form-control-sm"
                            value="{{ old('title') }}">
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">نوع صورتحساب</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="current" {{ old('type') == 'current' ? 'selected' : '' }}>جاری</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>ثابت</option>
                        </select>
                        @error('type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">مبلغ</label>
                        <input type="number" name="amount" class="form-control form-control-sm"
                            value="{{ old('amount') }}">
                        @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">تاریخ مهلت پرداخت</label>
                        <input type="date" name="due_date" class="form-control form-control-sm"
                            value="{{ old('due_date') }}">
                        @error('due_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small">توضیحات (اختیاری)</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-sm add-btn w-100 py-2">
                            <i class="bi bi-check-circle me-1"></i> ثبت صورتحساب
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // فقط وقتی ساختمان انتخاب شد، واحدها لود بشن
            document.getElementById('building_id').addEventListener('change', function () {
                const buildingId = this.value;
                const unitSelect = document.getElementById('unit_id');

                // پاک کردن گزینه‌های قبلی
                unitSelect.innerHTML = '<option value="">لطفاً صبر کنید...</option>';

                if (!buildingId) {
                    unitSelect.innerHTML = '<option value="">ابتدا ساختمان را انتخاب کنید</option>';
                    return;
                }

                // ارسال درخواست AJAX برای دریافت واحدها
                fetch(`/admin/invoices/get-units/${buildingId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        unitSelect.innerHTML = '<option value="">انتخاب واحد</option>';
                        if (data.units.length > 0) {
                            data.units.forEach(unit => {
                                const option = document.createElement('option');
                                option.value = unit.id;
                                option.text = `واحد ${unit.unit_number}`;
                                unitSelect.appendChild(option);
                            });
                        } else {
                            unitSelect.innerHTML = '<option value="">واحدی برای این ساختمان یافت نشد</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        unitSelect.innerHTML = '<option value="">خطا در بارگذاری واحدها</option>';
                    });
            });
        </script>
    @endpush
@endsection
