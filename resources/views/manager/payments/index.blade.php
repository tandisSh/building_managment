@extends('layouts.app')

@section('content')
    <div class="admin-header d-flex justify-content-between align-items-center mb-3 shadow-sm rounded flex-wrap">
        <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-receipt"></i> لیست صورتحساب‌ها</h6>

        <div class="tools-box">
            <input type="text" class="form-control form-control-sm search-input" placeholder="جستجو..." />
            <button class="btn filter-btn">فیلتر</button>
        </div>
    </div>

    <div class="card admin-table-card">
        <div class="card-body table-responsive">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-striped align-middle small table-units">
                <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>عنوان</th>
                        <th>مبلغ</th>
                        <th>تاریخ پرداخت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $payment->invoice->title }}</td>
                            <td>{{ number_format($payment->amount) }} تومان</td>
                            <td>{{ jdate($payment->paid_at)->format('Y/m/d') }}</td>
                            <td>
                                <a href="{{ route('payments.show', $payment->id) }}"
                                    class="btn btn-sm btn-outline-primary" title="نمایش">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">هیچ پرداختی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
