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
                        <div class="form-group">
                            <label for="unit_id" class="form-label small">انتخاب واحد *</label>
                            <select name="unit_id" id="unit_id" class="form-select form-select-sm">
                                <option value="">-- انتخاب کنید --</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        واحد {{ $unit->unit_number }} - طبقه {{ $unit->floor }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- نام --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label small">نام کامل *</label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- تلفن --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone" class="form-label small">شماره موبایل *</label>
                            <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- ایمیل --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label small">ایمیل</label>
                            <input type="text" name="email" class="form-control form-control-sm" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- نقش --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="form-label small">نقش *</label>
                            <select name="role" id="role" class="form-select form-select-sm">
                                <option value="">-- انتخاب نقش --</option>
                                <option value="resident" {{ old('role') == 'resident' ? 'selected' : '' }}>ساکن (مستاجر)</option>
                                <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>مالک</option>
                                <option value="resident_owner" {{ old('role') == 'resident_owner' ? 'selected' : '' }}>مالک و ساکن</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="residents_count" class="form-label small">تعداد افراد خانوار *</label>
                            <input type="number" name="residents_count" id="residents_count"
                                   class="form-control form-control-sm"
                                   value="{{ old('residents_count', 1) }}" min="1">
                            @error('residents_count')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- تاریخ شروع --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="from_date" class="form-label small">تاریخ شروع سکونت *</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ old('from_date') }}">
                            @error('from_date')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- تاریخ پایان --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to_date" class="form-label small">تاریخ پایان سکونت (اختیاری)</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ old('to_date') }}">
                            @error('to_date')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- دکمه ثبت --}}
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-sm add-btn w-100 py-2">
                            <i class="bi bi-check-circle me-1"></i> ثبت ساکن
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
            const residentsCountInput = document.getElementById('residents_count');

            if (role === 'owner' || role === '') {
                residentsCountInput.disabled = true;
                residentsCountInput.value = 1;
            } else {
                residentsCountInput.disabled = false;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            handleResidentsCountField(); // بار اول

            document.getElementById('role').addEventListener('change', function () {
                handleResidentsCountField();
            });
        });
    </script>
@endpush
