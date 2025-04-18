@extends('layouts.auth')

@section('title', 'ثبت نام مدیر جدید')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white text-center py-4">
                <h4><i class="bi bi-person-plus"></i> ثبت نام مدیر جدید</h4>
            </div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('register.manager') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="form-label">نام کامل</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="form-label">شماره موبایل</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">رمز عبور</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">تکرار رمز عبور</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-2">
                        <i class="bi bi-person-plus"></i> ثبت نام
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
