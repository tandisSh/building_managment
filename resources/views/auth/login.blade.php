@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">ورود</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            ایمیل یا رمز عبور اشتباه است.
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">ایمیل</label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">رمز عبور</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-success">ورود</button>
    </form>
</div>
@endsection
