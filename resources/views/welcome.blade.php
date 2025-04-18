<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'سیستم مدیریت ساختمان')

@section('content')
<div class="container text-center py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="mb-0">به سیستم مدیریت ساختمان خوش آمدید</h1>
                </div>
                <div class="card-body">
                    <p class="lead">برای ادامه لطفاً وارد شوید یا ثبت‌نام کنید</p>

                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                            <i class="bi bi-box-arrow-in-right"></i> ورود به سیستم
                        </a>
                        <a href="" class="btn btn-success btn-lg px-4">
                            <i class="bi bi-person-plus"></i> ثبت‌نام مدیر جدید
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
