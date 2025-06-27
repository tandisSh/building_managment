@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="admin-header d-flex justify-content-between align-items-center mb-4" style="background-color: #4e3cb3;">
            <h6 class="mb-0 fw-bold text-white py-2 px-3">
                <i class="bi bi-person-plus me-2"></i>افزودن ساکن جدید
            </h6>
        </div>

        <div class="admin-table-card p-4">
            <form action="{{ route('residents.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    {{-- واحد --}}
                    <div class="col-md-6">
                        <label for="unit_id" class="form-label small">انتخاب واحد *</label>
                        <select name="unit_id" id="unit_id"
                            class="form-select form-select-sm @error('unit_id') is-invalid @enderror">
                            <option value="">-- انتخاب کنید --</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    واحد {{ $unit->unit_number }} - طبقه {{ $unit->floor }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- نام --}}
                    <div class="col-md-6">
                        <label class="form-label small">نام کامل *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="form-control form-control-sm @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- تلفن --}}
                    <div class="col-md-6">
                        <label class="form-label small">شماره موبایل *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="form-control form-control-sm @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- ایمیل --}}
                    <div class="col-md-6">
                        <label class="form-label small">ایمیل</label>
                        <input type="text" name="email" value="{{ old('email') }}"
                            class="form-control form-control-sm @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- نقش --}}
                    <div class="col-md-6">
                        <label class="form-label small">نقش *</label>
                        <select name="role" id="role"
                            class="form-select form-select-sm @error('role') is-invalid @enderror">
                            <option value="">-- انتخاب نقش --</option>
                            <option value="resident" {{ old('role') == 'resident' ? 'selected' : '' }}>ساکن (مستاجر)
                            </option>
                            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>مالک</option>
                            <option value="resident_owner" {{ old('role') == 'resident_owner' ? 'selected' : '' }}>مالک و
                                ساکن</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- تعداد افراد خانوار --}}
                    <div class="col-md-6">
                        <label class="form-label small">تعداد افراد خانوار *</label>
                        <input type="number" name="resident_count" id="resident_count"
                            class="form-control form-control-sm @error('resident_count') is-invalid @enderror"
                            value="{{ old('resident_count', 1) }}" min="1">
                        @error('resident_count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- تاریخ شروع --}}
                    <div class="col-md-6">
                        <label class="form-label small">تاریخ شروع سکونت *</label>
                        <input type="date" name="from_date" value="{{ old('from_date') }}"
                            class="form-control form-control-sm @error('from_date') is-invalid @enderror">
                        @error('from_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- تاریخ پایان --}}
                    <div class="col-md-6">
                        <label class="form-label small">تاریخ پایان سکونت </label>
                        <input type="date" name="to_date" value="{{ old('to_date') }}" 
                            class="form-control form-control-sm @error('to_date') is-invalid @enderror"
                             >
                        @error('to_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('residents.index') }}" class="btn btn-sm cancel-btn">
                                <i class="bi bi-x-circle me-1"></i> انصراف
                            </a>
                            <button type="submit" class="btn btn-sm add-btn">
                                <i class="bi bi-check-circle me-1"></i> ثبت ساکن
                            </button>
                        </div>
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
            const toDate = document.getElementById('to_date');

            if (role === 'owner' || role === '') {
                countInput.disabled = true;
                countInput.value = 1;
                // toDate.disabled = true;
            } else if (role === 'resident_owner') {
                countInput.disabled = false;
                // toDate.disabled = true;
            } else {
                countInput.disabled = false;
                // toDate.disabled = false;
                // toDate.required = true;
            }

        }
        document.addEventListener('DOMContentLoaded', function() {
            handleResidentsCountField();
            document.getElementById('role').addEventListener('change', handleResidentsCountField);
        });
    </script>
@endpush
