@extends('layouts.app')

@section('content')
<div class="container">
    <h4>بازیابی رمز عبور</h4>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label>ایمیل</label>
            <input type="email" name="email" class="form-control" required>
            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">ارسال لینک بازیابی</button>
    </form>
</div>
@endsection
