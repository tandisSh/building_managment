@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">درگاه پرداخت آنلاین</h5>
                </div>
                <div class="card-body text-center">
                    <div class="my-4">
                        <h2 class="display-4 fw-bold">{{ number_format($amount, 0) }} <small>تومان</small></h2>
                        <p class="text-muted">بابت: هزینه راه‌اندازی و فعال‌سازی اولیه</p>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        این یک درگاه پرداخت شبیه‌سازی شده است. در محیط واقعی، اینجا درگاه پرداخت واقعی قرار می‌گیرد.
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <form action="{{ $callback_url }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="payment_id" value="{{ $payment_id }}">
                                <input type="hidden" name="payment_status" value="success">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="bi bi-check-circle me-2"></i>
                                    پرداخت موفق
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ $callback_url }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="payment_id" value="{{ $payment_id }}">
                                <input type="hidden" name="payment_status" value="failed">
                                <button type="submit" class="btn btn-danger btn-lg w-100">
                                    <i class="bi bi-x-circle me-2"></i>
                                    پرداخت ناموفق
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('manager.initial-payment.show') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            بازگشت
                        </a>
                    </div>
                </div>
                <div class="card-footer text-muted small">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>شماره تراکنش:</strong> {{ 'TXN-' . time() . '-' . $payment_id }}
                        </div>
                        <div class="col-md-6">
                            <strong>تاریخ:</strong> {{ \Morilog\Jalali\Jalalian::now()->format('Y/m/d H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 