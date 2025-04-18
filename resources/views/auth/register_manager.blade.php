@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4">ثبت‌نام مدیر ساختمان</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.manager') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">نام کامل</label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">شماره تلفن</label>
            <input type="text" name="phone" class="form-control" id="phone" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">رمز عبور</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">تکرار رمز عبور</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-success">ثبت‌نام</button>
    </form>
</div>
@endsection
