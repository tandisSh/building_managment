@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
            <h6 class="mb-0 fw-bold text-white py-2 px-3">
                <i class="bi bi-pencil-square me-2"></i>ویرایش صورتحساب تکی
            </h6>
        </div>

        <div class="admin-table-card p-4">
            <form action="{{ route('superadmin.invoices.update-single', $invoice->id) }}" method="POST" id="edit-invoice-form">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small">ساختمان</label>
                        <select name="building_id" id="building_id" class="form-select form-select-sm">
                            <option value="">انتخاب ساختمان</option>
                            @foreach ($buildings as $building)
                                <option value="{{ $building->id }}" {{ $invoice->unit->building_id == $building->id ? 'selected' : '' }}>
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
                            value="{{ old('title', $invoice->title) }}">
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">نوع صورتحساب</label>
                        <select name="type" class="form-select form-select-sm">
                            <option value="current" {{ $invoice->type == 'current' ? 'selected' : '' }}>جاری</option>
                            <option value="fixed" {{ $invoice->type == 'fixed' ? 'selected' : '' }}>ثابت</option>
                        </select>
                        @error('type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">مبلغ</label>
                        <input type="number" name="amount" class="form-control form-control-sm"
                            value="{{ old('amount', $invoice->amount) }}">
                        @error('amount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">تاریخ مهلت پرداخت</label>
                        <input type="date" name="due_date" class="form-control form-control-sm"
                            value="{{ old('due_date', \Illuminate\Support\Carbon::parse($invoice->due_date)->format('Y-m-d')) }}">
                        @error('due_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label small">توضیحات (اختیاری)</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2">{{ old('description', $invoice->description) }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-sm btn-primary w-100 py-2">
                            <i class="bi bi-check-circle me-1"></i> بروزرسانی صورتحساب
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('building_id').addEventListener('change', function () {
                let buildingId = this.value;
                let unitSelect = document.getElementById('unit_id');

                unitSelect.innerHTML = '<option value="">لطفاً صبر کنید...</option>';

                if (!buildingId) {
                    unitSelect.innerHTML = '<option value="">ابتدا ساختمان را انتخاب کنید</option>';
                    return;
                }

                fetch('{{ route('superadmin.invoices.get-units', ['building' => ':building']) }}'.replace(':building', buildingId))
                    .then(response => response.json())
                    .then(data => {
                        unitSelect.innerHTML = '<option value="">انتخاب واحد</option>';
                        if (data.units.length > 0) {
                            data.units.forEach(unit => {
                                let option = document.createElement('option');
                                option.value = unit.id;
                                option.text = `واحد ${unit.unit_number}`;
                                unitSelect.appendChild(option);
                            });
                            // انتخاب خودکار واحد فعلی اگر وجود داشته باشه
                            let currentUnitId = '{{ $invoice->unit_id }}';
                            if (data.units.some(unit => unit.id == currentUnitId)) {
                                unitSelect.value = currentUnitId;
                            }
                        } else {
                            unitSelect.innerHTML = '<option value="">واحدی برای این ساختمان یافت نشد</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        unitSelect.innerHTML = '<option value="">خطا در بارگذاری واحدها</option>';
                    });
            });

            // بارگذاری اولیه واحدهای مربوط به ساختمان فعلی
            document.addEventListener('DOMContentLoaded', function () {
                let initialBuildingId = '{{ $invoice->unit->building_id ?? '' }}';
                if (initialBuildingId) {
                    document.getElementById('building_id').value = initialBuildingId;
                    document.getElementById('building_id').dispatchEvent(new Event('change'));
                } else {
                    console.warn('Building ID not found for invoice:', '{{ $invoice->id }}');
                }
            });
        </script>
    @endpush
@endsection
