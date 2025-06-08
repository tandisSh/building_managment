@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
            <h6 class="mb-0 fw-bold text-white py-2 px-3">
                <i class="bi bi-person-gear me-2"></i>ویرایش اطلاعات ساکن
            </h6>
        </div>

        <div class="admin-table-card p-4">
            <form action="{{ route('residents.update', $resident->id) }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="unit_id" class="form-label small">انتخاب واحد *</label>
                        <select name="unit_id" id="unit_id" class="form-select form-select-sm select2">
                            <option value="">-- انتخاب کنید --</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ old('unit_id', $resident->unit_id) == $unit->id ? 'selected' : '' }}>
                                    واحد {{ $unit->unit_number }} - طبقه {{ $unit->floor }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label small">نام کامل *</label>
                        <input type="text" name="name" id="name" class="form-control form-control-sm"
                            value="{{ old('name', $resident->name) }}">
                        @error('name')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="phone" class="form-label small">شماره موبایل *</label>
                        <input type="text" name="phone" id="phone" class="form-control form-control-sm"
                            value="{{ old('phone', $resident->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label small">ایمیل</label>
                        <input type="email" name="email" id="email" class="form-control form-control-sm"
                            value="{{ old('email', $resident->email) }}">
                        @error('email')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="role" class="form-label small">نقش *</label>
                        <select name="role" id="role" class="form-select form-select-sm select2">
                            <option value="resident" {{ old('role', $resident->role) == 'resident' ? 'selected' : '' }}>
                                ساکن (مستاجر)</option>
                            <option value="owner" {{ old('role', $resident->role) == 'owner' ? 'selected' : '' }}>مالک
                            </option>
                            <option value="resident_owner"
                                {{ old('role', $resident->role) == 'resident_owner' ? 'selected' : '' }}>مالک و ساکن
                            </option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="resident_count" class="form-label small">تعداد افراد خانوار *</label>
                        <input type="number" min="1" name="resident_count" id="resident_count"
                            class="form-control form-control-sm"
                            value="{{ old('resident_count', $resident->resident_count ?? 1) }}">
                        @error('resident_count')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="from_date" class="form-label small">تاریخ شروع سکونت *</label>
                        <input type="date" name="from_date" id="from_date" class="form-control form-control-sm"
                            value="{{ old('from_date', $resident->from_date) }}">
                        @error('from_date')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="to_date" class="form-label small">تاریخ پایان سکونت (اختیاری)</label>
                        <input type="date" name="to_date" id="to_date" class="form-control form-control-sm"
                            value="{{ old('to_date', $resident->to_date) }}">
                        @error('to_date')
                            <div class="invalid-feedback d-block small">{{ $message }}</div>
                        @enderror
                    </div>
<div class="col-md-6">
    <label for="status" class="form-label small">وضعیت کاربر *</label>
    <select name="status" id="status" class="form-select form-select-sm">
        <option value="active" {{ old('status', $resident->status) == 'active' ? 'selected' : '' }}>فعال</option>
        <option value="inactive" {{ old('status', $resident->status) == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
    </select>
    @error('status')
        <div class="invalid-feedback d-block small">{{ $message }}</div>
    @enderror
</div>

                    <div class="col-12 mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('residents.index') }}" class="btn btn-sm filter-btn">
                            <i class="bi bi-x-circle me-1"></i> انصراف
                        </a>
                        <button type="submit" class="btn btn-sm add-btn">
                            <i class="bi bi-check-circle me-1"></i> ذخیره تغییرات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function handleResidentsCountField() {
            const role = document.getElementById('role').value;
            const countInput = document.getElementById('resident_count');

            if (role === 'owner' || role === '') {
                countInput.disabled = true;
                countInput.value = 1;
            } else {
                countInput.disabled = false;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            handleResidentsCountField();
            document.getElementById('role').addEventListener('change', handleResidentsCountField);
        });
    </script>
@endpush
