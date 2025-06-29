@extends('layouts.app')

@section('content')
<div class="container mt-3">

    {{-- هدر --}}
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded" style="background-color: #4e3cb3;">
        <h6 class="mb-0 fw-bold text-white py-2 px-3">
            <i class="bi bi-person-gear me-2"></i>ویرایش پروفایل سوپر ادمین
        </h6>
    </div>

    {{-- فرم ویرایش اطلاعات کاربری --}}
    <div class="card admin-table-card p-4 mb-4 shadow-sm rounded border-0">
        <div class="card-header bg-light fw-bold">
            <i class="bi bi-person-lines-fill me-2"></i>اطلاعات کاربری
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.profile.update') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small">نام کامل *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-control form-control-sm @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">شماره تماس *</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="form-control form-control-sm @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">ایمیل *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-control form-control-sm @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-sm add-btn w-100 py-2">
                            <i class="bi bi-check-circle me-1"></i>ثبت تغییرات اطلاعات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- فرم تغییر رمز عبور --}}
    <div class="card admin-table-card p-4 shadow-sm rounded border-0">
        <div class="card-header bg-light fw-bold">
            <i class="bi bi-shield-lock me-2"></i>تغییر رمز عبور
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.profile.password') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small">رمز عبور جدید *</label>
                        <input type="password" name="password"
                               class="form-control form-control-sm @error('password') is-invalid @enderror">
                        @error('password')
                            <div class="invalid-feedback small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small">تکرار رمز عبور *</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-sm">
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-sm btn-outline-primary w-100 py-2">
                            <i class="bi bi-lock-fill me-1"></i>ثبت رمز جدید
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection 