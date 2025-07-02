@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">پرداخت اولیه برای فعال‌سازی حساب</h5>
                </div>
                <div class="card-body text-center">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('warning'))
                        <div class="alert alert-warning">{{ session('warning') }}</div>
                    @endif

                    <p class="lead">برای دسترسی به تمام امکانات مدیریت ساختمان، لطفاً هزینه فعال‌سازی را پرداخت نمایید.</p>
                    
                    <div class="my-4">
                        <h2 class="display-4 fw-bold">{{ number_format($payment->amount, 0) }} <small>تومان</small></h2>
                        <p class="text-muted">بابت: هزینه راه‌اندازی و فعال‌سازی اولیه</p>
                    </div>

                    <form action="{{ route('manager.initial-payment.pay') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-credit-card me-2"></i>
                            پرداخت آنلاین
                        </button>
                    </form>
                </div>
                <div class="card-footer text-muted small">
                    پس از پرداخت موفق، حساب شما به صورت خودکار فعال خواهد شد.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 