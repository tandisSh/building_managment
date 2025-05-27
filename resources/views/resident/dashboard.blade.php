@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="bi bi-house-door me-1"></i> داشبورد ساکن
        </h6>
    </div>
@if($unit)
    {{ $unit->name }}
@endif

    {{-- اطلاعیه‌ها --}}
    {{-- <div class="card admin-table-card mb-4">
        <div class="card-header bg-light fw-bold">
            <i class="bi bi-bell-fill me-1"></i> اطلاعیه‌های جدید
        </div>
        <div class="card-body p-3">
            @if($notices->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> اطلاعیه‌ای ثبت نشده است.
                </div>
            @else
                <ul class="list-group small">
                    @foreach($notices as $notice)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $notice->title }}</strong>
                            </div>
                            <span class="text-muted">{{ \Morilog\Jalali\Jalalian::fromDateTime($notice->created_at)->format('Y/m/d') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div> --}}

    {{-- صورتحساب‌های پرداخت‌نشده --}}
    <div class="card admin-table-card">
        <div class="card-header bg-light fw-bold">
            <i class="bi bi-receipt-cutoff me-1"></i> صورتحساب‌های پرداخت‌نشده
        </div>
        <div class="card-body p-3">
            @if($invoices->isEmpty())
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i> صورتحساب پرداخت‌نشده‌ای ندارید.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle small">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>عنوان</th>
                                <th>مبلغ</th>
                                <th>مهلت پرداخت</th>
                                <th>وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $index => $invoice)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $invoice->title }}</td>
                                    <td>{{ number_format($invoice->amount) }} تومان</td>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($invoice->due_date)->format('Y/m/d') }}</td>
                                    <td>
                                        <span class="badge bg-danger py-1 px-3 rounded-pill">پرداخت نشده</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
