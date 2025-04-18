<!-- resources/views/auth/register/manager.blade.php -->
@extends('layouts.app')

@section('title', 'ثبت‌نام مدیر جدید')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">فرم ثبت‌نام مدیر ساختمان</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register.manager') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">نام کامل</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">شماره موبایل</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                        <small class="text-muted">فرمت: 09123456789</small>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">رمز عبور</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">تکرار رمز عبور</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-2">
                        <i class="bi bi-person-plus"></i> ثبت‌نام مدیر
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
