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
                    <div class="form-group">
                        <label for="unit_id" class="form-label small">انتخاب واحد *</label>
                        <select name="unit_id" id="unit_id" class="form-select form-select-sm select2">
                            <option value="">-- انتخاب کنید --</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ $resident->unit_id == $unit->id ? 'selected' : '' }}>
                                    واحد {{ $unit->unit_number }} - طبقه {{ $unit->floor }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label small">نام کامل *</label>
                        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $resident->name) }}">
                        @error('name') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone" class="form-label small">شماره موبایل *</label>
                        <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone', $resident->phone) }}">
                        @error('phone') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label small">ایمیل</label>
                        <input type="text" name="email" class="form-control form-control-sm" value="{{ old('email', $resident->email) }}">
                        @error('email') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role" class="form-label small">نقش *</label>
                        <select name="role" class="form-select form-select-sm select2">
                            <option value="resident" {{ old('role', $resident->role) == 'resident' ? 'selected' : '' }}>ساکن (مستاجر)</option>
                            <option value="owner" {{ old('role', $resident->role) == 'owner' ? 'selected' : '' }}>مالک</option>
                        </select>
                        @error('role') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="from_date" class="form-label small">تاریخ شروع سکونت *</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ old('from_date', $resident->from_date) }}">
                        @error('from_date') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="to_date" class="form-label small">تاریخ پایان سکونت (اختیاری)</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ old('to_date', $resident->to_date) }}">
                        @error('to_date') <div class="invalid-feedback small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ url()->previous() }}" class="btn btn-sm filter-btn">
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "انتخاب کنید",
            width: '100%',
            theme: 'bootstrap-5',
            dropdownParent: $('.admin-table-card')
        });
    });
</script>
@endsection
